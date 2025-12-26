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
  'flex flex-row justify-center items-center gap-2 focus:ring-2 focus:outline-hidden font-medium rounded-sm text-sm px-5 py-1.5 text-center'

const variantClasses = {
  default: {
    enabled: `text-gray-900 bg-neutral-500 hover:bg-neutral-600 focus:ring-neutral-500 border border-transparent ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-gray-500 bg-neutral-400 border border-transparent cursor-not-allowed'
  },
  primary: {
    enabled: `text-white bg-primary-500 hover:bg-primary-600 focus:ring-primary-500 border border-transparent ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-primary-100 bg-primary-400 border border-transparent cursor-not-allowed'
  },
  info: {
    enabled: `text-white bg-info-500 hover:bg-info-600 focus:ring-info-500 border border-transparent ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-info-100 bg-info-400 border border-transparent cursor-not-allowed'
  },
  warning: {
    enabled: `text-gray-900 bg-warning-500 hover:bg-warning-600 focus:ring-warning-500 border border-transparent ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-gray-500 bg-warning-400 border border-transparent cursor-not-allowed'
  },
  success: {
    enabled: `text-white bg-success-500 hover:bg-success-600 focus:ring-success-500 border border-transparent ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-success-100 bg-success-400 border border-transparent cursor-not-allowed'
  },
  danger: {
    enabled: `text-white bg-danger-500 hover:bg-danger-600 focus:ring-danger-500 border border-transparent ${
      props.loading ? 'cursor-progress' : 'cursor-pointer'
    }`,
    disabled: 'text-danger-100 bg-danger-400 border border-transparent cursor-not-allowed'
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
