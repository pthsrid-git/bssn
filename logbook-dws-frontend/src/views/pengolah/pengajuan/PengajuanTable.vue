<template>
  <div class="flex flex-col w-full gap-4">
    <TableDefault>
      <template #header>
        <tr>
          <TableHeader alignment="center" customClass="w-0">No</TableHeader>
          <TableHeader alignment="center">Nama Pegawai</TableHeader>
          <TableHeader alignment="center">Tanggal Pengajuan</TableHeader>
          <TableHeader alignment="center">Periode Gaji</TableHeader>
          <TableHeader alignment="center">Keperluan</TableHeader>
          <TableHeader alignment="center">Catatan Khusus</TableHeader>
          <TableHeader alignment="center">Status</TableHeader>
          <TableHeader alignment="center">Keterangan</TableHeader>
        </tr>
      </template>
      <template #body>
        <template v-if="records.data!.length > 0">
          <tr v-for="(record, recordIndex) in records.data" :key="recordIndex">
            <TableData alignment="center" customClass="w-0">
              {{ (records.meta.currentPage - 1) * records.meta.perPage + 1 + recordIndex }}
            </TableData>
            <TableData>
              {{ record.nama }}
            </TableData>
            <TableData>
              {{ readableDate(formatDate(record.tanggalPengajuan)) }}
            </TableData>
            <TableData>
              {{ record.periodeGaji }}
            </TableData>
            <TableData :alignment="record.keperluan ? 'left' : 'center'">
              {{ record.keperluan ?? '-' }}
            </TableData>
            <TableData :alignment="record.catatan ? 'left' : 'center'">
              {{ record.catatan ?? '-' }}
            </TableData>
            <TableData>
              <BadgeDefault
                v-if="record.status === 'dibuat'"
                variant="info"
                customClass="cursor-pointer"
                @click="reviewClick(record)"
              >
                {{ capitalizeFirst(record.status) }}
              </BadgeDefault>
              <BadgeDefault
                v-if="record.status === 'diproses'"
                variant="warning"
                customClass="cursor-pointer"
                @click="reviewClick(record)"
              >
                {{ capitalizeFirst(record.status) }}
              </BadgeDefault>
              <BadgeDefault
                v-if="record.status === 'ditolak'"
                variant="danger"
                customClass="opacity-80 cursor-not-allowed"
              >
                {{ capitalizeFirst(record.status) }}
              </BadgeDefault>
              <BadgeDefault
                v-if="record.status === 'selesai'"
                variant="success"
                customClass="opacity-80 cursor-not-allowed"
              >
                {{ capitalizeFirst(record.status) }}
              </BadgeDefault>
            </TableData>
            <TableData :alignment="record.keterangan ? 'left' : 'center'">
              {{ record.keterangan ?? '-' }}
            </TableData>
          </tr>
        </template>
        <template v-else>
          <tr>
            <TableDataNone />
          </tr>
        </template>
      </template>
    </TableDefault>
    <TablePagination :pagination="records.meta" @update:page="pageChange">
      <template #action>
        <slot name="action"></slot>
      </template>
    </TablePagination>
  </div>
</template>

<script setup lang="ts">
import {
  BadgeDefault,
  capitalizeFirst,
  readableDate,
  TableData,
  TableDataNone,
  TableDefault,
  TableHeader,
  TablePagination,
  type PaginatedRequestState
} from '@bssn/ui-kit-frontend'
import type { PropType } from 'vue'

import { formatDate } from '@/helpers/custom'
import type { PengajuanData } from '@/models/pengolah/pengajuan'

// ================================================================================================
// Props & Emits
// ================================================================================================
defineProps({
  records: {
    type: Object as PropType<PaginatedRequestState<PengajuanData[]>>,
    required: true
  }
})
const emit = defineEmits(['pageChange', 'reviewClick'])

// ================================================================================================
// Methods
// ================================================================================================
const pageChange = (page: number) => {
  emit('pageChange', page)
}
const reviewClick = (record: PengajuanData) => {
  emit('reviewClick', record)
}
</script>
