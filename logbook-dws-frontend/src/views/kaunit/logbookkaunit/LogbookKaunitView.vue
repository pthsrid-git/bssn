<template>
  <PageDefault
    ref="pageDefault"
    pageStatus="success"
    :isViewerFile="true"
    :isDrawerDefault="true"
    :isModalDefault="true"
    :isModalConfirmation="false"
    :isModalAlert="false"
    @confirmModalConfirmation="() => {}"
  >
    <div class="flex flex-col w-full h-full gap-4 xl:gap-6">
      <TitleSection variant="warning">LOGBOOK KAUNIT</TitleSection>

      <!-- Card Daftar Pegawai -->
      <CardDefault v-if="!selectedPegawai">
        <!-- Header -->
        <div class="border-b border-gray-200 pb-4 mb-6">
          <h1 class="text-2xl font-bold text-gray-900">Logbook Kaunit</h1>

          <hr class="my-4 border-gray-200" />

          <div class="mt-4 inline-grid grid-cols-1 md:grid-flow-col md:auto-cols-max gap-8">
            <div class="flex flex-col whitespace-nowrap">
              <span class="text-sm font-medium text-gray-500">Nama:</span>
              <span class="text-sm text-gray-900">{{ user?.fullname }}</span>
            </div>

            <div class="flex flex-col whitespace-nowrap">
              <span class="text-sm font-medium text-gray-500">Jumlah Pegawai:</span>
              <span class="text-sm text-gray-900">{{ pegawaiRecords.meta?.total || 0 }}</span>
            </div>

            <div class="flex flex-col whitespace-nowrap">
              <span class="text-sm font-medium text-gray-500">Unit Kerja:</span>
              <span class="text-sm text-gray-900">
                Biro Organisasi dan Sumber Daya Manusia
              </span>
            </div>
          </div>
        </div>

        <!-- Section Title -->
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Daftar Pegawai Unit Kerja</h2>

        <!-- Table -->
        <LogbookKaunitTable
          :records="pegawaiRecords"
          @viewPegawaiLogbook="viewPegawaiLogbook"
          @pageChange="onPageChange"
        />
      </CardDefault>

      <!-- Card Logbook Pegawai -->
      <CardDefault v-else>
        <LogbookKaunitDetail
          :pegawaiLogs="pegawaiLogs"
          :loadingLogs="loadingLogs"
          :selectedMonth="selectedMonth"
          :pegawaiName="selectedPegawai?.nama"
          :pegawaiNip="selectedPegawai?.nip"
          :pegawaiPangkat="selectedPegawai?.pangkat"
          :pegawaiJabatan="selectedPegawai?.jabatan"
          @viewDetail="viewLogDetail"
          @approve="handleApprove"
          @reject="handleReject"
          @back="backToPegawaiList"
        />
      </CardDefault>
    </div>
  </PageDefault>
</template>

<script setup lang="ts">
import { PageDefault, TitleSection, CardDefault, type PageDefaultExposed } from '@bssn/ui-kit-frontend';
import { ref, onMounted, computed } from 'vue';
import LogbookKaunitTable from './LogbookKaunitTable.vue';
import LogbookKaunitDetail from './form/LogbookKaunitDetail.vue';
import LogbookKaunitAdd from './form/LogbookKaunitAdd.vue';
import { useLogbookKaunitStore } from '@/stores/kaunit/logbookKaunitStore';
import type { LogbookKaunitData, PegawaiData } from '@/models/kaunit/logbookKaunit';

const pageDefault = ref<PageDefaultExposed | null>(null);
const logbookkaunitstore = useLogbookKaunitStore();

const pegawaiRecords = computed(() => logbookkaunitstore.pegawaiList);
const selectedPegawai = ref<PegawaiData | null>(null);
const pegawaiLogs = computed(() => logbookkaunitstore.pegawaiLogs.data || []);
const loadingLogs = computed(() => logbookkaunitstore.pegawaiLogs.status === 'loading');
const selectedMonth = ref('');
const user = ref<any>(null);

const filterKeyword = ref<string>('');
const currentPage = ref<number>(1);

const onFilterKeywordUpdated = (value: string) => {
  filterKeyword.value = value;
  currentPage.value = 1;
  onLoadPegawai();
};

const onPageChange = (page: number) => {
  currentPage.value = page;
  onLoadPegawai();
};

const onLoadPegawai = async () => {
  await logbookkaunitstore.callPegawaiList(filterKeyword.value, currentPage.value);
};

onMounted(() => {
  const storedUser = localStorage.getItem('user');
  if (storedUser) {
    user.value = JSON.parse(storedUser);
  }

  const now = new Date();
  selectedMonth.value = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;

  onLoadPegawai();
});

const viewPegawaiLogbook = async (pegawai: PegawaiData) => {
  selectedPegawai.value = pegawai;
  await loadPegawaiLogs();
};

const backToPegawaiList = () => {
  selectedPegawai.value = null;
  logbookkaunitstore.clearPegawaiLogs();
};

const loadPegawaiLogs = async () => {
  if (!selectedPegawai.value?.id) {
    return;
  }

  const filters: { start_date?: string; end_date?: string } = {};
  if (selectedMonth.value) {
    const [year, month] = selectedMonth.value.split('-');
    const startDate = `${year}-${month}-01`;
    const daysInMonth = new Date(parseInt(year), parseInt(month), 0).getDate();
    filters.start_date = startDate;
    filters.end_date = `${year}-${month}-${String(daysInMonth).padStart(2, '0')}`;
  }

  await logbookkaunitstore.callPegawaiLogs(selectedPegawai.value.id, filters);
};

const viewLogDetail = (log: LogbookKaunitData) => {
  pageDefault.value?.openDrawerDefault(
    'Detail Logbook',
    LogbookKaunitAdd,
    {
      record: log,
      pegawaiName: selectedPegawai.value?.nama,
      pegawaiNip: selectedPegawai.value?.nip,
      readOnly: true
    }
  );
};

const approveCatatanKaunit = async (catatan: string, logId: number) => {
  try {
    await logbookkaunitstore.callApproveLog(logId, catatan);
    pageDefault.value?.closeDrawerDefault();
  } catch (error) {
    // Error handled by store
  }
};

const rejectCatatanKaunit = async (catatan: string, logId: number) => {
  try {
    await logbookkaunitstore.callRejectLog(logId, catatan);
    pageDefault.value?.closeDrawerDefault();
  } catch (error) {
    // Error handled by store
  }
};

const handleApprove = async (activity: LogbookKaunitData) => {
  pageDefault.value?.openDrawerDefault(
    'Setujui Logbook',
    LogbookKaunitAdd,
    {
      record: activity,
      pegawaiName: selectedPegawai.value?.nama,
      pegawaiNip: selectedPegawai.value?.nip,
      onApprove: approveCatatanKaunit,
      onReject: rejectCatatanKaunit
    }
  );
};

const handleReject = async (activity: LogbookKaunitData) => {
  pageDefault.value?.openDrawerDefault(
    'Tolak Logbook',
    LogbookKaunitAdd,
    {
      record: activity,
      pegawaiName: selectedPegawai.value?.nama,
      pegawaiNip: selectedPegawai.value?.nip,
      onApprove: approveCatatanKaunit,
      onReject: rejectCatatanKaunit
    }
  );
};
</script>
