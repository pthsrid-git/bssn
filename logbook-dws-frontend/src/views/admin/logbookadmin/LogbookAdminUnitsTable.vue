<template>
  <div class="flex flex-col w-full gap-4">
    <TableDefault>
      <template #header>
        <tr>
          <TableHeader alignment="center" customClass="w-0">No</TableHeader>
          <TableHeader>Kode Unit</TableHeader>
          <TableHeader>Nama Unit</TableHeader>
          <TableHeader alignment="center">Total Pegawai</TableHeader>
          <TableHeader alignment="center">Total Logbook</TableHeader>
          <TableHeader alignment="center">Pending</TableHeader>
          <TableHeader alignment="center">Disetujui</TableHeader>
          <TableHeader alignment="center">Ditolak</TableHeader>
          <TableHeader alignment="center" customClass="w-32">Aksi</TableHeader>
        </tr>
      </template>
      <template #body>
        <tr v-if="records.status === 'loading'">
          <td colspan="9">
            <StateLoading info="Memuat data unit..." />
          </td>
        </tr>
        <template v-else-if="records.data && records.data.length > 0">
          <tr v-for="(unit, index) in records.data" :key="unit.kode_unit">
            <TableData alignment="center" customClass="w-0">
              {{ (records.meta.currentPage - 1) * records.meta.perPage + 1 + index }}
            </TableData>
            <TableData>{{ unit.kode_unit }}</TableData>
            <TableData>{{ unit.nama_unit }}</TableData>
            <TableData alignment="center">{{ unit.total_pegawai }}</TableData>
            <TableData alignment="center">{{ unit.total_logbook }}</TableData>
            <TableData alignment="center">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
                {{ unit.pending }}
              </span>
            </TableData>
            <TableData alignment="center">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                {{ unit.disetujui }}
              </span>
            </TableData>
            <TableData alignment="center">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                {{ unit.ditolak }}
              </span>
            </TableData>
            <TableData alignment="center">
              <ButtonOutline variant="info" @click="$emit('viewUnit', unit)">
                <div class="text-nowrap">Lihat Detail</div>
              </ButtonOutline>
            </TableData>
          </tr>
        </template>
        <template v-else>
          <tr>
            <TableDataNone label="Tidak ada data unit" />
          </tr>
        </template>
      </template>
    </TableDefault>
    <TablePagination :pagination="records.meta" @update:page="pageChange" />
  </div>
</template>

<script setup lang="ts">
import {
  TableDefault,
  TableHeader,
  TableData,
  TableDataNone,
  TablePagination,
  ButtonOutline,
  StateLoading,
  type PaginatedRequestState
} from '@bssn/ui-kit-frontend';
import type { PropType } from 'vue';
import type { UnitData } from '@/models/admin/logbookAdmin';

defineProps({
  records: {
    type: Object as PropType<PaginatedRequestState<UnitData[]>>,
    required: true
  }
});

const emit = defineEmits<{
  viewUnit: [unit: UnitData];
  pageChange: [page: number];
}>();

const pageChange = (page: number) => {
  emit('pageChange', page);
};
</script>
