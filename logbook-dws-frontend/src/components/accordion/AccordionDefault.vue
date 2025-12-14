<template>
  <div class="flex flex-col w-full gap-y-4">
    <slot :active="modelValue" :toggle="toggle" />
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'

const props = defineProps({
  modelValue: {
    type: Number as () => number | null,
    default: null
  }
})
const emit = defineEmits(['update:modelValue'])

const toggle = (index: number) => {
  const newValue = props.modelValue === index ? null : index
  emit('update:modelValue', newValue)
}

onMounted(() => {
  if (props.modelValue === null) {
    emit('update:modelValue', 0)
  }
})
</script>
