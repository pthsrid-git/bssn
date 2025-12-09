<template>
  <div 
    v-if="show" 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    @click.self="$emit('close')"
  >
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
      <div class="p-8">
        <!-- Header -->
        <div class="mb-6">
          <h2 class="text-xl font-bold text-gray-900 mb-1">Tambah Aktivitas Harian</h2>
          <p class="text-sm text-gray-500">Tambahkan aktivitas atau kegiatan harian anda disini</p>
        </div>

        <form @submit.prevent="submitForm" class="space-y-5">
          <!-- Pilih Tanggal -->
          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">Pilih tanggal</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <input 
                v-model="formData.tanggal"
                type="date" 
                class="w-full pl-10 pr-2 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                required
              />
            </div>
          </div>

          <!-- Jam Mulai & Selesai -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">Jam mulai</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <input 
                  v-model="formData.jam_mulai"
                  type="text"
                  placeholder="HH:MM"
                  pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]"
                  maxlength="5"
                  @input="formatTime($event, 'jam_mulai')"
                  class="w-full pl-10 pr-2 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                  required
                />
              </div>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">Jam selesai</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <input 
                  v-model="formData.jam_selesai"
                  type="text"
                  placeholder="HH:MM"
                  pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]"
                  maxlength="5"
                  @input="formatTime($event, 'jam_selesai')"
                  class="w-full pl-10 pr-2 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                  required
                />
              </div>
            </div>
          </div>

          <!-- Rencana Hasil Kinerja SKP & Indikator -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">Rencana Hasil Kinerja SKP</label>
              <div class="relative">
                <select 
                  v-model="formData.rencana_hasil_kinerja_skp"
                  class="w-full px-2 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none pr-10"
                  :class="formData.rencana_hasil_kinerja_skp ? 'text-gray-900' : 'text-gray-400'"
                  required
                >
                  <option value="" disabled hidden>Pilih rencana hasil kinerja SKP</option>
                  <option value="rencana1">Rencana Kinerja 1</option>
                  <option value="rencana2">Rencana Kinerja 2</option>
                  <option value="rencana3">Rencana Kinerja 3</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </div>
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">Indikator Hasil Rencana Kerja</label>
              <div class="relative">
                <select 
                  v-model="formData.indikator_hasil_rencana_kerja"
                  class="w-full px-2 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none pr-10"
                  :class="formData.indikator_hasil_rencana_kerja ? 'text-gray-900' : 'text-gray-400'"
                  required
                >
                  <option value="" disabled hidden>Pilih indikator hasil rencana kerja</option>
                  <option value="indikator1">Indikator 1</option>
                  <option value="indikator2">Indikator 2</option>
                  <option value="indikator3">Indikator 3</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Aktivitas -->
          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">
              Aktivitas / Kegiatan Harian
            </label>
            <div class="relative">
              <select
                v-model="formData.aktivitas_kegiatan_harian"
                class="w-full px-2 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none pr-10"
                :class="formData.aktivitas_kegiatan_harian ? 'text-gray-900' : 'text-gray-400'"
                required
              >
                <option value="" disabled hidden>Pilih aktivitas / kegiatan harian</option>
                <option value="Rapat">Rapat</option>
                <option value="Surat Menyurat">Surat Menyurat</option>
                <option value="Pelayanan Publik">Pelayanan Publik</option>
                <option value="Monitoring & Evaluasi">Monitoring & Evaluasi</option>
                <option value="Pengawasan">Pengawasan</option>
                <option value="Penyusunan Dokumen">Penyusunan Dokumen</option>
              </select>
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </div>
          </div>

          <!-- Keterangan -->
          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">Keterangan</label>
            <textarea 
              v-model="formData.keterangan"
              placeholder="Tambah keterangan"
              rows="3"
              class="w-full px-2 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none text-gray-900 placeholder-gray-400"
            ></textarea>
          </div>

          <!-- File Upload -->
          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">Bukti Aktivitas Harian</label>
            <div 
              class="border-2 border-dashed border-gray-300 rounded-lg p-3 text-center hover:border-blue-400 transition-colors cursor-pointer bg-white"
              @click="$refs.fileInput.click()"
              @dragover.prevent="isDragging = true"
              @dragleave.prevent="isDragging = false"
              @drop.prevent="handleDrop"
              :class="{ 'border-blue-400 bg-blue-50': isDragging }"
            >
              <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
              </svg>
              <p class="text-sm text-gray-600 mb-1">
                <span v-if="!selectedFile" class="font-medium">Tambahkan file</span>
                <span v-else class="text-blue-600 font-medium">{{ selectedFile.name }}</span>
              </p>
              <p v-if="!selectedFile" class="text-xs text-gray-500">
                PDF, DOC, DOCX, JPG, PNG (Max 10MB)
              </p>
              <input 
                ref="fileInput"
                type="file" 
                @change="handleFileChange"
                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                class="hidden"
              />
            </div>
          </div>

          <!-- Success Message -->
          <div v-if="successMessage" class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1">
              <p class="text-sm font-medium text-green-800">{{ successMessage }}</p>
            </div>
          </div>

          <!-- Error Message -->
          <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1">
              <p class="text-sm font-medium text-red-800 mb-1">Gagal menyimpan</p>
              <p class="text-sm text-red-700">{{ error }}</p>
            </div>
          </div>

          <!-- Buttons -->
          <div class="flex justify-end pt-4">
            <button
              type="submit"
              :disabled="loading"
              class="px-8 py-3 text-sm font-semibold text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
            >
              <svg v-if="loading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ loading ? 'Menyimpan...' : 'Tambah Kegiatan Harian' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'
