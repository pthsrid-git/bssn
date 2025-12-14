<template>
  <!-- Card Daftar Pegawai -->
  <Card v-if="!selectedPegawai">
    <!-- Header -->
    <div class="border-b border-gray-200 pb-4 mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Logbook Atasan</h1>

      <hr class="my-4 border-gray-200" />

      <div class="mt-4 inline-grid grid-cols-1 md:grid-flow-col md:auto-cols-max gap-8">
        <div class="flex flex-col whitespace-nowrap">
          <span class="text-sm font-medium text-gray-500">Nama:</span>
          <span class="text-sm text-gray-900">{{ user?.fullname }}</span>
        </div>

        <div class="flex flex-col whitespace-nowrap">
          <span class="text-sm font-medium text-gray-500">Jumlah Pegawai:</span>
          <span class="text-sm text-gray-900">{{ pegawaiList.length }}</span>
        </div>

        <div class="flex flex-col whitespace-nowrap">
          <span class="text-sm font-medium text-gray-500">Unit Kerja:</span>
          <span class="text-sm text-gray-900">
            Biro Organisasi dan Sumber Daya Manusia
          </span>
        </div>
      </div>
    </div>

    <!-- Section Title -->
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Daftar Pegawai Unit Kerja</h2>
    
    <!-- Table -->
    <TableDefault>
      <template #header>
        <tr>
          <TableHeader alignment="center" custom-class="w-16">No</TableHeader>
          <TableHeader>Nama</TableHeader>
          <TableHeader>NIP</TableHeader>
          <TableHeader>Pangkat</TableHeader>
          <TableHeader>Jabatan</TableHeader>
          <TableHeader alignment="center" custom-class="w-32">Aksi</TableHeader>
        </tr>
      </template>
      <template #body>
        <tr v-if="loading">
          <TableData colspan="6" alignment="center">
            <div class="flex items-center justify-center py-4">
              <svg class="animate-spin h-6 w-6 text-warning-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span class="text-gray-600">Memuat data...</span>
            </div>
          </TableData>
        </tr>
        <template v-else-if="pegawaiList.length > 0">
          <tr v-for="(pegawai, index) in pegawaiList" :key="pegawai.id" class="hover:bg-gray-50">
            <TableData alignment="center">{{ index + 1 }}</TableData>
            <TableData>{{ pegawai.nama }}</TableData>
            <TableData>{{ pegawai.nip }}</TableData>
            <TableData>{{ pegawai.pangkat }}</TableData>
            <TableData>{{ pegawai.jabatan }}</TableData>
            <TableData alignment="center">
              <button 
                class="px-3 py-1 h-fit border border-info-300 text-info-600 rounded text-xs hover:bg-info-50 whitespace-nowrap"
                @click="viewPegawaiLogbook(pegawai)"
              >
                Lihat Logbook
              </button>
            </TableData>
          </tr>
        </template>
        <tr v-else>
          <TableDataNone label="Tidak ada data pegawai" />
        </tr>
      </template>
    </TableDefault>
  </Card>

  <!-- Card Logbook Pegawai (Replace Card Daftar Pegawai) -->
  <Card v-else>
    <div class="space-y-4">
      <!-- Header with Back Button & Pegawai Info -->
      <div class="border-b border-gray-200 pb-4">
        <button 
          @click="backToPegawaiList"
          class="flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-3"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          <span class="text-sm font-semibold">{{ selectedPegawai.nama }}</span>
        </button>
        
        <div class="flex items-center gap-1 text-sm text-gray-500">
          <span>{{ selectedPegawai.nip }}</span>
          <span>|</span>
          <span>{{ selectedPegawai.pangkat }}</span>
          <span>|</span>
          <span>{{ selectedPegawai.jabatan }}</span>
        </div>
      </div>

      <!-- Month Selector & Filters -->
      <div class="flex justify-between items-center">
        <div class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-lg">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span class="text-sm font-semibold text-gray-800">
            {{ formattedMonth }}
          </span>
        </div>

        <div class="flex gap-3">
          <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm flex items-center gap-2 hover:bg-gray-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            <span>Filter</span>
          </button>
          <div class="px-4 py-2 border border-gray-300 rounded-lg text-sm flex items-center gap-2 hover:bg-gray-50 focus-within:ring-1 focus-within:ring-warning-500">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
              type="text"
              placeholder="Cari..."
              class="w-full border-0 p-0 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-0 bg-transparent"
            />
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loadingLogs" class="text-center py-12">
        <svg class="animate-spin h-8 w-8 text-warning-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-600">Memuat logbook...</p>
      </div>

      <!-- Logbook Cards -->
      <div v-else class="space-y-4">
        <CardDefault
          v-for="dateGroup in groupedPegawaiLogs"
          :key="dateGroup.date"
          :backgroundClass="isWeekend(dateGroup.date) ? 'bg-gray-100' : 'bg-white'"
        >
          <div class="flex gap-4 min-h-[80px]">
            <!-- Date Column -->
            <div class="flex-shrink-0 w-16 flex flex-col items-center justify-center text-center">
              <div class="text-xs text-gray-900">{{ dateGroup.dayName }}</div>
              <div class="text-2xl font-bold text-gray-900 leading-none">
                {{ dateGroup.day }}
              </div>
            </div>

            <!-- Separator -->
            <div class="w-px" :class="isWeekend(dateGroup.date) ? 'bg-gray-300' : 'bg-gray-200'"></div>

            <!-- Content -->
            <div class="flex-1 flex items-center">
              <div v-if="dateGroup.activities.length === 0" class="py-2 text-gray-900 text-sm">
                Tidak ada aktivitas
              </div>

              <div v-else class="space-y-3 w-full">
                <div
                  v-for="(activity, index) in dateGroup.activities"
                  :key="activity.id"
                >
                  <div v-if="index > 0" class="border-t border-gray-100 pt-3"></div>
                  
                  <div class="flex gap-4">
                    <!-- Time & Status -->
                    <div class="flex-shrink-0 w-28 space-y-1.5">
                      <div class="flex items-center gap-2 text-sm text-gray-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ activity.jam_mulai }} - {{ activity.jam_selesai }}</span>
                      </div>
                      <div class="flex items-center gap-2 text-sm text-gray-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>{{ getFileCount(activity) }} file</span>
                      </div>
                      <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-5 h-5">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                          </svg>
                        </span>
                        <span
                          :class="getStatusClass(activity.status)"
                          class="text-xs px-3 py-1 rounded-full font-medium"
                        >
                          {{ getStatusText(activity.status) }}
                        </span>
                      </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="flex-1 grid grid-cols-[1fr_1fr_1fr_1fr_1fr_1fr_auto] gap-x-3 gap-y-1.5 text-sm">
                      <div>
                        <div class="text-xs font-semibold text-gray-900 mb-1">Rencana Hasil Kinerja SKP</div>
                        <div class="text-gray-700 text-xs">{{ activity.rencana_hasil_kinerja_skp }}</div>
                      </div>

                      <div>
                        <div class="text-xs font-semibold text-gray-900 mb-1">Indikator Hasil</div>
                        <div class="text-gray-700 text-xs">{{ activity.indikator_hasil_rencana_kerja }}</div>
                      </div>

                      <div>
                        <div class="text-xs font-semibold text-gray-900 mb-1">Aktivitas Harian</div>
                        <div class="text-gray-700 text-xs">{{ activity.aktivitas_kegiatan_harian || activity.judul_kegiatan }}</div>
                      </div>

                      <div>
                        <div class="text-xs font-semibold text-gray-900 mb-1">Keterangan</div>
                        <div class="text-gray-700 text-xs">{{ activity.keterangan || activity.deskripsi || '-' }}</div>
                      </div>

                      <div>
                        <div class="text-xs font-semibold text-gray-900 mb-1">Catatan Katim</div>
                        <div class="text-gray-700 text-xs">{{ activity.catatan_katim || '-' }}</div>
                      </div>

                      <div>
                        <div class="text-xs font-semibold text-gray-900 mb-1">Catatan Atasan</div>
                        <div class="text-gray-700 text-xs">{{ activity.catatan_atasan || '-' }}</div>
                      </div>
                      
                      <div class="flex flex-col items-stretch gap-2">
                        <!-- Tombol Detail selalu muncul -->
                        <button
                          @click="viewLogDetail(activity)"
                          class="px-3 py-1 w-full border border-info-300 text-info-600 rounded text-xs text-center hover:bg-info-50 transition-colors flex items-center justify-center gap-1"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                          </svg>
                          Detail
                        </button>

                        <!-- Tombol Setuju & Tolak muncul jika status Disubmit -->
                        <div v-if="activity.status === 'Disubmit'" class="flex flex-col gap-2 w-full">
                          <button
                            @click="handleApprove(activity)"
                            :disabled="approving || rejecting"
                            class="px-3 py-1 w-full bg-success-500 text-black rounded text-xs text-center hover:bg-success-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-1"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ approving ? 'Memproses...' : 'Setuju' }}
                          </button>

                          <button
                            @click="handleReject(activity)"
                            :disabled="approving || rejecting"
                            class="px-3 py-1 w-full bg-red-500 text-white rounded text-xs text-center hover:bg-red-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-1"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            {{ rejecting ? 'Memproses...' : 'Tolak' }}
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </CardDefault>

        <!-- Empty State -->
        <div v-if="groupedPegawaiLogs.length === 0" class="text-center py-12">
          <div class="text-gray-400 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <p class="text-gray-600 mb-2">Belum ada aktivitas yang tercatat</p>
          <p class="text-sm text-gray-500">Tidak ada logbook untuk bulan ini</p>
        </div>
      </div>
    </div>
  </Card>

  <!-- Modal Detail Logbook -->
  <ModalDetailLogbookAtasan ref="detailModal" :entry="selectedLog" @success="handleUpdateSuccess" />
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { LogbookAtasanService, type Pegawai, type PegawaiLog } from '@/services/logbookAtasanService'
import Card from '@/components/card/CardDefault.vue'
import CardDefault from '@/components/card/CardDefault.vue'
import TableDefault from '@/components/table/TableDefault.vue'
import TableHeader from '@/components/table/TableHeader.vue'
import TableData from '@/components/table/TableData.vue'
import TableDataNone from '@/components/table/TableDataNone.vue'
import ModalDetailLogbookAtasan from '@/components/modal/ModalDetailLogbookAtasan.vue'

