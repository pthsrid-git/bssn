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
      <TitleSection variant="warning">ADMIN KEUANGAN - PENDAPATAN</TitleSection>
      <CardDefault>
        <SectionDefault :sectionStatus="sectionStatus">
          <div class="flex flex-col gap-4">
            <div class="flex flex-col justify-between gap-4 md:items-center md:flex-row">
              <FieldSelect
                :initialValue="defaultTahun"
                name="filter.tahun"
                :required="false"
                :options="periodeTahunOptions"
                placeholder="Pilih Tahun"
                @update:value="onFilterTahunUpdated"
              />
              <ButtonDefault variant="warning" @clicked="onAddClick">Tambah</ButtonDefault>
            </div>
            <PendapatanTable :records="periodeRecords" />
          </div>
        </SectionDefault>
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

import PendapatanAdd from './form/PendapatanAdd.vue'
import PendapatanTable from './PendapatanTable.vue'

import { usePeriodeStore } from '@/stores/periode'

const pageDefault = ref<PageDefaultExposed | null>(null)

const sectionStatus = ref<SectionStatus>('initial')

const periodeStore = usePeriodeStore()
const periodeAll = computed(() => periodeStore.all)
const periodeTahunOptions = computed(() => periodeStore.tahunOptions)
const periodeRecords = computed(() => periodeStore.records)

const defaultTahun = ref<string>('')

const onFilterTahunUpdated = (value: string) => {
  periodeStore.selectedTahun = value
}

const onAddClick = () => {
  pageDefault.value?.openDrawerDefault('Tambah Pendapatan', PendapatanAdd, {
    onDone: async () => {
      await periodeStore.callAll()
    }
  })
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
})
</script>
