<template>
  <div class="flex flex-col gap-y-4">
    <TitleSection>models/index.ts</TitleSection>
    <CardDefault>
      <pre
        class="p-4 text-xs rounded-lg bg-primary-50"
      ><code class="w-full h-full">{{ modelsCode }}</code></pre>
    </CardDefault>
  </div>
</template>

<script setup lang="ts">
import CardDefault from '@/components/card/CardDefault.vue'
import TitleSection from '@/components/title/TitleSection.vue'
import modelsCode from '@/models/index.ts?raw'
</script>
