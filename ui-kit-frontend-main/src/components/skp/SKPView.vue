<template>
  <div class="space-y-6">
    <!-- List View -->
    <div v-if="!showDetail">
      <!-- Title -->
      <h1 class="text-2xl font-bold text-gray-900">Sasaran Kerja Pegawai (SKP)</h1>

      <div class="h-px bg-gray-200 my-4"></div>

      <!-- Data Profil Section -->
      <div class="space-y-4">
        <div class="flex justify-between items-center">
          <div class="flex-1">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Data Profil</h2>
            
            <!-- Profile Grid -->
            <div class="flex flex-wrap gap-x-8 gap-y-4">
              <div>
                <div class="text-xs text-gray-500 mb-1">Nama</div>
                <div class="text-sm font-medium text-gray-900">{{ userData?.fullname || userData?.name || '-' }}</div>
              </div>
              <div>
                <div class="text-xs text-gray-500 mb-1">NIP</div>
                <div class="text-sm font-medium text-gray-900">{{ userData?.nip || '-' }}</div>
              </div>
              <div>
                <div class="text-xs text-gray-500 mb-1">Pangkat / Gol. Ruang</div>
                <div class="text-sm font-medium text-gray-900">{{ userData?.pangkat || '-' }}</div>
              </div>
              <div>
                <div class="text-xs text-gray-500 mb-1">Jabatan</div>
                <div class="text-sm font-medium text-gray-900">{{ userData?.jabatan || '-' }}</div>
              </div>
            </div>
          </div>
          
          <button 
            @click="syncEkinerja"
            class="flex items-center gap-2 bg-warning-50 text-warning-700 px-4 py-2 rounded-lg text-sm font-medium border border-warning-200 hover:bg-warning-100 transition-colors flex-shrink-0"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Sinkronasi ekinerja BKN
          </button>
        </div>
      </div>

      <div class="h-px bg-gray-200 my-4"></div>

      <!-- Daftar SKP -->
      <div class="space-y-4">
        <h2 class="text-base font-semibold text-gray-900">Daftar Sasaran Kerja Pegawai (SKP)</h2>

        <!-- Loading State -->
        <CardDefault v-if="isLoading">
          <div class="py-12 text-center">
            <svg class="animate-spin h-8 w-8 text-primary-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-600">Memuat data...</p>
          </div>
        </CardDefault>

        <!-- Table -->
        <CardDefault v-else paddingClass="p-0">
          <TableDefault>
            <template #header>
              <tr>
                <th class="px-4 py-3 text-left">Periode</th>
                <th class="px-4 py-3 text-left">Pendekatan</th>
                <th class="px-4 py-3 text-left">Unit Kerja</th>
                <th class="px-4 py-3 text-left">Status Pegawai</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Jabatan</th>
                <th class="px-4 py-3 text-left">Aksi</th>
              </tr>
            </template>
            <template #body>
              <tr v-if="skpData.length === 0">
                <TableDataNone label="Tidak ada data SKP" />
              </tr>
              <tr v-for="skp in skpData" :key="skp.id" class="border-b border-gray-200 hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-900">{{ skp.periode }}</td>
                <td class="px-4 py-3">
                  <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-info-50 text-info-700">
                    {{ skp.pendekatan }}
                  </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ skp.unitKerja }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ skp.statusPegawai }}</td>
                <td class="px-4 py-3">
                  <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-success-50 text-success-700">
                    {{ skp.status }}
                  </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ skp.jabatan }}</td>
                <td class="px-4 py-3">
                  <button 
                    @click="viewDetail(skp.id)"
                    class="px-3 py-1 border border-info-500 text-info-600 rounded text-xs font-medium hover:bg-info-50"
                  >
                    Lihat detail
                  </button>
                </td>
              </tr>
            </template>
          </TableDefault>
        </CardDefault>
      </div>
    </div>

    <!-- Detail View -->
    <div v-else class="space-y-6">
      <!-- Header -->
      <div class="flex items-center gap-3">
        <button 
          @click="backToList"
          class="p-2 hover:bg-gray-100 rounded-full transition-colors"
        >
          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
        </button>
        <h1 class="text-2xl font-bold text-gray-900">Detail SKP</h1>
        <span class="px-3 py-1 rounded-full text-xs font-medium bg-success-50 text-success-700 border border-success-200">
          Persetujuan
        </span>
        <span class="px-3 py-1 rounded-full text-xs font-medium bg-info-50 text-info-700 border border-info-200">
          Kuantitatif
        </span>
      </div>

      <div class="h-px bg-gray-200 my-4"></div>

      <!-- Info Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Pegawai yang dinilai -->
        <CardDefault backgroundClass="bg-info-50">
          <h3 class="text-sm font-semibold text-gray-900 mb-3">Pegawai yang dinilai</h3>
          <div class="space-y-2 text-xs">
            <div class="flex gap-2">
              <span class="text-gray-600 w-60 flex-shrink-0">Nama</span>
              <span class="text-gray-900 font-medium">{{ userData?.fullname || userData?.name || '-' }}</span>
            </div>
            <div class="flex gap-2">
              <span class="text-gray-600 w-60 flex-shrink-0">NIP</span>
              <span class="text-gray-900 font-medium">{{ userData?.nip || '-' }}</span>
            </div>
            <div class="flex gap-2">
              <span class="text-gray-600 w-60 flex-shrink-0">Pangkat / Gol. Ruang</span>
              <span class="text-gray-900 font-medium">{{ userData?.pangkat || '-' }}</span>
            </div>
            <div class="flex gap-2">
              <span class="text-gray-600 w-60 flex-shrink-0">Jabatan</span>
              <span class="text-gray-900 font-medium">{{ userData?.jabatan || '-' }}</span>
            </div>
            <div class="flex gap-2">
              <span class="text-gray-600 w-60 flex-shrink-0">Unit Kerja</span>
              <span class="text-gray-900 font-medium">{{ userData?.unit_kerja || '-' }}</span>
            </div>
            <div class="flex gap-2">
              <span class="text-gray-600 w-60 flex-shrink-0"></span>
              <span class="text-gray-900 font-medium">ID: {{ userData?.guid || '-' }}</span>
            </div>
          </div>
        </CardDefault>

        <!-- Pejabat penilai -->
        <CardDefault backgroundClass="bg-info-50">
          <h3 class="text-sm font-semibold text-gray-900 mb-3">Pejabat penilai kinerja</h3>
          
          <!-- Loading State -->
          <div v-if="loadingPenilai" class="text-xs text-gray-500 text-center py-4">
            <div class="animate-pulse">Memuat data...</div>
          </div>
          
          <!-- Data Pejabat Penilai -->
          <div v-else-if="pejabatPenilai" class="space-y-2 text-xs">
            <div class="flex gap-2">
              <span class="text-gray-600 w-60 flex-shrink-0">Nama</span>
              <span class="text-gray-900 font-medium">{{ pejabatPenilai.fullname || pejabatPenilai.name || '-' }}</span>
            </div>
            <div class="flex gap-2">
              <span class="text-gray-600 w-60 flex-shrink-0">NIP</span>
              <span class="text-gray-900 font-medium">{{ pejabatPenilai.nip || '-' }}</span>
            </div>
            <div class="flex gap-2">
              <span class="text-gray-600 w-60 flex-shrink-0">Pangkat / Gol. Ruang</span>
              <span class="text-gray-900 font-medium">{{ pejabatPenilai.pangkat || '-' }}</span>
            </div>
            <div class="flex gap-2">
              <span class="text-gray-600 w-60 flex-shrink-0">Jabatan</span>
              <span class="text-gray-900 font-medium">{{ pejabatPenilai.jabatan || '-' }}</span>
            </div>
            <div class="flex gap-2">
              <span class="text-gray-600 w-60 flex-shrink-0">Unit Kerja</span>
              <span class="text-gray-900 font-medium">{{ pejabatPenilai.unit_kerja || '-' }}</span>
            </div>
            <div class="flex gap-2">
              <span class="text-gray-600 w-60 flex-shrink-0"></span>
              <span class="text-gray-900 font-medium">ID: {{ pejabatPenilai?.guid || '-' }}</span>
            </div>
          </div>
          
          <!-- No Data State -->
          <div v-else class="text-xs text-gray-500 text-center py-4">
            Tidak ada data pejabat penilai
          </div>
        </CardDefault>
      </div>

      <!-- Hasil Kerja Section -->
      <div class="space-y-4">
        <h3 class="text-base font-semibold text-gray-900">Hasil Kerja</h3>

        <!-- Sub Tabs -->
        <div class="border-b border-gray-200">
          <div class="flex gap-6">
            <button 
              @click="detailTab = 'utama'"
              :class="[
                'pb-3 text-sm font-medium border-b-2 transition-colors',
                detailTab === 'utama' 
                  ? 'border-info-500 text-info-600' 
                  : 'border-transparent text-gray-600 hover:text-gray-900'
              ]"
            >
              Utama
            </button>
            <button 
              @click="detailTab = 'tambahan'"
              :class="[
                'pb-3 text-sm font-medium border-b-2 transition-colors',
                detailTab === 'tambahan' 
                  ? 'border-info-500 text-info-600' 
                  : 'border-transparent text-gray-600 hover:text-gray-900'
              ]"
            >
              Tambahan
            </button>
            <button 
              @click="detailTab = 'perilaku'"
              :class="[
                'pb-3 text-sm font-medium border-b-2 transition-colors',
                detailTab === 'perilaku' 
                  ? 'border-info-500 text-info-600' 
                  : 'border-transparent text-gray-600 hover:text-gray-900'
              ]"
            >
              Perilaku Kerja
            </button>
            <button 
              @click="detailTab = 'lampiran'"
              :class="[
                'pb-3 text-sm font-medium border-b-2 transition-colors',
                detailTab === 'lampiran' 
                  ? 'border-info-500 text-info-600' 
                  : 'border-transparent text-gray-600 hover:text-gray-900'
              ]"
            >
              Lampiran
            </button>
          </div>
        </div>

        <!-- Utama Tab -->
        <CardDefault v-if="detailTab === 'utama'" paddingClass="p-0">
          <TableDefault>
            <template #header>
              <tr>
                <th class="px-4 py-3 text-left w-12">No</th>
                <th class="px-4 py-3 text-left">Rencana Hasil Kerja Pimpinan</th>
                <th class="px-4 py-3 text-left">Rencana Hasil Kerja</th>
                <th class="px-4 py-3 text-left">Aspek</th>
                <th class="px-4 py-3 text-left">Indikator Kinerja</th>
                <th class="px-4 py-3 text-left">Target</th>
              </tr>
            </template>
            <template #body>
              <tr v-if="skpDetail.hasilKerja.length === 0">
                <TableDataNone label="Tidak ada data hasil kerja" />
              </tr>
              <tr v-for="(item, index) in skpDetail.hasilKerja" :key="index" class="border-b border-gray-200 hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-900">{{ index + 1 }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ item.rencanaPimpinan }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ item.rencanaHasil }}</td>
                <td class="px-4 py-3">
                  <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-success-100 text-success-700">
                    {{ item.aspek }}
                  </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ item.indikator }}</td>
                <td class="px-4 py-3">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-info-100 text-info-700">
                    {{ item.target }}
                  </span></td>
              </tr>
            </template>
          </TableDefault>
        </CardDefault>

        <!-- Tambahan Tab -->
        <CardDefault v-else-if="detailTab === 'tambahan'" paddingClass="p-0">
          <TableDefault>
            <template #header>
              <tr>
                <th class="px-4 py-3 text-left w-12">No</th>
                <th class="px-4 py-3 text-left">Rencana Hasil Kerja Pimpinan</th>
                <th class="px-4 py-3 text-left">Rencana Hasil Kerja</th>
                <th class="px-4 py-3 text-left">Aspek</th>
                <th class="px-4 py-3 text-left">Indikator Kinerja</th>
                <th class="px-4 py-3 text-left">Target</th>
              </tr>
            </template>
            <template #body>
              <tr>
                <TableDataNone label="Tidak ada data tambahan" />
              </tr>
            </template>
          </TableDefault>
        </CardDefault>

        <!-- Perilaku Kerja Tab -->
        <CardDefault v-else-if="detailTab === 'perilaku'" paddingClass="p-0">
          <TableDefault>
            <template #header>
              <tr>
                <th class="px-4 py-3 text-left w-12">No</th>
                <th class="px-4 py-3 text-left">Aspek</th>
                <th class="px-4 py-3 text-left">Rincian</th>
                <th class="px-4 py-3 text-left">Ekspektasi Khusus</th>
              </tr>
            </template>
            <template #body>
              <tr v-if="skpDetail.perilakuKerja.length === 0">
                <TableDataNone label="Tidak ada data perilaku kerja" />
              </tr>
              <tr v-for="(item, index) in skpDetail.perilakuKerja" :key="index" class="border-b border-gray-200 hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-900">{{ index + 1 }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ item.aspek }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">
                  <ol class="list-decimal list-inside space-y-1">
                    <li v-for="(rincian, idx) in item.rincian" :key="idx">{{ rincian }}</li>
                  </ol>
                </td>
                <td class="px-4 py-3 text-sm text-gray-500 text-center">{{ item.ekspektasi }}</td>
              </tr>
            </template>
          </TableDefault>
        </CardDefault>

        <!-- Lampiran Tab -->
        <div v-else-if="detailTab === 'lampiran'" class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <CardDefault>
            <div class="bg-gray-50 px-4 py-2 -mx-4 -mt-4 mb-4 border-b">
              <h4 class="text-sm font-medium text-gray-700">Dukungan Sumber Daya</h4>
            </div>
            <div class="space-y-2">
              <div v-for="(item, index) in skpDetail.lampiran.dukunganSumberDaya" :key="index" class="text-sm text-gray-600">
                • {{ item }}
              </div>
            </div>
          </CardDefault>

          <CardDefault>
            <div class="bg-gray-50 px-4 py-2 -mx-4 -mt-4 mb-4 border-b">
              <h4 class="text-sm font-medium text-gray-700">Skema Pertanggungjawaban</h4>
            </div>
            <div class="h-32 flex items-center justify-center">
              <span class="text-sm text-gray-400">-</span>
            </div>
          </CardDefault>

          <CardDefault>
            <div class="bg-gray-50 px-4 py-2 -mx-4 -mt-4 mb-4 border-b">
              <h4 class="text-sm font-medium text-gray-700">Konsekuensi</h4>
            </div>
            <div class="h-32 flex items-center justify-center">
              <span class="text-sm text-gray-400">-</span>
            </div>
          </CardDefault>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import CardDefault from '@/components/card/CardDefault.vue'
