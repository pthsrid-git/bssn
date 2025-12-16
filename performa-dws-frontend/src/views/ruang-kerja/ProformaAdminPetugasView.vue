<template>
  <!-- Upload Modal - Shared across all tabs -->
  <div v-if="showModal" class="fixed inset-0 z-600 flex items-center justify-center p-4 bg-gray-900/60">
  <div class="w-full max-w-2xl bg-white rounded-lg shadow-lg">
    <div class="flex items-start justify-between gap-4 p-6 border-b">
      <h2 class="text-lg font-semibold text-gray-900">Upload File</h2>
      <button @click="closeUploadModal" class="text-gray-400 hover:text-gray-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    
    <div class="p-6 space-y-6">
      <!-- Select Box untuk Pilih Nama -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Pilih Nama
        </label>
        <select 
          v-model="selectedName"
          class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500"
        >
          <option value="" disabled>-- Pilih Nama --</option>
          <option v-for="name in nameOptions" :key="name.value" :value="name.value">
            {{ name.label }}
          </option>
        </select>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end gap-3 pt-4 border-t">
        <button
          @click="closeUploadModal"
          class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
        >
          Kembali
        </button>
        <button
          @click="handleUpload"
          :disabled="!selectedName"
          class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Simpan
        </button>
      </div>
    </div>
  </div>
