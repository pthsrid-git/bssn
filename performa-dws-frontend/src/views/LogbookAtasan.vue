<!-- logbook-dws-frontend/src/views/LogbookAtasan.vue -->
<template>
  <div class="bg-white rounded-xl shadow-sm p-6">
    <!-- Header -->
    <div class="flex items-start gap-4 pb-6">
      <button 
        v-if="selectedPegawai"
        @click="handleBackToList"
        class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors mt-1"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>

      <div>
        <h1 class="text-xl font-semibold text-gray-900">
          {{ selectedPegawai ? selectedPegawai.nama_pegawai : 'Logbook Atasan' }}
        </h1>
        <div v-if="selectedPegawai" class="mt-1 text-sm text-gray-600">
          {{ selectedPegawai.nip_nrp }} | {{ selectedPegawai.pangkat_golongan }} | {{ selectedPegawai.nama_unit_organisasi }} | {{ selectedPegawai.nama_unit_organisasi || '-' }}
        </div>
      </div>
    </div>

    <!-- Divider -->
    <div class="border-t border-gray-200"></div>

    <!-- View: Daftar Unit Kerja -->
    <div v-if="!selectedPegawai">
      <!-- Data Unit Kerja Section -->
      <div class="py-6 border-b border-gray-200">
        <h2 class="text-sm font-medium text-gray-500 mb-4">Data Unit Kerja</h2>
        
        <div class="flex flex-wrap gap-8">
          <div class="w-auto">
            <p class="text-xs text-gray-400 mb-1">Nama</p>
            <p class="text-sm text-gray-900">{{ userData?.nama_unit_organisasi || '-' }}</p>
          </div>

          <div class="w-auto">
            <p class="text-xs text-gray-400 mb-1">Jumlah Anggota</p>
            <p class="text-sm text-gray-900">{{ totalPegawai }}</p>
          </div>

          <div class="w-auto">
            <p class="text-xs text-gray-400 mb-1">Unit Kerja</p>
            <p class="text-sm text-gray-900">{{ userData?.nama_unit_organisasi || '-' }}</p>
          </div>
        </div>
      </div>

      <!-- Daftar Pegawai Unit Kerja -->
      <div class="pt-6">
        <h2 class="text-sm font-medium text-gray-700 mb-4">
          Daftar Anggota Tim
        </h2>

        <!-- Loading State -->
        <div v-if="loading" class="min-h-[400px] flex items-center justify-center">
          <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-[#19203B] mb-4"></div>
            <p class="text-gray-600">Memuat data pegawai...</p>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else-if="pegawaiList.length === 0" class="min-h-[400px] flex items-center justify-center">
          <div class="text-center">
            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Tidak Ada Pegawai</h2>
            <p class="text-gray-600">Belum ada pegawai yang terdaftar di unit kerja Anda</p>
          </div>
        </div>

        <!-- Table -->
        <div v-else class="overflow-x-auto border border-gray-200 rounded-xl">
          <table class="w-full">
            <thead>
              <tr class="border-b border-gray-200 bg-gray-50">
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 w-16">No</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500">Nama</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500">NIP</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500">Pangkat</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500">Jabatan</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500">Tim</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 w-40">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr 
                v-for="(pegawai, index) in paginatedPegawai" 
                :key="pegawai.id"
                class="hover:bg-gray-50 transition-colors"
              >
                <td class="px-6 py-4 text-sm text-gray-600">
                  {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ pegawai.nama_pegawai }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ pegawai.nip_nrp }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ pegawai.pangkat_golongan }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ pegawai.nama_jabatan }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ pegawai.tim || '-' }}</td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center h-full">
                    <button 
                      @click="handleViewLogbook(pegawai)"
                      type="button" 
                      class="btn btn-outline-primary btn-sm w-32"
                    >
                      Lihat logbook
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Pagination -->
          <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-600">
              {{ paginationText }}
            </div>
            <div class="flex items-center gap-2">
              <!-- Rows per page -->
              <select 
                v-model="itemsPerPage"
                class="text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option :value="5">5</option>
                <option :value="10">10</option>
                <option :value="25">25</option>
                <option :value="50">50</option>
              </select>

              <!-- Page buttons -->
              <button
                @click="currentPage = 1"
                :disabled="currentPage === 1"
                class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
              </button>
              <button
                @click="currentPage--"
                :disabled="currentPage === 1"
                class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                ‹
              </button>

              <!-- Page numbers -->
              <button
                v-for="page in visiblePages"
                :key="page"
                @click="currentPage = page"
                :class="[
                  'px-3 py-1 text-sm border rounded',
                  currentPage === page 
                    ? 'bg-blue-500 text-white border-blue-500' 
                    : 'border-gray-300 hover:bg-gray-50'
                ]"
              >
                {{ page }}
              </button>

              <span v-if="totalPages > 10" class="px-2">...</span>

              <button
                @click="currentPage++"
                :disabled="currentPage === totalPages"
                class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                ›
              </button>
              <button
                @click="currentPage = totalPages"
                :disabled="currentPage === totalPages"
                class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- View: Detail Logbook Pegawai -->
    <LogbookAtasanDetail
      v-else
      :pegawaiData="selectedPegawai"
      @back="handleBackToList"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { logbookAtasanService } from '../services/Logbook/logbookAtasanService'
