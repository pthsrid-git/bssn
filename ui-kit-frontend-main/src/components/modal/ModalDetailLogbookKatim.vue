<template>
  <ModalDefault
    ref="modal"
    label="Detail Logbook Anggota"
    maxWidthClass="max-w-3xl"
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
        <p class="text-sm text-gray-500">Berikut adalah detail logbook anggota tim</p>
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
            <div class="text-sm text-gray-900 font-bold">{{ formatTime(entry?.jam_mulai) }} - {{ formatTime(entry?.jam_selesai) }}</div>
          </div>
        </div>
        <div class="border-t border-gray-200"></div>
      </div>

      <!-- Rencana Hasil Kinerja SKP -->
      <div>
        <label class="text-sm font-medium text-gray-900 block mb-2">Rencana Hasil Kinerja SKP</label>
        <div class="bg-white border border-gray-300 rounded-lg p-3 text-sm text-gray-900">
          {{ entry?.rencana_hasil_kinerja_skp || '-' }}
        </div>
      </div>

      <!-- Indikator Hasil Rencana Kerja -->
      <div>
        <label class="text-sm font-medium text-gray-900 block mb-2">Indikator Hasil Rencana Kerja</label>
        <div class="bg-white border border-gray-300 rounded-lg p-3 text-sm text-gray-900">
          {{ entry?.indikator_hasil_rencana_kerja || '-' }}
        </div>
      </div>

      <!-- Aktivitas / Kegiatan Harian -->
      <div>
        <label class="text-sm font-medium text-gray-900 block mb-2">Aktivitas / Kegiatan Harian</label>
        <div class="bg-white border border-gray-300 rounded-lg p-3 text-sm text-gray-900">
          {{ entry?.aktivitas_kegiatan_harian || '-' }}
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
        <div class="w-full md:w-[40%]">
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

      <!-- Catatan Ketua Tim -->
      <div>
        <label class="text-sm font-medium text-gray-900 block mb-2">Catatan Ketua Tim</label>
        <textarea
          v-model="catatanKatim"
          rows="4"
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-warning-500 focus:border-transparent text-sm"
          placeholder="Masukkan catatan Anda untuk logbook ini..."
          :disabled="isSubmitting"
        ></textarea>
        <p class="text-xs text-gray-500 mt-1">* Catatan wajib diisi untuk menolak logbook</p>
      </div>

      <!-- Catatan Atasan (Read-only) -->
      <div>
        <label class="text-sm font-medium text-gray-900 block mb-2">Catatan Atasan</label>
        <div class="bg-white border border-gray-300 rounded-lg p-3 text-sm text-gray-900">
          {{ entry?.catatan_atasan || '-' }}
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
        <button
          @click="close"
          class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
          :disabled="isSubmitting"
        >
          Tutup
        </button>

        <!-- Tombol Simpan Catatan (hanya jika catatan berubah) -->
        <button
          v-if="catatanKatim !== (entry?.catatan_katim || '') && entry?.status !== 'Ditolak'"
          @click="handleSaveCatatan"
          class="px-4 py-2 text-sm font-medium text-white bg-warning-600 rounded-lg hover:bg-warning-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          :disabled="isSubmitting"
        >
          {{ isSubmitting && actionType === 'save' ? 'Menyimpan...' : 'Simpan Catatan' }}
        </button>

        <!-- Tombol Tolak (tidak tampil jika sudah ditolak) -->
        <button
          v-if="entry?.status !== 'Ditolak' && entry?.status !== 'Disetujui'"
          @click="handleReject"
          class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          :disabled="isSubmitting || !catatanKatim.trim()"
        >
          {{ isSubmitting && actionType === 'reject' ? 'Menolak...' : 'Tolak' }}
        </button>

        <!-- Tombol Setujui (tidak tampil jika sudah disetujui) -->
        <button
          v-if="entry?.status !== 'Disetujui' && entry?.status !== 'Ditolak'"
          @click="handleApprove"
          class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          :disabled="isSubmitting"
        >
          {{ isSubmitting && actionType === 'approve' ? 'Menyetujui...' : 'Setujui' }}
        </button>
      </div>
    </div>
  </ModalDefault>
