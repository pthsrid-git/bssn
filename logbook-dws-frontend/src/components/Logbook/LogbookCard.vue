<template>
  <div>
    <div class="rounded-lg p-3 shadow-sm hover:shadow-md transition-shadow border-1 border-black" :class="entry.isEmpty ? 'bg-gray-100' : 'bg-white'">
      <!-- Empty State -->
      <div v-if="entry.isEmpty" class="flex gap-4">
        <div class="text-center min-w-[60px] flex-shrink-0">
          <div class="text-xs mb-1 font-medium">{{ getDayName(entry.date) }}</div>
          <div class="text-4xl font-bold">{{ getDateNumber(entry.date) }}</div>
        </div>
        
        <!-- Vertical Divider for Empty State -->
        <div class="w-0.5 bg-gray-400 self-stretch"></div>
        
        <div class="flex-1 flex items-center py-3 px-4">
          <span class="text-gray-900 font-medium">{{ entry.message }}</span>
        </div>
      </div>
      
      <!-- Content State -->
      <div v-else class="flex gap-4">
        <!-- Date Section -->
        <div class="text-center min-w-[60px] flex-shrink-0">
          <div class="text-xs text-gray-500 mb-1 font-medium">{{ getDayName(entry.date) }}</div>
          <div class="text-4xl font-bold text-[#19203B]">{{ getDateNumber(entry.date) }}</div>
        </div>

        <!-- Vertical Divider -->
        <div class="w-px bg-gray-200 self-stretch"></div>

        <!-- Content Grid - 8 Columns (7 info + 1 button) -->
        <div class="flex-1 grid grid-cols-8 gap-3 items-start">
          <!-- Column 1: Time, File, Status -->
          <div class="space-y-2">
            <div class="flex items-center gap-1.5 text-xs text-gray-600">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span>{{ entry.time }}</span>
            </div>
            <div class="flex items-center gap-1.5 text-xs text-gray-600">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
              </svg>
              <span>{{ entry.files }}</span>
            </div>
            <div class="flex items-center gap-1.5 text-xs font-medium">
              <span
                :class="[
                  'px-2 py-0.5 rounded-full border text-xs font-medium',
                  statusTextClass
                ]"
              >
                {{ props.entry.status }}
              </span>
            </div>
          </div>

          <!-- Column 2: Rencana Hasil Kinerja SKP -->
          <div>
            <div class="font-semibold text-xs mb-1 text-gray-900">{{ entry.rencanaKerja }}</div>
            <div class="text-xs text-gray-600 leading-relaxed">{{ entry.rencanaDesc }}</div>
          </div>

          <!-- Column 3: Indikator -->
          <div>
            <div class="font-semibold text-xs mb-1 text-gray-900">{{ entry.indikator }}</div>
            <div class="text-xs text-gray-600 leading-relaxed">{{ entry.indikatorDesc }}</div>
          </div>

          <!-- Column 4: Aktivitas Harian -->
          <div>
            <div class="font-semibold text-xs mb-1 text-gray-900">{{ entry.aktivitas }}</div>
            <div class="text-xs text-gray-600 leading-relaxed">{{ entry.aktivitasDesc }}</div>
          </div>

          <!-- Column 5: Keterangan -->
          <div>
            <div class="font-semibold text-xs mb-1 text-gray-900">{{ entry.keterangan }}</div>
            <div class="text-xs text-gray-600 leading-relaxed">{{ entry.keteranganDesc }}</div>
          </div>

          <!-- Column 6: Catatan Katim -->
          <div>
            <div class="font-semibold text-xs mb-1 text-gray-900">Catatan Katim</div>
            <div class="text-xs text-gray-600 leading-relaxed">{{ entry.catatanKatim || '-' }}</div>
          </div>

          <!-- Column 7: Catatan Atasan -->
          <div>
            <div class="font-semibold text-xs mb-1 text-gray-900">Catatan Atasan</div>
            <div class="text-xs text-gray-600 leading-relaxed">{{ entry.catatanAtasan || '-' }}</div>
          </div>

          <!-- Column 8: Detail Button -->
          <div class="flex items-center justify-center h-full">
            <button 
              @click="$emit('detail', entry)"
              type="button" 
              class="btn btn-outline-primary btn-sm w-32"
            >
              Detail
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  entry: {
    type: Object,
    required: true
  }
})

defineEmits(['detail'])

const getDayName = (dateStr) => {
  const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu']
  
  if (dateStr && dateStr.includes('-')) {
    const date = new Date(dateStr)
    return days[date.getDay()]
  }

  const now = new Date()
  const year = now.getFullYear()
  const month = now.getMonth() // 0-11
  const day = parseInt(dateStr) || 1
  
  const fullDate = new Date(year, month, day)
  return days[fullDate.getDay()]
}

const getDateNumber = (dateStr) => {
  // Jika dateStr adalah full date (YYYY-MM-DD)
  if (dateStr && dateStr.includes('-')) {
    const date = new Date(dateStr)
    return String(date.getDate()).padStart(2, '0')
  }
  
  // Jika sudah dalam format angka
  return String(dateStr).padStart(2, '0')
}

const statusTextClass = computed(() => {
  switch (props.entry.statusColor) {
    case 'blue':
      return 'text-blue-600 border-blue-600'
    case 'yellow':
      return 'text-yellow-400 border-yellow-400'
    case 'green':
      return 'text-green-600 border-green-600'
    case 'red':
      return 'text-red-600 border-red-600'
    default:
      return 'text-gray-600 border-gray-400'
  }
})
</script>

<style scoped>
.btn {
  @apply px-4 py-2 rounded-lg font-medium transition-all;
}

.btn-outline-primary {
  @apply border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white;
}

.btn-sm {
  @apply text-xs px-3 py-1.5;
}
</style>