import TableDefault from '@/components/table/TableDefault.vue'
import TableDataNone from '@/components/table/TableDataNone.vue'
import { useUserDwsStore } from '@/stores/userDwsStore'

const userStore = useUserDwsStore()
const userData = computed(() => userStore.user)

const showDetail = ref(false)
const detailTab = ref('utama')
const selectedSkpId = ref<number | null>(null)
const isLoading = ref(false)

// Pejabat Penilai
const pejabatPenilai = ref(null)
const loadingPenilai = ref(false)

// Dummy data
const skpData = ref([
  {
    id: 1,
    periode: '1 Januari 2025 - 31 Desember 2025',
    pendekatan: 'Kuantitatif',
    unitKerja: 'Pusat Data dan Teknologi Informasi Komunikasi',
    statusPegawai: 'Definitif',
    status: 'Persetujuan',
    jabatan: 'Pranata Komputer Terampil'
  },
  {
    id: 2,
    periode: '1 November 2024 - 31 Desember 2024',
    pendekatan: 'Kuantitatif',
    unitKerja: 'Pusat Data dan Teknologi Informasi Komunikasi',
    statusPegawai: 'Definitif',
    status: 'Persetujuan',
    jabatan: 'Pranata Komputer Terampil'
  }
])

const skpDetail = ref({
  hasilKerja: [
    {
      rencanaPimpinan: 'Pengangkatan pegawai BSSN yang sesuai kebutuhan',
      rencanaHasil: 'Terpenuhinya KepKA BSSN tentang Pengangkatan CPNS',
      aspek: 'Kuantitas',
      indikator: 'Terpenuhinya Surat pengangkatan CPNS',
      target: '100%'
    },
    {
      rencanaPimpinan: 'Pengangkatan pegawai BSSN yang sesuai kebutuhan',
      rencanaHasil: 'Terpenuhinya KepKA BSSN tentang Pengangkatan CPNS',
      aspek: 'Kuantitas',
      indikator: 'Terpenuhinya Surat pengangkatan CPNS',
      target: '100%'
    }
  ],
  perilakuKerja: [
    {
      aspek: 'Berorientasi Pelayanan',
      rincian: [
        'Memahami dan memenuhi kebutuhan masyarakat',
        'Ramah, cekatan, solutif, dan dapat diandalkan',
        'Melakukan perbaikan tiada henti'
      ],
      ekspektasi: '-'
    },
    {
      aspek: 'Akuntabel',
      rincian: [
        'Melaksanakan tugas dengan jujur bertanggung jawab',
        'Menggunakan kekayaan negara secara bertanggung jawab',
        'Tidak menyalahgunakan kewenangan jabatan'
      ],
      ekspektasi: '-'
    }
  ],
  lampiran: {
    dukunganSumberDaya: ['POK Biro OSDM Tahun 2025', 'Program Kerja Biro OSDM Tahun 2025']
  }
})

