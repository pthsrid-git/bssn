<template>
  <PageDefault
    ref="pageDefault"
    pageStatus="success"
    :isViewerFile="true"
    :isDrawerDefault="true"
    :isModalDefault="true"
    :isModalConfirmation="false"
    :isModalAlert="false"
    @confirmModalConfirmation="() => {}"
  >
    <div class="flex flex-col w-full h-full gap-4 xl:gap-6">
      <TitleSection variant="warning">LOGBOOK ADMIN</TitleSection>
      <TabDefault
        ref="tabDefault"
        :tabs="tabs"
        initial-tab="dashboard"
      >
        <template #dashboard>
          <DashboardView :pageDefault="pageDefault" />
        </template>
        <template #abk>
          <AbkView :pageDefault="pageDefault" />
        </template>
      </TabDefault>
    </div>
  </PageDefault>
</template>

<script setup lang="ts">
import { PageDefault, TabDefault, TitleSection, type PageDefaultExposed } from '@bssn/ui-kit-frontend';
import { ref } from 'vue';
import DashboardView from './dashboard/DashboardView.vue';
import AbkView from './abk/AbkView.vue';

const pageDefault = ref<PageDefaultExposed | null>(null);

const tabs = [
  {
    name: 'dashboard',
    label: 'Dashboard'
  },
  {
    name: 'abk',
    label: 'ABK'
  }
];
</script>
