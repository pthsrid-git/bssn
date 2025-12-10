<!-- logbook-dws-frontend/src/components/Logbook/LogbookKatimDetailModal.vue -->
<template>
  <div 
    v-if="show" 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    @click.self="handleClose"
  >
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
      <!-- Modal Body -->
      <div class="p-6 space-y-6">
        <!-- Header Section dengan Close Button -->
        <div class="flex items-start justify-between -mt-2">
          <div>
            <div class="flex items-center gap-2">
              <h2 class="text-lg font-semibold text-gray-900">Detail Logbook</h2>
              <span 
                :class="[
                  'px-2 py-0.5 text-xs font-medium rounded',
                  getStatusClass(entry?.status)
                ]"
              >
                {{ entry?.status || 'Draft' }}
              </span>
            </div>
            <p class="text-sm text-gray-500 mt-1">Berikut adalah detail logbook anggota tim</p>
          </div>
          <button 
            @click="handleClose"
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
              <div class="text-sm text-gray-900 font-bold">{{ formatDate(entry?.date) }}</div>
            </div>
            <div class="flex gap-16">
              <label class="text-sm font-medium text-gray-700 w-20">Waktu</label>
              <div class="text-sm text-gray-900 font-bold">{{ entry?.time }}</div>
            </div>
          </div>
          <div class="border-t border-gray-200"></div>
        </div>

        <!-- Rencana Hasil Kinerja SKP -->
        <div>
          <label class="text-sm font-medium text-gray-900 block mb-2">Rencana Hasil Kinerja SKP</label>
          <div class="bg-gray-50 border border-gray-300 rounded-lg p-3 text-sm text-gray-900">
            {{ entry?.rencana_hasil_kinerja || '-' }}
          </div>
        </div>

        <!-- Indikator Hasil Rencana Kerja -->
        <div>
          <label class="text-sm font-medium text-gray-900 block mb-2">Indikator Hasil Rencana Kerja</label>
          <div class="bg-gray-50 border border-gray-300 rounded-lg p-3 text-sm text-gray-900">
            {{ entry?.indikator_hasil || '-' }}
          </div>
        </div>

        <!-- Aktivitas / Kegiatan Harian -->
        <div>
          <label class="text-sm font-medium text-gray-900 block mb-2">Aktivitas / Kegiatan Harian</label>
          <div class="bg-gray-50 border border-gray-300 rounded-lg p-3 text-sm text-gray-900">
            {{ entry?.aktivitas_harian || '-' }}
          </div>
        </div>

        <!-- Keterangan -->
        <div>
          <label class="text-sm font-medium text-gray-900 block mb-2">Keterangan</label>
          <div class="bg-gray-50 border border-gray-300 rounded-lg p-3 text-sm text-gray-900">
            {{ entry?.keterangan || '-' }}
          </div>
        </div>

        <!-- Bukti Aktivitas Harian -->
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

        <!-- Catatan Katim - EDITABLE (INPUT TEXT) -->
        <div>
          <label class="text-sm font-medium text-gray-900 block mb-2">Catatan Katim</label>
          <input
            type="text"
            v-model="formData.catatan_katim"
            class="w-full border border-gray-300 rounded-lg p-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Masukkan catatan untuk anggota tim..."
          />
        </div>

        <!-- Catatan Atasan - READ ONLY -->
        <div>
          <label class="text-sm font-medium text-gray-900 block mb-2">Catatan Atasan</label>
          <div class="bg-gray-50 border border-gray-300 rounded-lg p-3 text-sm text-gray-900">
            {{ entry?.catatan_atasan || '-' }}
          </div>
        </div>

        <!-- Action Buttons - HANYA TOMBOL SIMPAN -->
        <div class="flex justify-end items-center pt-4 border-t border-gray-200">
          <button
            @click="handleSave"
            :disabled="processing"
            type="button"
            class="px-4 py-2 text-sm font-medium text-black bg-yellow-500 rounded-lg hover:bg-yellow-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
          >
            <span 
              v-if="processing" 
              class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-black"
            ></span>
            {{ processing ? 'Menyimpan...' : 'Simpan' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { logbookKatimService } from '@/services/Logbook/logbookKatimService'

const props = defineProps({
  show: Boolean,
  entry: Object
})

const emit = defineEmits(['close', 'saved'])

// State
const processing = ref(false)
const formData = ref({
  catatan_katim: ''
})

// Watch untuk sync data ketika modal dibuka
watch(() => props.entry, (newEntry) => {
  if (newEntry) {
    formData.value.catatan_katim = newEntry.catatan_katim || ''
  }
}, { immediate: true })

// Methods
const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  
  const months = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ]
  
  const date = new Date(dateStr)
  const day = String(date.getDate()).padStart(2, '0')
  const month = months[date.getMonth()]
  const year = date.getFullYear()
  
  return `${day} ${month} ${year}`
}

const getStatusClass = (status) => {
  const statusLower = status?.toLowerCase() || ''
  
  if (statusLower === 'disetujui' || statusLower === 'approved') {
    return 'bg-green-100 text-green-800'
  } else if (statusLower === 'disubmit' || statusLower === 'submitted') {
    return 'bg-yellow-100 text-yellow-800'
  } else if (statusLower === 'ditolak' || statusLower === 'rejected') {
    return 'bg-red-100 text-red-800'
  } else if (statusLower === 'draft') {
    return 'bg-blue-100 text-blue-800'
  } else {
    return 'bg-gray-100 text-gray-800'
  }
}

const getFileName = () => {
  if (props.entry?.file_name) {
    return props.entry.file_name
  }
  
  if (props.entry?.file_path) {
    const path = props.entry.file_path
    const parts = path.split('/')
    const fullFileName = parts[parts.length - 1]
    const fileName = fullFileName.replace(/^\d+_/, '')
    return fileName
  }
  
  return 'File.pdf'
}

const formatFileSize = (bytes) => {
  if (!bytes || bytes === 0) return '0 KB'
  
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

  // Karena direct link sudah work, langsung pakai itu
  let baseUrl = import.meta.env.VITE_API_URL || window.location.origin
  baseUrl = baseUrl.replace(/\/api$/, '')
  
  const fileUrl = `${baseUrl}/storage/${props.entry.file_path}`
  
  console.log('📥 Opening file:', fileUrl)
  console.log('📁 File path:', props.entry.file_path)
  
  // Langsung buka di tab baru
  window.open(fileUrl, '_blank')
}

const handleSave = async () => {
  if (!props.entry?.id) {
    console.error('Entry ID tidak ditemukan')
    return
  }

  processing.value = true
  
  try {
    // Kirim catatan_katim ke backend
    console.log('📤 Sending catatan:', formData.value.catatan_katim)
    
    const response = await logbookKatimService.approveLog(
      props.entry.id,
      formData.value.catatan_katim
    )

    console.log('📥 Response:', response)

    if (response.success) {
      console.log('✅ Catatan berhasil disimpan')
      emit('saved', response.data)
      handleClose()
    }
  } catch (error) {
    console.error('❌ Error saving catatan:', error)
    alert('Gagal menyimpan catatan. Silakan coba lagi.')
  } finally {
    processing.value = false
  }
}

const handleClose = () => {
  // Reset form
  formData.value.catatan_katim = ''
  emit('close')
}
</script>

<style scoped>
/* Smooth scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>