import LogbookAtasanDetail from '../components/Logbook/LogbookAtasanDetail.vue'

const router = useRouter()

// ==================== STATE ====================
const loading = ref(false)
const userData = ref(null)
const pegawaiList = ref([])
const totalPegawaiCount = ref(0) // Total pegawai termasuk Ka-unit
const totalDisplayed = ref(0) // Total pegawai yang tampil di tabel
const selectedPegawai = ref(null)
const currentPage = ref(1)
const itemsPerPage = ref(5)

// ==================== COMPUTED ====================
const totalPegawai = computed(() => totalPegawaiCount.value) // Dari API response

const totalPages = computed(() => {
  return Math.ceil(pegawaiList.value.length / itemsPerPage.value)
})

const paginatedPegawai = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return pegawaiList.value.slice(start, end)
})

const paginationText = computed(() => {
  const totalData = totalDisplayed.value // Gunakan total_displayed dari API
  const start = (currentPage.value - 1) * itemsPerPage.value + 1
  const end = Math.min(start + itemsPerPage.value - 1, totalData)
  return `${start} s/d ${end} dari ${totalData} hasil`
})

const visiblePages = computed(() => {
  const pages = []
  const maxVisible = 10
  
  if (totalPages.value <= maxVisible) {
    for (let i = 1; i <= totalPages.value; i++) {
      pages.push(i)
    }
  } else {
    // Show first 8 pages, then jump to last page
    for (let i = 1; i <= Math.min(8, totalPages.value); i++) {
      pages.push(i)
    }
  }
  
  return pages
})

// ==================== METHODS ====================
const fetchUserData = async () => {
  try {
    const storedUser = localStorage.getItem('user')
    if (storedUser) {
      userData.value = JSON.parse(storedUser)
    }
  } catch (error) {
    console.error('Error fetching user data:', error)
  }
}

const fetchPegawaiList = async () => {
  loading.value = true
  try {
    const response = await logbookAtasanService.getPegawaiList()
    
    if (response.success) {
      pegawaiList.value = response.data
      totalPegawaiCount.value = response.total_pegawai || response.data.length
      totalDisplayed.value = response.total_displayed || response.data.length
    }
  } catch (error) {
    console.error('Error fetching pegawai list:', error)
    pegawaiList.value = []
    totalPegawaiCount.value = 0
    totalDisplayed.value = 0
  } finally {
    loading.value = false
  }
}

const handleViewLogbook = (pegawai) => {
  selectedPegawai.value = pegawai
}

const handleBackToList = () => {
  selectedPegawai.value = null
  currentPage.value = 1
}

// ==================== LIFECYCLE ====================
onMounted(() => {
  fetchUserData()
  fetchPegawaiList()
})
</script>

<style scoped>
.btn {
  @apply px-4 py-2 rounded-lg font-medium transition-all;
}

.btn-outline-primary {
  @apply border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white;
}

.btn-sm {
  @apply text-xs px-3 py-1.5;
}
</style>