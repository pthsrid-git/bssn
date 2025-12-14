<template>
  <ModalDefault
    ref="modal"
    label="Detail Logbook"
    maxWidthClass="max-w-2xl"
  >
    <div class="space-y-6">
      <!-- Status Badge -->
      <div class="flex items-center gap-2">
        <span 
          :class="getStatusClass(entry?.status)"
          class="px-2 py-0.5 text-xs font-medium rounded-full"
        >
          {{ getStatusLabel(entry?.status) }}
        </span>
        <p class="text-sm text-gray-500">Berikut adalah detail logbook anda</p>
      </div>

      <!-- Tanggal & Waktu -->
      <div class="space-y-3">
        <div class="space-y-2">
          <div class="flex gap-16">
            <label class="text-sm font-medium text-gray-700 w-20">Tanggal</label>
            <div class="text-sm text-gray-900 font-bold">{{ formatDate(entry?.tanggal) }}</div>
          </div>
          <div class="flex gap-16">
            <label class="text-sm font-medium text-gray-700 w-20">Waktu</label>
            <div class="text-sm text-gray-900 font-bold">{{ entry?.jam_mulai }} - {{ entry?.jam_selesai }}</div>
          </div>
        </div>
        <div class="border-t border-gray-200"></div>
      </div>

      <!-- Rencana Hasil Kinerja SKP -->
      <div>
        <label class="text-sm font-medium text-gray-900 block mb-2">Rencana Hasil Kinerja SKP</label>
        <div class="bg-white border border-gray-300 rounded-lg p-3 text-sm text-gray-900">
          {{ entry?.rencana_hasil_kinerja_skp }}
        </div>
      </div>

      <!-- Indikator Hasil Rencana Kerja -->
      <div>
        <label class="text-sm font-medium text-gray-900 block mb-2">Indikator Hasil Rencana Kerja</label>
        <div class="bg-white border border-gray-300 rounded-lg p-3 text-sm text-gray-900">
          {{ entry?.indikator_hasil_rencana_kerja }}
        </div>
      </div>

      <!-- Aktivitas / Kegiatan Harian -->
      <div>
        <label class="text-sm font-medium text-gray-900 block mb-2">Aktivitas / Kegiatan Harian</label>
        <div class="bg-white border border-gray-300 rounded-lg p-3 text-sm text-gray-900">
          {{ entry?.aktivitas_kegiatan_harian }}
        </div>
      </div>

      <!-- Keterangan -->
      <div>
        <label class="text-sm font-medium text-gray-900 block mb-2">Keterangan</label>
        <div class="bg-white border border-gray-300 rounded-lg p-3 text-sm text-gray-900">
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

      <!-- Catatan Katim -->
      <div>
        <label class="text-sm font-medium text-gray-900 block mb-2">Catatan Katim</label>
        <div class="bg-white border border-gray-300 rounded-lg p-3 text-sm text-gray-900">
          {{ entry?.catatan_katim || '-' }}
        </div>
      </div>

      <!-- Catatan Atasan -->
      <div>
        <label class="text-sm font-medium text-gray-900 block mb-2">Catatan Atasan</label>
        <div class="bg-white border border-gray-300 rounded-lg p-3 text-sm text-gray-900">
          {{ entry?.catatan_atasan || '-' }}
        </div>
      </div>
    </div>
  </ModalDefault>
</template>

<script setup>
import { ref } from 'vue'
import ModalDefault from '@/components/modal/ModalDefault.vue'

const props = defineProps({
  entry: Object
})

const modal = ref()

const formatDate = (dateString) => {
  if (!dateString) return '-'
  
  const date = new Date(dateString)
  const months = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ]
  
  const day = String(date.getDate()).padStart(2, '0')
  const month = months[date.getMonth()]
  const year = date.getFullYear()
  
  return `${day} ${month} ${year}`
}

const getStatusClass = (status) => {
  const classes = {
    'Disetujui': 'bg-primary-100 text-primary-700',
    'Disubmit': 'bg-warning-100 text-warning-700',
    'Ditolak': 'bg-red-100 text-red-700'
  }
  return classes[status] || 'bg-gray-100 text-gray-700'
}

const getStatusLabel = (status) => {
  return status || 'Disubmit'
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

  // Gunakan API URL dari environment variable
  const baseUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000'
  
  // Hilangkan /api jika ada di akhir URL
  const cleanBaseUrl = baseUrl.replace(/\/api$/, '')
  
  // Buat URL lengkap ke Laravel storage
  const fileUrl = `${cleanBaseUrl}/storage/${props.entry.file_path}`
  
  console.log('Downloading file from:', fileUrl)
  
  // Buat link element untuk download
  const link = document.createElement('a')
  link.href = fileUrl
  link.download = getFileName()
  link.target = '_blank'
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
}

const open = () => {
  modal.value?.open()
}

const close = () => {
  modal.value?.close()
}

defineExpose({
  open,
  close
})
</script>