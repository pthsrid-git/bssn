<template>
  <button type="button" :class="computedClasses" :disabled="disabled" @click="handleClick">
    <font-awesome-icon :icon="['fas', 'xmark']" class="w-4 h-4" />
  </button>
</template>

<script setup lang="ts">
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
    'flex flex-row justify-center items-center gap-2 focus:outline-hidden rounded-full p-2',
    !props.disabled
      ? 'text-gray-900 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 cursor-pointer'
      : 'text-gray-700 bg-gray-200 cursor-not-allowed',
    props.customClass
  ].join(' ')
)

const handleClick = () => {
  emit('clicked')
}
</script>
