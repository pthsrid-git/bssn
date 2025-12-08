<template>
  <div 
    v-if="show" 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    @click.self="$emit('close')"
  >
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
      <!-- Modal Body (dengan header digabung) -->
      <div class="p-6 space-y-6">
        <!-- Header Section dengan Close Button -->
        <div class="flex items-start justify-between -mt-2">
          <div>
            <div class="flex items-center gap-2">
              <h2 class="text-lg font-semibold text-gray-900">Detail Logbook</h2>
              <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 text-xs font-medium rounded">
                {{ entry?.status || 'Disubmit' }}
              </span>
            </div>
            <p class="text-sm text-gray-500 mt-1">Berikut adalah detail logbook anda</p>
          </div>
          <button 
            @click="$emit('close')"
            class="text-gray-400 hover:text-gray-600 transition-colors -mr-2"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <!-- Tanggal & Waktu -->
        <div class="space-y-3">
          <div class="space-y-2">
            <div class="flex gap-16">
              <label class="text-sm font-medium text-gray-700 w-20">Tanggal</label>
              <div class="text-sm text-gray-900 font-bold">{{ entry?.fullDate || '01 Juli 2025' }}</div>
            </div>
            <div class="flex gap-16">
              <label class="text-sm font-medium text-gray-700 w-20">Waktu</label>
              <div class="text-sm text-gray-900 font-bold">{{ entry?.time }}</div>
            </div>
          </div>
          <!-- Garis pemisah di bawah -->
          <div class="border-t border-gray-200"></div>
        </div>

        <!-- Rencana Hasil Kinerja SKP -->
        <div>
          <label class="text-sm font-medium text-gray-900 block mb-2">Rencana Hasil Kinerja SKP</label>
          <div class="bg-white border border-gray-300 rounded-lg p-3 text-sm text-gray-900 cursor-not-allowed">
            {{ entry?.rencanaDesc }}
          </div>
        </div>

        <!-- Indikator Hasil Rencana Kerja -->
        <div>
          <label class="text-sm font-medium text-gray-900 block mb-2">Indikator Hasil Rencana Kerja</label>
          <div class="bg-white border border-gray-300 rounded-lg p-3 text-sm text-gray-900 cursor-not-allowed">
            {{ entry?.indikatorDesc }}
          </div>
        </div>

        <!-- Aktivitas / Kegiatan Harian -->
        <div>
          <label class="text-sm font-medium text-gray-900 block mb-2">Aktivitas / Kegiatan Harian</label>
          <div class="bg-white border border-gray-300 rounded-lg p-3 text-sm text-gray-900 cursor-not-allowed">
            {{ entry?.aktivitasDesc }}
          </div>
        </div>

        <!-- Keterangan -->
        <div>
          <label class="text-sm font-medium text-gray-900 block mb-2">Keterangan</label>
          <div class="bg-white border border-gray-300 rounded-lg p-3 text-sm text-gray-900 cursor-not-allowed">
            {{ entry?.keteranganDesc }}
          </div>
        </div>

        <!-- Bukti Aktivitas Harian - 30% width -->
        <div v-if="entry?.file_path || entry?.file_name">
          <label class="text-sm font-medium text-gray-900 block mb-2">Bukti Aktivitas Harian</label>
          <div class="w-[30%]">
            <div 
              @click="downloadFile"
              class="border border-gray-200 rounded-lg p-3 flex items-center justify-between hover:bg-gray-50 transition-colors cursor-pointer"
            >
              <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="w-10 h-10 bg-red-50 rounded flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                  </svg>
                </div>
                <div class="min-w-0 flex-1">
                  <div class="text-sm font-medium text-gray-900 truncate">{{ getFileName() }}</div>
                  <div class="text-xs text-gray-500">{{ formatFileSize(entry?.file_size) }}</div>
                </div>
              </div>
              <div class="text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0 ml-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Catatan Katim -->
        <div>
          <label class="text-sm font-medium text-gray-900 block mb-2">Catatan Katim</label>
          <div class="bg-white border border-gray-300 rounded-lg p-3 text-sm text-gray-900 cursor-not-allowed">
            {{ entry?.catatanKatim || 'Lanjutkan' }}
          </div>
        </div>

        <!-- Catatan Atasan -->
        <div>
          <label class="text-sm font-medium text-gray-900 block mb-2">Catatan Atasan</label>
          <div class="bg-white border border-gray-300 rounded-lg p-3 text-sm text-gray-900 cursor-not-allowed">
            {{ entry?.catatanAtasan || 'Lanjutkan' }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  show: Boolean,
  entry: Object
})

defineEmits(['close'])

const getFileName = () => {
  // Prioritas: gunakan file_name dari backend dulu
  if (props.entry?.file_name) {
    return props.entry.file_name
  }
  
  // Fallback: ekstrak dari file_path
  if (props.entry?.file_path) {
    const path = props.entry.file_path
    const parts = path.split('/')
    const fullFileName = parts[parts.length - 1]
    
    // Hilangkan timestamp di awal jika ada
    const fileName = fullFileName.replace(/^\d+_/, '')
    return fileName
  }
  
  return 'File.pdf'
}

const formatFileSize = (bytes) => {
  if (!bytes || bytes === 0) return '0 KB'
  
  // Konversi ke number jika string
  const size = typeof bytes === 'string' ? parseInt(bytes) : bytes
  
  if (size < 1024) {
    return `${size} B`
  } else if (size < 1024 * 1024) {
    return `${(size / 1024).toFixed(2)} KB`
  } else {
    return `${(size / (1024 * 1024)).toFixed(2)} MB`
  }
}

const downloadFile = () => {
  if (!props.entry?.file_path) {
    console.warn('File path tidak ditemukan')
    alert('File tidak tersedia')
    return
  }

  // Buat URL lengkap untuk Laravel storage
  let baseUrl = import.meta.env.VITE_API_URL || window.location.origin
  
  // Hapus /api jika ada di akhir URL
  baseUrl = baseUrl.replace(/\/api$/, '')
  
  const fileUrl = `${baseUrl}/storage/${props.entry.file_path}`
  
  console.log('Opening file URL:', fileUrl)
  
  // Langsung buka di tab baru - ini akan bypass CORS
  window.open(fileUrl, '_blank')
}
</script>