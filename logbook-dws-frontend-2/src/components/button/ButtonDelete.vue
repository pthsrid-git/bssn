<template>
  <button type="button" :class="computedClasses" :disabled="disabled" @click="handleClick">
    <font-awesome-icon :icon="['fad', 'trash-can']" class="w-5 h-5" />
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
      ? 'text-danger-600 hover:bg-danger-50 focus:ring-danger-300 border border-danger-300 cursor-pointer'
      : 'text-danger-300 bg-danger-50 border border-danger-100 cursor-not-allowed',
    props.customClass
  ].join(' ')
)

const handleClick = () => {
  emit('clicked')
}
</script>
