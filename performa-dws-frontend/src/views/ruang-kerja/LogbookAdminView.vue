<template>
  <!-- Main Card Container with Tabs Inside -->
  <div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <!-- Tabs Inside Card -->
    <div class="border-b border-gray-200 px-6">
      <div class="flex overflow-x-auto">
        <button 
          @click="activeTab = 'finaloutcome'"
          :class="[
            'py-4 px-2 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
            activeTab === 'finaloutcome' 
              ? 'border-warning-500 text-gray-900' 
              : 'border-transparent text-gray-500 hover:text-gray-700'
          ]"
        >
          FINAL OUTCOME
        </button>
        <button 
          @click="activeTab = 'intermediateoutcomelv1'"
          :class="[
            'py-4 px-2 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
            activeTab === 'intermediateoutcomelv1' 
              ? 'border-warning-500 text-gray-900' 
              : 'border-transparent text-gray-500 hover:text-gray-700'
          ]"
        >
          INTERMEDIATE OUTCOME LV1
        </button>
        <button 
          @click="activeTab = 'intermediateoutcomelv2'"
          :class="[
            'py-4 px-2 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
            activeTab === 'intermediateoutcomelv2' 
              ? 'border-warning-500 text-gray-900' 
              : 'border-transparent text-gray-500 hover:text-gray-700'
          ]"
        >
          INTERMEDIATE OUTCOME LV2
        </button>
        <button 
          @click="activeTab = 'intermediateoutcomelv3'"
          :class="[
            'py-4 px-2 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
            activeTab === 'intermediateoutcomelv3' 
              ? 'border-warning-500 text-gray-900' 
              : 'border-transparent text-gray-500 hover:text-gray-700'
          ]"
        >
          INTERMEDIATE OUTCOME LV3
        </button>
        <button 
          @click="activeTab = 'output'"
          :class="[
            'py-4 px-2 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
            activeTab === 'output' 
              ? 'border-warning-500 text-gray-900' 
              : 'border-transparent text-gray-500 hover:text-gray-700'
          ]"
        >
          OUTPUT
        </button>
        <button 
          @click="activeTab = 'penerjemahintermediateoutcomelv2'"
          :class="[
            'py-4 px-2 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
            activeTab === 'penerjemahintermediateoutcomelv2' 
              ? 'border-warning-500 text-gray-900' 
              : 'border-transparent text-gray-500 hover:text-gray-700'
          ]"
        >
          PENERJEMAH INTERMEDIATE OUTCOME LV2
        </button>
        <button 
          @click="activeTab = 'penerjemahintermediateoutcomelv3'"
          :class="[
            'py-4 px-2 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
            activeTab === 'penerjemahintermediateoutcomelv3' 
              ? 'border-warning-500 text-gray-900' 
              : 'border-transparent text-gray-500 hover:text-gray-700'
          ]"
        >
          PENERJEMAH INTERMEDIATE OUTCOME LV3
        </button>
      </div>
    </div>

    <!-- Tab Content Final Outcome -->
    <div class="p-4">
      <!-- Dashboard Content -->
      <div v-if="activeTab === 'finaloutcome'" class="space-y-4">
        <!-- Daftar Laporan Unit Kerja Card -->
        <Card>
          <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">TABEL FINAL OUTCOME</h2>

            <!-- Table -->
            <TableDefault>
              <template #header>
                <tr>
                  <TableHeader alignment="center" custom-class="w-16">No</TableHeader>
                  <TableHeader>KODE UO</TableHeader>
                  <TableHeader>SASARAN</TableHeader>
                  <TableHeader>INDIKATOR KINERJA</TableHeader>
                  <TableHeader>PERIODE</TableHeader>
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
                <template v-else-if="finaloutcome.length > 0">
                  <tr v-for="(item, index) in paginated" :key="item.id" class="hover:bg-gray-50">
                    <TableData alignment="center">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</TableData>
                    <TableData>{{ item.kode_uo }}</TableData>
                    <TableData>{{ item.sasaran }}</TableData>
                    <TableData>{{ item.indikator_kinerja }}</TableData>
                    <TableData>{{ item.periode }}</TableData>
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
      <!-- Intermediate Outcome LV1 Tab Content -->
      <div v-if="activeTab === 'intermediateoutcomelv1'" class="space-y-4">
        <!-- Daftar Laporan Unit Kerja Card -->
        <Card>
          <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">TABEL INTERMEDIATE OUTCOME LV1</h2>

            <!-- Table -->
            <TableDefault>
              <template #header>
                <tr>
                  <TableHeader alignment="center" custom-class="w-16">No</TableHeader>
                  <TableHeader>KODE UO</TableHeader>
                  <TableHeader>KODE Int LV1</TableHeader>
                  <TableHeader>SASARAN</TableHeader>
                  <TableHeader>INDIKATOR KINERJA</TableHeader>
                  <TableHeader>PERIODE</TableHeader>
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
                <template v-else-if="intermediateOutcomeLv1List.length > 0">
                  <template v-for="(item, index) in paginated" :key="item.id">
                    <tr v-for="(indikator, i) in (Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja : [item.indikator_kinerja])" :key="item.id + '-' + i" class="hover:bg-gray-50">
                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" alignment="center" custom-class="align-top">
                        {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                      </TableData><TableData v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" alignment="left" custom-class="align-top">
                        {{ item.kode_uo }}
                      </TableData><TableData v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" alignment="left" custom-class="align-top">
                        {{ item.kode_int_lv1 }}
                      </TableData>
                       <TableData v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" alignment="left" custom-class="align-top">
                        {{ item.sasaran }}
                      </TableData>
                      <TableData>
                        {{ indikator }}
                      </TableData>
                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" alignment="left" custom-class="align-top">
                        {{ item.periode }}
                      </TableData>
                    </tr>
                  </template>
                </template>
                <tr v-else>
                  <TableDataNone label="Tidak ada data" colspan="7" />
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
import { ref, computed, onMounted, watch } from 'vue'
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
// const currentPage = ref(1)
// const itemsPerPage = ref(5)
const totalLogbook = ref(1000)
const logbookPercentage = ref(90)


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

// Pagination computed properties
// const totalPages = computed(() => Math.ceil(pegawaiList.value.length / itemsPerPage.value))

// const paginated = computed(() => {
//   const start = (currentPage.value - 1) * itemsPerPage.value
//   const end = start + itemsPerPage.value
//   return pegawaiList.value.slice(start, end)
// })

// =======================================================================
// Data Dummy for All Tabs (FIXED)
// =======================================================================

const itemsPerPage = ref(5)
const currentPage = ref(1)

const finaloutcome = ref([
  {
    id: 1,
    kode_uo: 'UO 1',
    sasaran: 'Meningkatkan ketahanan siber nasional',
    indikator_kinerja: 'Persentase penurunan insiden siber kritis',
    periode: '2025 - 2029'
  },
  {
    id: 2,
    kode_uo: 'UO 2',
    sasaran: 'Peningkatan kapabilitas SDM keamanan siber',
    indikator_kinerja: 'Jumlah SDM tersertifikasi keamanan siber',
    periode: '2025 - 2029'
  }
])

const intermediateOutcomeLv1List = ref([
  {
    id: 1,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    sasaran: 'Meningkatkan ketahanan siber nasional',
    indikator_kinerja: [
      'Indeks keamanan dan ketahanan siber',
      'Indeks keamanan informasi'
    ],
    periode: '2025 - 2029'
  },
  {
    id: 2,
    kode_uo: 'UO 2',
    kode_int_lv1: 'Int.0.2',
    sasaran: 'Peningkatan kapabilitas SDM keamanan siber',
    indikator_kinerja: 'Jumlah SDM tersertifikasi keamanan siber',
    periode: '2025 - 2029'
  }
])

const intermediateOutcomeLv2List = ref([])

const activeList = computed(() => {
  switch (activeTab.value) {
    case 'finaloutcome':
      return finaloutcome.value
    case 'intermediateoutcomelv1':
      return intermediateOutcomeLv1List.value
    case 'intermediateoutcomelv2':
      return intermediateOutcomeLv2List.value
    default:
      return []
  }
})

const paginated = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  return activeList.value.slice(start, start + itemsPerPage.value)
})

const totalPages = computed(() => {
  return Math.ceil(activeList.value.length / itemsPerPage.value)
})

watch(activeTab, () => {
  currentPage.value = 1
})
</script>