</template>

<script setup>
import { ref, watch } from 'vue'
import ModalDefault from '@/components/modal/ModalDefault.vue'
import { logbookKatimService } from '@/services/logbookKatimService'

const props = defineProps({
  entry: Object
})

const emit = defineEmits(['saved'])

const modal = ref()
const catatanKatim = ref('')
const isSubmitting = ref(false)
const actionType = ref(null)

// Watch entry changes
watch(() => props.entry, (newEntry) => {
  if (newEntry) {
    catatanKatim.value = newEntry.catatan_katim || ''
  }
}, { immediate: true })

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

const formatTime = (timeStr) => {
  if (!timeStr) return '00:00'
  return timeStr.substring(0, 5)
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
  return status || 'Draft'
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

  const baseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'
  const cleanBaseUrl = baseUrl.replace(/\/api$/, '')
  const fileUrl = `${cleanBaseUrl}/storage/${props.entry.file_path}`
  
  console.log('Downloading file from:', fileUrl)
  
  const link = document.createElement('a')
  link.href = fileUrl
  link.download = getFileName()
  link.target = '_blank'
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
}

const handleApprove = async () => {
  if (!props.entry?.id) return
  
  if (!confirm('Apakah Anda yakin ingin menyetujui logbook ini?')) return
  
  isSubmitting.value = true
  actionType.value = 'approve'
  
  try {
    const response = await logbookKatimService.approveLog(
      props.entry.id,
      catatanKatim.value.trim() || undefined
    )
    
    if (response.success) {
      alert('Logbook berhasil disetujui')
      emit('saved')
      close()
    } else {
      alert(response.message || 'Gagal menyetujui logbook')
    }
  } catch (error) {
    console.error('Error approving log:', error)
    alert('Terjadi kesalahan saat menyetujui logbook')
  } finally {
    isSubmitting.value = false
    actionType.value = null
  }
}

const handleReject = async () => {
  if (!props.entry?.id) return
  
  if (!catatanKatim.value.trim()) {
    alert('Catatan wajib diisi untuk menolak logbook')
    return
  }
  
  if (!confirm('Apakah Anda yakin ingin menolak logbook ini?')) return
  
  isSubmitting.value = true
  actionType.value = 'reject'
  
  try {
    const response = await logbookKatimService.rejectLog(
      props.entry.id,
      catatanKatim.value.trim()
    )
    
    if (response.success) {
      alert('Logbook berhasil ditolak')
      emit('saved')
      close()
    } else {
      alert(response.message || 'Gagal menolak logbook')
    }
  } catch (error) {
    console.error('Error rejecting log:', error)
    alert('Terjadi kesalahan saat menolak logbook')
  } finally {
    isSubmitting.value = false
    actionType.value = null
  }
}

const handleSaveCatatan = async () => {
  if (!props.entry?.id) return
  
  if (!catatanKatim.value.trim()) {
    alert('Catatan tidak boleh kosong')
    return
  }
  
  isSubmitting.value = true
  actionType.value = 'save'
  
  try {
    const response = await logbookKatimService.updateCatatanKatim(
      props.entry.id,
      catatanKatim.value.trim()
    )
    
    if (response.success) {
      alert('Catatan berhasil disimpan')
      emit('saved')
      close()
    } else {
      alert(response.message || 'Gagal menyimpan catatan')
    }
  } catch (error) {
    console.error('Error saving catatan:', error)
    alert('Terjadi kesalahan saat menyimpan catatan')
  } finally {
    isSubmitting.value = false
    actionType.value = null
  }
}

const open = () => {
  modal.value?.open()
}

const close = () => {
  modal.value?.close()
  // Reset state setelah close
  setTimeout(() => {
    catatanKatim.value = props.entry?.catatan_katim || ''
    isSubmitting.value = false
    actionType.value = null
  }, 300)
}

defineExpose({
  open,
  close
})
</script>