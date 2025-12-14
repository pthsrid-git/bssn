<template>
  <div :class="labelComputedClasses">
    <div class="w-full overflow-hidden truncate">
      {{ value }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps({
  value: {
    type: String,
    default: ''
  },
  customClass: {
    type: String,
    default: ''
  }
})

const labelComputedClasses = computed(() =>
  [
    'block w-full px-2.5 py-2 rounded-sm bg-white overflow-hidden border border-white text-sm',
    props.customClass
  ].join(' ')
)
</script>
