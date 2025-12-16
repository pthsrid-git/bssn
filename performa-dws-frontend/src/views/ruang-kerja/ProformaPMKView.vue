<template>
  <PageDefault
    ref="pageDefault"
    :pageStatus="pageStatus"
    :isDrawerDefault="true"
  >
    <!-- PDF Viewer Fullscreen Overlay -->
    <div 
      v-if="showPdfViewer" 
      @click="closePdfViewer"
      class="fixed inset-0 z-600 flex items-center justify-center p-4 bg-gray-900/80"
    >
      <div 
        @click.stop
        class="w-full max-w-5xl h-[75vh] bg-white rounded-lg shadow-2xl overflow-hidden"
      >
        <ViewerFile :title="pdfTitle" :url="pdfUrl" :wrapper="false" />
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col w-full h-full p-4 overflow-auto gap-y-4 xl:gap-y-6 xl:p-6 bg-primary-50">
      <!-- Main Card Container with Tabs Inside -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Tabs Inside Card -->
        <div class="border-b border-gray-200 px-6">
          <div class="flex overflow-x-auto">
            <button 
              @click="activeTab = 'verifikasitahunan'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'verifikasitahunan' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              VERIFIKASI KINERJA TAHUNAN
            </button>
            <button 
              @click="activeTab = 'verifikasitriwulan'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'verifikasitriwulan' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              VERIFIKASI KINERJA TRIWULAN
            </button>
          </div>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
          <!-- Realisasi Tahunan Tab -->
          <div v-if="activeTab === 'verifikasitahunan'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL REALISASI KINERJA - TAHUNAN</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">TAHUN</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                  </select>
                </div>
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
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE UO</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE Int.O LV1</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE Int.O LV2</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">SASARAN PROGRAM</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">IKSP</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">TAHUN</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">TARGET</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">REALISASI</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">DATA DUKUNG</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">STATUS</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">REALISASI PMK</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">CATATAN</th>
                    <th class="px-4 py-3 border-b border-gray-300">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loading">
                    <td colspan="14" class="px-4 py-8 text-center">
                      <div class="flex items-center justify-center">
                        <svg class="animate-spin h-6 w-6 text-yellow-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-gray-600">Memuat data...</span>
                      </div>
                    </td>
                  </tr>
                  <template v-else-if="paginated.length > 0">
                    <template v-for="(item, index) in paginated" :key="item.id">
                      <tr 
                        v-for="(indikator, i) in (Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja : [item.indikator_kinerja])" 
                        :key="item.id + '-' + i" 
                        :class="[index % 2 === 0 ? 'bg-white' : 'bg-gray-50', 'hover:bg-gray-100']"
                      >
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 text-center align-top border-b border-r border-gray-300">
                          {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                        </td>
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 align-top border-b border-r border-gray-300">
                          {{ item.kode_uo }}
                        </td>
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 align-top border-b border-r border-gray-300">
                          {{ item.kode_int_lv1 }}
                        </td>
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 align-top border-b border-r border-gray-300">
                          {{ item.kode_int_lv2 }}
                        </td>
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 align-top border-b border-r border-gray-300">
                          {{ item.sasaran }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">{{ indikator }}</td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.periode) ? item.periode[i] : item.periode }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.target) ? item.target[i] : item.target }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.realisasi) ? item.realisasi[i] : item.realisasi }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          <a :href="Array.isArray(item.datduk) ? item.datduk[i] : item.datduk" target="_blank" class="text-blue-500 hover:underline">Link</a>
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.status) ? item.status[i] : item.status }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.realisasi_pmk) ? item.realisasi_pmk[i] : item.realisasi_pmk }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.catatan) ? item.catatan[i] : item.catatan }}
                        </td>
                        <td class="px-4 py-3 border-b border-gray-300">
                          <button
                            @click="openEditverifikasitahunanDrawer(item, i)"
                            class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition-colors"
                            title="Edit"
                          >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                          </button>
                        </td>
                      </tr>
                    </template>
                  </template>
                  <tr v-else>
                    <td colspan="14" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="flex items-center justify-between pt-4">
              <div class="flex items-center gap-2">
                <button @click="currentPage = 1" :disabled="currentPage === 1" class="px-3 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">&lt;&lt; First</button>
                <button @click="currentPage--" :disabled="currentPage === 1" class="px-3 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">&lt; Previous</button>
                <template v-for="(page, idx) in visiblePages" :key="idx">
                  <span v-if="page === '...'" class="px-3 py-1.5 text-sm text-gray-500">...</span>
                  <button v-else @click="currentPage = page" :class="['px-3 py-1.5 rounded text-sm transition-colors', currentPage === page ? 'bg-yellow-500 text-white' : 'border border-gray-300 hover:bg-gray-50']">{{ page }}</button>
                </template>
                <button @click="currentPage++" :disabled="currentPage === totalPages" class="px-3 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">Next &gt;</button>
              </div>
            </div>
          </div>

          <!-- Realisasi Triwulan Tab -->
          <div v-if="activeTab === 'verifikasitriwulan'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL REALISASI - TRIWULAN</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">TAHUN</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                  </select>
                </div>
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">TRIWULAN</label>
                  <select v-model="filterTriwulan" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="1">TW I</option>
                    <option value="2">TW II</option>
                    <option value="3">TW III</option>
                    <option value="4">TW IV</option>
                  </select>
                </div>
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
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE UO</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE Int.O LV1</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE Int.O LV2</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">SASARAN PROGRAM</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">IKSP</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">TAHUN</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">TARGET TW {{ triwulanRoman }}</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">REALISASI TW {{ triwulanRoman }}</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">DATDUK</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">STATUS</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">REALISASI TW {{ triwulanRoman }} PMK</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">CATATAN</th>
                    <th class="px-4 py-3 border-b border-gray-300">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loading">
                    <td colspan="14" class="px-4 py-8 text-center">
                      <div class="flex items-center justify-center">
                        <svg class="animate-spin h-6 w-6 text-yellow-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-gray-600">Memuat data...</span>
                      </div>
                    </td>
                  </tr>
                  <template v-else-if="paginated.length > 0">
                    <template v-for="(item, index) in paginated" :key="item.id">
                      <tr 
                        v-for="(indikator, i) in (Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja : [item.indikator_kinerja])" 
                        :key="item.id + '-' + i" 
                        :class="[index % 2 === 0 ? 'bg-white' : 'bg-gray-50', 'hover:bg-gray-100']"
                      >
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 text-center align-top border-b border-r border-gray-300">
                          {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                        </td>
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 align-top border-b border-r border-gray-300">
                          {{ item.kode_uo }}
                        </td>
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 align-top border-b border-r border-gray-300">
                          {{ item.kode_int_lv1 }}
                        </td>
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 align-top border-b border-r border-gray-300">
                          {{ item.kode_int_lv2 }}
                        </td>
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 align-top border-b border-r border-gray-300">
                          {{ item.sasaran }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">{{ indikator }}</td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.periode) ? item.periode[i] : item.periode }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ getTriwulanData(item, i, 'target') }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ getTriwulanData(item, i, 'realisasi') }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          <a :href="getTriwulanData(item, i, 'datduk')" target="_blank" class="text-blue-500 hover:underline">Link</a>
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ getTriwulanData(item, i, 'status') }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ getTriwulanData(item, i, 'realisasi_pmk') }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ getTriwulanData(item, i, 'catatan') }}
                        </td>
                        <td class="px-4 py-3 border-b border-gray-300">
                          <button
                            @click="openEditverifikasitriwulanDrawer(item, i)"
                            class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition-colors"
                            title="Edit"
                          >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                          </button>
                        </td>
                      </tr>
                    </template>
                  </template>
                  <tr v-else>
                    <td colspan="14" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="flex items-center justify-between pt-4">
              <div class="flex items-center gap-2">
                <button @click="currentPage = 1" :disabled="currentPage === 1" class="px-3 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">&lt;&lt; First</button>
                <button @click="currentPage--" :disabled="currentPage === 1" class="px-3 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">&lt; Previous</button>
                <template v-for="(page, idx) in visiblePages" :key="idx">
                  <span v-if="page === '...'" class="px-3 py-1.5 text-sm text-gray-500">...</span>
                  <button v-else @click="currentPage = page" :class="['px-3 py-1.5 rounded text-sm transition-colors', currentPage === page ? 'bg-yellow-500 text-white' : 'border border-gray-300 hover:bg-gray-50']">{{ page }}</button>
                </template>
                <button @click="currentPage++" :disabled="currentPage === totalPages" class="px-3 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">Next &gt;</button>
              </div>
              <button 
                @click="generateAndViewNKO" 
                :disabled="isGenerating"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
              >
                <svg v-if="isGenerating" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ isGenerating ? 'GENERATING...' : 'DOWNLOAD NKO' }}
              </button>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </PageDefault>
