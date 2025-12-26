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
            <PengajuanTable
              :records="pengajuanRecords"
              @pageChange="onPageChange"
              @reviewClick="onReviewClick"
            >
              <template #action>
                <div class="flex flex-col gap-2 xl:flex-row">
                  <ButtonDefault
                    @clicked="onDiprosesAll()"
                    :loading="pengajuanDiprosesAll.status === 'loading'"
                    :disabled="pengajuanDiprosesAll.status === 'loading'"
                  >
                    Proses Semua
                  </ButtonDefault>
                  <ButtonDefault
                    @clicked="onDiselesaikanAll()"
                    :loading="pengajuanDiselesaikanAll.status === 'loading'"
                    :disabled="pengajuanDiselesaikanAll.status === 'loading'"
                  >
                    Selesaikan Semua
                  </ButtonDefault>
                </div>
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

import PengajuanReview from './form/PengajuanReview.vue'
import PengajuanTable from './PengajuanTable.vue'

import type { PengajuanData } from '@/models/pengolah/pengajuan'
import { usePengajuanStore } from '@/stores/pengolah/pengajuanStore'

const pageDefault = ref<PageDefaultExposed | null>(null)

const pengajuanStore = usePengajuanStore()
const pengajuanRecords = computed(() => pengajuanStore.records)
const pengajuanDiprosesAll = computed(() => pengajuanStore.diprosesAll)
const pengajuanDiselesaikanAll = computed(() => pengajuanStore.diselesaikanAll)

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

const onReviewClick = (record: PengajuanData) => {
  pageDefault.value?.openDrawerDefault('Reviu Pengajuan Logbook', PengajuanReview, {
    record: record,
    onDone: () => {
      onLoad()
    }
  })
}

const onDiprosesAll = () => {
  pageDefault.value?.openModalConfirmation(
    'danger',
    'Ya, saya yakin',
    'Tidak, batalkan',
    '',
    '',
    `Apakah anda yakin ingin memproses seluruh pengajuan slip gaji?`,
    {
      type: 'diproses'
    },
    'center',
    'max-w-xl'
  )
}

const onDiselesaikanAll = () => {
  pageDefault.value?.openModalConfirmation(
    'danger',
    'Ya, saya yakin',
    'Tidak, batalkan',
    '',
    '',
    `Apakah anda yakin ingin menyelesaikan seluruh pengajuan slip gaji?`,
    {
      type: 'diselesaikan'
    },
    'center',
    'max-w-xl'
  )
}

const onConfirmModalConfirmation = async (id: string, data: Record<string, any>) => {
  if (data.type === 'diproses') {
    await pengajuanStore.callDiprosesAll({
      status: 'diproses'
    })
    if (pengajuanDiprosesAll.value.status === 'success') {
      pengajuanStore.clearDiprosesAll()
      if (currentPage.value > 1 && pengajuanStore.records.data!.length === 1) {
        currentPage.value--
      }
      await onLoad()
      pageDefault.value?.closeModalConfirmation()
    } else {
      pageDefault.value?.stopModalConfirmation()
    }
  } else if (data.type === 'diselesaikan') {
    await pengajuanStore.callDiselesaikanAll({
      status: 'selesai',
      keterangan: 'Slip gaji sudah dapat diambil'
    })
    if (pengajuanDiselesaikanAll.value.status === 'success') {
      pengajuanStore.clearDiselesaikanAll()
      if (currentPage.value > 1 && pengajuanStore.records.data!.length === 1) {
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
  pengajuanStore.clearRecords()
  pengajuanStore.clearDiprosesAll()
  pengajuanStore.clearDiselesaikanAll()
})
</script>
