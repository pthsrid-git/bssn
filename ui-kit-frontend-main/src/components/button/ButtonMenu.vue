<template>
  <button type="button" :class="computedClasses" :disabled="disabled" @click="handleClick">
    <IconMenu class="w-5 h-5"></IconMenu>
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'

import IconMenu from '@/components/icon/IconMenu.vue'

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
    'inline-flex items-center px-2.5 py-1.5 text-gray-900 bg-white hover:bg-gray-100 rounded-sm cursor-pointer',
    props.customClass
  ].join(' ')
)

const handleClick = () => {
  emit('clicked')
}
</script>
