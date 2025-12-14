<template>
  <div class="flex flex-col items-center justify-center w-full h-full gap-2 p-8">
    <font-awesome-icon :icon="['fad', 'triangle-exclamation']" class="w-8 h-8 text-danger-500" />
    <p class="text-sm text-center">{{ message }}</p>
  </div>
</template>

<script setup lang="ts">
defineProps({
  message: {
    type: String,
    default: 'Terjadi kesalahan. Silakan coba lagi.'
  }
})
</script>
