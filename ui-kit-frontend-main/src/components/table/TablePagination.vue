<template>
  <div class="relative flex flex-col items-center justify-between w-full gap-4 md:flex-row">
    <div>
      <p class="pr-2 text-sm text-gray-700">
        Menampilkan <span class="font-medium">{{ startItem }}</span> -
        <span class="font-medium">{{ endItem }}</span> dari
        <span class="font-medium">{{ pagination.total }}</span>
      </p>
    </div>
    <div
      v-if="pagination.total > 0 && pagination.total > pagination.perPage"
      class="lg:absolute lg:left-1/2 lg:transform lg:-translate-x-1/2"
    >
      <nav class="relative z-0 flex flex-row gap-2 -space-x-px rounded-sm" aria-label="Pagination">
        <button
          @click="previousPage"
          :disabled="pagination.currentPage === 1"
          :class="`relative inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 border border-gray-300 rounded-sm ${pagination.currentPage === 1 ? 'cursor-not-allowed bg-gray-300' : 'bg-white hover:bg-gray-100 cursor-pointer'}`"
        >
          <span class="sr-only">Sebelumnya</span>
          <font-awesome-icon :icon="['fas', 'angle-left']" class="w-3 h-3" />
        </button>
        <span
          v-for="(page, index) in pages"
          :key="page !== null ? page : `null-key-${index}`"
          class="hidden md:inline-flex"
        >
          <button
            v-if="page"
            @click="goToPage(page)"
            :class="
              page === pagination.currentPage
                ? 'bg-warning-500'
                : 'bg-white hover:bg-gray-100 cursor-pointer border border-gray-300'
            "
            class="relative inline-flex items-center px-4 py-1.5 text-sm font-medium text-gray-700 rounded-sm"
          >
            {{ page }}
          </button>
          <span
            v-else
            class="relative inline-flex items-center px-4 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default hover:bg-gray-50"
          >
            ...
          </span>
        </span>
        <button
          @click="nextPage"
          :disabled="pagination.currentPage === pagination.lastPage"
          :class="`relative inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 border border-gray-300 rounded-sm ${pagination.currentPage === pagination.lastPage ? 'cursor-not-allowed bg-gray-300' : 'bg-white hover:bg-gray-100 cursor-pointer'}`"
        >
          <span class="sr-only">Selanjutnya</span>
          <font-awesome-icon :icon="['fas', 'angle-right']" class="w-3 h-3" />
        </button>
      </nav>
    </div>
    <slot name="action"></slot>
  </div>
</template>

<script setup lang="ts">
import { computed, type PropType } from 'vue'

import type { MetaData } from '@/models'

const props = defineProps({
  pagination: {
    type: Object as PropType<MetaData>,
    required: true
  }
})

const emit = defineEmits(['update:page'])

const startItem = computed(() =>
  props.pagination.total > 0 ? (props.pagination.currentPage - 1) * props.pagination.perPage + 1 : 0
)
const endItem = computed(() =>
  Math.min(props.pagination.currentPage * props.pagination.perPage, props.pagination.total)
)

const pages = computed(() => {
  const currentPage = props.pagination.currentPage
  const lastPage = props.pagination.lastPage
  const delta = currentPage === 1 || currentPage === lastPage ? 2 : 1
  const range = []
  const rangeWithDots = []
  let l

  range.push(1)
  if (lastPage > 1) {
    for (let i = currentPage - delta; i <= currentPage + delta; i++) {
      if (i >= 2 && i <= lastPage - 1) {
        range.push(i)
      }
    }
    range.push(lastPage)
  }

  for (const i of range) {
    if (l) {
      if (i - l === 2) {
        rangeWithDots.push(l + 1)
      } else if (i - l !== 1) {
        rangeWithDots.push(null)
      }
    }
    rangeWithDots.push(i)
    l = i
  }

  return rangeWithDots
})

const previousPage = () => {
  if (props.pagination.currentPage > 1) {
    emit('update:page', props.pagination.currentPage - 1)
  }
}

const nextPage = () => {
  if (props.pagination.currentPage < props.pagination.lastPage) {
    emit('update:page', props.pagination.currentPage + 1)
  }
}

const goToPage = (page: number) => {
  if (props.pagination.currentPage != page) {
    emit('update:page', page)
  }
}
</script>
