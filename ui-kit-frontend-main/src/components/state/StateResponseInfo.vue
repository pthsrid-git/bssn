<template>
  <div class="flex flex-row gap-2 p-2 rounded-sm bg-info-50">
    <font-awesome-icon :icon="['fad', 'circle-exclamation']" class="w-5 h-5 text-info-500" />
    <p class="text-sm text-info-500">{{ message }}</p>
  </div>
</template>

<script setup lang="ts">
defineProps({
  message: {
    type: String,
    required: true
  }
})
</script>
