<template>
  <button :type="type" :class="computedClasses" :disabled="disabled" @click="handleClick">
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
  'flex flex-row justify-center items-center gap-2 focus:outline-hidden font-medium rounded-sm text-sm py-1.5 text-center'

const variantClasses = {
  default: {
    enabled: `text-gray-800 hover:text-gray-900 ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-gray-300 cursor-not-allowed'
  },
  primary: {
    enabled: `text-primary-500 hover:text-primary-600 ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-primary-300 cursor-not-allowed'
  },
  info: {
    enabled: `text-info-500 hover:text-info-600 ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-info-300 cursor-not-allowed'
  },
  warning: {
    enabled: `text-warning-500 hover:text-warning-600 ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-warning-300 cursor-not-allowed'
  },
  success: {
    enabled: `text-success-500 hover:text-success-600 ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-success-300 cursor-not-allowed'
  },
  danger: {
    enabled: `text-danger-500 hover:text-danger-600 ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-danger-300 cursor-not-allowed'
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
