<template>
  <ModalDefault
    ref="modalRef"
    label="Tambah Aktivitas Harian"
    max-width-class="max-w-2xl"
    @is-visible="handleVisibility"
  >
    <div>

      <!-- Subtitle -->
      <p class="text-sm text-gray-500 mb-4">
        Tambahkan aktivitas atau kegiatan harian anda di sini
      </p>

      <form @submit.prevent="submitForm" class="space-y-4">

        <!-- Tanggal -->
        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-2">
            Pilih tanggal
          </label>
          <div class="relative" @click="openDatePicker">
            <svg class="absolute left-3 top-3 text-gray-400 z-10 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <div class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-lg cursor-pointer bg-white">
              {{ formattedTanggal }}
            </div>
            <input
              ref="hiddenDateInput"
              type="date"
              v-model="form.tanggal"
              @change="updateFormattedDate"
              class="absolute left-0 top-0 w-full h-full opacity-0 cursor-pointer pointer-events-none"
            />
          </div>
        </div>

        <!-- Jam -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold mb-2">Jam Mulai</label>
            <div class="relative w-1/2">
              <svg class="absolute left-3 top-3 text-gray-400 pointer-events-none w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
              <input
                type="text"
                v-model="form.jam_mulai"
                @input="handleTimeInput($event, 'jam_mulai')"
                placeholder="00:00"
                maxlength="5"
                class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-warning-400"
                required
              />
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold mb-2">Jam Selesai</label>
            <div class="relative w-1/2">
              <svg class="absolute left-3 top-3 text-gray-400 pointer-events-none w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
              <input
                type="text"
                v-model="form.jam_selesai"
                @input="handleTimeInput($event, 'jam_selesai')"
                placeholder="00:00"
                maxlength="5"
                class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-warning-400"
                required
              />
            </div>
          </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 my-6"></div>

        <!-- SKP & Indikator -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold mb-2">Rencana Hasil Kinerja SKP</label>
            <select
              v-model="form.rencana_hasil_kinerja_skp"
              class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-warning-400"
              required
            >
              <option value="" disabled>Pilih rencana hasil kinerja SKP</option>
              <option value="skp1">SKP 1 - Pelayanan Administrasi</option>
              <option value="skp2">SKP 2 - Pengembangan Sistem</option>
              <option value="skp3">SKP 3 - Koordinasi Tim</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold mb-2">Indikator Hasil Rencana Kerja</label>
            <select
              v-model="form.indikator_hasil_rencana_kerja"
              class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-warning-400"
              required
            >
              <option value="" disabled>Pilih indikator hasil rencana kerja SKP</option>
              <option value="ind1">Indikator 1 - Kecepatan Layanan</option>
              <option value="ind2">Indikator 2 - Kualitas Output</option>
              <option value="ind3">Indikator 3 - Ketepatan Waktu</option>
            </select>
          </div>
        </div>

        <!-- Aktivitas -->
        <div>
          <label class="block text-sm font-semibold mb-2">Aktivitas / Kegiatan Harian</label>
          <select
            v-model="form.aktivitas_kegiatan_harian"
            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-warning-400"
            required
          >
            <option value="" disabled>Pilih aktivitas / kegiatan harian sesuai jabatan</option>
            <option value="meeting">Meeting Koordinasi</option>
            <option value="laporan">Penyusunan Laporan</option>
            <option value="analisis">Analisis Data</option>
            <option value="monitoring">Monitoring Proyek</option>
            <option value="presentasi">Presentasi</option>
            <option value="training">Pelatihan/Training</option>
          </select>
        </div>

        <!-- Keterangan -->
        <div>
          <label class="block text-sm font-semibold mb-2">Keterangan</label>
          <textarea
            v-model="form.keterangan"
            rows="3"
            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-warning-400"
            placeholder="Tambahkan keterangan"
          />
        </div>

        <!-- Upload -->
        <div>
          <label class="block text-sm font-semibold mb-2">Bukti Aktivitas Harian</label>
          <div
            class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-warning-400 transition"
            @click="$refs.fileInput.click()"
          >
            <svg class="mx-auto text-gray-400 mb-2 w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            <p class="text-sm text-gray-600">
              {{ selectedFile?.name || 'Tambahkan file' }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
              PDF, DOC, atau gambar (max 5MB)
            </p>
            <input
              ref="fileInput"
              type="file"
              class="hidden"
              accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
              @change="handleFile"
            />
          </div>
        </div>

        <!-- Error Message -->
        <div v-if="errorMessage" class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
          {{ errorMessage }}
        </div>

        <!-- Success Message -->
        <div v-if="successMessage" class="p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
          {{ successMessage }}
        </div>

        <!-- Submit -->
        <div class="flex justify-end pt-2">
          <button
            type="submit"
            :disabled="loading"
            class="px-6 py-2.5 bg-warning-500 text-white font-medium rounded-lg hover:bg-warning-600 disabled:opacity-50 transition"
          >
            {{ loading ? 'Menyimpan...' : 'Tambah Kegiatan Harian' }}
          </button>
        </div>

      </form>
    </div>
  </ModalDefault>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import ModalDefault from '@/components/modal/ModalDefault.vue'

// Define emits
const emit = defineEmits(['success'])

const modalRef = ref()
const fileInput = ref<HTMLInputElement>()
const hiddenDateInput = ref<HTMLInputElement>()
const selectedFile = ref<File | null>(null)
const loading = ref(false)
const errorMessage = ref('')
const successMessage = ref('')

const today = new Date()
const formattedTanggal = ref('')

const form = ref({
  tanggal: today.toISOString().split('T')[0],
  jam_mulai: '07:30',
  jam_selesai: '16:00',
  rencana_hasil_kinerja_skp: '',
  indikator_hasil_rencana_kerja: '',
  aktivitas_kegiatan_harian: '',
  keterangan: '',
  status: 'Disubmit'
})

// Format tanggal Indonesia
const formatTanggalIndonesia = (dateString: string) => {
  const months = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ]
  const date = new Date(dateString)
  return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`
}

// Initialize formatted date
formattedTanggal.value = formatTanggalIndonesia(form.value.tanggal)

const updateFormattedDate = () => {
  formattedTanggal.value = formatTanggalIndonesia(form.value.tanggal)
}

const openDatePicker = () => {
  if (hiddenDateInput.value) {
    hiddenDateInput.value.showPicker()
  }
}

// Handle time input dengan masking
const handleTimeInput = (event: Event, field: 'jam_mulai' | 'jam_selesai') => {
  const input = event.target as HTMLInputElement
  let value = input.value.replace(/\D/g, '')
  
  if (value.length >= 2) {
    const hours = value.substring(0, 2)
    const minutes = value.substring(2, 4)
    
    // Validasi jam maksimal 23
    if (parseInt(hours) > 23) value = '23' + minutes
    // Validasi menit maksimal 59
    if (minutes && parseInt(minutes) > 59) value = hours + '59'
    
    // Tambahkan titik dua setelah 2 digit
    if (value.length > 2) {
      value = value.substring(0, 2) + ':' + value.substring(2, 4)
    }
  }
  
  form.value[field] = value
}

const handleFile = (e: Event) => {
  const file = (e.target as HTMLInputElement).files?.[0]
  if (file) selectedFile.value = file
}

const submitForm = async () => {
  loading.value = true
  errorMessage.value = ''
  successMessage.value = ''

  const fd = new FormData()
  fd.append('tanggal', form.value.tanggal)
  fd.append('jam_mulai', form.value.jam_mulai)
  fd.append('jam_selesai', form.value.jam_selesai)
  fd.append('rencana_hasil_kinerja_skp', form.value.rencana_hasil_kinerja_skp)
  fd.append('indikator_hasil_rencana_kerja', form.value.indikator_hasil_rencana_kerja)
  fd.append('aktivitas_kegiatan_harian', form.value.aktivitas_kegiatan_harian)
  fd.append('keterangan', form.value.keterangan || '')
  fd.append('status', form.value.status)

  if (selectedFile.value) {
    fd.append('file', selectedFile.value)
  }

  try {
    const response = await fetch(`${import.meta.env.VITE_API_BASE_URL}/logbooks`, {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
        Accept: 'application/json'
      },
      body: fd
    })
    
    if (response.ok) {
      successMessage.value = 'Aktivitas berhasil ditambahkan!'
      
      // Emit success event untuk trigger refresh di parent
      emit('success')
      
      // Tunggu sebentar agar user bisa melihat success message
      setTimeout(() => {
        modalRef.value.close()
      }, 1000)
    } else {
      const errorData = await response.json()
      errorMessage.value = errorData.message || 'Gagal menambahkan aktivitas'
    }
  } catch (error) {
    console.error('Error submitting form:', error)
    errorMessage.value = 'Terjadi kesalahan saat menambahkan aktivitas'
  } finally {
    loading.value = false
  }
}

const handleVisibility = (visible: boolean) => {
  if (!visible) {
    selectedFile.value = null
    errorMessage.value = ''
    successMessage.value = ''
    // Reset form
    form.value = {
      tanggal: today.toISOString().split('T')[0],
      jam_mulai: '07:30',
      jam_selesai: '16:00',
      rencana_hasil_kinerja_skp: '',
      indikator_hasil_rencana_kerja: '',
      aktivitas_kegiatan_harian: '',
      keterangan: '',
      status: 'Disubmit'
    }
    formattedTanggal.value = formatTanggalIndonesia(form.value.tanggal)
  }
}

defineExpose({ open: () => modalRef.value.open() })
</script>