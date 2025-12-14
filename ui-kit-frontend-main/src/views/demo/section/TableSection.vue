<template>
  <div class="flex flex-col items-center justify-between w-full gap-4">
    <TableDefault>
      <template #header>
        <tr>
          <TableHeader alignment="center" customClass="w-0">No</TableHeader>
          <TableHeader alignment="center">Pegawai</TableHeader>
          <TableHeader alignment="center">Aksi</TableHeader>
        </tr>
      </template>
      <template #body>
        <tr class="odd:bg-white even:bg-gray-50">
          <TableData alignment="center" customClass="w-0">1</TableData>
          <TableData alignment="center">Agung Kurniawan</TableData>
          <TableData alignment="center">
            <div class="flex flex-row items-center justify-center gap-2">
              <ButtonEdit />
              <ButtonDelete />
            </div>
          </TableData>
        </tr>
        <tr>
          <TableDataNone />
        </tr>
      </template>
    </TableDefault>
    <TablePagination
      :pagination="{
        currentPage: 1,
        lastPage: 3,
        perPage: 5,
        total: 6
      }"
    >
    </TablePagination>
  </div>
</template>

<script setup lang="ts">
import ButtonDelete from '@/components/button/ButtonDelete.vue'
import ButtonEdit from '@/components/button/ButtonEdit.vue'
import TableData from '@/components/table/TableData.vue'
import TableDataNone from '@/components/table/TableDataNone.vue'
import TableDefault from '@/components/table/TableDefault.vue'
import TableHeader from '@/components/table/TableHeader.vue'
import TablePagination from '@/components/table/TablePagination.vue'
</script>
