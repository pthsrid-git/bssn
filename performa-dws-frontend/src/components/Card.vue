<template>
  <div :class="['bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200', containerClass]">
    <!-- Card Body Only -->
    <div :class="['p-6', bodyClass]">
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  bodyClass: {
    type: String,
    default: ''
  },
  containerClass: {
    type: String,
    default: ''
  }
})
</script>