</template>

<script setup lang="ts">
import { ref, computed, watch, defineComponent, h } from 'vue'
import PageDefault from '@/components/page/PageDefault.vue'
import ViewerFile from '@/components/viewer/ViewerFile.vue'
import { type PageDefaultExposed, type PageStatus } from '@/models'

const pageStatus = ref<PageStatus>('success')
const pageDefault = ref<PageDefaultExposed | null>(null)

// PDF Viewer states
const showPdfViewer = ref(false)
const pdfTitle = ref('')
const pdfUrl = ref('')

const activeTab = ref('verifikasitahunan')
const loading = ref(false)
const search = ref('')
const filterTahun = ref('2026')
const filterTriwulan = ref('1') // Default TW I
const currentPage = ref(1)
const itemsPerPage = ref(5)

// Ref untuk drawer form
const selectedFile = ref<File | null>(null)
const uploadFileName = ref('')

const verifikasitahunan = ref([
  {
    id: 1,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.1',
    sasaran: 'Meningkatnya kematangan PSE dan penyelenggara persandian sektor dalam menyelenggarakan keamanan siber dsan sandi',
    indikator_kinerja: [
      'Persentase PSE sektor Pemerintahan dan Pembangunan manusia dengan tingkat kematangan keamanan siber minimum level 3 (terdefinisi)',
      'Persentase PSE sektor Perekonomian manusia dengan tingkat kematangan keamanan siber minimum level 3 (terdefinisi)',
    ],
    periode: ['2026', '2026'],
    target: ['100%', '100%'],
    realisasi: ['100%', '80%'],
    datduk: ['https://drive.bssn.go.id/dokuk1', 'https://drive.bssn.go.id/dokuk2'],
    status: ['SESUAI', 'PERLU PERBAIKAN'],
    realisasi_pmk: ['80%', '70%'],
    catatan: ['Kurang', 'Lebih'],
  }
])

