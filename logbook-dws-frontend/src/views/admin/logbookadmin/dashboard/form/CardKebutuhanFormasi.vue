<template>
  <CardDefault>
    <div class="space-y-2">
      <h3 class="text-xs font-semibold text-gray-900">Kebutuhan Formasi Ideal</h3>

      <div class="h-40">
        <ChartBar
          :data="chartData"
          :height="150"
          :legend="true"
          legend-position="bottom"
          :legend-offset-y="6"
        />
      </div>
    </div>
  </CardDefault>
</template>

<script setup lang="ts">
import { CardDefault, ChartBar } from '@bssn/ui-kit-frontend';
import type { PropType } from 'vue';

defineProps({
  chartData: {
    type: Object as PropType<{
      series: Array<{
        name: string;
        data: Array<{ x: string; y: string }>;
      }>;
      colors: string[];
    }>,
    required: true
  }
});
</script>