import { logbookService } from '../../services/Logbook/logbookService'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'success'])

const fileInput = ref(null)
const selectedFile = ref(null)
const loading = ref(false)
const error = ref('')
const isDragging = ref(false)
const successMessage = ref('')

const formData = reactive({
  tanggal: '',
  jam_mulai: '',
  jam_selesai: '',
  rencana_hasil_kinerja_skp: '',
  indikator_hasil_rencana_kerja: '',
  aktivitas_kegiatan_harian: '',
  keterangan: '',
  status: 'Disubmit'
})

watch(() => props.show, (newVal) => {
  if (newVal) {
    resetForm()
  }
})

const handleFileChange = (event) => {
  const file = event.target.files[0]
  console.log('📁 File selected:', file)
  
  if (file) {
    if (file.size > 10 * 1024 * 1024) {
      error.value = 'Ukuran file maksimal 10MB'
      selectedFile.value = null
      return
    }
    
    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/jpg', 'image/png']
    if (!allowedTypes.includes(file.type)) {
      error.value = 'Tipe file tidak diizinkan. Gunakan PDF, DOC, DOCX, JPG, atau PNG'
      selectedFile.value = null
      return
    }
    
    selectedFile.value = file
    error.value = ''
    console.log('✅ File validated:', file.name)
  }
}

const handleDrop = (event) => {
  isDragging.value = false
  const file = event.dataTransfer.files[0]
  if (file) {
    if (file.size > 10 * 1024 * 1024) {
      error.value = 'Ukuran file maksimal 10MB'
      return
    }
    
    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/jpg', 'image/png']
    if (!allowedTypes.includes(file.type)) {
      error.value = 'Tipe file tidak diizinkan. Gunakan PDF, DOC, DOCX, JPG, atau PNG'
      return
    }
    
    selectedFile.value = file
    error.value = ''
  }
}