</div>

  <!-- Main Card Container with Tabs Inside -->
  <div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <!-- Tabs Inside Card -->
    <div class="border-b border-gray-200 px-6">
      <div class="flex overflow-x-auto">
        <button 
          @click="activeTab = 'admineperforma'"
          :class="[
            'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
            activeTab === 'admineperforma' 
              ? 'border-yellow-500 text-gray-900' 
              : 'border-transparent text-gray-500 hover:text-gray-700'
          ]"
        >
          ADMIN EPERFORMA
        </button>
        <button 
          @click="activeTab = 'pengelolakinerjaorganisasi'"
          :class="[
            'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
            activeTab === 'pengelolakinerjaorganisasi' 
              ? 'border-yellow-500 text-gray-900' 
              : 'border-transparent text-gray-500 hover:text-gray-700'
          ]"
        >
          PENGELOLA KINERJA ORGANISASI
        </button>
        <button 
          @click="activeTab = 'pmk'"
          :class="[
            'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
            activeTab === 'pmk' 
              ? 'border-yellow-500 text-gray-900' 
              : 'border-transparent text-gray-500 hover:text-gray-700'
          ]"
        >
          PMK
        </button>
      </div>
    </div>

    <!-- Tab Content -->
    <div class="p-6">
      <!-- Admin Eperforma Tab -->
      <div v-if="activeTab === 'admineperforma'" class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
          <h2 class="text-lg font-semibold text-gray-900">TABEL ADMIN EPERFORMA</h2>
          <div class="flex flex-wrap items-center gap-3">
            <div class="relative">
              <input v-model="search" type="text" placeholder="search" class="border rounded-md pl-9 pr-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500" />
              <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-300">
          <table class="w-full text-sm text-left border-collapse">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-center w-16 border-b border-r border-gray-300">NO</th>
                <th class="px-4 py-3 border-b border-r border-gray-300">NAMA</th>
                <th class="px-4 py-3 border-b border-r border-gray-300">AKSI</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="5" class="px-4 py-8 text-center">
                  <div class="flex items-center justify-center">
                    <svg class="animate-spin h-6 w-6 text-yellow-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-gray-600">Memuat data...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="paginated.length === 0">
                <td colspan="5" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
              </tr>
              <tr v-else v-for="(item, index) in paginated" :key="item.id" :class="[index % 2 === 0 ? 'bg-white' : 'bg-gray-50', 'hover:bg-gray-100']">
                <td class="px-4 py-3 text-center border-b border-r border-gray-300">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                <td class="px-4 py-3 border-b border-r border-gray-300">{{ item.nama }}</td>
                <td class="px-4 py-3 border-b border-r border-gray-300">
                  <button
                    class="p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors"
                    title="Delete"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="flex items-center justify-between pt-4">
          <div class="flex items-center gap-2">
            <button 
              @click="currentPage = 1"
              :disabled="currentPage === 1"
              class="px-3 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              &lt;&lt; First
            </button>
            <button 
              @click="currentPage--"
              :disabled="currentPage === 1"
              class="px-3 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              &lt; Previous
            </button>

            <template v-for="(page, idx) in visiblePages" :key="idx">
              <span v-if="page === '...'" class="px-3 py-1.5 text-sm text-gray-500">...</span>
              <button
                v-else
                @click="currentPage = page"
                :class="[
                  'px-3 py-1.5 rounded text-sm transition-colors',
                  currentPage === page 
                    ? 'bg-yellow-500 text-white' 
                    : 'border border-gray-300 hover:bg-gray-50'
                ]"
              >
                {{ page }}
              </button>
            </template>

            <button 
              @click="currentPage++"
              :disabled="currentPage === totalPages"
              class="px-3 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Next &gt;
            </button>
          </div>

          <button
            @click="openUploadModal('admineperforma')"
            class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors"
          >
            TAMBAH PETUGAS
          </button>
        </div>
      </div>

      <div v-if="activeTab === 'pengelolakinerjaorganisasi'" class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
          <h2 class="text-lg font-semibold text-gray-900">TABEL PENGELOLAH KINERJA ORGANISASI</h2>
          <div class="flex flex-wrap items-center gap-3">
            <div class="relative">
              <input v-model="search" type="text" placeholder="search" class="border rounded-md pl-9 pr-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500" />
              <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-300">
          <table class="w-full text-sm text-left border-collapse">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-center w-16 border-b border-r border-gray-300">NO</th>
                <th class="px-4 py-3 border-b border-r border-gray-300">NAMA</th>
                <th class="px-4 py-3 border-b border-r border-gray-300">UNIT KERJA</th>
                <th class="px-4 py-3 border-b border-r border-gray-300">AKSI</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="5" class="px-4 py-8 text-center">
                  <div class="flex items-center justify-center">
                    <svg class="animate-spin h-6 w-6 text-yellow-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-gray-600">Memuat data...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="paginated.length === 0">
                <td colspan="5" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
              </tr>
              <tr v-else v-for="(item, index) in paginated" :key="item.id" :class="[index % 2 === 0 ? 'bg-white' : 'bg-gray-50', 'hover:bg-gray-100']">
                <td class="px-4 py-3 text-center border-b border-r border-gray-300">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                <td class="px-4 py-3 border-b border-r border-gray-300">{{ item.nama }}</td>
                <td class="px-4 py-3 border-b border-r border-gray-300">{{ item.unit_kerja }}</td>
                <td class="px-4 py-3 border-b border-r border-gray-300">
                  <button
                    class="p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors"
                    title="Delete"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="flex items-center justify-between pt-4">
          <div class="flex items-center gap-2">
            <button 
              @click="currentPage = 1"
              :disabled="currentPage === 1"
              class="px-3 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              &lt;&lt; First
            </button>
            <button 
              @click="currentPage--"
              :disabled="currentPage === 1"
              class="px-3 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              &lt; Previous
            </button>

            <template v-for="(page, idx) in visiblePages" :key="idx">
              <span v-if="page === '...'" class="px-3 py-1.5 text-sm text-gray-500">...</span>
              <button
                v-else
                @click="currentPage = page"
                :class="[
                  'px-3 py-1.5 rounded text-sm transition-colors',
                  currentPage === page 
                    ? 'bg-yellow-500 text-white' 
                    : 'border border-gray-300 hover:bg-gray-50'
                ]"
              >
                {{ page }}
              </button>
            </template>

            <button 
              @click="currentPage++"
              :disabled="currentPage === totalPages"
              class="px-3 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Next &gt;
            </button>
          </div>

          <button
            @click="openUploadModal('pengelolakinerjaorganisasi')"
            class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors"
          >
            TAMBAH PETUGAS
          </button>
        </div>
      </div>

      <div v-if="activeTab === 'pmk'" class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
          <h2 class="text-lg font-semibold text-gray-900">TABEL PMK</h2>
          <div class="flex flex-wrap items-center gap-3">
            <div class="relative">
              <input v-model="search" type="text" placeholder="search" class="border rounded-md pl-9 pr-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500" />
              <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-300">
          <table class="w-full text-sm text-left border-collapse">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-center w-16 border-b border-r border-gray-300">NO</th>
                <th class="px-4 py-3 border-b border-r border-gray-300">NAMA</th>
                <th class="px-4 py-3 border-b border-r border-gray-300">UNIT KERJA</th>
                <th class="px-4 py-3 border-b border-r border-gray-300">AKSI</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="loading">
                <td colspan="5" class="px-4 py-8 text-center">
                  <div class="flex items-center justify-center">
                    <svg class="animate-spin h-6 w-6 text-yellow-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-gray-600">Memuat data...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="paginated.length === 0">
                <td colspan="5" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
              </tr>
              <tr v-else v-for="(item, index) in paginated" :key="item.id" :class="[index % 2 === 0 ? 'bg-white' : 'bg-gray-50', 'hover:bg-gray-100']">
                <td class="px-4 py-3 text-center border-b border-r border-gray-300">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                <td class="px-4 py-3 border-b border-r border-gray-300">{{ item.nama }}</td>
                <td class="px-4 py-3 border-b border-r border-gray-300">{{ item.unit_kerja }}</td>
                <td class="px-4 py-3 border-b border-r border-gray-300">
                  <button
                    class="p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors"
                    title="Delete"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="flex items-center justify-between pt-4">
          <div class="flex items-center gap-2">
            <button 
              @click="currentPage = 1"
              :disabled="currentPage === 1"
              class="px-3 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              &lt;&lt; First
            </button>
            <button 
              @click="currentPage--"
              :disabled="currentPage === 1"
              class="px-3 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              &lt; Previous
            </button>

            <template v-for="(page, idx) in visiblePages" :key="idx">
              <span v-if="page === '...'" class="px-3 py-1.5 text-sm text-gray-500">...</span>
              <button
                v-else
                @click="currentPage = page"
                :class="[
                  'px-3 py-1.5 rounded text-sm transition-colors',
                  currentPage === page 
                    ? 'bg-yellow-500 text-white' 
                    : 'border border-gray-300 hover:bg-gray-50'
                ]"
              >
                {{ page }}
              </button>
            </template>

            <button 
              @click="currentPage++"
              :disabled="currentPage === totalPages"
              class="px-3 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Next &gt;
            </button>
          </div>

          <button
            @click="openUploadModal('pmk')"
            class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors"
          >
            TAMBAH PETUGAS
          </button>
        </div>
      </div>

      <!-- Add similar structure for other tabs -->
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

