<template>
  <div class="w-full p-4 overflow-hidden border border-gray-300 rounded-sm">
    <button
      type="button"
      :class="`flex flex-row items-center w-full cursor-pointer ${isOpen ? 'border-b border-gray-300 pb-4 mb-4' : ''}`"
      @click="onToggle"
    >
      <div class="flex-1 font-semibold text-start">{{ title }}</div>
      <font-awesome-icon v-if="isOpen" :icon="['fas', 'minus']" class="w-3 h-3" />
      <font-awesome-icon v-else :icon="['fas', 'plus']" class="w-3 h-3" />
    </button>

    <div v-if="isOpen" class="text-gray-900">
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  index: {
    type: Number,
    required: true
  },
  active: {
    type: [Number, null],
    required: true
  }
})

const emit = defineEmits(['toggle'])

const isOpen = computed(() => props.active === props.index)

const onToggle = () => emit('toggle', props.index)
</script>
