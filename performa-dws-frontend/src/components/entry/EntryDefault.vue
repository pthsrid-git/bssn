<template>
  <div class="flex flex-col gap-2 text-sm">
    <div class="font-medium text-gray-900">{{ label }}</div>
    <div class="block w-full px-2.5 py-[9px] rounded-sm bg-white">
      <slot></slot>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps({
  label: {
    type: String,
    required: true
  }
})
</script>
