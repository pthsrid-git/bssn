<template>
  <div class="bg-gray-200 px-4 sm:px-6 py-2.5 flex items-center justify-between">
    <div class="flex items-center gap-3 sm:gap-4">
      <!-- Mobile Menu Button -->
      <button
        @click="$emit('toggle-menu')"
        class="lg:hidden text-gray-700 hover:text-gray-900"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      <span class="text-sm sm:text-base text-gray-900 font-semibold">DWS</span>
      <span class="text-sm sm:text-base text-gray-500">|</span>
      <span class="text-sm sm:text-base font-semibold text-gray-900 hidden sm:inline">{{ currentSection }}</span>
    </div>
    
    <div class="flex items-center gap-3 sm:gap-4">
      <div class="w-9 h-9 sm:w-10 sm:h-10 bg-[#FBC143] rounded-full flex-shrink-0"></div>
      <div class="hidden sm:block">
        <div class="text-sm font-semibold text-gray-900">{{ user.nama_pegawai }}</div>
        <div class="text-xs text-gray-600">{{ user.nama_jabatan }}</div>
      </div>
      <!-- Mobile: Show only first name -->
      <div class="sm:hidden">
        <div class="text-xs font-semibold text-gray-900 truncate max-w-[120px]">{{ user.nama_pegawai}}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  user: {
    type: Object,
    required: true
  },
  activeMenu: {
    type: String,
    required: true
  }
})

defineEmits(['toggle-menu'])

// Menentukan section berdasarkan menu aktif
const currentSection = computed(() => {
  const ruangPribadiMenus = ['beranda', 'logbook', 'kepegawaian', 'keuangan', 'pengetahuan', 'kesehatan', 'halo-pusdaik']
  const ruangKerjaMenus = ['logbook-katim', 'logbook-atasan']
  
  if (ruangKerjaMenus.includes(props.activeMenu)) {
    return 'RUANG KERJA'
  } else if (ruangPribadiMenus.includes(props.activeMenu)) {
    return 'RUANG PRIBADI'
  }
  
  return 'RUANG PRIBADI' // Default
})
</script>