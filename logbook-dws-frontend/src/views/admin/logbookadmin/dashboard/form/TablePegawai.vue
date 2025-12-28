<template>
  <div class="space-y-4">
    <h2 class="text-lg font-semibold text-gray-900">Daftar Laporan Unit Kerja</h2>

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
              <div class="flex justify-center">
                <button
                  class="px-3 py-1 border border-info-300 text-info-600 rounded text-xs hover:bg-info-50 whitespace-nowrap"
                  @click="$emit('viewPegawai', pegawai)"
                >
                  Lihat logbook
                </button>
              </div>
            </TableData>
          </tr>
        </template>
        <tr v-else>
          <TableDataNone label="Tidak ada data pegawai" colspan="7" />
        </tr>
      </template>
    </TableDefault>

    <TablePagination
      v-if="pegawaiRecords.meta"
      :pagination="pegawaiRecords.meta"
    />
  </div>
</template>

<script setup lang="ts">
import {
  TableDefault,
  TableHeader,
  TableData,
  TableDataNone,
  TablePagination
} from '@bssn/ui-kit-frontend';
import type { PropType } from 'vue';
import type { PegawaiAdminData } from '@/models/admin/logbookAdmin';

interface PegawaiRecords {
  data: PegawaiAdminData[] | null;
  status: string;
  meta: {
    currentPage: number;
    lastPage: number;
    perPage: number;
    total: number;
  };
  [key: string]: any;
}

defineProps({
  pegawaiRecords: {
    type: Object as PropType<PegawaiRecords>,
    required: true
  }
});

defineEmits<{
  viewPegawai: [pegawai: PegawaiAdminData];
}>();
</script>
