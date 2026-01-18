<template>
  <div class="flex flex-col w-full gap-4">
    <TableDefault>
      <template #header>
        <tr>
          <TableHeader alignment="center" customClass="w-0">No</TableHeader>
          <TableHeader>Nama</TableHeader>
          <TableHeader>NIP</TableHeader>
          <TableHeader>Pangkat</TableHeader>
          <TableHeader>Jabatan</TableHeader>
          <TableHeader alignment="center" customClass="w-32">Aksi</TableHeader>
        </tr>
      </template>
      <template #body>
        <tr v-if="records.status === 'loading'">
          <td colspan="6">
            <StateLoading info="Memuat data pegawai..." />
          </td>
        </tr>
        <template v-else-if="records.data && records.data.length > 0">
          <tr v-for="(pegawai, index) in records.data" :key="pegawai.id">
            <TableData alignment="center" customClass="w-0">
              {{ (records.meta.currentPage - 1) * records.meta.perPage + 1 + index }}
            </TableData>
            <TableData>{{ pegawai.nama }}</TableData>
            <TableData>{{ pegawai.nip }}</TableData>
            <TableData>{{ pegawai.pangkat }}</TableData>
            <TableData>{{ pegawai.jabatan }}</TableData>
            <TableData alignment="center">
              <ButtonOutline variant="info" @click="$emit('viewPegawaiLogbook', pegawai)">
                <div class="text-nowrap">Lihat Logbook</div>
              </ButtonOutline>
            </TableData>
          </tr>
        </template>
        <template v-else>
          <tr>
            <TableDataNone label="Tidak ada data pegawai" />
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
import type { PegawaiAdminData } from '@/models/admin/logbookAdmin';

defineProps({
  records: {
    type: Object as PropType<PaginatedRequestState<PegawaiAdminData[]>>,
    required: true
  }
});

const emit = defineEmits<{
  viewPegawaiLogbook: [pegawai: PegawaiAdminData];
  pageChange: [page: number];
}>();

const pageChange = (page: number) => {
  emit('pageChange', page);
};
</script>
