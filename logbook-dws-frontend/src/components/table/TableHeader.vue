<template>
  <th :rowspan="rowspan" :colspan="colspan" :class="computedClasses">
    <div :class="`text-${alignment} text-nowrap`">
      <slot></slot>
    </div>
  </th>
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
  ['px-4 py-2.5 align-middle border border-gray-200 font-semibold', props.customClass].join(' ')
)
</script>
