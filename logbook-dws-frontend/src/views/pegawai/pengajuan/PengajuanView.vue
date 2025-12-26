<template>
  <PageDefault
    ref="pageDefault"
    pageStatus="success"
    :isViewerFile="false"
    :isDrawerDefault="true"
    :isModalDefault="false"
    :isModalConfirmation="false"
    :isModalAlert="false"
    @confirmModalConfirmation="() => {}"
  >
    <div class="flex flex-col w-full h-full gap-4 xl:gap-6">
      <TitleSection variant="warning">DAFTAR PENGAJUAN SLIP GAJI</TitleSection>
      <CardDefault>
        <div class="flex flex-col gap-4">
          <div class="flex flex-col justify-end gap-4 md:items-center md:flex-row">
            <FieldSearch
              name="filter.keyword"
              placeholder="Cari"
              @update:value="onFilterKeywordUpdated"
            />
          </div>
          <SectionDefault :sectionStatus="pengajuanRecords.status">
            <PengajuanTable :records="pengajuanRecords" @pageChange="onPageChange">
              <template #action>
                <ButtonDefault variant="warning" @clicked="onAddClick">Tambah</ButtonDefault>
              </template>
            </PengajuanTable>
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

import PengajuanAdd from './form/PengajuanAdd.vue'
import PengajuanTable from './PengajuanTable.vue'

import { usePengajuanStore } from '@/stores/pegawai/pengajuanStore'

const pageDefault = ref<PageDefaultExposed | null>(null)

const pengajuanStore = usePengajuanStore()
const pengajuanRecords = computed(() => pengajuanStore.records)

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
  await pengajuanStore.callRecords(filterKeyword.value, currentPage.value)
}

const onAddClick = () => {
  pageDefault.value?.openDrawerDefault('Tambah Pengajuan Logbook', PengajuanAdd, {
    onDone: () => {
      onLoad()
    }
  })
}

// ================================================================================================
// Lifecycle
// ================================================================================================
onMounted(() => {
  onLoad()
})
onUnmounted(() => {
  pengajuanStore.clearRecords()
})
</script>
