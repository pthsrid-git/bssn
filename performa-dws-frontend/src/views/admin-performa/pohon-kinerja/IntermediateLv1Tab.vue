<template>
  <div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
      <div>
        <h2 class="text-xl font-semibold text-gray-800">Intermediate Outcome LV1</h2>
      </div>
      
      <div class="flex gap-3 items-center">
        <div class="flex gap-2 items-center">
          <label class="text-sm font-medium text-gray-700">Periode:</label>
          <select
            v-model="selectedPeriode"
            @change="loadData"
            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FBC143] focus:border-[#FBC143] text-sm"
            :disabled="loadingPeriodes"
          >
            <option value="">Semua Periode</option>
            <option 
              v-for="periode in periodes" 
              :key="periode?.id" 
              :value="periode?.id"
            >
              {{ periode?.nama }}
            </option>
          </select>
        </div>

        <div class="relative">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari data..."
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FBC143] focus:border-[#FBC143] text-sm w-64"
          />
          <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode UO</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode Int. O LV1</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sasaran</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Indikator Kinerja</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Periode</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="loading">
              <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                <div class="flex items-center justify-center gap-2">
                  <svg class="animate-spin h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Memuat data...
                </div>
              </td>
            </tr>
            <tr v-else-if="filteredData.length === 0">
              <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                Tidak ada data
              </td>
            </tr>
            <tr v-else v-for="(item, index) in filteredData" :key="item?.id || index" class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-900">{{ index + 1 }}</td>
              <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ item?.kode_uo || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ item?.kode_int_o_lv1 || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ item?.sasaran || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">
                <ul v-if="item?.indikator_kinerja && item.indikator_kinerja.length > 0" class="list-disc list-inside space-y-1">
                  <li v-for="(indikator, idx) in item.indikator_kinerja" :key="idx">{{ indikator }}</li>
                </ul>
                <span v-else class="text-gray-400">-</span>
              </td>
              <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">
                {{ item?.periode ? `${item.periode.nama}` : '-' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Footer -->
    <div class="flex items-center justify-between">
      <!-- Pagination -->
      <div class="flex items-center gap-4">
        <div class="text-sm text-gray-700">
          <span v-if="filteredData.length > 0">Menampilkan {{ filteredData.length }} data</span>
        </div>
        <div v-if="filteredData.length > 0" class="flex gap-2">
          <button
            @click="previousPage"
            :disabled="currentPage === 1"
            class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Previous
          </button>
          <button
            @click="nextPage"
            :disabled="!hasNextPage"
            class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Next
          </button>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex gap-2">
        <button
          @click="downloadTemplate"
          class="px-3 py-1.5 text-sm border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-colors flex items-center gap-2"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
          </svg>
          Download Template
        </button>
        <button
          @click="showUploadModal = true"
          class="px-3 py-1.5 text-sm border border-yellow-600 text-yellow-600 rounded-lg hover:bg-yellow-600 hover:text-white transition-colors flex items-center gap-2"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
          </svg>
          Upload Intermediate LV1
        </button>
      </div>
    </div>

    <!-- Upload Modal -->
    <UploadModal
      v-if="showUploadModal"
      title="Upload Intermediate Outcome LV1"
      upload-type="intermediate-lv1"
      :periodes="periodes"
      @close="showUploadModal = false"
      @uploaded="handleUploaded"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { periodeService } from '../../../services/periode'
import { pohonKinerjaService } from '../../../services/pohonKinerja'
import UploadModal from '../../../components/admin-performa/pohon-kinerja/UploadModal.vue'

const loading = ref(false)
const loadingPeriodes = ref(false)
const data = ref([])
const periodes = ref([])
const selectedPeriode = ref('')
const searchQuery = ref('')
const currentPage = ref(1)
const hasNextPage = ref(false)
const showUploadModal = ref(false)

const filteredData = computed(() => {
  if (!searchQuery.value) {
    return data.value
  }
  
  const query = searchQuery.value.toLowerCase()
  return data.value.filter(item => {
    return (
      item?.kode_uo?.toLowerCase().includes(query) ||
      item?.kode_int_o_lv1?.toLowerCase().includes(query) ||
      item?.sasaran?.toLowerCase().includes(query) ||
      item?.indikator_kinerja?.some(ind => ind?.toLowerCase().includes(query))
    )
  })
})

const loadData = async () => {
  loading.value = true
  try {
    const params = { page: currentPage.value }
    
    if (selectedPeriode.value) {
      params.periode_id = selectedPeriode.value
    }
    
    const response = await pohonKinerjaService.getIntermediateLv1(params)
    
    if (response.data) {
      data.value = response.data || []
      hasNextPage.value = response.next_page_url !== null
    } else {
      data.value = []
    }
  } catch (error) {
    console.error('Error loading data:', error)
    data.value = []
  } finally {
    loading.value = false
  }
}

const loadPeriodes = async () => {
  loadingPeriodes.value = true
  try {
    const response = await periodeService.getAll()
    
    if (response?.data) {
      periodes.value = Array.isArray(response.data) ? response.data : []
    } else {
      periodes.value = []
    }
  } catch (error) {
    console.error('Error loading periodes:', error)
    periodes.value = []
  } finally {
    loadingPeriodes.value = false
  }
}

const downloadTemplate = async () => {
  try {
    await pohonKinerjaService.downloadTemplate('intermediate-lv1')
  } catch (error) {
    console.error('Error downloading template:', error)
    alert('Gagal mengunduh template')
  }
}

const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
    loadData()
  }
}

const nextPage = () => {
  if (hasNextPage.value) {
    currentPage.value++
    loadData()
  }
}

const handleUploaded = (response) => {
  showUploadModal.value = false
  currentPage.value = 1
  loadData()
  
  if (response.message) {
    alert(response.message)
  }
}

onMounted(async () => {
  await loadPeriodes()
  await loadData()
})
</script>