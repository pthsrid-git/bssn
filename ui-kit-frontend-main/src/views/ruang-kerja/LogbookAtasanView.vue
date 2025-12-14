<template>
  <!-- Main Card Container dengan Tabs -->
  <div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <!-- Header dengan Tabs -->
    <div class="border-b border-gray-200 px-6">
      <div class="flex items-center justify-between py-4">
        <h1 class="text-2xl font-bold text-gray-900">Logbook Atasan</h1>
        
        <!-- Tabs Navigation -->
        <div class="flex gap-4 overflow-x-auto">
          <button 
            @click="activeTab = 'daftar-unit'"
            :class="[
              'py-2 px-4 text-sm font-semibold border-b-2 transition-colors whitespace-nowrap',
              activeTab === 'daftar-unit' 
                ? 'border-warning-500 text-gray-900' 
                : 'border-transparent text-gray-500 hover:text-gray-700'
            ]"
          >
            DAFTAR UNIT KERJA
          </button>
          <button 
            @click="activeTab = 'detail-logbook'"
            :disabled="!selectedPegawai"
            :class="[
                'py-2 px-4 text-sm font-semibold border-b-2 transition-colors whitespace-nowrap',
                activeTab === 'detail-logbook' 
                ? 'border-warning-500 text-gray-900' 
                : 'border-transparent text-gray-500 hover:text-gray-700',
                { 'opacity-50 cursor-not-allowed': !selectedPegawai }
            ]"
            >
            DETAIL LOGBOOK
            </button>
        </div>
      </div>
    </div>

    <!-- Tab Content -->
    <div class="p-6">
      <!-- Tab: Daftar Unit Kerja -->
      <div v-if="activeTab === 'daftar-unit'">
        <!-- Data Unit Kerja Section -->
        <div class="mb-8">
          <h2 class="text-sm font-medium text-gray-500 mb-4">Data Unit Kerja</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
              <p class="text-xs text-gray-400 mb-1">Nama Unit</p>
              <p class="text-sm text-gray-900">{{ userData?.nama_unit_organisasi || '-' }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-400 mb-1">Jumlah Anggota</p>
              <p class="text-sm text-gray-900">{{ pegawaiList.length }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-400 mb-1">Jabatan Anda</p>
              <p class="text-sm text-gray-900">{{ userData?.nama_jabatan || '-' }}</p>
            </div>
          </div>
        </div>

        <!-- Daftar Pegawai Unit Kerja -->
        <div>
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Anggota Unit Kerja</h2>
            
            <!-- Search & Filter -->
            <div class="flex gap-3">
              <div class="relative">
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Cari pegawai..."
                  class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-warning-500 focus:border-warning-500"
                >
                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Loading State -->
          <div v-if="loading" class="text-center py-12">
            <svg class="animate-spin h-8 w-8 text-warning-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-600">Memuat data pegawai...</p>
          </div>

          <!-- Empty State -->
          <div v-else-if="filteredPegawai.length === 0" class="text-center py-12">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Pegawai</h2>
            <p class="text-gray-600">Belum ada pegawai yang terdaftar di unit kerja Anda</p>
          </div>

          <!-- Table -->
          <div v-else class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pangkat</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tim</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr 
                  v-for="(pegawai, index) in filteredPegawai" 
                  :key="pegawai.id"
                  class="hover:bg-gray-50 transition-colors"
                >
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ index + 1 }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ pegawai.nama_pegawai }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ pegawai.nip_nrp }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ pegawai.pangkat_golongan }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ pegawai.nama_jabatan }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ pegawai.tim || '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <button
                      @click="selectPegawai(pegawai)"
                      class="px-4 py-2 bg-warning-500 text-black text-sm rounded-lg hover:bg-warning-600 transition-colors"
                    >
                      Lihat Logbook
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Tab: Detail Logbook -->
      <div v-if="activeTab === 'detail-logbook'">
        <!-- Header Detail -->
        <div class="mb-6">
          <div class="flex items-center gap-4 mb-4">
            <button
              @click="activeTab = 'daftar-unit'"
              class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
              </svg>
              <span class="text-sm">Kembali ke Daftar</span>
            </button>
          </div>
          
          <div>
            <h2 class="text-xl font-bold text-gray-900">{{ selectedPegawai?.nama_pegawai }}</h2>
            <div class="mt-1 text-sm text-gray-600">
              {{ selectedPegawai?.nip_nrp }} | {{ selectedPegawai?.pangkat_golongan }} | {{ selectedPegawai?.nama_jabatan }}
            </div>
          </div>
        </div>

        <!-- Filter Bulan -->
        <div class="mb-6 flex justify-between items-center">
          <div class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-sm font-semibold text-gray-800">
              {{ formatMonth(selectedMonth) }}
            </span>
          </div>

          <div class="flex gap-3">
            <input
              v-model="selectedMonth"
              type="month"
              @change="handleMonthChange"
              class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-warning-500"
            >
            <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm flex items-center gap-2 hover:bg-gray-50">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
              </svg>
              <span>Filter</span>
            </button>
            <div class="px-4 py-2 border border-gray-300 rounded-lg text-sm flex items-center gap-2 hover:bg-gray-50 focus-within:ring-1 focus-within:ring-warning-500">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              <input
                v-model="searchActivity"
                type="text"
                placeholder="Cari aktivitas..."
                class="w-full border-0 p-0 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-0 bg-transparent"
              >
            </div>
          </div>
        </div>

        <!-- Loading Logbook -->
        <div v-if="loadingEntries" class="text-center py-12">
          <svg class="animate-spin h-8 w-8 text-warning-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="text-gray-600">Memuat data logbook...</p>
        </div>

        <!-- Logbook Cards -->
        <div v-else class="space-y-4">
          <!-- Each Day Card -->
          <div
            v-for="dateGroup in groupedLogbooks"
            :key="dateGroup.date"
            class="border border-gray-200 rounded-lg p-4"
            :class="isWeekend(dateGroup.date) ? 'bg-gray-100' : 'bg-white'"
          >
            <div class="flex gap-4 min-h-[80px]">
              <!-- Date Column (Left) -->
              <div class="flex-shrink-0 w-16 flex flex-col items-center justify-center text-center">
                <div class="text-xs text-gray-900">{{ dateGroup.dayName }}</div>
                <div class="text-2xl font-bold text-gray-900 leading-none">
                  {{ dateGroup.day }}
                </div>
              </div>

              <!-- Separator Line -->
              <div class="w-px" :class="isWeekend(dateGroup.date) ? 'bg-gray-300' : 'bg-gray-200'"></div>

              <!-- Content Column (Right) -->
              <div class="flex-1 flex items-center">
                <!-- No activities -->
                <div v-if="dateGroup.activities.length === 0" class="py-2 text-gray-900 text-sm">
                  Tidak ada aktivitas
                </div>

                <!-- Has activities -->
                <div v-else class="space-y-3 w-full">
                  <div
                    v-for="(activity, index) in dateGroup.activities"
                    :key="activity.id"
                  >
                    <!-- Divider between activities -->
                    <div v-if="index > 0" class="border-t border-gray-100 pt-3"></div>
                    
                    <!-- Activity Item -->
                    <div class="flex gap-4">
                      <!-- Time & Status Column -->
                      <div class="flex-shrink-0 w-28 space-y-1.5">
                        <div class="flex items-center gap-2 text-sm text-gray-900">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                          </svg>
                          <span>{{ activity.time }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-900">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                          </svg>
                          <span>{{ activity.files || '0 file' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                          <span class="flex items-center justify-center w-5 h-5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                          </span>
                          <span
                            :class="getStatusClass(activity.status)"
                            class="text-xs px-3 py-1 rounded-full font-medium"
                          >
                            {{ getStatusLabel(activity.status) }}
                          </span>
                        </div>
                      </div>

                      <!-- Details Grid -->
                      <div class="flex-1 grid grid-cols-[1fr_1fr_1fr_1fr_1fr_1fr_auto] gap-x-3 gap-y-1.5 text-sm">
                        <div>
                          <div class="text-xs font-semibold text-gray-900 mb-1">Rencana Hasil Kinerja SKP</div>
                          <div class="text-gray-700 text-xs">{{ activity.rencana_hasil_kinerja }}</div>
                        </div>

                        <div>
                          <div class="text-xs font-semibold text-gray-900 mb-1">Indikator Hasil Rencana Kerja</div>
                          <div class="text-gray-700 text-xs">{{ activity.indikator_hasil }}</div>
                        </div>

                        <div>
                          <div class="text-xs font-semibold text-gray-900 mb-1">Aktivitas Harian</div>
                          <div class="text-gray-700 text-xs">{{ activity.aktivitas_harian }}</div>
                        </div>

                        <div>
                          <div class="text-xs font-semibold text-gray-900 mb-1">Keterangan</div>
                          <div class="text-gray-700 text-xs">{{ activity.keterangan || '-' }}</div>
                        </div>

                        <div>
                          <div class="text-xs font-semibold text-gray-900 mb-1">Catatan Katim</div>
                          <div class="text-gray-700 text-xs">{{ activity.catatan_katim || '-' }}</div>
                        </div>

                        <div>
                          <div class="text-xs font-semibold text-gray-900 mb-1">Catatan Atasan</div>
                          <div class="text-gray-700 text-xs">{{ activity.catatan_atasan || '-' }}</div>
                        </div>
                        
                        <div class="flex items-center justify-center">
                          <button
                            @click="viewDetail(activity)"
                            class="px-3 py-1 h-fit border border-info-300 text-info-600 rounded text-xs hover:bg-info-50"
                          >
                            Detail
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="groupedLogbooks.length === 0" class="text-center py-12">
            <div class="text-gray-400 mb-4">
              <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
            <p class="text-gray-600 mb-2">Belum ada aktivitas yang tercatat</p>
            <p class="text-sm text-gray-500">Pegawai belum mengisi logbook untuk bulan ini</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Detail -->
  <!-- Modal component akan ditambahkan di sini -->
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

// State
const activeTab = ref('daftar-unit')
const loading = ref(false)
const loadingEntries = ref(false)
const searchQuery = ref('')
const searchActivity = ref('')
const userData = ref<any>(null)
const pegawaiList = ref<any[]>([])
const selectedPegawai = ref<any>(null)
const logbookEntries = ref<any[]>([])
const selectedMonth = ref(getCurrentMonth())
const showDetailModal = ref(false)
const selectedEntry = ref<any>(null)

// Computed
const filteredPegawai = computed(() => {
  if (!searchQuery.value) return pegawaiList.value
  return pegawaiList.value.filter(pegawai => 
    pegawai.nama_pegawai?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    pegawai.nip_nrp?.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const groupedLogbooks = computed(() => {
  const groups: any[] = []
  
  if (selectedMonth.value) {
    const [year, month] = selectedMonth.value.split('-')
    const daysInMonth = new Date(parseInt(year), parseInt(month), 0).getDate()
    
    for (let day = 1; day <= daysInMonth; day++) {
      const date = `${year}-${month}-${String(day).padStart(2, '0')}`
      const dateObj = new Date(date)
      const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
      
      const activities = logbookEntries.value.filter(entry => 
        entry.date === date && !entry.isEmpty
      )
      
      groups.push({
        date,
        day: String(day).padStart(2, '0'),
        dayName: dayNames[dateObj.getDay()],
        activities
      })
    }
  }
  
  return groups
})

// Methods
function getCurrentMonth(): string {
  const now = new Date()
  const year = now.getFullYear()
  const month = String(now.getMonth() + 1).padStart(2, '0')
  return `${year}-${month}`
}

function formatMonth(monthStr: string): string {
  const [year, month] = monthStr.split('-')
  const monthNames = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ]
  return `${monthNames[parseInt(month) - 1]} ${year}`
}

function isWeekend(date: string): boolean {
  const dateObj = new Date(date)
  const day = dateObj.getDay()
  return day === 0 || day === 6
}

function getStatusClass(status: string): string {
  const classes: Record<string, string> = {
    'Disetujui': 'bg-primary-100 text-primary-700',
    'Disubmit': 'bg-warning-100 text-warning-700',
    'Ditolak': 'bg-red-100 text-red-700',
    'Draft': 'bg-gray-100 text-gray-700',
    'Menunggu Persetujuan': 'bg-yellow-100 text-yellow-700',
    'Dikembalikan': 'bg-orange-100 text-orange-700'
  }
  return classes[status] || 'bg-gray-100 text-gray-700'
}

function getStatusLabel(status: string): string {
  return status || 'Draft'
}

const selectPegawai = (pegawai: any) => {
  selectedPegawai.value = pegawai
  activeTab.value = 'detail-logbook'
  fetchPegawaiLogbook(pegawai.id)
}

const handleMonthChange = () => {
  if (selectedPegawai.value) {
    fetchPegawaiLogbook(selectedPegawai.value.id)
  }
}

const viewDetail = (activity: any) => {
  selectedEntry.value = activity
  showDetailModal.value = true
  console.log('View detail:', activity)
}

const handleCloseModal = () => {
  showDetailModal.value = false
  selectedEntry.value = null
}

const handleSaved = () => {
  if (selectedPegawai.value) {
    fetchPegawaiLogbook(selectedPegawai.value.id)
  }
}

// Mock Data untuk development
const mockPegawaiList = [
  {
    id: 1,
    nama_pegawai: 'Budi Santoso',
    nip_nrp: '198012312345678901',
    pangkat_golongan: 'III/a',
    nama_jabatan: 'Analis Kebijakan',
    tim: 'Tim A'
  },
  {
    id: 2,
    nama_pegawai: 'Siti Nurhaliza',
    nip_nrp: '197812312345678902',
    pangkat_golongan: 'IV/a',
    nama_jabatan: 'Kepala Sub Bagian',
    tim: 'Tim B'
  },
  {
    id: 3,
    nama_pegawai: 'Joko Widodo',
    nip_nrp: '196112312345678903',
    pangkat_golongan: 'IV/b',
    nama_jabatan: 'Kepala Bagian',
    tim: 'Tim C'
  },
  {
    id: 4,
    nama_pegawai: 'Megawati Soekarnoputri',
    nip_nrp: '194712312345678904',
    pangkat_golongan: 'IV/c',
    nama_jabatan: 'Deputi',
    tim: 'Tim A'
  },
  {
    id: 5,
    nama_pegawai: 'Prabowo Subianto',
    nip_nrp: '195112312345678905',
    pangkat_golongan: 'IV/d',
    nama_jabatan: 'Sekretaris',
    tim: 'Tim B'
  }
]

const mockLogbookData = [
  {
    id: 1,
    date: '2024-01-15',
    isEmpty: false,
    time: '08:00 - 12:00',
    files: '2 file',
    status: 'Disetujui',
    rencana_hasil_kinerja: 'Laporan bulanan',
    indikator_hasil: 'Laporan selesai 100%',
    aktivitas_harian: 'Menyusun laporan bulanan',
    keterangan: 'Laporan untuk divisi IT',
    catatan_katim: 'Sudah baik',
    catatan_atasan: 'Diajukan untuk rapat'
  },
  {
    id: 2,
    date: '2024-01-15',
    isEmpty: false,
    time: '13:00 - 17:00',
    files: '1 file',
    status: 'Menunggu Persetujuan',
    rencana_hasil_kinerja: 'Meeting koordinasi',
    indikator_hasil: 'Notulen rapat dibuat',
    aktivitas_harian: 'Rapat koordinasi tim',
    keterangan: 'Rapat perencanaan Q1',
    catatan_katim: '',
    catatan_atasan: ''
  }
]

// API Functions
const fetchUserData = async () => {
  try {
    const storedUser = localStorage.getItem('user')
    if (storedUser) {
      userData.value = JSON.parse(storedUser)
    } else {
      // Mock data jika tidak ada di localStorage
      userData.value = {
        nama_unit_organisasi: 'Pusat Data dan Informasi',
        nama_jabatan: 'Kepala Divisi'
      }
    }
  } catch (error) {
    console.error('Error fetching user data:', error)
  }
}

const fetchPegawaiList = async () => {
  loading.value = true
  try {
    // TODO: Ganti dengan API call yang sebenarnya
    // const response = await logbookAtasanService.getPegawaiList()
    // pegawaiList.value = response.data
    
    // Mock data untuk development
    setTimeout(() => {
      pegawaiList.value = mockPegawaiList
      loading.value = false
    }, 1000)
  } catch (error) {
    console.error('Error fetching pegawai list:', error)
    loading.value = false
  }
}

const fetchPegawaiLogbook = async (pegawaiId: string) => {
  loadingEntries.value = true
  try {
    // TODO: Ganti dengan API call yang sebenarnya
    // const response = await logbookAtasanService.getPegawaiLogs(pegawaiId, selectedMonth.value)
    // logbookEntries.value = transformLogbookData(response.data)
    
    // Mock data untuk development
    setTimeout(() => {
      logbookEntries.value = mockLogbookData
      loadingEntries.value = false
    }, 1000)
  } catch (error) {
    console.error('Error fetching logbook:', error)
    loadingEntries.value = false
  }
}

// Lifecycle
onMounted(() => {
  fetchUserData()
  fetchPegawaiList()
})
</script>

<style scoped>
/* Custom styles jika diperlukan */
</style>