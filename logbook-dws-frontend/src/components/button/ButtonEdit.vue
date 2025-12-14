<template>
  <button type="button" :class="computedClasses" :disabled="disabled" @click="handleClick">
    <font-awesome-icon :icon="['fad', 'pen-to-square']" class="w-5 h-5" />
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
      ? 'text-info-600 hover:bg-info-50 focus:ring-info-300 border border-info-300 cursor-pointer'
      : 'text-info-300 bg-info-50 border border-info-100 cursor-not-allowed',
    props.customClass
  ].join(' ')
)

const handleClick = () => {
  emit('clicked')
}
</script>
