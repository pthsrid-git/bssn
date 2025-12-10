<!-- logbook-dws-frontend/src/components/Logbook/LogbookAtasanEntryCard.vue -->
<template>
  <div>
    <div class="rounded-lg p-3 shadow-sm hover:shadow-md transition-shadow border border-gray-200" 
         :class="entry.isEmpty ? 'bg-gray-50' : 'bg-white'">
      
      <!-- Empty State -->
      <div v-if="entry.isEmpty" class="flex gap-4 min-h-[60px]">
        <div class="text-center min-w-[60px] flex-shrink-0">
          <div class="text-xs mb-1 font-medium text-gray-500">{{ getDayName(entry.date) }}</div>
          <div class="text-4xl font-bold text-gray-400">{{ getDateNumber(entry.date) }}</div>
        </div>
        
        <div class="w-px bg-gray-300 self-stretch my-2"></div>
        
        <div class="flex-1 flex items-center py-3 px-4">
          <span class="text-gray-500 font-medium text-sm">{{ entry.message }}</span>
        </div>
      </div>
      
      <!-- Content State -->
      <div v-else class="flex gap-6 min-h-[60px]">
        <!-- Date Section -->
        <div class="text-center min-w-[60px] flex-shrink-0 pt-1">
          <div class="text-xs text-gray-500 mb-1 font-medium">{{ getDayName(entry.date) }}</div>
          <div class="text-4xl font-bold text-[#19203B]">{{ getDateNumber(entry.date) }}</div>
        </div>

        <!-- Vertical Divider -->
        <div class="w-px bg-gray-200 self-stretch my-2"></div>

        <!-- Content Grid -->
        <div class="flex-1 py-2">
          <div class="grid grid-cols-8 gap-4 items-start">
            <!-- Column 1: Time, File, Status -->
            <div class="space-y-2.5">
              <div class="flex items-center gap-1.5 text-xs text-gray-600">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ entry.time }}</span>
              </div>
              <div class="flex items-center gap-1.5 text-xs text-gray-600">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <span>{{ entry.files }}</span>
              </div>
              <div class="flex items-center">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>

                <span
                  :class="[
                    'px-2.5 py-0.3 rounded-full text-xs font-medium inline-block',
                    statusClass
                  ]"
                >
                  {{ entry.status }}
                </span>
              </div>
            </div>

            <!-- Column 2: Rencana Hasil Kinerja SKP -->
            <div class="space-y-1">
              <div class="font-semibold text-xs text-gray-900 leading-tight">Rencana Hasil Kinerja SKP</div>
              <div class="text-xs text-gray-600 leading-relaxed">{{ entry.rencana_hasil_kinerja || '-' }}</div>
            </div>

            <!-- Column 3: Indikator -->
            <div class="space-y-1">
              <div class="font-semibold text-xs text-gray-900 leading-tight">Indikator Hasil Rencana Kerja</div>
              <div class="text-xs text-gray-600 leading-relaxed">{{ entry.indikator_hasil || '-' }}</div>
            </div>

            <!-- Column 4: Aktivitas Harian -->
            <div class="space-y-1">
              <div class="font-semibold text-xs text-gray-900 leading-tight">Aktivitas Harian</div>
              <div class="text-xs text-gray-600 leading-relaxed">{{ entry.aktivitas_harian || '-' }}</div>
            </div>

            <!-- Column 5: Keterangan -->
            <div class="space-y-1">
              <div class="font-semibold text-xs text-gray-900 leading-tight">Keterangan</div>
              <div class="text-xs text-gray-600 leading-relaxed">{{ entry.keterangan || '-' }}</div>
            </div>

            <!-- Column 6: Catatan Katim -->
            <div class="space-y-1">
              <div class="font-semibold text-xs text-gray-900 leading-tight">Catatan Katim</div>
              <div class="text-xs text-gray-600 leading-relaxed">{{ entry.catatan_katim || '-' }}</div>
            </div>

            <!-- Column 7: Catatan Atasan -->
            <div class="space-y-1">
              <div class="font-semibold text-xs text-gray-900 leading-tight">Catatan Atasan</div>
              <div class="text-xs text-gray-600 leading-relaxed">{{ entry.catatan_atasan || '-' }}</div>
            </div>

            <!-- Column 8: Action Buttons -->
            <div class="flex flex-col gap-2 items-stretch pt-1">
              <button 
                @click="$emit('detail', entry)"
                type="button" 
                class="px-4 py-1.5 text-xs font-medium border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white rounded-md transition-all w-full"
              >
                Detail
              </button>
              
              <!-- Setujui button - hanya muncul jika status Disetujui Katim atau Disubmit -->
              <button 
                v-if="canApprove"
                @click="$emit('approve', entry)"
                type="button" 
                class="px-4 py-1.5 text-xs font-medium bg-green-500 text-white hover:bg-green-600 rounded-md transition-all flex items-center justify-center gap-1 w-full"
              >
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Setujui
              </button>
              
              <!-- Tolak button - hanya muncul jika status Disetujui Katim atau Disubmit -->
              <button 
                v-if="canApprove"
                @click="$emit('reject', entry)"
                type="button" 
                class="px-4 py-1.5 text-xs font-medium bg-red-500 text-white hover:bg-red-600 rounded-md transition-all flex items-center justify-center gap-1 w-full"
              >
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Tolak
              </button>
            </div>
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

defineEmits(['detail', 'approve', 'reject'])

const getDayName = (dateStr) => {
  const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu']
  
  if (dateStr && dateStr.includes('-')) {
    const date = new Date(dateStr)
    return days[date.getDay()]
  }

  const now = new Date()
  const year = now.getFullYear()
  const month = now.getMonth()
  const day = parseInt(dateStr) || 1
  
  const fullDate = new Date(year, month, day)
  return days[fullDate.getDay()]
}

const getDateNumber = (dateStr) => {
  if (dateStr && dateStr.includes('-')) {
    const date = new Date(dateStr)
    return String(date.getDate()).padStart(2, '0')
  }
  
  return String(dateStr).padStart(2, '0')
}

const statusClass = computed(() => {
  const statusLower = props.entry.status?.toLowerCase() || ''
  
  if (statusLower === 'disetujui' || statusLower === 'approved') {
    return 'bg-green-50 text-green-700 border border-green-200'
  } else if (statusLower === 'disetujui katim') {
    return 'bg-yellow-50 text-yellow-700 border border-yellow-200'
  } else if (statusLower === 'disubmit' || statusLower === 'submitted') {
    return 'bg-blue-50 text-blue-700 border border-blue-200'
  } else if (statusLower === 'ditolak' || statusLower === 'rejected') {
    return 'bg-red-50 text-red-700 border border-red-200'
  } else if (statusLower === 'draft' || statusLower === 'dinamik') {
    return 'bg-gray-50 text-gray-700 border border-gray-200'
  } else {
    return 'bg-gray-50 text-gray-700 border border-gray-200'
  }
})

// Tombol Setujui/Tolak hanya muncul jika status Disetujui Katim atau Disubmit
const canApprove = computed(() => {
  const statusLower = props.entry.status?.toLowerCase() || ''
  return statusLower === 'disetujui katim' || statusLower === 'disubmit'
})
</script>