const pegawaiList = ref<Pegawai[]>([])
const loading = ref(false)
const selectedPegawai = ref<Pegawai | null>(null)
const pegawaiLogs = ref<PegawaiLog[]>([])
const loadingLogs = ref(false)
const selectedMonth = ref('')
const user = ref<any>(null)
const detailModal = ref()
const selectedLog = ref<PegawaiLog | null>(null)

const formattedMonth = computed(() => {
  if (!selectedMonth.value) return ''
  const [year, month] = selectedMonth.value.split('-')
  const monthNames = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ]
  const now = new Date()
  const day = now.getDate()
  return `${monthNames[parseInt(month) - 1]} (${day} ${monthNames[parseInt(month) - 1]} ${year})`
})

const groupedPegawaiLogs = computed(() => {
  const groups: any[] = []
  const dateMap = new Map()

  if (selectedMonth.value) {
    const [year, month] = selectedMonth.value.split('-')
    const daysInMonth = new Date(parseInt(year), parseInt(month), 0).getDate()
    
    for (let day = 1; day <= daysInMonth; day++) {
      const date = `${year}-${month}-${String(day).padStart(2, '0')}`
      const dateObj = new Date(date)
      const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
      
      dateMap.set(date, {
        date,
        day: String(day).padStart(2, '0'),
        dayName: dayNames[dateObj.getDay()],
        activities: []
      })
    }
  }

  pegawaiLogs.value.forEach(log => {
    const tanggalOnly = log.tanggal.split('T')[0]
    if (dateMap.has(tanggalOnly)) {
      dateMap.get(tanggalOnly).activities.push(log)
    }
  })

  dateMap.forEach(value => groups.push(value))
  return groups
})