const showModal = ref(false)
const selectedFile = ref(null)
const uploadFileName = ref('')
const currentUploadTab = ref('')

const activeTab = ref('admineperforma')
const loading = ref(false)
const search = ref('')
const filterTahun = ref('2025-2029')
const currentPage = ref(1)
const itemsPerPage = ref(5)

const nameOptions = ref([
  { value: 'nama1', label: 'Ahmad Budiman' },
  { value: 'nama2', label: 'Siti Nurhaliza' },
  { value: 'nama3', label: 'Budi Santoso' },
  { value: 'nama4', label: 'Dewi Lestari' },
  { value: 'nama5', label: 'Eko Prasetyo' },
])

const admineperforma = ref([
  {
    id: 1,
    nama: 'Ahmad Budiman',
    unit_kerja: 'S1'
  },
  {
    id: 2,
    nama: 'Siti Nurhaliza',
    unit_kerja: 'S2'
  },
  {
    id: 3,
    nama: 'Budi Santoso',
    unit_kerja: 'D1'
  },
  {
    id: 4,
    nama: 'Dewi Lestari',
    unit_kerja: 'D2'
  },
  {
    id: 5,
    nama: 'Eko Prasetyo',
    unit_kerja: 'E1'
  }
])

// Fixed: Map activeTab values to their corresponding data refs
const activeData = computed(() => {
  const dataMap = {
    'admineperforma': admineperforma.value,
    'pengelolakinerjaorganisasi': admineperforma.value,
    'pmk': admineperforma.value,
  }
  
  return dataMap[activeTab.value] || []
})

const paginated = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  return activeData.value.slice(start, start + itemsPerPage.value)
})

const totalPages = computed(() => {
  return Math.ceil(activeData.value.length / itemsPerPage.value)
})

const visiblePages = computed(() => {
  const pages = []
  const maxVisible = 5

  if (totalPages.value === 0) return pages

  let start = Math.max(1, currentPage.value - 2)
  let end = Math.min(totalPages.value, start + maxVisible - 1)

  if (end - start < maxVisible - 1) {
    start = Math.max(1, end - maxVisible + 1)
  }

  if (start > 1) {
    pages.push(1)
    if (start > 2) pages.push('...')
  }

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }

  if (end < totalPages.value) {
    if (end < totalPages.value - 1) pages.push('...')
    pages.push(totalPages.value)
  }

  return pages
})

// Keep visiblePagesLv1 as an alias for backward compatibility with template
const visiblePagesLv1 = visiblePages
const totalPagesLv1 = totalPages

const uploadLabel = computed(() => {
  const labels = {
    'finaloutcome': 'UPLOAD FINAL OUTCOME',
    'intermediateoutcomelv1': 'UPLOAD INTERMEDIATE OUTCOME LV1',
    'intermediateoutcomelv2': 'UPLOAD INTERMEDIATE OUTCOME LV2',
    'intermediateoutcomelv3': 'UPLOAD INTERMEDIATE OUTCOME LV3',
    'output': 'UPLOAD OUTPUT',
    'penerjemahintermediateoutcomelv2': 'UPLOAD PENERJEMAH INTERMEDIATE OUTCOME LV2',
    'penerjemahintermediateoutcomelv3': 'UPLOAD PENERJEMAH INTERMEDIATE OUTCOME LV3'
  }
  return labels[currentUploadTab.value] || 'UPLOAD FILE'
})

const openUploadModal = (tabName) => {
  currentUploadTab.value = tabName
  showModal.value = true
}

const closeUploadModal = () => {
  showModal.value = false
  selectedFile.value = null
  uploadFileName.value = ''
  currentUploadTab.value = ''
}

const handleFileSelect = (event) => {
  const target = event.target
  if (target.files && target.files[0]) {
    selectedFile.value = target.files[0]
    uploadFileName.value = target.files[0].name
  }
}

const handleUpload = () => {
  if (!selectedFile.value) return
  
  console.log(`Uploading file for ${currentUploadTab.value}:`, selectedFile.value.name)
  // Add your upload logic here based on currentUploadTab.value
  
  closeUploadModal()
}

watch(activeTab, () => {
  currentPage.value = 1
})
</script>