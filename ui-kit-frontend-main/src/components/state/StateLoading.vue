<template>
  <div class="flex flex-col items-center justify-center w-full h-full gap-2 p-8">
    <IconLoading customClass="w-6 h-6"></IconLoading>
    <span v-if="info" class="text-sm text-center">{{ info }}</span>
  </div>
</template>

<script setup lang="ts">
import IconLoading from '../icon/IconLoading.vue'

defineProps({
  info: {
    type: String,
    default: ''
  }
})
</script>
