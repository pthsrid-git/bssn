<template>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header with Actions -->
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
      <div>
        <h3 class="text-lg font-semibold text-gray-800">{{ title }}</h3>
        <p v-if="subtitle" class="text-sm text-gray-600 mt-1">{{ subtitle }}</p>
      </div>
      <div v-if="$slots.actions" class="flex items-center gap-2">
        <slot name="actions"></slot>
      </div>
    </div>

    <!-- Filters -->
    <div v-if="$slots.filters" class="px-6 py-4 border-b border-gray-200 bg-white">
      <slot name="filters"></slot>
    </div>

    <!-- Table Content -->
    <div class="p-6">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th 
                v-for="(header, index) in headers" 
                :key="index"
                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
              >
                {{ header }}
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <slot name="body"></slot>
            
            <!-- Empty State -->
            <tr v-if="showEmpty">
              <td :colspan="headers.length" class="px-6 py-12 text-center text-gray-500">
                <div class="flex flex-col items-center justify-center">
                  <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                  </svg>
                  <p class="text-sm">{{ emptyMessage }}</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="$slots.pagination" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
      <slot name="pagination"></slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  subtitle: {
    type: String,
    default: ''
  },
  headers: {
    type: Array,
    required: true
  },
  showEmpty: {
    type: Boolean,
    default: false
  },
  emptyMessage: {
    type: String,
    default: 'Tidak ada data'
  }
})
</script>