// Fetch Pejabat Penilai
const fetchPejabatPenilai = async () => {
  const parentId = userData.value?.parent_id
  
  if (!parentId) {
    console.log('Tidak ada parent_id')
    return
  }

  loadingPenilai.value = true
  
  try {
    const apiUrl = import.meta.env.VITE_API_BASE_URL
    const token = localStorage.getItem('auth_token')
    
    console.log('Fetching user ID:', parentId)
    
    if (!token) {
      throw new Error('Token tidak ditemukan')
    }
    
    // URL BARU: /api/users/{id}
    const response = await fetch(`${apiUrl}/users/${parentId}`, {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }
    })
    
    console.log('Response status:', response.status)
    
    if (!response.ok) {
      const errorText = await response.text()
      console.error('Error response:', errorText)
      throw new Error(`HTTP error! status: ${response.status}`)
    }
    
    const data = await response.json()
    console.log('Response data:', data)
    
    if (data.success) {
      pejabatPenilai.value = data.data
      console.log('Pejabat Penilai:', pejabatPenilai.value)
    } else {
      console.error('Failed to fetch pejabat penilai:', data.message)
    }
  } catch (error) {
    console.error('Error fetching pejabat penilai:', error)
  } finally {
    loadingPenilai.value = false
  }
}

const syncEkinerja = () => {
  alert('Fitur sinkronisasi ekinerja BKN akan segera tersedia')
}

const viewDetail = (id: number) => {
  selectedSkpId.value = id
  showDetail.value = true
  detailTab.value = 'utama'
  // Fetch pejabat penilai saat masuk detail
  fetchPejabatPenilai()
}

const backToList = () => {
  showDetail.value = false
  selectedSkpId.value = null
  pejabatPenilai.value = null
}

// Fetch pejabat penilai saat component di-mount (opsional, jika mau load di awal)
onMounted(() => {
  // Uncomment jika mau load data pejabat penilai di awal
  // fetchPejabatPenilai()
})
</script>