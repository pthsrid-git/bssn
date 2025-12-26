<template>
  <PageDefault
    ref="pageDefault"
    :pageStatus="pageStatus"
    :isViewerFile="true"
    :isDrawerDefault="true"
    :isModalDefault="true"
    :isModalConfirmation="true"
    :isModalAlert="true"
    @confirmModalConfirmation="onConfirmModalConfirmation"
  >
    <div
      class="flex flex-col w-full h-full p-4 overflow-auto gap-y-4 xl:gap-y-6 xl:p-6 bg-primary-50"
    >
      <div class="w-full text-center">
        <TitlePage>Components</TitlePage>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Accordion</TitleSection>
          <AccordionSection />
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Badge</TitleSection>
          <BadgeSection />
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Breadcrumb</TitleSection>
          <BreadcrumbSection />
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Button</TitleSection>
          <ButtonSection />
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Chart</TitleSection>
          <ChartSection />
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Chip</TitleSection>
          <ChipSection />
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Drawer</TitleSection>
          <div class="flex flex-row flex-wrap items-center justify-center gap-2">
            <ButtonDefault @click="openDrawerDefault"> Open Drawer Default </ButtonDefault>
          </div>
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Entry</TitleSection>
          <div class="p-4 rounded-sm bg-primary-50">
            <EntrySection />
          </div>
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Form</TitleSection>
          <FormSection />
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Icon</TitleSection>
          <IconSection />
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Image</TitleSection>
          <div class="flex flex-row items-center justify-center">
            <ImageDefault objectFit="cover" url="https://picsum.photos/seed/thumb8/400/200" />
          </div>
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Link</TitleSection>
          <LinkSection />
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Modal</TitleSection>
          <div class="flex flex-row flex-wrap items-center justify-center gap-2">
            <ButtonDefault @click="openModalDefault"> Open Modal Button </ButtonDefault>
            <ButtonDefault @click="openModalConfirmation">
              Open Modal Confirmation Button
            </ButtonDefault>
            <ButtonDefault @click="openModalAlert"> Open Modal Alert Button </ButtonDefault>
            <ButtonDefault @click="openModalAlertDuration">
              Open Modal Alert Duration Button
            </ButtonDefault>
          </div>
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">State</TitleSection>
          <SectionSection />
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Tab</TitleSection>
          <TabSection />
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Table</TitleSection>
          <TableSection />
        </CardDefault>
      </div>

      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Title</TitleSection>
          <div class="flex flex-col items-center justify-center gap-2">
            <TitlePage>Page Title</TitlePage>
            <TitleSection variant="default">Section Title</TitleSection>
            <TitleSection variant="primary">Section Title</TitleSection>
            <TitleSection variant="warning">Section Title</TitleSection>
            <TitleSection variant="success">Section Title</TitleSection>
            <TitleSection variant="danger">Section Title</TitleSection>
            <TitleSection variant="info">Section Title</TitleSection>
          </div>
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">Viewer</TitleSection>
          <div class="flex flex-row flex-wrap items-center justify-center gap-2">
            <ButtonDefault @click="openViewerFileDocument">
              Open Viewer File Document
            </ButtonDefault>
            <ButtonDefault @click="openViewerFileVideo"> Open Viewer File Video </ButtonDefault>
            <ButtonDefault @click="openViewerFileYoutube"> Open Viewer File Youtube </ButtonDefault>
            <ButtonDefault @click="openViewerFileImage"> Open Viewer File Image </ButtonDefault>
            <ButtonDefault @click="openViewerFileUnknown"> Open Viewer File Unknown </ButtonDefault>
          </div>
          <div class="flex flex-row flex-wrap items-center justify-center gap-2">
            <ViewerFile
              title="File Document"
              url="https://ads.agungkdev.com/creatives/test.pdf"
              :wrapper="false"
            />
            <ViewerFile
              title="File Video"
              url="https://ads.agungkdev.com/creatives/test.mp4"
              :wrapper="false"
            />
            <ViewerFile
              title="File Youtube"
              url="https://www.youtube.com/watch?v=WML5zfS4ivQ"
              :wrapper="false"
            />
            <ViewerFile
              title="File Image"
              url="https://ads.agungkdev.com/creatives/test.jpg"
              :wrapper="false"
            />
          </div>
        </CardDefault>
      </div>
      <div class="flex flex-col">
        <CardDefault class="flex flex-col gap-4">
          <TitleSection variant="warning">*Special Menu Badge Controller</TitleSection>
          <div class="flex flex-row flex-wrap items-center justify-center gap-2">
            <ButtonDefault @click="menuBadgeDropdown(1)"> Badge Type 1 </ButtonDefault>
            <ButtonDefault @click="menuBadgeDropdown(2)"> Badge Type 2 </ButtonDefault>
            <ButtonDefault @click="menuBadgeDropdown(3)"> Badge Type 3 </ButtonDefault>
          </div>
        </CardDefault>
      </div>
    </div>
  </PageDefault>
