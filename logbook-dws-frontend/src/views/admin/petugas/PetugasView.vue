<template>
  <PageDefault
    ref="pageDefault"
    pageStatus="success"
    :isViewerFile="false"
    :isDrawerDefault="true"
    :isModalDefault="false"
    :isModalConfirmation="true"
    :isModalAlert="false"
    @confirmModalConfirmation="onConfirmModalConfirmation"
  >
    <div class="flex flex-col w-full h-full gap-4 xl:gap-6">
      <TitleSection variant="warning">ADMIN KEUANGAN - PETUGAS</TitleSection>
      <CardDefault>
        <div class="flex flex-col gap-4">
          <div class="flex flex-col justify-end gap-4 md:items-center md:flex-row">
            <FieldSearch
              name="filter.keyword"
              placeholder="Cari"
              @update:value="onFilterKeywordUpdated"
            />
          </div>
          <SectionDefault :sectionStatus="petugasRecords.status">
            <PetugasTable
              :records="petugasRecords"
              @pageChange="onPageChange"
              @editClick="onEditClick"
              @deleteClick="onDeleteClick"
            >
              <template #action>
                <ButtonDefault variant="warning" @clicked="onAddClick">Tambah</ButtonDefault>
              </template>
            </PetugasTable>
          </SectionDefault>
        </div>
      </CardDefault>
    </div>
  </PageDefault>
</template>

<script setup lang="ts">
import {
  ButtonDefault,
  CardDefault,
  FieldSearch,
  PageDefault,
  SectionDefault,
  TitleSection,
  type PageDefaultExposed
} from '@bssn/ui-kit-frontend'
import { computed, onMounted, onUnmounted, ref } from 'vue'

import PetugasAdd from './form/PetugasAdd.vue'
import PetugasEdit from './form/PetugasEdit.vue'
import PetugasTable from './PetugasTable.vue'

import { readableRole } from '@/helpers/custom'
import type { PetugasData } from '@/models/admin/petugas'
import { usePetugasStore } from '@/stores/admin/petugasStore'

const pageDefault = ref<PageDefaultExposed | null>(null)

const petugasStore = usePetugasStore()
const petugasRecords = computed(() => petugasStore.records)
const petugasDelete = computed(() => petugasStore.delete)

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
  await petugasStore.callRecords(filterKeyword.value, currentPage.value)
}

const onAddClick = () => {
  pageDefault.value?.openDrawerDefault('Tambah Petugas', PetugasAdd, {
    onDone: () => {
      onLoad()
    }
  })
}

const onEditClick = (record: PetugasData, role: string) => {
  pageDefault.value?.openDrawerDefault('Ubah Petugas', PetugasEdit, {
    record: record,
    role: role,
    onDone: () => {
      onLoad()
    }
  })
}

const onDeleteClick = (record: PetugasData, role: string) => {
  pageDefault.value?.openModalConfirmation(
    'danger',
    'Ya, saya yakin',
    'Tidak, batalkan',
    record.guid,
    '',
    `Apakah anda yakin ingin menghapus role ${readableRole(role)} dari petugas bernama ${record.name}?`,
    {
      type: 'delete',
      role: role
    },
    'center',
    'max-w-xl'
  )
}

const onConfirmModalConfirmation = async (guid: string, data: Record<string, any>) => {
  if (data.type === 'delete') {
    await petugasStore.callDelete({
      guid: guid,
      role: data.role
    })
    if (petugasDelete.value.status === 'success') {
      petugasStore.clearDelete()
      if (currentPage.value > 1 && petugasStore.records.data!.length === 1) {
        currentPage.value--
      }
      await onLoad()
      pageDefault.value?.closeModalConfirmation()
    } else {
      pageDefault.value?.stopModalConfirmation()
    }
  }
}

// ================================================================================================
// Lifecycle
// ================================================================================================
onMounted(() => {
  onLoad()
})
onUnmounted(() => {
  petugasStore.clearRecords()
  petugasStore.clearDelete()
})
</script>
