<template>
  <!-- Main Card Container with Tabs Inside -->
  <div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <!-- Tabs Inside Card -->
    <div class="border-b border-gray-200 px-6">
      <div class="flex gap-8 overflow-x-auto">
        <button 
          @click="activeTab = 'dashboard'"
          :class="[
            'py-4 px-2 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
            activeTab === 'dashboard' 
              ? 'border-warning-500 text-gray-900' 
              : 'border-transparent text-gray-500 hover:text-gray-700'
          ]"
        >
          DASHBOARD
        </button>
        <button 
          @click="activeTab = 'ask'"
          :class="[
            'py-4 px-2 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
            activeTab === 'ask' 
              ? 'border-warning-500 text-gray-900' 
              : 'border-transparent text-gray-500 hover:text-gray-700'
          ]"
        >
          ABK
        </button>
      </div>
    </div>

    <!-- Tab Content Inside Card -->
    <div class="p-4">
      <!-- Dashboard Content -->
      <div v-if="activeTab === 'dashboard'" class="space-y-4">
        <!-- Cards Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
          <!-- Card 1: Pengisian Logbook -->
          <Card>
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Pengisian Logbook</h3>
                <button class="flex items-center gap-2 px-3 py-1.5 border border-gray-300 rounded text-xs hover:bg-gray-50">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  Juli
                </button>
              </div>

              <div class="space-y-2">
                <div class="flex items-end gap-2">
                  <div class="text-5xl font-bold text-gray-900">{{ totalLogbook }}</div>
                  <div class="text-sm text-gray-500 mb-2">({{ logbookPercentage }}%)</div>
                </div>
                <div class="text-xs text-gray-500">1 Juli 2025 - 31 Juli 2025</div>
              </div>
            </div>
          </Card>

          <!-- Card 2: Pengisian Logbook Unit Kerja -->
          <Card>
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Pengisian Logbook Unit Kerja</h3>
                <button 
                  class="flex items-center gap-1 text-xs text-info-600 hover:text-info-700"
                  @click="viewAllUnits"
                >
                  Lihat semua
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>

              <div class="space-y-3">
                <div v-for="unit in unitStats.slice(0, 4)" :key="unit.id" class="space-y-1">
                  <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-700">{{ unit.name }}</span>
                    <span class="font-medium text-gray-900">{{ unit.percentage }}%</span>
                  </div>
                  <div class="w-full bg-gray-200 rounded-full h-2">
                    <div 
                      class="bg-info-500 h-2 rounded-full transition-all duration-300"
                      :style="{ width: unit.percentage + '%' }"
                    ></div>
                  </div>
                </div>
              </div>

              <div class="text-xs text-gray-500 pt-2">1 Juli 2025 - 31 Juli 2025</div>
            </div>
          </Card>

          <!-- Card 3: Kebutuhan Formasi Ideal -->
          <Card>
            <div class="space-y-4">
              <h3 class="text-sm font-semibold text-gray-900">Kebutuhan Formasi Ideal</h3>
              
              <div class="h-64">
                <ChartBar 
                  v-if="formasiChartData"
                  :data="formasiChartData"
                  :height="240"
                  :legend="true"
                  legend-position="bottom"
                  :legend-offset-y="10"
                />
              </div>
            </div>
          </Card>
        </div>

        <!-- Daftar Laporan Unit Kerja Card -->
        <Card>
          <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Laporan Unit Kerja</h2>

            <!-- Table -->
            <TableDefault>
              <template #header>
                <tr>
                  <TableHeader alignment="center" custom-class="w-16">No</TableHeader>
                  <TableHeader>Nama</TableHeader>
                  <TableHeader>NIP</TableHeader>
                  <TableHeader>Pangkat</TableHeader>
                  <TableHeader>Jabatan</TableHeader>
                  <TableHeader>Tim</TableHeader>
                  <TableHeader alignment="center" custom-class="w-32">Aksi</TableHeader>
                </tr>
              </template>
              <template #body>
                <tr v-if="loading">
                  <TableData colspan="7" alignment="center">
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
                  <tr v-for="(pegawai, index) in paginatedPegawai" :key="pegawai.id" class="hover:bg-gray-50">
                    <TableData alignment="center">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</TableData>
                    <TableData>{{ pegawai.nama }}</TableData>
                    <TableData>{{ pegawai.nip }}</TableData>
                    <TableData>{{ pegawai.pangkat }}</TableData>
                    <TableData>{{ pegawai.jabatan }}</TableData>
                    <TableData>{{ pegawai.unit_kerja }}</TableData>
                    <TableData alignment="center">
                        <div class="flex justify-center">
                            <button 
                            class="px-3 py-1 border border-info-300 text-info-600 rounded text-xs hover:bg-info-50 whitespace-nowrap"
                            @click="viewLogbook(pegawai)"
                            >
                            Lihat logbook
                            </button>
                        </div>
                        </TableData>
                  </tr>
                </template>
                <tr v-else>
                  <TableDataNone label="Tidak ada data pegawai" colspan="7" />
                </tr>
              </template>
            </TableDefault>

            <!-- Pagination -->
            <div v-if="pegawaiList.length > 0" class="flex items-center justify-between pt-4">
              <div class="flex items-center gap-2">
                <span class="text-sm text-gray-700">{{ paginationText }}</span>
                <select 
                  v-model="itemsPerPage"
                  class="px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-warning-500"
                >
                  <option :value="5">5</option>
                  <option :value="10">10</option>
                  <option :value="25">25</option>
                  <option :value="50">50</option>
                </select>
              </div>

              <div class="flex items-center gap-2">
                <button 
                  @click="currentPage--"
                  :disabled="currentPage === 1"
                  class="px-3 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                  </svg>
                </button>

                <template v-for="(page, idx) in visiblePages" :key="idx">
                  <span v-if="page === '...'" class="px-3 py-1.5 text-sm text-gray-500">...</span>
                  <button
                    v-else
                    @click="currentPage = page"
                    :class="[
                      'px-3 py-1.5 rounded text-sm transition-colors',
                      currentPage === page 
                        ? 'bg-info-500 text-white' 
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
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </Card>
      </div>

      <!-- ABK Tab Content -->
      <div v-if="activeTab === 'abk'">
        <Card>
          <div class="text-center py-12">
            <p class="text-gray-500">Konten ABK akan ditampilkan di sini</p>
          </div>
        </Card>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import Card from '@/components/card/CardDefault.vue'