</template>

<script setup lang="ts">
import { ref } from 'vue'

import AccordionSection from './section/AccordionSection.vue'
import BadgeSection from './section/BadgeSection.vue'
import BreadcrumbSection from './section/BreadcrumbSection.vue'
import ButtonSection from './section/ButtonSection.vue'
import ChartSection from './section/ChartSection.vue'
import ChipSection from './section/ChipSection.vue'
import EntrySection from './section/EntrySection.vue'
import FormSection from './section/FormSection.vue'
import IconSection from './section/IconSection.vue'
import LinkSection from './section/LinkSection.vue'
import SectionSection from './section/SectionSection.vue'
import TableSection from './section/TableSection.vue'
import TabSection from './section/TabSection.vue'

import ButtonDefault from '@/components/button/ButtonDefault.vue'
import CardDefault from '@/components/card/CardDefault.vue'
import ImageDefault from '@/components/image/ImageDefault.vue'
import PageDefault from '@/components/page/PageDefault.vue'
import StateUnderConstruction from '@/components/state/StateUnderConstruction.vue'
import TitlePage from '@/components/title/TitlePage.vue'
import TitleSection from '@/components/title/TitleSection.vue'
import ViewerFile from '@/components/viewer/ViewerFile.vue'
import { log } from '@/helpers'
import { updateBadgeDropdownUiKitFrontend } from '@/index'
import { type PageDefaultExposed, type PageStatus } from '@/models'
import { useDropdownStore } from '@/stores/dropdownStore'

const pageStatus = ref<PageStatus>('success')
const pageDefault = ref<PageDefaultExposed | null>(null)

//----Drawer----//
const openDrawerDefault = () => {
  pageDefault.value?.openDrawerDefault('Drawer Default', StateUnderConstruction)
}

//----Modal----//
const openModalAlert = () => {
  pageDefault.value?.openModalAlert(
    'info',
    'Modal Alert',
    'You have done this.',
    0,
    'center',
    'max-w-xl'
  )
}
const openModalAlertDuration = () => {
  pageDefault.value?.openModalAlert(
    'success',
    'Modal Alert',
    'Successfully done.',
    3000,
    'center',
    'max-w-xl'
  )
}
const openModalConfirmation = () => {
  pageDefault.value?.openModalConfirmation(
    'danger',
    "Yes, I'm sure",
    'No, Cancel',
    '1',
    'Modal Confirmation',
    'Are you sure you want to do this?',
    {},
    'center',
    'max-w-xl'
  )
}
const onConfirmModalConfirmation = async (id: string, data: Record<string, any>) => {
  log(id, data)
  await new Promise((resolve) => setTimeout(resolve, 1000))
  pageDefault.value?.closeModalConfirmation()
}
const openModalDefault = () => {
  pageDefault.value?.openModalDefault(
    'Modal Default',
    StateUnderConstruction,
    {},
    'center',
    true,
    'max-w-xl'
  )
}

//----Viewer----//
const openViewerFileDocument = () => {
  pageDefault.value?.openViewerFile('Title', 'https://ads.agungkdev.com/creatives/test.pdf')
}
const openViewerFileVideo = () => {
  pageDefault.value?.openViewerFile('Title', 'https://ads.agungkdev.com/creatives/test.mp4')
}

const openViewerFileYoutube = () => {
  pageDefault.value?.openViewerFile('Title', 'https://www.youtube.com/watch?v=WML5zfS4ivQ')
}

const openViewerFileImage = () => {
  pageDefault.value?.openViewerFile('Title', 'https://ads.agungkdev.com/creatives/test.jpg')
}

const openViewerFileUnknown = () => {
  pageDefault.value?.openViewerFile('Title', '')
}

//----Menu Badge----//
const menuBadgeDropdown = async (type: number) => {
  const dropdownStore = useDropdownStore()
  await dropdownStore.callBadge(type)

  if (dropdownStore.badge.status === 'success' && dropdownStore.badge.data) {
    updateBadgeDropdownUiKitFrontend(dropdownStore.badge.data)
  }
}
</script>
