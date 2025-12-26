<template>
  <PageDefault
    ref="pageDefault"
    pageStatus="success"
    :isViewerFile="false"
    :isDrawerDefault="false"
    :isModalDefault="false"
    :isModalConfirmation="false"
    :isModalAlert="false"
    @confirmModalConfirmation="() => {}"
  >
    <div class="flex flex-col w-full h-full gap-4 xl:gap-6">
      <TitleSection variant="warning">DATA PENDAPATAN</TitleSection>
      <CardDefault>
        <div class="flex flex-col">
          <SectionDefault :sectionStatus="sectionStatus">
            <div class="flex flex-col justify-start gap-4 md:items-center md:flex-row">
              <FieldSelect
                :initialValue="defaultTahun"
                name="filter.tahun"
                :required="false"
                :options="periodeTahunOptions"
                placeholder="Pilih Tahun"
                @update:value="onFilterTahunUpdated"
              />
              <FieldSelect
                :initialValue="filterBulan"
                name="filter.bulan"
                :required="false"
                :options="periodeBulanOptions"
                placeholder="Pilih Bulan"
                :disabled="periodeBulanOptions.length === 0"
                @update:value="onFilterBulanUpdated"
              />
              <ButtonDefault
                variant="warning"
                :disabled="pendapatanRecord.status === 'loading' || filterBulan === ''"
                :loading="pendapatanRecord.status === 'loading'"
                @clicked="onLoad"
              >
                <div class="flex flex-row">
                  <font-awesome-icon :icon="['fad', 'search']" class="w-4 h-4 mr-1.5" />
                  <span> Cari </span>
                </div>
              </ButtonDefault>
            </div>
          </SectionDefault>
          <SectionDefault :sectionStatus="pendapatanRecord.status">
            <PendapatanTable :record="pendapatanRecord" />
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
  FieldSelect,
  PageDefault,
  SectionDefault,
  TitleSection,
  type PageDefaultExposed,
  type SectionStatus
} from '@bssn/ui-kit-frontend'
import { computed, onMounted, onUnmounted, ref } from 'vue'

import PendapatanTable from './PendapatanTable.vue'

import { usePendapatanStore } from '@/stores/pegawai/pendapatanStore'
import { usePeriodeStore } from '@/stores/periode'

const pageDefault = ref<PageDefaultExposed | null>(null)

const sectionStatus = ref<SectionStatus>('initial')

const periodeStore = usePeriodeStore()
const periodeAll = computed(() => periodeStore.all)
const periodeTahunOptions = computed(() => periodeStore.tahunOptions)
const periodeBulanOptions = computed(() => periodeStore.bulanOptions)
const pendapatanStore = usePendapatanStore()
const pendapatanRecord = computed(() => pendapatanStore.record)

const defaultTahun = ref<string>('')
const filterBulan = ref<string>('')

const onFilterTahunUpdated = (value: string) => {
  periodeStore.selectedTahun = value
}

const onFilterBulanUpdated = (value: string) => {
  filterBulan.value = value
}

const onLoad = async () => {
  if (filterBulan.value !== '') {
    await pendapatanStore.callRecord(filterBulan.value)
  }
}

// ================================================================================================
// Lifecycle
// ================================================================================================
onMounted(async () => {
  sectionStatus.value = 'loading'
  await periodeStore.callAll()
  if (periodeAll.value.status === 'success') {
    defaultTahun.value =
      periodeTahunOptions.value.length > 0
        ? periodeTahunOptions.value[periodeTahunOptions.value.length - 1].value
        : ''
    onFilterTahunUpdated(defaultTahun.value)

    sectionStatus.value = 'success'
  } else {
    sectionStatus.value = 'error'
  }
})
onUnmounted(() => {
  periodeStore.clearAll()
  pendapatanStore.clearRecord()
})
</script>
