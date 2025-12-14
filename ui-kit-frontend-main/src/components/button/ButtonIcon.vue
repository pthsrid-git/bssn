<template>
  <button type="button" :class="computedClasses" :disabled="disabled" @click="handleClick">
    <slot></slot>
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'

import type { ButtonType, ButtonVariant } from '@/models'

const props = defineProps({
  type: {
    type: String as () => ButtonType,
    default: 'button'
  },
  variant: {
    type: String as () => ButtonVariant,
    default: 'default'
  },
  disabled: {
    type: Boolean,
    default: false
  },
  customClass: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['clicked'])

const baseClasses =
  'flex flex-row justify-center items-center space-x-2 focus:ring-1 focus:outline-none rounded-sm px-2.5 py-1.5'

const variantClasses = {
  default: {
    enabled:
      'text-gray-900 hover:bg-neutral-100 focus:ring-neutral-300 border border-gray-300 cursor-pointer',
    disabled: 'text-gray-600 bg-neutral-50 border border-gray-100 cursor-not-allowed'
  },
  primary: {
    enabled:
      'text-primary-600 hover:bg-primary-50 focus:ring-primary-300 border border-primary-300 cursor-pointer',
    disabled: 'text-primary-200 bg-primary-50 border border-primary-100 cursor-not-allowed'
  },
  info: {
    enabled:
      'text-info-600 hover:bg-info-50 focus:ring-info-300 border border-info-300 cursor-pointer',
    disabled: 'text-info-300 bg-info-50 border border-info-100 cursor-not-allowed'
  },
  warning: {
    enabled:
      'text-warning-600 hover:bg-warning-50 focus:ring-warning-300 border border-warning-300 cursor-pointer',
    disabled: 'text-warning-300 bg-warning-50 border border-warning-100 cursor-not-allowed'
  },
  success: {
    enabled:
      'text-success-600 hover:bg-success-50 focus:ring-success-300 border border-success-300 cursor-pointer',
    disabled: 'text-success-300 bg-success-50 border border-success-100 cursor-not-allowed'
  },
  danger: {
    enabled:
      'text-danger-600 hover:bg-danger-50 focus:ring-danger-300 border border-danger-300 cursor-pointer',
    disabled: 'text-danger-300 bg-danger-50 border border-danger-100 cursor-not-allowed'
  }
}

const computedClasses = computed(() => {
  const variant = variantClasses[props.variant] || variantClasses.default
  const variantClass = props.disabled ? variant.disabled : variant.enabled

  return [baseClasses, variantClass, props.customClass].filter(Boolean).join(' ')
})

const handleClick = () => {
  emit('clicked')
}
</script>
