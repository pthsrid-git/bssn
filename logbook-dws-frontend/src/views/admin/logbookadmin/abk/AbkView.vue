<template>
  <StateUnderConstruction info="Fitur Analisis Beban Kerja (ABK) sedang dalam pengembangan" />
</template>

<script setup lang="ts">
import { StateUnderConstruction } from '@bssn/ui-kit-frontend';
import type { PropType } from 'vue';
import type { PageDefaultExposed } from '@bssn/ui-kit-frontend';

defineProps({
  pageDefault: {
    type: Object as PropType<PageDefaultExposed | null>,
    default: null
  }
});
</script>
