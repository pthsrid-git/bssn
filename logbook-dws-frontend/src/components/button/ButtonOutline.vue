<template>
  <button :type="type" :class="computedButtonClasses" :disabled="disabled" @click="handleClick">
    <IconLoading v-if="loading" customClass="w-4 h-4"></IconLoading>
    <div>
      <slot></slot>
    </div>
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'

import IconLoading from '../icon/IconLoading.vue'

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
  loading: {
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
  'flex flex-row justify-center items-center gap-2 focus:ring-1 focus:outline-hidden font-medium rounded-sm text-sm px-5 py-1.5 text-center'

const variantClasses = {
  default: {
    enabled: `text-gray-900 bg-neutral-100 hover:bg-neutral-200 focus:ring-neutral-300 border border-gray-300 ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-gray-900 bg-neutral-50 border border-gray-100 cursor-not-allowed'
  },
  primary: {
    enabled: `text-primary-600 bg-primary-100 hover:bg-primary-200 focus:ring-primary-300 border border-primary-300 ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-primary-600 bg-primary-50 border border-primary-100 cursor-not-allowed'
  },
  info: {
    enabled: `text-info-600 bg-info-100 hover:bg-info-200 focus:ring-info-300 border border-info-300 ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-info-600 bg-info-50 border border-info-100 cursor-not-allowed'
  },
  warning: {
    enabled: `text-warning-600 bg-warning-100 hover:bg-warning-200 focus:ring-warning-300 border border-warning-300 ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-warning-600 bg-warning-50 border border-warning-100 cursor-not-allowed'
  },
  success: {
    enabled: `text-success-600 bg-success-100 hover:bg-success-200 focus:ring-success-300 border border-success-300 ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-success-600 bg-success-50 border border-success-100 cursor-not-allowed'
  },
  danger: {
    enabled: `text-danger-600 bg-danger-100 hover:bg-danger-200 focus:ring-danger-300 border border-danger-300 ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-danger-600 bg-danger-50 border border-danger-100 cursor-not-allowed'
  }
}

const computedButtonClasses = computed(() => {
  const variant = variantClasses[props.variant] || variantClasses.default
  const variantClass = props.disabled ? variant.disabled : variant.enabled

  return [baseClasses, variantClass, props.customClass].filter(Boolean).join(' ')
})

const handleClick = () => {
  emit('clicked')
}
</script>
