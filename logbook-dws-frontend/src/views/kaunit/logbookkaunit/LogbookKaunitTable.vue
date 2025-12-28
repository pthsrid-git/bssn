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
        <template v-if="records.data && records.data.length > 0">
          <tr v-for="(pegawai, index) in records.data" :key="pegawai.id" class="hover:bg-gray-50">
            <TableData alignment="center" customClass="w-0">
              {{ (records.meta.currentPage - 1) * records.meta.perPage + 1 + index }}
            </TableData>
            <TableData>{{ pegawai.nama }}</TableData>
            <TableData>{{ pegawai.nip }}</TableData>
            <TableData>{{ pegawai.pangkat }}</TableData>
            <TableData>{{ pegawai.jabatan }}</TableData>
            <TableData alignment="center">
              <ButtonOutline variant="info" @click="$emit('viewPegawaiLogbook', pegawai)">
                Lihat
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
  type PaginatedRequestState
} from '@bssn/ui-kit-frontend';
import type { PropType } from 'vue';
import type { PegawaiData } from '@/models/kaunit/logbookKaunit';

// ================================================================================================
// Props & Emits
// ================================================================================================
defineProps({
  records: {
    type: Object as PropType<PaginatedRequestState<PegawaiData[]>>,
    required: true
  }
});

const emit = defineEmits<{
  viewPegawaiLogbook: [pegawai: PegawaiData];
  pageChange: [page: number];
}>();

// ================================================================================================
// Methods
// ================================================================================================
const pageChange = (page: number) => {
  emit('pageChange', page);
};
</script>
