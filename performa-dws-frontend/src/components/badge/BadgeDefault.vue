<template>
  <div :class="computedClasses">
    <slot></slot>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

import type { BadgeVariant } from '@/models'

const props = defineProps({
  variant: {
    type: String as () => BadgeVariant,
    default: 'default'
  },
  customClass: {
    type: String,
    default: ''
  }
})

const baseClasses = 'max-w-fit text-xs font-medium px-2.5 py-0.5 rounded-sm text-center'

const variantClasses: Record<typeof props.variant, string> = {
  default: 'bg-neutral-500 text-gray-900',
  primary: 'bg-primary-500 text-white',
  info: 'bg-info-500 text-white',
  warning: 'bg-warning-500 text-gray-900',
  success: 'bg-success-500 text-white',
  danger: 'bg-danger-500 text-white'
}

const computedClasses = computed(() =>
  [baseClasses, variantClasses[props.variant], props.customClass].join(' ')
)
</script>
