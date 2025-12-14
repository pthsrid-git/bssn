<template>
  <div class="flex flex-col gap-y-4">
    <TitleSection>helpers/index.d.ts</TitleSection>
    <CardDefault>
      <pre
        class="p-4 text-xs rounded-lg bg-primary-50"
      ><code class="w-full h-full">{{ code }}</code></pre>
    </CardDefault>
  </div>
</template>

<script setup lang="ts">
import code from '../../../type/helpers/index.d.ts?raw'

import CardDefault from '@/components/card/CardDefault.vue'
import TitleSection from '@/components/title/TitleSection.vue'
</script>
