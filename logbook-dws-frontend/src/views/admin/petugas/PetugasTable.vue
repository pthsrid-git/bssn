<template>
  <div class="flex flex-col w-full gap-4">
    <TableDefault>
      <template #header>
        <tr>
          <TableHeader alignment="center" customClass="w-0">No</TableHeader>
          <TableHeader alignment="center">Pegawai</TableHeader>
          <TableHeader alignment="center">Role</TableHeader>
          <TableHeader alignment="center">Aksi</TableHeader>
        </tr>
      </template>
      <template #body>
        <template v-if="records.data!.length > 0">
          <template v-for="(record, recordIndex) in records.data" :key="recordIndex">
            <tr v-for="(role, roleIndex) in record.roles" :key="role">
              <template v-if="roleIndex === 0">
                <TableData :rowspan="record.roles.length" alignment="center" customClass="w-0">
                  {{ (records.meta.currentPage - 1) * records.meta.perPage + 1 + recordIndex }}
                </TableData>
                <TableData :rowspan="record.roles.length">
                  {{ record.name }}
                </TableData>
              </template>
              <TableData>
                {{ readableRole(role) }}
              </TableData>
              <TableData alignment="center" customClass="w-0">
                <div class="flex flex-row gap-2">
                  <ButtonEdit @clicked="editClick(record, role)" />
                  <ButtonDelete @clicked="deleteClick(record, role)" />
                </div>
              </TableData>
            </tr>
          </template>
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
  ButtonDelete,
  ButtonEdit,
  TableData,
  TableDataNone,
  TableDefault,
  TableHeader,
  TablePagination,
  type PaginatedRequestState
} from '@bssn/ui-kit-frontend'
import type { PropType } from 'vue'

import { readableRole } from '@/helpers/custom'
import type { PetugasData } from '@/models/admin/petugas'

// ================================================================================================
// Props & Emits
// ================================================================================================
defineProps({
  records: {
    type: Object as PropType<PaginatedRequestState<PetugasData[]>>,
    required: true
  }
})
const emit = defineEmits(['pageChange', 'editClick', 'deleteClick'])

// ================================================================================================
// Methods
// ================================================================================================
const pageChange = (page: number) => {
  emit('pageChange', page)
}
const editClick = (record: PetugasData, role: string) => {
  emit('editClick', record, role)
}
const deleteClick = (record: PetugasData, role: string) => {
  emit('deleteClick', record, role)
}
</script>
