<template>
  <nav v-if="breadcrumbs.length > 1" class="flex items-center space-x-2 text-sm text-gray-600">
    <RouterLink to="/" class="text-primary-500 hover:text-primary-600">
      <font-awesome-icon :icon="['fad', 'house']" class="w-4 h-4"
    /></RouterLink>
    <span v-for="(crumb, index) in breadcrumbs" :key="index" class="flex items-center">
      <RouterLink v-if="index < breadcrumbs.length - 1" :to="crumb.to" class="hover:text-gray-500">
        {{ crumb.label }}
      </RouterLink>
      <span v-else class="font-semibold text-gray-900">
        {{ crumb.label }}
      </span>

      <font-awesome-icon
        v-if="index < breadcrumbs.length - 1"
        :icon="['fas', 'angle-right']"
        class="w-3 h-3 mx-1.5"
      />
    </span>
  </nav>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute, RouterLink } from 'vue-router'

const route = useRoute()

const breadcrumbs = computed(() => {
  const matched = route.matched

  return matched.map((r) => ({
    label: r.meta.title as string,
    to: { name: r.name as string, params: route.params }
  }))
})
</script>