const submitForm = async () => {
  console.log('🎯 submitForm called')
  
  loading.value = true
  error.value = ''
  successMessage.value = ''

  try {
    const timePattern = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/
    if (!timePattern.test(formData.jam_mulai)) {
      error.value = 'Format jam mulai salah. Gunakan format HH:MM (contoh: 07:30)'
      loading.value = false
      return
    }
    if (!timePattern.test(formData.jam_selesai)) {
      error.value = 'Format jam selesai salah. Gunakan format HH:MM (contoh: 16:00)'
      loading.value = false
      return
    }

    console.log('📦 Creating FormData')
    const submitData = new FormData()
    
    console.log('📝 Adding fields to FormData:')
    submitData.append('tanggal', formData.tanggal)
    console.log('  ✓ tanggal:', formData.tanggal)
    
    submitData.append('jam_mulai', formData.jam_mulai)
    console.log('  ✓ jam_mulai:', formData.jam_mulai)
    
    submitData.append('jam_selesai', formData.jam_selesai)
    console.log('  ✓ jam_selesai:', formData.jam_selesai)
    
    submitData.append('rencana_hasil_kinerja_skp', formData.rencana_hasil_kinerja_skp)
    console.log('  ✓ rencana_hasil_kinerja_skp:', formData.rencana_hasil_kinerja_skp)
    
    submitData.append('indikator_hasil_rencana_kerja', formData.indikator_hasil_rencana_kerja)
    console.log('  ✓ indikator_hasil_rencana_kerja:', formData.indikator_hasil_rencana_kerja)
    
    submitData.append('aktivitas_kegiatan_harian', formData.aktivitas_kegiatan_harian)
    console.log('  ✓ aktivitas_kegiatan_harian:', formData.aktivitas_kegiatan_harian)
    
    submitData.append('keterangan', formData.keterangan || '')
    console.log('  ✓ keterangan:', formData.keterangan || '')
    
    submitData.append('status', formData.status)
    console.log('  ✓ status:', formData.status)
    
    if (selectedFile.value) {
      submitData.append('file', selectedFile.value)
      console.log('  ✓ file:', selectedFile.value.name)
    }

    console.log('✅ FormData created')
    
    // Verify FormData entries
    console.log('📋 Verifying entries:')
    for (let pair of submitData.entries()) {
      if (pair[1] instanceof File) {
        console.log(`  - ${pair[0]}: [File] ${pair[1].name}`)
      } else {
        console.log(`  - ${pair[0]}: "${pair[1]}"`)
      }
    }

    console.log('🚀 Calling logbookService.createLogbook')
    const response = await logbookService.createLogbook(submitData)
    
    console.log('✅ Success response:', response)

    emit('success', response.data || response)
    successMessage.value = 'Logbook berhasil disimpan!'
    
    setTimeout(() => {
      handleClose()
    }, 1500)
    
  } catch (err) {
    console.error('❌ Error in submitForm:', err)
    console.error('❌ Error response:', err.response?.data)
    
    if (err.response?.data?.errors) {
      const errors = err.response.data.errors
      const firstError = Object.values(errors)[0]
      error.value = Array.isArray(firstError) ? firstError[0] : firstError
    } else {
      error.value = err.response?.data?.message || 'Gagal menyimpan logbook'
    }
  } finally {
    loading.value = false
  }
}

const handleClose = () => {
  if (!loading.value) {
    resetForm()
    emit('close')
  }
}

const resetForm = () => {
  Object.keys(formData).forEach(key => {
    if (key === 'status') {
      formData[key] = 'Draft'
    } else {
      formData[key] = ''
    }
  })
  selectedFile.value = null
  error.value = ''
  successMessage.value = ''
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const formatTime = (event, field) => {
  let value = event.target.value.replace(/[^0-9]/g, '')
  
  if (value.length >= 2) {
    value = value.slice(0, 2) + ':' + value.slice(2, 4)
  }
  
  formData[field] = value
}
</script>

<style scoped>
select::-ms-expand {
  display: none;
}

/* Select placeholder styling */
select option[value=""] {
  color: #9ca3af;
}

select option:not([value=""]) {
  color: #111827;
}
</style>