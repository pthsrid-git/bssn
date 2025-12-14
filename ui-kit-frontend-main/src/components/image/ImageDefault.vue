<template>
  <div v-if="loading" class="relative p-4">
    <IconLoading customClass="w-5 h-5"></IconLoading>
  </div>

  <img
    v-show="!loading"
    :src="url"
    :class="`object-${objectFit}`"
    @load="handleLoad"
    @error="handleError"
  />
</template>

<script setup lang="ts">
import { ref } from 'vue'

import IconLoading from '../icon/IconLoading.vue'

defineProps({
  url: {
    type: String,
    required: true
  },
  objectFit: {
    type: String,
    default: 'contain'
  }
})

const loading = ref(true)

const handleLoad = () => {
  loading.value = false
}

const handleError = () => {
  loading.value = false
}
</script>
