<template>
  <!-- <CardDefault> -->
    <div class="flex flex-col gap-4">
      <div class="flex flex-row items-center justify-between">
        <span class="font-semibold">TABEL ADMIN</span>
        <FieldSearch
          name="filter.keyword"
          placeholder="search"
          @update:value="onFilterKeywordUpdated"
        />
      </div>
      <SectionDefault :sectionStatus="records.status">
        <div class="flex flex-col w-full gap-4">
          <TableDefault>
            <template #header>
              <tr>
                <TableHeader alignment="center" customClass="w-0">NO</TableHeader>
                <TableHeader alignment="left">NAMA</TableHeader>
                <TableHeader alignment="center" customClass="w-0">AKSI</TableHeader>
              </tr>
            </template>
            <template #body>
              <template v-if="records.data && records.data.length > 0">
                <tr v-for="(record, index) in records.data" :key="record.guid">
                  <TableData alignment="center">
                    {{ (records.meta.currentPage - 1) * records.meta.perPage + 1 + index }}
                  </TableData>
                  <TableData>{{ record.name }}</TableData>
                  <TableData alignment="center">
                    <div class="flex flex-row justify-center gap-2">
                      <ButtonDelete @clicked="onDeleteClick(record)" />
                    </div>
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
          <TablePagination :pagination="records.meta" @update:page="onPageChange">
            <template #action>
              <ButtonDefault variant="warning" @clicked="onAddClick">Tambah Petugas</ButtonDefault>
            </template>
          </TablePagination>
        </div>
      </SectionDefault>
    </div>
  <!-- </CardDefault> -->
</template>

<script setup lang="ts">
import {
  ButtonDefault,
  ButtonDelete,
  CardDefault,
  FieldSearch,
  SectionDefault,
  TableData,
  TableDataNone,
  TableDefault,
  TableHeader,
  TablePagination,
  type PageDefaultExposed
} from '@bssn/ui-kit-frontend'
import { computed, onMounted, onUnmounted, ref, type PropType } from 'vue'

import AdminEperformaAdd from '../form/AdminEperformaAdd.vue'
import { useAdminEperformaStore } from '@/stores/admin/adminEperformaStore'
import type { AdminEperformaData } from '@/models/admin/petugasLogbook'

const props = defineProps({
  pageDefault: {
    type: Object as PropType<PageDefaultExposed | null>,
    required: true
  }
})

const emit = defineEmits(['confirmDelete'])

const adminEperformaStore = useAdminEperformaStore()
const records = computed(() => adminEperformaStore.records)
const deleteState = computed(() => adminEperformaStore.delete)

const filterKeyword = ref<string>('')
const currentPage = ref<number>(1)

const onFilterKeywordUpdated = (value: string) => {
  filterKeyword.value = value
  currentPage.value = 1
  onLoad()
}

const onPageChange = (page: number) => {
  currentPage.value = page
  onLoad()
}

const onLoad = async () => {
  await adminEperformaStore.callRecords(filterKeyword.value, currentPage.value)
}

const onAddClick = () => {
  props.pageDefault?.openDrawerDefault('Tambah Admin ePerforma', AdminEperformaAdd, {
    onDone: () => {
      onLoad()
    }
  })
}

const onDeleteClick = (record: AdminEperformaData) => {
  emit('confirmDelete',
    'Hapus Admin ePerforma',
    `Apakah anda yakin ingin menghapus ${record.name} dari Admin ePerforma?`,
    record.guid,
    async () => {
      await adminEperformaStore.callDelete(record.guid)
      if (deleteState.value.status === 'success') {
        adminEperformaStore.clearDelete()
        if (currentPage.value > 1 && records.value.data!.length === 1) {
          currentPage.value--
        }
        await onLoad()
      }
    }
  )
}

onMounted(() => {
  onLoad()
})

onUnmounted(() => {
  adminEperformaStore.clearRecords()
  adminEperformaStore.clearDelete()
})
</script>
