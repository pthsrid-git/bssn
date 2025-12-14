<template>
  <div class="flex flex-row flex-wrap items-center justify-center gap-2">
    <ChipDefault title="Default Chip" variant="default"></ChipDefault>
    <ChipDefault title="Primary Chip" variant="primary"></ChipDefault>
    <ChipDefault title="Warning Chip" variant="warning"></ChipDefault>
    <ChipDefault title="Success Chip" variant="success"></ChipDefault>
    <ChipDefault title="Danger Chip" variant="danger"></ChipDefault>
    <ChipDefault title="Info Chip" variant="info"></ChipDefault>
  </div>
</template>

<script setup lang="ts">
import ChipDefault from '@/components/chip/ChipDefault.vue'
</script>
