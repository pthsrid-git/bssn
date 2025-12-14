<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
      <!-- Header -->
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
        <button
          @click="$emit('close')"
          class="text-gray-400 hover:text-gray-600 transition-colors"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div class="px-6 py-6 space-y-4">
        <!-- File Upload Area -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            UPLOAD FOTO LVFIO LVEIM LV3/OUTPUT/TERJEMAHAN IO LV1/TERJEMAHAN IO LV2
          </label>
          <div
            @click="triggerFileInput"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="handleDrop"
            class="border-2 border-dashed rounded-lg p-8 text-center cursor-pointer transition-colors"
            :class="isDragging ? 'border-[#FBC143] bg-yellow-50' : 'border-gray-300 hover:border-[#FBC143]'"
          >
            <input
              ref="fileInput"
              type="file"
              accept=".xlsx,.xls"
              @change="handleFileSelect"
              class="hidden"
            />
            
            <div v-if="!selectedFile" class="space-y-2">
              <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
              </svg>
              <div class="text-sm text-gray-600">
                <span class="font-medium text-[#FBC143]">Click to upload</span> or drag and drop
              </div>
              <p class="text-xs text-gray-500">Excel files only (XLSX, XLS)</p>
            </div>

            <div v-else class="space-y-2">
              <svg class="mx-auto w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div class="text-sm font-medium text-gray-900">{{ selectedFile.name }}</div>
              <div class="text-xs text-gray-500">{{ formatFileSize(selectedFile.size) }}</div>
              <button
                @click.stop="removeFile"
                class="text-sm text-red-600 hover:text-red-700 font-medium"
              >
                Remove file
              </button>
            </div>
          </div>
        </div>

        <!-- Periode Selection -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Periode
          </label>
          <select
            v-model="selectedPeriode"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FBC143] focus:border-[#FBC143]"
          >
            <option value="">Pilih Periode</option>
            <option v-for="periode in periodes" :key="periode.id" :value="periode.id">
              {{ periode.nama }}
            </option>
          </select>
        </div>

        <!-- Error Messages -->
        <div v-if="errorMessage" class="bg-red-50 border border-red-200 rounded-lg p-4">
          <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-sm text-red-800">{{ errorMessage }}</div>
          </div>
        </div>

        <!-- Success Message -->
        <div v-if="successMessage" class="bg-green-50 border border-green-200 rounded-lg p-4">
          <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-sm text-green-800">{{ successMessage }}</div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">
        <button
          @click="$emit('close')"
          class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
          :disabled="uploading"
        >
          Cancel
        </button>
        <button
          @click="handleSubmit"
          class="px-6 py-2 text-sm font-medium text-white bg-[#FBC143] rounded-lg hover:bg-[#E5AD3A] transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
          :disabled="!selectedFile || !selectedPeriode || uploading"
        >
          <svg v-if="uploading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          {{ uploading ? 'Uploading...' : 'SUBMIT' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { pohonKinerjaService } from '@/services/pohonKinerja'

const props = defineProps({
  title: {
    type: String,
    default: 'Upload File'
  },
  periodes: {
    type: Array,
    default: () => []
  },
  uploadType: {
    type: String,
    default: 'final-outcome'
  }
})

const emit = defineEmits(['close', 'uploaded'])

const fileInput = ref(null)
const selectedFile = ref(null)
const selectedPeriode = ref('')
const isDragging = ref(false)
const uploading = ref(false)
const errorMessage = ref('')
const successMessage = ref('')

const triggerFileInput = () => {
  fileInput.value?.click()
}

const handleFileSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    validateAndSetFile(file)
  }
}

const handleDrop = (event) => {
  isDragging.value = false
  const file = event.dataTransfer.files[0]
  if (file) {
    validateAndSetFile(file)
  }
}

const validateAndSetFile = (file) => {
  errorMessage.value = ''
  
  const allowedTypes = [
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/vnd.ms-excel'
  ]
  
  if (!allowedTypes.includes(file.type)) {
    errorMessage.value = 'File harus berformat Excel (.xlsx atau .xls)'
    return
  }
  
  if (file.size > 10 * 1024 * 1024) {
    errorMessage.value = 'Ukuran file maksimal 10MB'
    return
  }
  
  selectedFile.value = file
}

const removeFile = () => {
  selectedFile.value = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
  errorMessage.value = ''
  successMessage.value = ''
}

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i]
}

// ✅ FIXED: Only this function
const handleSubmit = async () => {
  if (!selectedFile.value || !selectedPeriode.value) {
    errorMessage.value = 'Harap pilih file dan periode'
    return
  }

  uploading.value = true
  errorMessage.value = ''
  successMessage.value = ''

  try {
    const formData = new FormData()
    formData.append('file', selectedFile.value)
    formData.append('periode_id', selectedPeriode.value)

    const response = await pohonKinerjaService.uploadFinalOutcome(formData)
    
    if (response.success) {
      // ✅ FIXED: Handle different response structures
      const importedCount = response.imported_count || 
                           response.data?.imported_count || 
                           response.data?.total_imported || 
                           0
      
      successMessage.value = response.message || `Berhasil mengupload ${importedCount} data`
      
      // Show success message for 2 seconds then close
      setTimeout(() => {
        emit('uploaded', response)
        emit('close')
      }, 2000)
    }
  } catch (error) {
    console.error('Error uploading file:', error)
    
    if (error.response?.data?.errors) {
      const errors = error.response.data.errors
      if (Array.isArray(errors)) {
        errorMessage.value = `Validasi gagal pada ${errors.length} baris. Periksa kembali file Anda.`
      } else {
        errorMessage.value = Object.values(errors).flat().join(', ')
      }
    } else if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message
    } else {
      errorMessage.value = 'Gagal mengupload file. Silakan coba lagi.'
    }
  } finally {
    uploading.value = false
  }
}
</script>