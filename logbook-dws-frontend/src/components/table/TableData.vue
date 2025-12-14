<template>
  <td :rowspan="rowspan" :colspan="colspan" :class="computedClasses">
    <div :class="`w-full flex items-center justify-${alignment} min-h-8`">
      <slot></slot>
    </div>
  </td>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps({
  alignment: {
    type: String as () => 'left' | 'center' | 'right',
    default: 'left'
  },
  rowspan: {
    type: Number,
    default: 1
  },
  colspan: {
    type: Number,
    default: 1
  },
  customClass: {
    type: String,
    default: ''
  }
})

const computedClasses = computed(() =>
  ['px-4 py-3 align-middle border border-gray-200', props.customClass].join(' ')
)
</script>
