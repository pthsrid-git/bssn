<template>
  <div :class="computedClasses">
    <font-awesome-icon :icon="['fas', 'circle']" :class="`w-3 h-3 ${iconClass[variant]}`" />
    {{ title }}
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

import type { BadgeVariant } from '@/models'

const props = defineProps({
  title: {
    type: String,
    default: ''
  },
  variant: {
    type: String as () => BadgeVariant,
    default: 'default'
  },
  customClass: {
    type: String,
    default: ''
  }
})

const baseClasses =
  'max-w-fit text-xs font-medium px-2.5 py-1.5 rounded-sm text-center flex items-center gap-1.5'

const variantClasses: Record<typeof props.variant, string> = {
  default: 'bg-neutral-500 text-gray-900',
  primary: 'bg-primary-500 text-white',
  info: 'bg-info-500 text-white',
  warning: 'bg-warning-500 text-gray-900',
  success: 'bg-success-500 text-white',
  danger: 'bg-danger-500 text-white'
}

const iconClass: Record<typeof props.variant, string> = {
  default: 'text-neutral-700',
  primary: 'text-primary-700',
  info: 'text-info-700',
  warning: 'text-warning-700',
  success: 'text-success-700',
  danger: 'text-danger-700'
}

const computedClasses = computed(() =>
  [baseClasses, variantClasses[props.variant], props.customClass].join(' ')
)
</script>
