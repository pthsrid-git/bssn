<template>
  <h2 :class="computedClasses"><slot></slot></h2>
</template>

<script setup lang="ts">
import { computed } from 'vue'

import type { TitleVariant } from '@/models'

const props = defineProps({
  variant: {
    type: String as () => TitleVariant,
    default: 'default'
  }
})

const variantClasses: Record<typeof props.variant, string> = {
  default: 'border-neutral-500',
  primary: 'border-primary-500',
  info: 'border-info-500',
  warning: 'border-warning-500',
  success: 'border-success-500',
  danger: 'border-danger-500'
}

const computedClasses = computed(() =>
  ['p-1 pl-3 font-semibold text-gray-900 border-l-4', variantClasses[props.variant]].join(' ')
)
</script>