onMounted(() => {
  const storedUser = localStorage.getItem('user')
  if (storedUser) {
    user.value = JSON.parse(storedUser)
  }
  
  const now = new Date()
  selectedMonth.value = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`
  
  loadPegawaiList()
})

const loadPegawaiList = async () => {
  loading.value = true
  
  try {
    const response = await LogbookAtasanService.getPegawaiList()
    if (response?.data?.data && Array.isArray(response.data.data)) {
      pegawaiList.value = response.data.data
    } else if (response?.data && Array.isArray(response.data)) {
      pegawaiList.value = response.data
    } else {
      pegawaiList.value = []
    }
  } catch (error) {
    pegawaiList.value = []
  } finally {
    loading.value = false
  }
}

const viewPegawaiLogbook = async (pegawai: Pegawai) => {
  selectedPegawai.value = pegawai
  await loadPegawaiLogs()
}

const backToPegawaiList = () => {
  selectedPegawai.value = null
  pegawaiLogs.value = []
  selectedLog.value = null
}

const loadPegawaiLogs = async () => {
  if (!selectedPegawai.value?.id) return
  
  loadingLogs.value = true
  try {
    const filters: any = {}
    if (selectedMonth.value) {
      const [year, month] = selectedMonth.value.split('-')
      const startDate = `${year}-${month}-01`
      const endDate = new Date(parseInt(year), parseInt(month), 0)
      filters.start_date = startDate
      filters.end_date = `${year}-${month}-${String(endDate.getDate()).padStart(2, '0')}`
    }
    
    const response = await LogbookAtasanService.getPegawaiLogs(selectedPegawai.value.id, filters)
    
    if (response?.data?.data && Array.isArray(response.data.data)) {
      pegawaiLogs.value = response.data.data
    } else if (response?.data && Array.isArray(response.data)) {
      pegawaiLogs.value = response.data
    } else {
      pegawaiLogs.value = []
    }
  } catch (error) {
    pegawaiLogs.value = []
  } finally {
    loadingLogs.value = false
  }
}

const viewLogDetail = (log: PegawaiLog) => {
  selectedLog.value = log
  detailModal.value?.open()
}

const handleUpdateSuccess = () => {
  loadPegawaiLogs()
}

const getStatusText = (status: string) => {
  const statusMap: Record<string, string> = {
    'Disubmit': 'Disubmit',
    'Disetujui': 'Disetujui',
    'Ditolak': 'Ditolak',
    'pending': 'Disubmit'
  }
  return statusMap[status] || status
}

const getStatusClass = (status: string) => {
  const classes: Record<string, string> = {
    'Disubmit': 'bg-warning-100 text-warning-700',
    'Disetujui': 'bg-green-100 text-green-700',
    'Ditolak': 'bg-red-100 text-red-700',
    'pending': 'bg-warning-100 text-warning-700'
  }
  return classes[status] || 'bg-gray-100 text-gray-700'
}

const getFileCount = (activity: any) => {
  return activity.file_path || activity.file_name ? 1 : 0
}

const isWeekend = (date: string) => {
  const dateObj = new Date(date)
  const day = dateObj.getDay()
  return day === 0 || day === 6
}
</script>