<template>
  <button
    type="button"
    class="flex flex-row items-center justify-center gap-2 text-sm font-medium text-center text-gray-700 rounded-sm cursor-pointer hover:text-gray-900 focus:outline-hidden"
    @click="handleClick"
  >
    <div>
      <slot></slot>
    </div>
    <font-awesome-icon :icon="['fas', 'arrow-right']" class="w-3 h-3 text-warning-500" />
  </button>
</template>

<script setup>
const emit = defineEmits(['clicked'])

const handleClick = () => {
  emit('clicked')
}
</script>
