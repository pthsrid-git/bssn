<template>
  <div v-if="label" :class="labelComputedClasses">
    {{ label }} <span v-if="required" class="text-danger-500">*</span>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps({
  label: {
    type: String,
    default: ''
  },
  required: {
    type: Boolean,
    default: true
  },
  customClass: {
    type: String,
    default: ''
  }
})

const labelComputedClasses = computed(() =>
  ['text-sm font-medium text-gray-900 mb-[8px]', props.customClass].join(' ')
)
</script>
