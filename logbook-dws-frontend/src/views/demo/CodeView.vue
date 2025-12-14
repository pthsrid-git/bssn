<template>
  <div class="flex flex-col gap-6">
    <div class="flex flex-col gap-4">
      <TitleSection>ComponentView.vue</TitleSection>
      <CardDefault>
        <pre
          class="p-4 text-xs rounded-lg bg-primary-50"
        ><code class="w-full h-full">{{ ComponentView }}</code></pre>
      </CardDefault>
    </div>
    <div class="flex flex-col gap-4">
      <TitleSection>AccordionSection.vue</TitleSection>
      <CardDefault>
        <pre
          class="p-4 text-xs rounded-lg bg-primary-50"
        ><code class="w-full h-full">{{ AccordionSection }}</code></pre>
      </CardDefault>
    </div>
    <div class="flex flex-col gap-4">
      <TitleSection>BadgeSection.vue</TitleSection>
      <CardDefault>
        <pre
          class="p-4 text-xs rounded-lg bg-primary-50"
        ><code class="w-full h-full">{{ BadgeSection }}</code></pre>
      </CardDefault>
    </div>
    <div class="flex flex-col gap-4">
      <TitleSection>BreadcrumbSection.vue</TitleSection>
      <CardDefault>
        <pre
          class="p-4 text-xs rounded-lg bg-primary-50"
        ><code class="w-full h-full">{{ BreadcrumbSection }}</code></pre>
      </CardDefault>
    </div>
    <div class="flex flex-col gap-4">
      <TitleSection>ButtonSection.vue</TitleSection>
      <CardDefault>
        <pre
          class="p-4 text-xs rounded-lg bg-primary-50"
        ><code class="w-full h-full">{{ ButtonSection }}</code></pre>
      </CardDefault>
    </div>
    <div class="flex flex-col gap-4">
      <TitleSection>ChartSection.vue</TitleSection>
      <CardDefault>
        <pre
          class="p-4 text-xs rounded-lg bg-primary-50"
        ><code class="w-full h-full">{{ ChartSection }}</code></pre>
      </CardDefault>
    </div>
    <div class="flex flex-col gap-4">
      <TitleSection>ChipSection.vue</TitleSection>
      <CardDefault>
        <pre
          class="p-4 text-xs rounded-lg bg-primary-50"
        ><code class="w-full h-full">{{ ChipSection }}</code></pre>
      </CardDefault>
    </div>
    <div class="flex flex-col gap-4">
      <TitleSection>EntrySection.vue</TitleSection>
      <CardDefault>
        <pre
          class="p-4 text-xs rounded-lg bg-primary-50"
        ><code class="w-full h-full">{{ EntrySection }}</code></pre>
      </CardDefault>
    </div>
    <div class="flex flex-col gap-4">
      <TitleSection>FormSection.vue</TitleSection>
      <CardDefault>
        <pre
          class="p-4 text-xs rounded-lg bg-primary-50"
        ><code class="w-full h-full">{{ FormSection }}</code></pre>
      </CardDefault>
    </div>
    <div class="flex flex-col gap-4">
      <TitleSection>IconSection.vue</TitleSection>
      <CardDefault>
        <pre
          class="p-4 text-xs rounded-lg bg-primary-50"
        ><code class="w-full h-full">{{ IconSection }}</code></pre>
      </CardDefault>
    </div>
    <div class="flex flex-col gap-4">
      <TitleSection>LinkSection.vue</TitleSection>
      <CardDefault>
        <pre
          class="p-4 text-xs rounded-lg bg-primary-50"
        ><code class="w-full h-full">{{ LinkSection }}</code></pre>
      </CardDefault>
    </div>
    <div class="flex flex-col gap-4">
      <TitleSection>SectionSection.vue</TitleSection>
      <CardDefault>
        <pre
          class="p-4 text-xs rounded-lg bg-primary-50"
        ><code class="w-full h-full">{{ SectionSection }}</code></pre>
      </CardDefault>
    </div>
    <div class="flex flex-col gap-4">
      <TitleSection>TableSection.vue</TitleSection>
      <CardDefault>
        <pre
          class="p-4 text-xs rounded-lg bg-primary-50"
        ><code class="w-full h-full">{{ TableSection }}</code></pre>
      </CardDefault>
    </div>
    <div class="flex flex-col gap-4">
      <TitleSection>TabSection.vue</TitleSection>
      <CardDefault>
        <pre
          class="p-4 text-xs rounded-lg bg-primary-50"
        ><code class="w-full h-full">{{ TabSection }}</code></pre>
      </CardDefault>
    </div>
  </div>
</template>

<script setup lang="ts">
import ComponentView from './ComponentView.vue?raw'
import AccordionSection from './section/AccordionSection.vue?raw'
import BadgeSection from './section/BadgeSection.vue?raw'
import BreadcrumbSection from './section/BreadcrumbSection.vue?raw'
import ButtonSection from './section/ButtonSection.vue?raw'
import ChartSection from './section/ChartSection.vue?raw'
import ChipSection from './section/ChipSection.vue'
import EntrySection from './section/EntrySection.vue?raw'
import FormSection from './section/FormSection.vue?raw'
import IconSection from './section/IconSection.vue?raw'
import LinkSection from './section/LinkSection.vue?raw'
import SectionSection from './section/SectionSection.vue?raw'
import TableSection from './section/TableSection.vue?raw'
import TabSection from './section/TabSection.vue?raw'

import CardDefault from '@/components/card/CardDefault.vue'
import TitleSection from '@/components/title/TitleSection.vue'
</script>
