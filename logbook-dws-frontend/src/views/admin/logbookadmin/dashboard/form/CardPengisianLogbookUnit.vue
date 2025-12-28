<template>
  <CardDefault>
    <div class="space-y-2">
      <div class="flex items-center justify-between">
        <h3 class="text-xs font-semibold text-gray-900">Pengisian Logbook Unit Kerja</h3>
        <button
          class="flex items-center gap-1 text-xs text-info-600 hover:text-info-700"
          @click="$emit('scrollToTable')"
        >
          Lihat semua
          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>

      <div v-if="topUnits.length > 0" class="space-y-2">
        <div v-for="unit in topUnits" :key="unit.kode_unit" class="space-y-0.5">
          <div class="flex items-center justify-between text-xs">
            <span class="text-gray-700">{{ unit.nama_unit }}</span>
            <span class="font-medium text-gray-900">{{ unit.percentage }}%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-1.5">
            <div
              class="bg-info-500 h-1.5 rounded-full transition-all duration-300"
              :style="{ width: unit.percentage + '%' }"
            ></div>
          </div>
        </div>
      </div>

      <div v-else class="flex items-center justify-center h-20 text-gray-400 text-xs">
        Tidak ada data unit kerja
      </div>

      <div class="text-xs text-gray-500 pt-1">{{ dateRange }}</div>
    </div>
  </CardDefault>
</template>

<script setup lang="ts">
import { CardDefault } from '@bssn/ui-kit-frontend';
import type { PropType } from 'vue';

interface UnitWithPercentage {
  kode_unit: string;
  nama_unit: string;
  percentage: number;
}

defineProps({
  topUnits: {
    type: Array as PropType<UnitWithPercentage[]>,
    default: () => []
  },
  dateRange: {
    type: String,
    required: true
  }
});

defineEmits<{
  scrollToTable: [];
}>();
</script>