const verifikasitriwulan = ref([
  {
    id: 1,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.1',
    sasaran: 'Meningkatnya kematangan PSE dan penyelenggara persandian sektor dalam menyelenggarakan keamanan siber dsan sandi',
    indikator_kinerja: [
      'Persentase PSE sektor Pemerintahan dan Pembangunan manusia dengan tingkat kematangan keamanan siber minimum level 3 (terdefinisi)',
    ],
    periode: ['2026'],
    target1: ['100%'],
    target2: ['100%'],
    target3: ['100%'],
    target4: ['100%'],
    realisasi1: ['50%'],
    realisasi2: ['70%'],
    realisasi3: ['85%'],
    realisasi4: ['100%'],
    datduk1: ['https://drive.bssn.go.id/tw1'],
    datduk2: ['https://drive.bssn.go.id/tw2'],
    datduk3: ['https://drive.bssn.go.id/tw3'],
    datduk4: ['https://drive.bssn.go.id/tw4'],
    status1: ['SESUAI'],
    status2: ['SESUAI'],
    status3: ['PERLU PERBAIKAN'],
    status4: ['SESUAI'],
    realisasi_pmk1: ['45%'],
    realisasi_pmk2: ['65%'],
    realisasi_pmk3: ['80%'],
    realisasi_pmk4: ['95%'],
    catatan1: ['On track'],
    catatan2: ['Good progress'],
    catatan3: ['Need improvement'],
    catatan4: ['Target achieved'],
  }
])