import TableDefault from '@/components/table/TableDefault.vue'
import TableHeader from '@/components/table/TableHeader.vue'
import TableData from '@/components/table/TableData.vue'
import TableDataNone from '@/components/table/TableDataNone.vue'
import ChartBar from '@/components/chart/ChartBar.vue'
import { LogbookAtasanService, type Pegawai } from '@/services/logbookAtasanService'
import type { BarChartData } from '@/models'

const activeTab = ref('dashboard')
const loading = ref(false)
const pegawaiList = ref<Pegawai[]>([])
const currentPage = ref(1)
const itemsPerPage = ref(5)
const totalLogbook = ref(1000)
const logbookPercentage = ref(90)

// Unit stats data
const unitStats = ref([
  { id: 1, name: 'SU1', percentage: 92 },
  { id: 2, name: 'D1', percentage: 95 },
  { id: 3, name: 'D2', percentage: 100 },
  { id: 4, name: 'D3', percentage: 90 },
  { id: 5, name: 'D4', percentage: 92 },
  { id: 6, name: 'P1', percentage: 90 },
  { id: 7, name: 'P2', percentage: 95 },
  { id: 8, name: 'P3', percentage: 95 }
])

// Chart data for Kebutuhan Formasi Ideal
const formasiChartData = computed<BarChartData>(() => ({
  categories: ['Pelaksana', 'Fungsional', 'Manjerial'],
  series: [
    {
      name: 'Jumlah',
      data: [45, 48, 40]
    },
    {
      name: 'Kebutuhan Ideal',
      data: [50, 50, 50]
    }
  ],
  colors: ['#3B82F6', '#10B981']
}))

// Pagination computed properties
const totalPages = computed(() => Math.ceil(pegawaiList.value.length / itemsPerPage.value))

const paginatedPegawai = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return pegawaiList.value.slice(start, end)
})

const paginationText = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value + 1
  const end = Math.min(currentPage.value * itemsPerPage.value, pegawaiList.value.length)
  return `${start} s/d ${end} dari ${pegawaiList.value.length} hasil`
})

const visiblePages = computed(() => {
  const pages: any[] = []
  const maxVisible = 5
  let start = Math.max(1, currentPage.value - 2)
  let end = Math.min(totalPages.value, start + maxVisible - 1)
  
  if (end - start < maxVisible - 1) {
    start = Math.max(1, end - maxVisible + 1)
  }
  
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  
  if (end < totalPages.value - 1) {
    pages.push('...')
  }
  if (end < totalPages.value) {
    pages.push(totalPages.value)
  }
  
  return pages
})

onMounted(() => {
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
    console.error('Error loading pegawai list:', error)
    pegawaiList.value = []
  } finally {
    loading.value = false
  }
}

const viewLogbook = (pegawai: Pegawai) => {
  console.log('View logbook for:', pegawai)
  // Navigate to logbook detail page or open modal
}

const viewAllUnits = () => {
  console.log('View all units')
  // Navigate to units overview page
}
</script>