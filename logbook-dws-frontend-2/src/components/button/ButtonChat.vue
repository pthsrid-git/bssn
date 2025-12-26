<template>
  <button type="button" :class="computedClasses" :disabled="disabled" @click="handleClick">
    <font-awesome-icon :icon="['fad', 'messages']" class="w-5 h-5" />
    <span class="text-sm text-gray-900">Chat</span>
    <div v-if="badge > 0" class="absolute -right-2 -top-2">
      <BadgeNotification :value="badge" variant="danger" />
    </div>
  </button>
</template>

<script setup>
import { computed } from 'vue'

import BadgeNotification from '../badge/BadgeNotification.vue'

const props = defineProps({
  badge: {
    type: Number,
    default: 0
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

const computedClasses = computed(() =>
  [
    'flex flex-row justify-center items-center space-x-2 focus:ring-1 focus:outline-none rounded-tl-sm rounded-tr-sm px-3 py-2 cursor-pointer text-white bg-warning-500 hover:bg-warning-600 focus:ring-warning-300 relative',
    props.customClass
  ].join(' ')
)

const handleClick = () => {
  emit('clicked')
}
</script>