const uploadnko = ref([
  {
    id: 1,
    nama_file: 'NKO SU 2026 TAHUNAN',
    tahun: '2026',
    jenis: 'TAHUNAN',
    file: 'https://ads.agungkdev.com/creatives/test.pdf',
  },
  {
    id: 2,
    nama_file: 'NKO SU 2026 TW I',
    tahun: '2026',
    jenis: 'TW I',
    file: 'https://ads.agungkdev.com/creatives/test.pdf',
  },
  {
    id: 3,
    nama_file: 'NKO SU 2026 TW II',
    tahun: '2026',
    jenis: 'TW II',
    file: 'https://ads.agungkdev.com/creatives/test.pdf',
  }
])

const activeData = computed(() => {
  const dataMap: Record<string, any[]> = {
    'verifikasitahunan': verifikasitahunan.value,
    'verifikasitriwulan': verifikasitriwulan.value,
    'uploadnko': uploadnko.value,
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
  const pages: (number | string)[] = []
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

// Convert triwulan number to roman
const triwulanRoman = computed(() => {
  const romans = ['I', 'II', 'III', 'IV']
  return romans[parseInt(filterTriwulan.value) - 1] || 'I'
})

// Get data berdasarkan triwulan yang dipilih
const getTriwulanData = (item: any, index: number, field: string) => {
  const tw = filterTriwulan.value
  const fieldName = `${field}${tw}`
  
  if (Array.isArray(item[fieldName])) {
    return item[fieldName][index]
  }
  return item[fieldName] || '-'
}

// PDF Viewer functions
const openPdfViewer = (title: string, url: string) => {
  pdfTitle.value = title
  pdfUrl.value = url
  showPdfViewer.value = true
}

const closePdfViewer = () => {
  showPdfViewer.value = false
  pdfTitle.value = ''
  pdfUrl.value = ''
}

// Drawer for Edit Realisasi Tahunan
// Drawer for Edit Realisasi Tahunan
const openEditverifikasitahunanDrawer = (item: any, index: number) => {
  const selectedStatus = ref('PERLU PERBAIKAN')
  const editRealisasiPMK = ref('')
  const editCatatan = ref('')

  const DrawerComponent = defineComponent({
    name: 'EditverifikasitahunanDrawer',
    setup() {
      const handleSave = () => {
        console.log('Saving Realisasi Tahunan:', {
          id: item.id,
          index,
          status: selectedStatus.value,
          realisasiPMK: editRealisasiPMK.value,
          catatan: editCatatan.value
        })
        
        pageDefault.value?.closeDrawerDefault()
      }

      return () => h('div', { class: 'p-6 space-y-6' }, [
        // Title
        h('h2', { class: 'text-lg font-semibold text-gray-900 mb-6' }, 'VERIFIKASI PMK'),

        // IKSP
        h('div', { class: 'grid grid-cols-[140px_1fr] gap-4 items-start' }, [
          h('label', { class: 'text-sm font-medium text-gray-900 pt-2' }, 'IKSP'),
          h('div', { class: 'text-sm text-gray-900' }, Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja[index] : item.indikator_kinerja)
        ]),

        // Target
        h('div', { class: 'grid grid-cols-[140px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'TARGET'),
          h('div', { class: 'text-sm text-gray-900' }, Array.isArray(item.target) ? item.target[index] : item.target)
        ]),

        // Realisasi
        h('div', { class: 'grid grid-cols-[140px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'REALISASI'),
          h('div', { class: 'text-sm text-gray-900' }, Array.isArray(item.realisasi) ? item.realisasi[index] : item.realisasi)
        ]),

        // Data Dukung
        h('div', { class: 'grid grid-cols-[140px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'DATA DUKUNG'),
          h('a', { 
            href: Array.isArray(item.datduk) ? item.datduk[index] : item.datduk,
            target: '_blank',
            class: 'text-sm text-blue-500 hover:underline' 
          }, Array.isArray(item.datduk) ? item.datduk[index] : item.datduk)
        ]),

        // Status
        h('div', { class: 'grid grid-cols-[140px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'STATUS'),
          h('div', { class: 'flex gap-6' }, [
            h('label', { class: 'flex items-center gap-2 cursor-pointer' }, [
              h('input', {
                type: 'radio',
                name: 'status',
                value: 'PERLU PERBAIKAN',
                checked: selectedStatus.value === 'PERLU PERBAIKAN',
                onChange: (e: Event) => {
                  selectedStatus.value = (e.target as HTMLInputElement).value
                },
                class: 'w-4 h-4 text-blue-500'
              }),
              h('span', { class: 'text-sm text-gray-900' }, 'PERLU PERBAIKAN')
            ]),
            h('label', { class: 'flex items-center gap-2 cursor-pointer' }, [
              h('input', {
                type: 'radio',
                name: 'status',
                value: 'SESUAI',
                checked: selectedStatus.value === 'SESUAI',
                onChange: (e: Event) => {
                  selectedStatus.value = (e.target as HTMLInputElement).value
                },
                class: 'w-4 h-4 text-blue-500'
              }),
              h('span', { class: 'text-sm text-gray-900' }, 'SESUAI')
            ])
          ])
        ]),

        // Realisasi PMK
        h('div', { class: 'grid grid-cols-[140px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'REALISASI PMK'),
          h('div', { class: 'flex items-center gap-2' }, [
            h('input', {
              type: 'number',
              value: editRealisasiPMK.value,
              placeholder: '3',
              onInput: (e: Event) => {
                editRealisasiPMK.value = (e.target as HTMLInputElement).value
              },
              class: 'w-24 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500'
            }),
            h('div', { class: 'flex flex-col' }, [
              h('button', {
                class: 'px-2 py-0.5 border border-gray-300 rounded-t bg-gray-100 hover:bg-gray-200 transition-colors',
                onClick: () => {
                  const currentVal = parseFloat(editRealisasiPMK.value) || 0
                  editRealisasiPMK.value = String(currentVal + 1)
                }
              }, [
                h('svg', { class: 'w-3 h-3', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
                  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M5 15l7-7 7 7' })
                ])
              ]),
              h('button', {
                class: 'px-2 py-0.5 border border-t-0 border-gray-300 rounded-b bg-gray-100 hover:bg-gray-200 transition-colors',
                onClick: () => {
                  const currentVal = parseFloat(editRealisasiPMK.value) || 0
                  editRealisasiPMK.value = String(Math.max(0, currentVal - 1))
                }
              }, [
                h('svg', { class: 'w-3 h-3', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
                  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M19 9l-7 7-7-7' })
                ])
              ])
            ])
          ])
        ]),
        
        // Catatan
        h('div', { class: 'grid grid-cols-[140px_1fr] gap-4 items-start' }, [
          h('label', { class: 'text-sm font-medium text-gray-900 pt-2' }, 'CATATAN'),
          h('textarea', {
            value: editCatatan.value,
            placeholder: 'abcd....',
            onInput: (e: Event) => {
              editCatatan.value = (e.target as HTMLTextAreaElement).value
            },
            rows: 5,
            class: 'w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none'
          })
        ]),
        
        // Buttons
        h('div', { class: 'flex justify-end gap-3 pt-6' }, [
          h('button', {
            class: 'px-6 py-2 bg-blue-500 text-white rounded text-sm font-medium hover:bg-blue-600 transition-colors',
            onClick: handleSave
          }, 'SIMPAN'),
          h('button', {
            class: 'px-6 py-2 border border-gray-900 rounded text-sm font-medium text-gray-900 hover:bg-gray-50 transition-colors',
            onClick: () => pageDefault.value?.closeDrawerDefault()
          }, 'KEMBALI')
        ])
      ])
    }
  })

  pageDefault.value?.openDrawerDefault('', DrawerComponent)
}

// Drawer for Edit Realisasi Triwulan
const openEditverifikasitriwulanDrawer = (item: any, index: number) => {
  const selectedStatus = ref('PERLU PERBAIKAN')
  const editRealisasiPMK = ref('')
  const editCatatan = ref('')

  const DrawerComponent = defineComponent({
    name: 'EditverifikasitriwulanDrawer',
    setup() {
      const handleSave = () => {
        console.log('Saving Realisasi Triwulan:', {
          id: item.id,
          index,
          status: selectedStatus.value,
          realisasiPMK: editRealisasiPMK.value,
          catatan: editCatatan.value
        })
        
        pageDefault.value?.closeDrawerDefault()
      }

      return () => h('div', { class: 'p-6 space-y-6' }, [
        // Title
        h('h2', { class: 'text-lg font-semibold text-gray-900 mb-6' }, 'VERIFIKASI PMK'),
        
        // IKSP
        h('div', { class: 'grid grid-cols-[140px_1fr] gap-4 items-start' }, [
          h('label', { class: 'text-sm font-medium text-gray-900 pt-2' }, 'IKSP'),
          h('div', { class: 'text-sm text-gray-900' }, Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja[index] : item.indikator_kinerja)
        ]),
        
        // Target TWI (dinamis sesuai filterTriwulan)
        h('div', { class: 'grid grid-cols-[140px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, `TARGET TW ${triwulanRoman.value}`),
          h('div', { class: 'text-sm text-gray-900' }, getTriwulanData(item, index, 'target'))
        ]),
        
        // Realisasi TWI (dinamis sesuai filterTriwulan)
        h('div', { class: 'grid grid-cols-[140px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, `REALISASI TW ${triwulanRoman.value}`),
          h('div', { class: 'text-sm text-gray-900' }, getTriwulanData(item, index, 'realisasi'))
        ]),
        
        // Data Dukung
        h('div', { class: 'grid grid-cols-[140px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'DATA DUKUNG'),
          h('a', { 
            href: getTriwulanData(item, index, 'datduk'),
            target: '_blank',
            class: 'text-sm text-blue-500 hover:underline' 
          }, getTriwulanData(item, index, 'datduk'))
        ]),
        
        // Status
        h('div', { class: 'grid grid-cols-[140px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'STATUS'),
          h('div', { class: 'flex gap-6' }, [
            h('label', { class: 'flex items-center gap-2 cursor-pointer' }, [
              h('input', {
                type: 'radio',
                name: 'status-tw',
                value: 'PERLU PERBAIKAN',
                checked: selectedStatus.value === 'PERLU PERBAIKAN',
                onChange: (e: Event) => {
                  selectedStatus.value = (e.target as HTMLInputElement).value
                },
                class: 'w-4 h-4 text-blue-500'
              }),
              h('span', { class: 'text-sm text-gray-900' }, 'PERLU PERBAIKAN')
            ]),
            h('label', { class: 'flex items-center gap-2 cursor-pointer' }, [
              h('input', {
                type: 'radio',
                name: 'status-tw',
                value: 'SESUAI',
                checked: selectedStatus.value === 'SESUAI',
                onChange: (e: Event) => {
                  selectedStatus.value = (e.target as HTMLInputElement).value
                },
                class: 'w-4 h-4 text-blue-500'
              }),
              h('span', { class: 'text-sm text-gray-900' }, 'SESUAI')
            ])
          ])
        ]),
        
        // Realisasi PMK
        h('div', { class: 'grid grid-cols-[140px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'REALISASI PMK'),
          h('div', { class: 'flex items-center gap-2' }, [
            h('input', {
              type: 'number',
              value: editRealisasiPMK.value,
              placeholder: '3',
              onInput: (e: Event) => {
                editRealisasiPMK.value = (e.target as HTMLInputElement).value
              },
              class: 'w-24 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500'
            }),
            h('div', { class: 'flex flex-col' }, [
              h('button', {
                type: 'button',
                class: 'px-2 py-0.5 border border-gray-300 rounded-t bg-gray-100 hover:bg-gray-200 transition-colors',
                onClick: () => {
                  const currentVal = parseFloat(editRealisasiPMK.value) || 0
                  editRealisasiPMK.value = String(currentVal + 1)
                }
              }, [
                h('svg', { class: 'w-3 h-3', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
                  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M5 15l7-7 7 7' })
                ])
              ]),
              h('button', {
                type: 'button',
                class: 'px-2 py-0.5 border border-t-0 border-gray-300 rounded-b bg-gray-100 hover:bg-gray-200 transition-colors',
                onClick: () => {
                  const currentVal = parseFloat(editRealisasiPMK.value) || 0
                  editRealisasiPMK.value = String(Math.max(0, currentVal - 1))
                }
              }, [
                h('svg', { class: 'w-3 h-3', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
                  h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M19 9l-7 7-7-7' })
                ])
              ])
            ]),
            h('span', { class: 'text-sm text-gray-900' }, '%')
          ])
        ]),
        
        // Catatan
        h('div', { class: 'grid grid-cols-[140px_1fr] gap-4 items-start' }, [
          h('label', { class: 'text-sm font-medium text-gray-900 pt-2' }, 'CATATAN'),
          h('textarea', {
            value: editCatatan.value,
            placeholder: 'abcd....',
            onInput: (e: Event) => {
              editCatatan.value = (e.target as HTMLTextAreaElement).value
            },
            rows: 5,
            class: 'w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none'
          })
        ]),
        
        // Buttons
        h('div', { class: 'flex justify-end gap-3 pt-6' }, [
          h('button', {
            type: 'button',
            class: 'px-6 py-2 bg-blue-500 text-white rounded text-sm font-medium hover:bg-blue-600 transition-colors',
            onClick: handleSave
          }, 'SIMPAN'),
          h('button', {
            type: 'button',
            class: 'px-6 py-2 border border-gray-900 rounded text-sm font-medium text-gray-900 hover:bg-gray-50 transition-colors',
            onClick: () => pageDefault.value?.closeDrawerDefault()
          }, 'KEMBALI')
        ])
      ])
    }
  })

  pageDefault.value?.openDrawerDefault('', DrawerComponent)
}

const isGenerating = ref(false)

const generateAndViewNKO = async () => {
  isGenerating.value = true
  
  try {
    console.log('Generating NKO for year:', filterTahun.value, 'TW:', filterTriwulan.value)
    
    // Simulate delay
    await new Promise(resolve => setTimeout(resolve, 1500))
    
    // Mock generated PDF URL
    const generatedPdfUrl = 'https://ads.agungkdev.com/creatives/test.pdf'
    const romans = ['I', 'II', 'III', 'IV']
    const pdfTitle = `NKO TW ${romans[parseInt(filterTriwulan.value) - 1]} - ${filterTahun.value}`
    
    // Open PDF viewer
    openPdfViewer(pdfTitle, generatedPdfUrl)
    
  } catch (error) {
    console.error('Error generating NKO:', error)
    alert('Gagal generate NKO')
  } finally {
    isGenerating.value = false
  }
}

// Drawer for Upload NKO
const openUploadNKODrawer = () => {
  const selectedYear = ref('2026')
  const selectedJenis = ref('TAHUNAN')
  selectedFile.value = null
  uploadFileName.value = ''

  const DrawerComponent = defineComponent({
    name: 'UploadNKODrawer',
    setup() {
      const handleFileSelect = (event: Event) => {
        const target = event.target as HTMLInputElement
        if (target.files && target.files[0]) {
          selectedFile.value = target.files[0]
          uploadFileName.value = target.files[0].name
        }
      }

      const handleUpload = () => {
        if (!selectedFile.value) {
          alert('Mohon pilih file terlebih dahulu')
          return
        }
        
        console.log('Uploading NKO:', {
          year: selectedYear.value,
          jenis: selectedJenis.value,
          file: selectedFile.value.name
        })
        
        pageDefault.value?.closeDrawerDefault()
        
        selectedFile.value = null
        uploadFileName.value = ''
      }

      const jenisOptions = ['TAHUNAN', 'TW I', 'TW II', 'TW III', 'TW IV']

      return () => h('div', { class: 'p-6 space-y-6' }, [
        // Title
        h('h2', { class: 'text-lg font-semibold text-gray-900 mb-6' }, 'UPLOAD NKO'),
        
        // Tahun
        h('div', { class: 'grid grid-cols-[120px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'TAHUN'),
          h('select', {
            value: selectedYear.value,
            onChange: (e: Event) => {
              selectedYear.value = (e.target as HTMLSelectElement).value
            },
            class: 'w-full border border-gray-900 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white'
          }, [
            h('option', { value: '2024' }, '2024'),
            h('option', { value: '2025' }, '2025'),
            h('option', { value: '2026' }, '2026'),
            h('option', { value: '2027' }, '2027'),
            h('option', { value: '2028' }, '2028'),
          ])
        ]),
        
        // Jenis
        h('div', { class: 'grid grid-cols-[120px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'JENIS'),
          h('select', {
            value: selectedJenis.value,
            onChange: (e: Event) => {
              selectedJenis.value = (e.target as HTMLSelectElement).value
            },
            class: 'w-full border border-gray-900 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white'
          }, jenisOptions.map(jenis => 
            h('option', { value: jenis, key: jenis }, jenis)
          ))
        ]),
        
        // Pilih File
        h('div', { class: 'grid grid-cols-[120px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'PILIH FILE'),
          h('div', { class: 'flex gap-2' }, [
            h('input', {
              type: 'text',
              value: uploadFileName.value,
              readonly: true,
              placeholder: '',
              class: 'flex-1 border border-gray-900 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white'
            }),
            h('label', {
              class: 'px-4 py-2 border border-gray-900 rounded text-sm font-medium text-gray-900 hover:bg-gray-50 cursor-pointer transition-colors whitespace-nowrap'
            }, [
              'Browse...',
              h('input', {
                type: 'file',
                onChange: handleFileSelect,
                class: 'hidden',
                accept: '.pdf'
              })
            ])
          ])
        ]),
        
        // Buttons
        h('div', { class: 'flex justify-end gap-3 pt-6' }, [
          h('button', {
            class: `px-6 py-2 bg-blue-500 text-white rounded text-sm font-medium hover:bg-blue-600 transition-colors ${
              !selectedFile.value ? 'opacity-50 cursor-not-allowed' : ''
            }`,
            disabled: !selectedFile.value,
            onClick: handleUpload
          }, 'SIMPAN'),
          h('button', {
            class: 'px-6 py-2 border border-gray-900 rounded text-sm font-medium text-gray-900 hover:bg-gray-50 transition-colors',
            onClick: () => pageDefault.value?.closeDrawerDefault()
          }, 'KEMBALI')
        ])
      ])
    }
  })

  pageDefault.value?.openDrawerDefault('', DrawerComponent)
}

watch(activeTab, () => {
  currentPage.value = 1
})
</script>