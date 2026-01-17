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
      <TitleSection variant="warning">ADMIN PERFORMA - PETUGAS</TitleSection>
      <TabDefault
        ref="tabDefault"
        :tabs="tabs"
        initial-tab="admin-eperforma"
      >
        <template #admin-eperforma>
          <AdminEperformaTab
            :pageDefault="pageDefault"
            @confirmDelete="onConfirmDelete"
          />
        </template>
        <template #pengelola-kinerja-organisasi>
          <PengelolaKinerjaOrganisasiTab
            :pageDefault="pageDefault"
            @confirmDelete="onConfirmDelete"
          />
        </template>
        <template #pmk>
          <PmkTab
            :pageDefault="pageDefault"
            @confirmDelete="onConfirmDelete"
          />
        </template>
      </TabDefault>
    </div>
  </PageDefault>
</template>

<script setup lang="ts">
import {
  PageDefault,
  TabDefault,
  TitleSection,
  type PageDefaultExposed
} from '@bssn/ui-kit-frontend'
import { ref } from 'vue'

import AdminEperformaTab from './tabs/AdminEperformaTab.vue'
import PengelolaKinerjaOrganisasiTab from './tabs/PengelolaKinerjaOrganisasiTab.vue'
import PmkTab from './tabs/PmkTab.vue'

const pageDefault = ref<PageDefaultExposed | null>(null)

const tabs = [
  {
    name: 'admin-eperforma',
    label: 'Admin ePerforma'
  },
  {
    name: 'pengelola-kinerja-organisasi',
    label: 'Pengelola Kinerja Organisasi'
  },
  {
    name: 'pmk',
    label: 'PMK'
  }
]

// Store pending delete callback
const pendingDeleteCallback = ref<(() => void) | null>(null)

const onConfirmDelete = (
  title: string,
  message: string,
  guid: string,
  onConfirm: () => void
) => {
  pendingDeleteCallback.value = onConfirm
  pageDefault.value?.openModalConfirmation(
    'danger',
    'Ya, saya yakin',
    'Tidak, batalkan',
    guid,
    '',
    message,
    { type: 'delete' },
    'center',
    'max-w-xl'
  )
}

const onConfirmModalConfirmation = async (guid: string, data: Record<string, any>) => {
  if (data.type === 'delete' && pendingDeleteCallback.value) {
    await pendingDeleteCallback.value()
    pendingDeleteCallback.value = null
    pageDefault.value?.closeModalConfirmation()
  }
}
</script>
