<template>
  <div class="w-full h-full">
    <template v-if="sectionStatus === 'success'">
      <slot></slot>
    </template>
    <template v-else-if="sectionStatus === 'error'">
      <StateError />
    </template>
    <template v-else-if="sectionStatus === 'loading'">
      <StateLoading />
    </template>
    <template v-else></template>
  </div>
</template>

<script setup lang="ts">
import { type PropType } from 'vue'

import StateError from '../state/StateError.vue'
import StateLoading from '../state/StateLoading.vue'

import type { SectionStatus } from '@/models'

// ================================================================================================
// Defines
// ================================================================================================
defineProps({
  sectionStatus: {
    type: String as PropType<SectionStatus>,
    required: true
  }
})
</script>
