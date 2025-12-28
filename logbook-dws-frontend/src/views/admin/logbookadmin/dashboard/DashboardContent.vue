<template>
  <div class="space-y-4">
    <!-- Cards Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
      <CardPengisianLogbook
        :formatted-month="formattedMonth"
        :total-logbook="summary?.total_logbook || 0"
        :percentage="logbookPercentage"
        :date-range="dateRangeText"
      />

      <CardPengisianLogbookUnit
        :top-units="topUnits"
        :date-range="dateRangeText"
        @scroll-to-table="scrollToTable"
      />

      <CardKebutuhanFormasi :chart-data="formasiChartData" />
    </div>

    <!-- Table Pegawai -->
    <TablePegawai
      :pegawai-records="pegawaiRecords"
      @view-pegawai="onViewPegawai"
    />
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, onUnmounted } from 'vue';
import { useLogbookAdminStore } from '@/stores/admin/logbookAdminStore';
import type { PegawaiAdminData } from '@/models/admin/logbookAdmin';
import { formatCurrentMonth } from '@/helpers/custom';
import CardPengisianLogbook from './form/CardPengisianLogbook.vue';
import CardPengisianLogbookUnit from './form/CardPengisianLogbookUnit.vue';
import CardKebutuhanFormasi from './form/CardKebutuhanFormasi.vue';
import TablePegawai from './form/TablePegawai.vue';

const emit = defineEmits<{
  viewPegawai: [pegawai: PegawaiAdminData];
}>();

const logbookAdminStore = useLogbookAdminStore();
const pegawaiRecords = computed(() => logbookAdminStore.pegawaiList);
const unitsRecords = computed(() => logbookAdminStore.units);
const summary = computed(() => logbookAdminStore.summary.data);

const formattedMonth = computed(() => formatCurrentMonth());

// Date range text
const dateRangeText = computed(() => {
  const now = new Date();
  const year = now.getFullYear();
  const month = now.getMonth() + 1;
  const daysInMonth = new Date(year, month, 0).getDate();
  const monthName = now.toLocaleDateString('id-ID', { month: 'long' });
  return `1 ${monthName} ${year} - ${daysInMonth} ${monthName} ${year}`;
});

// Logbook percentage
const logbookPercentage = computed(() => {
  const total = summary.value?.total_logbook || 0;
  const pegawai = summary.value?.total_pegawai || 1;
  return Math.round((total / (pegawai * 30)) * 100);
});

// Top units with percentage
const topUnits = computed(() => {
  if (!unitsRecords.value.data || unitsRecords.value.data.length === 0) {
    return [];
  }

  return unitsRecords.value.data
    .slice(0, 4)
    .map(unit => ({
      ...unit,
      percentage: unit.total_logbook > 0 && unit.total_pegawai > 0
        ? Math.min(Math.round((unit.total_logbook / (unit.total_pegawai * 30)) * 100), 100)
        : 0
    }));
});

// Chart data for Kebutuhan Formasi Ideal
const formasiChartData = computed(() => ({
  series: [
    {
      name: 'Jumlah',
      data: [
        { x: 'Pelaksana', y: '45' },
        { x: 'Fungsional', y: '48' },
        { x: 'Manjerial', y: '40' }
      ]
    },
    {
      name: 'Kebutuhan Ideal',
      data: [
        { x: 'Pelaksana', y: '50' },
        { x: 'Fungsional', y: '50' },
        { x: 'Manjerial', y: '50' }
      ]
    }
  ],
  colors: ['#3B82F6', '#10B981']
}));

const onLoadPegawai = async () => {
  await logbookAdminStore.callAllPegawai('', 1);
};

const onLoadUnits = async () => {
  await logbookAdminStore.callUnits('', 1);
};

const onLoadSummary = async () => {
  await logbookAdminStore.callSummary();
};

const onViewPegawai = (pegawai: PegawaiAdminData) => {
  emit('viewPegawai', pegawai);
};

const scrollToTable = () => {
  const pegawaiTable = document.querySelector('.space-y-4 > div:last-child');
  if (pegawaiTable) {
    pegawaiTable.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
};

onMounted(async () => {
  await Promise.all([
    onLoadPegawai(),
    onLoadUnits(),
    onLoadSummary()
  ]);
});

onUnmounted(() => {
  logbookAdminStore.clearPegawaiList();
  logbookAdminStore.clearUnits();
  logbookAdminStore.clearSummary();
});
</script>
