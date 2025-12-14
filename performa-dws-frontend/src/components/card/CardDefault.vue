<template>
  <div :class="computedClasses">
    <slot></slot>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps({
  backgroundClass: {
    type: String,
    default: 'bg-white'
  },
  paddingClass: {
    type: String,
    default: 'p-4 xl:p-6'
  },
  customClass: {
    type: String,
    default: ''
  }
})

const computedClasses = computed(() =>
  [
    'border border-gray-200 rounded-sm shadow-xs',
    props.backgroundClass,
    props.paddingClass,
    props.customClass
  ].join(' ')
)
</script>
