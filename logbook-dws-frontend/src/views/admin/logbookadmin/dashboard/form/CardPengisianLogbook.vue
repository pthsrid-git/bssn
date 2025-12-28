<template>
  <CardDefault>
    <div class="space-y-2">
      <div class="flex items-center justify-between">
        <h3 class="text-xs font-semibold text-gray-900">Pengisian Logbook</h3>
        <button class="flex items-center gap-2 px-2 py-1 border border-gray-300 rounded text-xs hover:bg-gray-50">
          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          {{ formattedMonth }}
        </button>
      </div>

      <div class="space-y-1">
        <div class="flex items-end gap-2">
          <div class="text-3xl font-bold text-gray-900">{{ totalLogbook }}</div>
          <div class="text-xs text-gray-500 mb-0.5">({{ percentage }}%)</div>
        </div>
        <div class="text-xs text-gray-500">{{ dateRange }}</div>
      </div>
    </div>
  </CardDefault>
</template>

<script setup lang="ts">
import { CardDefault } from '@bssn/ui-kit-frontend';

defineProps({
  formattedMonth: {
    type: String,
    required: true
  },
  totalLogbook: {
    type: Number,
    default: 0
  },
  percentage: {
    type: Number,
    default: 0
  },
  dateRange: {
    type: String,
    required: true
  }
});
</script>
