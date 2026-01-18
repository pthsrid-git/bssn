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

        <!-- Table -->
        <TableDefault>
          <template #header>
            <tr>
              <TableHeader alignment="center" customClass="w-16">No</TableHeader>
              <TableHeader>Nama</TableHeader>
              <TableHeader>NIP</TableHeader>
              <TableHeader alignment="center">Pangkat</TableHeader>
              <TableHeader alignment="center">Jabatan</TableHeader>
              <TableHeader alignment="center">Tim</TableHeader>
              <TableHeader alignment="center" customClass="w-32">Aksi</TableHeader>
            </tr>
          </template>
          <template #body>
            <tr v-if="pegawaiRecords.status === 'loading'">
              <TableDataNone label="Memuat data..." colspan="7" />
            </tr>
            <template v-else-if="pegawaiRecords.data && pegawaiRecords.data.length > 0">
              <tr v-for="(pegawai, index) in pegawaiRecords.data" :key="pegawai.id" class="hover:bg-gray-50">
                <TableData alignment="center">{{ (pegawaiRecords.meta.currentPage - 1) * pegawaiRecords.meta.perPage + index + 1 }}</TableData>
                <TableData>{{ pegawai.nama }}</TableData>
                <TableData>{{ pegawai.nip }}</TableData>
                <TableData alignment="center">{{ pegawai.pangkat || '-' }}</TableData>
                <TableData alignment="center">{{ pegawai.jabatan || '-' }}</TableData>
                <TableData alignment="center">{{ pegawai.tim || '-' }}</TableData>
                <TableData alignment="center">
                <!-- <ButtonOutline
                    variant="info"
                    @clicked="onViewPegawai(pegawai)"
                    > Lihat Logbook 
                </ButtonOutline> -->
                </TableData>
              </tr>
            </template>
            <tr v-else>
              <TableDataNone label="Tidak ada data pegawai" colspan="7" />
            </tr>
          </template>
        </TableDefault>

        <!-- Pagination -->
        <TablePagination
          v-if="pegawaiRecords.meta"
          :pagination="pegawaiRecords.meta"
        />
      </div>
    </CardDefault>
  </div>
</template>

<script setup lang="ts">
import {
  CardDefault,
  TableDefault,
  TableHeader,
  TableData,
  TableDataNone,
  TablePagination,
  ButtonOutline
} from '@bssn/ui-kit-frontend';
import { computed, onMounted, onUnmounted, type PropType } from 'vue';
import { useLogbookAdminStore } from '@/stores/admin/logbookAdminStore';
import type { UnitData, PegawaiAdminData } from '@/models/admin/logbookAdmin';

const props = defineProps({
  unit: {
    type: Object as PropType<UnitData>,
    required: true
  }
});

// const emit = defineEmits<{
//   viewPegawai: [pegawai: PegawaiAdminData];
//   back: [];
// }>();

const logbookAdminStore = useLogbookAdminStore();
const pegawaiRecords = computed(() => logbookAdminStore.pegawaiList);

const onLoadPegawai = async () => {
  await logbookAdminStore.callUnitPegawai(
    props.unit.kode_unit,
    '',
    1
  );
};

// const onViewPegawai = (pegawai: PegawaiAdminData) => {
//   emit('viewPegawai', pegawai);
// };

onMounted(async () => {
  await onLoadPegawai();
});

onUnmounted(() => {
  logbookAdminStore.clearPegawaiList();
});
</script>
