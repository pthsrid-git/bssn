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
      <TitleSection variant="warning">LOGBOOK TIM (KATIM)</TitleSection>

      <!-- Card Daftar Anggota -->
      <CardDefault v-if="!selectedMember">
        <!-- Header -->
        <div class="border-b border-gray-200 pb-4 mb-6">
          <h1 class="text-2xl font-bold text-gray-900">Logbook Tim (Katim)</h1>

          <hr class="my-4 border-gray-200" />

          <div class="mt-4 inline-grid grid-cols-1 md:grid-flow-col md:auto-cols-max gap-8">
            <div class="flex flex-col whitespace-nowrap">
              <span class="text-sm font-medium text-gray-500">Nama:</span>
              <span class="text-sm text-gray-900">{{ user?.fullname }}</span>
            </div>

            <div class="flex flex-col whitespace-nowrap">
              <span class="text-sm font-medium text-gray-500">Jumlah Anggota:</span>
              <span class="text-sm text-gray-900">{{ teamMembers.length }}</span>
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
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Daftar Logbook Anggota Tim</h2>

        <!-- Table -->
        <LogbookKatimTabel
          :teamMembers="teamMembers"
          :loading="loading"
          @viewMemberLogbook="viewMemberLogbook"
        />
      </CardDefault>

      <!-- Card Logbook Anggota (Conditional Render) -->
      <CardDefault v-else>
        <LogbookKatimDetail
          :memberLogs="memberLogs"
          :loadingLogs="loadingLogs"
          :selectedMonth="selectedMonth"
          :memberName="selectedMember?.nama"
          :memberNip="selectedMember?.nip"
          :memberPangkat="selectedMember?.pangkat"
          :memberJabatan="selectedMember?.jabatan"
          @viewDetail="viewLogDetail"
          @back="backToTeamList"
        />
      </CardDefault>
    </div>

  </PageDefault>
</template>

<script setup lang="ts">
import { PageDefault, TitleSection, CardDefault, type PageDefaultExposed } from '@bssn/ui-kit-frontend';
import { ref, onMounted, computed } from 'vue';
import LogbookKatimTabel from './LogbookKatimTabel.vue';
import LogbookKatimDetail from './form/LogbookKatimDetail.vue';
import LogbookKatimAdd from './form/LogbookKatimAdd.vue';
import { useLogbookKatimStore } from '@/stores/pengolah/logbookKatimStore';
import type { LogbookKatimData } from '@/models/pengolah/logbookKatim';

const pageDefault = ref<PageDefaultExposed | null>(null);

const logbookkatimstore = useLogbookKatimStore();

const teamMembers = ref<any[]>([]);
const loading = computed(() => logbookkatimstore.teamMembers.status === 'loading');
const selectedMember = ref<any | null>(null);
const memberLogs = computed(() => logbookkatimstore.memberLogs.data || []);
const loadingLogs = computed(() => logbookkatimstore.memberLogs.status === 'loading');
const selectedMonth = ref('');
const user = ref<any>(null);

onMounted(() => {
  const storedUser = localStorage.getItem('user');
  if (storedUser) {
    user.value = JSON.parse(storedUser);
  }

  const now = new Date();
  selectedMonth.value = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;

  loadTeamMembers();
});

const loadTeamMembers = async () => {
  try {
    await logbookkatimstore.callTeamMembers();
    const members = logbookkatimstore.teamMembers;
    if (members?.data && Array.isArray(members.data)) {
      teamMembers.value = members.data;
    } else {
      teamMembers.value = [];
    }
  } catch (error) {
    teamMembers.value = [];
  }
};

const viewMemberLogbook = async (member: any) => {
  selectedMember.value = member;
  await loadMemberLogs();
};

const backToTeamList = () => {
  selectedMember.value = null;
  logbookkatimstore.clearMemberLogs();
};

const loadMemberLogs = async () => {
  if (!selectedMember.value?.id) {
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

  await logbookkatimstore.callMemberLogs(selectedMember.value.id, filters);
};

const viewLogDetail = (log: LogbookKatimData) => {
  pageDefault.value?.openDrawerDefault(
    'Detail Logbook',
    LogbookKatimAdd,
    {
      record: log,
      memberName: selectedMember.value?.nama,
      memberNip: selectedMember.value?.nip,
      onSave: saveCatatanKatim
    }
  );
};

const saveCatatanKatim = async (catatan: string, logId: number) => {
  console.log('Saving catatan:', { catatan, logId });
  const result = await logbookkatimstore.saveCatatanKatimAndReload(logId, catatan);
  console.log('Save result:', result);

  if (result.success) {
    pageDefault.value?.closeDrawerDefault();
  }
};
</script>