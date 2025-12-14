<template>
  <td colspan="100%" :class="computedClasses">
    <div class="flex items-center justify-center w-full gap-2 min-h-8">
      <font-awesome-icon :icon="['fad', 'circle-info']" class="w-4 h-4 text-warning-500" />
      <div>
        {{ label }}
      </div>
    </div>
  </td>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps({
  label: {
    type: String,
    default: 'Data Kosong'
  },
  customClass: {
    type: String,
    default: ''
  }
})

const computedClasses = computed(() =>
  ['px-4 py-3 align-middle border border-gray-200', props.customClass].join(' ')
)
</script>
