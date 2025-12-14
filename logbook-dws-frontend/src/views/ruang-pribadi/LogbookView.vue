<template>
  <!-- Main Card Container with Tabs Inside -->
  <div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <!-- Tabs Inside Card -->
    <div class="border-b border-gray-200 px-6">
      <div class="flex gap-8 overflow-x-auto">
        <button 
          @click="activeTab = 'pengisian-logbook'"
          :class="[
            'py-4 px-2 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
            activeTab === 'pengisian-logbook' 
              ? 'border-warning-500 text-gray-900' 
              : 'border-transparent text-gray-500 hover:text-gray-700'
          ]"
        >
          PENGISIAN LOGBOOK
        </button>
        <button 
          @click="activeTab = 'skp'"
          :class="[
            'py-4 px-2 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
            activeTab === 'skp' 
              ? 'border-warning-500 text-gray-900' 
              : 'border-transparent text-gray-500 hover:text-gray-700'
          ]"
        >
          SKP
        </button>
      </div>
    </div>

    <!-- Tab Content Inside Card -->
    <div class="p-4">
      <!-- Pengisian Logbook Content -->
      <div v-if="activeTab === 'pengisian-logbook'" class="space-y-4">
        <!-- Header with Button -->
        <div class="flex justify-between items-start">
          <h1 class="text-2xl font-bold text-gray-900">Pengisian Logbook</h1>
          <button
            @click="openAddModal"
            class="px-4 py-2 bg-warning-500 text-black rounded-lg hover:bg-warning-600 transition-colors flex items-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Tambah Aktivitas Harian</span>
          </button>
        </div>

        <div class="h-px bg-gray-200 my-3"></div>

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
        <div v-if="isLoading" class="text-center py-12">
          <svg class="animate-spin h-8 w-8 text-primary-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="text-gray-600">Memuat data...</p>
        </div>

        <!-- Logbook Cards - Each day in separate card -->
        <div v-else class="space-y-4">
          <!-- Each Day Card -->
          <CardDefault
            v-for="dateGroup in groupedLogbooks"
            :key="dateGroup.date"
            :backgroundClass="isWeekend(dateGroup.date) ? 'bg-gray-100' : 'bg-white'"
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
                <!-- No activities (including weekends) -->
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
                          <span class="flex items-center justify-center w-5 h-5 ">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
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
                          <div class="text-gray-700 text-xs">{{ activity.rencana_hasil_kinerja_skp }}</div>
                        </div>

                        <div>
                          <div class="text-xs font-semibold text-gray-900 mb-1">Indikator Hasil Rencana Kerja</div>
                          <div class="text-gray-700 text-xs">{{ activity.indikator_hasil_rencana_kerja }}</div>
                        </div>

                        <div>
                          <div class="text-xs font-semibold text-gray-900 mb-1">Aktivitas Harian</div>
                          <div class="text-gray-700 text-xs">{{ activity.aktivitas_kegiatan_harian }}</div>
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
          </CardDefault>

          <!-- Empty State -->
          <div v-if="groupedLogbooks.length === 0" class="text-center py-12">
            <div class="text-gray-400 mb-4">
              <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <p class="text-gray-600 mb-2">Belum ada aktivitas yang tercatat</p>
            <p class="text-sm text-gray-500">Mulai tambahkan aktivitas harian Anda</p>
          </div>
        </div>
      </div>

      <!-- SKP Tab Content -->
      <div v-if="activeTab === 'skp'">
        <SKPView />
      </div>
    </div>
  </div>

  <!-- Modal Tambah Aktivitas -->
  <ModalTambahAktivitas ref="tambahModal" @success="handleSuccess" />
  
  <!-- Modal Detail Logbook -->
  <ModalDetailLogbook ref="detailModal" :entry="selectedActivity" />
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import CardDefault from '@/components/card/CardDefault.vue'
import ModalTambahAktivitas from '@/components/modal/ModalTambahAktivitas.vue'
import ModalDetailLogbook from '@/components/modal/ModalDetailLogbook.vue'
import SKPView from '@/components/skp/SKPView.vue'

const activeTab = ref('pengisian-logbook')
const isLoading = ref(false)
const logbooks = ref<any[]>([])
const selectedMonth = ref('')
const tambahModal = ref()
const detailModal = ref()
const selectedActivity = ref(null)

const groupedLogbooks = computed(() => {
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

  logbooks.value.forEach(logbook => {
    const tanggalOnly = logbook.tanggal.split('T')[0]
    if (dateMap.has(tanggalOnly)) {
      dateMap.get(tanggalOnly).activities.push(logbook)
    }
  })

  dateMap.forEach(value => groups.push(value))
  return groups
})

const formattedMonth = computed(() => {
  const now = new Date()

  const monthNames = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ]

  const monthName = monthNames[now.getMonth()]
  const day = now.getDate()
  const year = now.getFullYear()

  return `${monthName} (${day} ${monthName} ${year})`
})

const loadLogbooks = async () => {
  isLoading.value = true
  try {
    const token = localStorage.getItem('auth_token')
    
    if (!token) {
      console.error('No auth token found')
      return
    }
    
    const params = new URLSearchParams()
    if (selectedMonth.value) params.append('month', selectedMonth.value)
    
    const response = await fetch(`${import.meta.env.VITE_API_BASE_URL}/logbooks?${params}`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    })
    
    if (response.status === 401) {
      console.error('Unauthorized - token invalid or expired')
      return
    }
    
    if (response.ok) {
      const data = await response.json()
      logbooks.value = data.data || []
    } else {
      console.error('Failed to load logbooks:', response.status)
    }
  } catch (error) {
    console.error('Failed to load logbooks:', error)
  } finally {
    isLoading.value = false
  }
}

const openAddModal = () => {
  tambahModal.value?.open()
}

const handleSuccess = () => {
  loadLogbooks()
}

const viewDetail = (activity: any) => {
  selectedActivity.value = activity
  detailModal.value?.open()
}

const getStatusClass = (status: string) => {
  const classes: Record<string, string> = {
    'Disetujui': 'bg-primary-100 text-primary-700',
    'Disubmit': 'bg-warning-100 text-warning-700',
    'Ditolak': 'bg-red-100 text-red-700'
  }
  return classes[status] || 'bg-gray-100 text-gray-700'
}

const getStatusLabel = (status: string) => {
  return status || 'Disubmit'
}

const getFileCount = (activity: any) => {
  return activity.file_path ? 1 : 0
}

const isWeekend = (date: string) => {
  const dateObj = new Date(date)
  const day = dateObj.getDay()
  return day === 0 || day === 6 // 0 = Sunday, 6 = Saturday
}

onMounted(() => {
  const now = new Date()
  selectedMonth.value = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`
  loadLogbooks()
})
</script>