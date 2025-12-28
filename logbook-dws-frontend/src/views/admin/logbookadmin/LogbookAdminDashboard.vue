<template>
  <div class="space-y-4">
    <!-- Cards Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
      <!-- Card 1: Total Logbook -->
      <CardDefault>
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900">Total Logbook</h3>
            <button class="flex items-center gap-2 px-3 py-1.5 border border-gray-300 rounded text-xs hover:bg-gray-50">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              {{ formattedMonth }}
            </button>
          </div>

          <div class="space-y-2">
            <div class="flex items-end gap-2">
              <div class="text-5xl font-bold text-gray-900">{{ summary?.total_logbook || 0 }}</div>
            </div>
            <div class="text-xs text-gray-500">Total logbook di semua unit</div>
          </div>
        </div>
      </CardDefault>

      <!-- Card 2: Total Unit & Pegawai -->
      <CardDefault>
        <div class="space-y-4">
          <h3 class="text-sm font-semibold text-gray-900">Statistik Organisasi</h3>

          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-700">Total Unit Kerja</span>
              <span class="text-2xl font-bold text-gray-900">{{ summary?.total_units || 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-700">Total Pegawai</span>
              <span class="text-2xl font-bold text-gray-900">{{ summary?.total_pegawai || 0 }}</span>
            </div>
          </div>
        </div>
      </CardDefault>

      <!-- Card 3: Status Logbook -->
      <CardDefault>
        <div class="space-y-4">
          <h3 class="text-sm font-semibold text-gray-900">Status Logbook</h3>

          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-700">Pending</span>
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-warning-100 text-warning-700">
                {{ summary?.pending || 0 }}
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-700">Disetujui</span>
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-700">
                {{ summary?.disetujui || 0 }}
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-700">Ditolak</span>
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-700">
                {{ summary?.ditolak || 0 }}
              </span>
            </div>
          </div>
        </div>
      </CardDefault>
    </div>

    <!-- Daftar Unit Kerja Card -->
    <CardDefault>
      <div class="space-y-4">
        <h2 class="text-lg font-semibold text-gray-900">Daftar Unit Kerja</h2>

        <!-- Search -->
        <div class="flex justify-end">
          <FieldSearch
            name="filter.keyword"
            placeholder="Cari unit..."
            @update:value="onFilterKeywordUpdated"
          />
        </div>

        <SectionDefault :sectionStatus="unitsRecords.status">
          <LogbookAdminUnitsTable
            :records="unitsRecords"
            @pageChange="onPageChange"
            @viewUnit="onViewUnit"
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
  SectionDefault,
  type PageDefaultExposed
} from '@bssn/ui-kit-frontend';
import { computed, onMounted, onUnmounted, ref, type PropType } from 'vue';
import LogbookAdminUnitsTable from './LogbookAdminUnitsTable.vue';
import { useLogbookAdminStore } from '@/stores/admin/logbookAdminStore';
import type { UnitData, PegawaiAdminData } from '@/models/admin/logbookAdmin';
import { formatCurrentMonth } from '@/helpers/custom';

const props = defineProps({
  pageDefault: {
    type: Object as PropType<PageDefaultExposed | null>,
    default: null
  }
});

const emit = defineEmits<{
  viewUnit: [unit: UnitData];
  viewPegawai: [pegawai: PegawaiAdminData];
}>();

const logbookAdminStore = useLogbookAdminStore();
const unitsRecords = computed(() => logbookAdminStore.units);
const summary = computed(() => logbookAdminStore.summary.data);

const filterKeyword = ref<string>('');
const currentPage = ref<number>(1);

const formattedMonth = computed(() => formatCurrentMonth());

const onFilterKeywordUpdated = (value: string) => {
  filterKeyword.value = value;
  currentPage.value = 1;
  onLoadUnits();
};

const onPageChange = (page: number) => {
  currentPage.value = page;
  onLoadUnits();
};

const onLoadUnits = async () => {
  await logbookAdminStore.callUnits(filterKeyword.value, currentPage.value);
};

const onLoadSummary = async () => {
  await logbookAdminStore.callSummary();
};

const onViewUnit = (unit: UnitData) => {
  emit('viewUnit', unit);
};

onMounted(async () => {
  await onLoadUnits();
  await onLoadSummary();
});

onUnmounted(() => {
  logbookAdminStore.clearUnits();
  logbookAdminStore.clearSummary();
});
</script>
