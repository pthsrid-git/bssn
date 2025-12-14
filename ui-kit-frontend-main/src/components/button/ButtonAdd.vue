<template>
  <button type="button" :class="computedClasses" :disabled="disabled" @click="handleClick">
    <font-awesome-icon :icon="['fas', 'plus']" class="w-5 h-5" />
  </button>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
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

const computedClasses = computed(() =>
  [
    'flex flex-row justify-center items-center space-x-2 focus:ring-1 focus:outline-none rounded-sm px-2.5 py-1.5',
    !props.disabled
      ? 'text-primary-600 hover:bg-primary-50 focus:ring-primary-300 border border-primary-300 cursor-pointer'
      : 'text-primary-200 bg-primary-50 border border-primary-100 cursor-not-allowed',
    props.customClass
  ].join(' ')
)

const handleClick = () => {
  emit('clicked')
}
</script>
