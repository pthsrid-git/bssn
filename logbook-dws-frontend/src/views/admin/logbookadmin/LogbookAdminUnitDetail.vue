<template>
  <div class="space-y-4">
    <!-- Header Section -->
    <div class="flex flex-col gap-4">
      <!-- Title and Back Button -->
      <div class="flex items-start gap-3">
        <button
          @click="$emit('back')"
          class="p-2 hover:bg-gray-100 rounded-full transition-colors flex-shrink-0 mt-1"
        >
          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <div class="flex flex-col gap-1">
          <h1 class="text-xl font-bold text-gray-900">{{ unit.nama_unit }}</h1>
          <div class="flex items-center gap-2 text-sm text-gray-500">
            <span>{{ unit.kode_unit }}</span>
            <span>|</span>
            <span>{{ unit.total_pegawai }} Pegawai</span>
            <span>|</span>
            <span>{{ unit.total_logbook }} Logbook</span>
          </div>
        </div>
      </div>

      <!-- Divider -->
      <div class="h-px bg-gray-200"></div>
    </div>

    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <CardDefault>
        <div class="flex items-center justify-between">
          <span class="text-sm text-gray-700">Pending</span>
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-warning-100 text-warning-700">
            {{ unit.pending }}
          </span>
        </div>
      </CardDefault>
      <CardDefault>
        <div class="flex items-center justify-between">
          <span class="text-sm text-gray-700">Disetujui</span>
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-700">
            {{ unit.disetujui }}
          </span>
        </div>
      </CardDefault>
      <CardDefault>
        <div class="flex items-center justify-between">
          <span class="text-sm text-gray-700">Ditolak</span>
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-700">
            {{ unit.ditolak }}
          </span>
        </div>
      </CardDefault>
    </div>

    <!-- Daftar Pegawai Card -->
    <CardDefault>
      <div class="space-y-4">
        <h2 class="text-lg font-semibold text-gray-900">Daftar Pegawai</h2>

        <!-- Search -->
        <div class="flex justify-end">
          <FieldSearch
            name="filter.keyword"
            placeholder="Cari pegawai..."
            @update:value="onFilterKeywordUpdated"
          />
        </div>

        <SectionDefault :sectionStatus="pegawaiRecords.status">
          <LogbookAdminPegawaiTable
            :records="pegawaiRecords"
            @pageChange="onPageChange"
            @viewPegawaiLogbook="onViewPegawai"
          />
        </SectionDefault>
      </div>
    </CardDefault>
  </div>
</template>

<script setup lang="ts">
import {
  CardDefault,
  FieldSearch,
  SectionDefault
} from '@bssn/ui-kit-frontend';
import { computed, onMounted, onUnmounted, ref, type PropType } from 'vue';
import LogbookAdminPegawaiTable from './LogbookAdminPegawaiTable.vue';
import { useLogbookAdminStore } from '@/stores/admin/logbookAdminStore';
import type { UnitData, PegawaiAdminData } from '@/models/admin/logbookAdmin';

const props = defineProps({
  unit: {
    type: Object as PropType<UnitData>,
    required: true
  }
});

const emit = defineEmits<{
  viewPegawai: [pegawai: PegawaiAdminData];
  back: [];
}>();

const logbookAdminStore = useLogbookAdminStore();
const pegawaiRecords = computed(() => logbookAdminStore.pegawaiList);

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
  await logbookAdminStore.callUnitPegawai(
    props.unit.kode_unit,
    filterKeyword.value,
    currentPage.value
  );
};

const onViewPegawai = (pegawai: PegawaiAdminData) => {
  emit('viewPegawai', pegawai);
};

onMounted(async () => {
  await onLoadPegawai();
});

onUnmounted(() => {
  logbookAdminStore.clearPegawaiList();
});
</script>
