<template>
  <PageDefault
    ref="pageDefault"
    :pageStatus="pageStatus"
  >
    <!-- PDF Viewer Modal Overlay (Click outside to close) -->
    <div 
      v-if="showPdfViewer" 
      @click="closePdfViewer"
      class="fixed inset-0 z-600 flex items-center justify-center p-4 bg-gray-900/80"
    >
      <div 
        @click.stop
        class="w-full max-w-5xl h-[70vh] bg-white rounded-lg shadow-2xl overflow-hidden"
      >
        <ViewerFile :title="pdfTitle" :url="pdfUrl" :wrapper="false" />
      </div>
    </div>

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
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              {{ uploadLabel }}
            </label>
            <div class="flex gap-3">
              <input
                type="text"
                v-model="uploadFileName"
                readonly
                placeholder="No file chosen"
                class="flex-1 border border-gray-300 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500"
              />
              <label
                class="px-6 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 cursor-pointer transition-colors"
              >
                Browse
                <input
                  type="file"
                  @change="handleFileSelect"
                  class="hidden"
                  accept=".xlsx,.xls,.csv"
                />
              </label>
            </div>
          </div>

          <div class="flex justify-end gap-3 pt-4 border-t">
            <button
              @click="closeUploadModal"
              class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              @click="handleUpload"
              :disabled="!selectedFile"
              class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              SUBMIT
            </button>
          </div>
        </div>
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
              @click="activeTab = 'intermediateoutcomelv1tahunan'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'intermediateoutcomelv1tahunan' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              INTERMEDIATE OUTCOME LV 1 TAHUNAN
            </button>
            <button 
              @click="activeTab = 'intermediateoutcomelv1triwulanan'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'intermediateoutcomelv1triwulanan' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              INTERMEDIATE OUTCOME LV 1 TRIWULAN
            </button>
            <button 
              @click="activeTab = 'intermediateoutcomelv2tahunan'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'intermediateoutcomelv2tahunan' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              INTERMEDIATE OUTCOME LV 2 TAHUNAN
            </button>
            <button 
              @click="activeTab = 'intermediateoutcomelv2triwulanan'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'intermediateoutcomelv2triwulanan' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              INTERMEDIATE OUTCOME LV 2 TRIWULAN
            </button>
            <button 
              @click="activeTab = 'intermediateoutcomelv3tahunan'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'intermediateoutcomelv3tahunan' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              INTERMEDIATE OUTCOME LV 3 TAHUNAN
            </button>
            <button 
              @click="activeTab = 'intermediateoutcomelv3triwulanan'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'intermediateoutcomelv3triwulanan' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              INTERMEDIATE OUTCOME LV 3 TRIWULAN
            </button>
            <button 
              @click="activeTab = 'perkinnko'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'perkinnko' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              PERKIN & NKO
            </button>
          </div>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
          <!-- Intermediate Outcome LV1 Tahunan Tab -->
          <div v-if="activeTab === 'intermediateoutcomelv1tahunan'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL INTERMEDIATE OUTCOME LV 1 - TAHUNAN</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">UNIT KERJA</label>
                  <select v-model="filterUnitKerja" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="SU">SU</option>
                    <option value="DEPUTI I">DEPUTI I</option>
                    <option value="DEPUTI II">DEPUTI II</option>
                    <option value="DEPUTI IV">DEPUTI IV</option>
                    <option value="P1">P1</option>
                    <option value="P2">P2</option>
                    <option value="P3">P3</option>
                  </select>
                </div>
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">PERIODE</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
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
                    <th class="px-4 py-3 border-b border-r border-gray-300">CRITICAL SUCCESS FACTOR</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">IKSP</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">TAHUN</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">PENGAMPU</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">TARGET</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">REALISASI</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">CAPAIAN</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">NORMALISASI</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">KOREKSI NORMALISASI</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">CAPAIAN AKHIR</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loading">
                    <td colspan="10" class="px-4 py-8 text-center">
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
                    <td colspan="14" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
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
                          {{ Array.isArray(item.tahun) ? item.tahun[i] : item.tahun }}
                        </td>
                        <td class="px-4 py-3 border-b border-gray-300">
                          {{ Array.isArray(item.pengampu) ? item.pengampu[i] : item.pengampu }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.target) ? item.target[i] : item.target }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.realisasi) ? item.realisasi[i] : item.realisasi }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.capaian) ? item.capaian[i] : item.capaian }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.normalisasi) ? item.normalisasi[i] : item.normalisasi }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.koreksi_normalisasi) ? item.koreksi_normalisasi[i] : item.koreksi_normalisasi }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.capaian_akhir) ? item.capaian_akhir[i] : item.capaian_akhir }}
                        </td>                        
                      </tr>
                    </template>
                  </template>
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
                @click="downloadData('intermediateoutcomelv2tahunan')"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors"
              >
                DOWNLOAD
              </button>
            </div>
          </div>

          <!-- Intermediate Outcome LV1 Triwilan Tab -->
          <div v-if="activeTab === 'intermediateoutcomelv1triwulan'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL INTERMEDIATE OUTCOME LV 1 - TRIWULAN</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">UNIT KERJA</label>
                  <select v-model="filterUnitKerja" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="SU">SU</option>
                    <option value="DEPUTI I">DEPUTI I</option>
                    <option value="DEPUTI II">DEPUTI II</option>
                    <option value="DEPUTI IV">DEPUTI IV</option>
                    <option value="P1">P1</option>
                    <option value="P2">P2</option>
                    <option value="P3">P3</option>
                  </select>
                </div>
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">PERIODE</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                  </select>
                </div>
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">TRIWULAN</label>
                  <select v-model="filterTriwulan" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="I">I</option>
                    <option value="II">II</option>
                    <option value="III">III</option>
                    <option value="IV">IV</option>
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
                    <th class="px-4 py-3 border-b border-r border-gray-300">CRITICAL SUCCESS FACTOR</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">IKSP</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">TAHUN</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">PENGAMPUH</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">TARGET TW1</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">REALISASI TW1</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">CAPAIAN TW1</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">NORMALISASI TW1</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">KOREKSI NORMALISASI TW1</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">CAPAIAN AKHIR TW1</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loading">
                    <td colspan="10" class="px-4 py-8 text-center">
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
                    <td colspan="10" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
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
                          {{ Array.isArray(item.tahun) ? item.tahun[i] : item.tahun }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.target) ? item.target[i] : item.target }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.realisasi) ? item.realisasi[i] : item.realisasi }}
                        </td>
                        <td class="px-4 py-3 border-b border-gray-300">
                          {{ Array.isArray(item.pengampu) ? item.pengampu[i] : item.pengampu }}
                        </td>
                      </tr>
                    </template>
                  </template>
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
                @click="downloadData('intermediateoutcomelv2tahunan')"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors"
              >
                DOWNLOAD
              </button>
            </div>
          </div>
          
          <!-- Intermediate Outcome LV2 Tahunan Tab -->
          <div v-if="activeTab === 'intermediateoutcomelv2tahunan'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL INTERMEDIATE OUTCOME LV 2 - TAHUNAN</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">UNIT KERJA</label>
                  <select v-model="filterUnitKerja" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="SU">SU</option>
                    <option value="DEPUTI I">DEPUTI I</option>
                    <option value="DEPUTI II">DEPUTI II</option>
                    <option value="DEPUTI IV">DEPUTI IV</option>
                    <option value="P1">P1</option>
                    <option value="P2">P2</option>
                    <option value="P3">P3</option>
                  </select>
                </div>
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">PERIODE</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
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
                    <th class="px-4 py-3 border-b border-gray-300">PENGAMPU</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loading">
                    <td colspan="10" class="px-4 py-8 text-center">
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
                    <td colspan="10" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
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
                          {{ Array.isArray(item.tahun) ? item.tahun[i] : item.tahun }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.target) ? item.target[i] : item.target }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.realisasi) ? item.realisasi[i] : item.realisasi }}
                        </td>
                        <td class="px-4 py-3 border-b border-gray-300">
                          {{ Array.isArray(item.pengampu) ? item.pengampu[i] : item.pengampu }}
                        </td>
                      </tr>
                    </template>
                  </template>
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
                @click="downloadData('intermediateoutcomelv2tahunan')"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors"
              >
                DOWNLOAD
              </button>
            </div>
          </div>

          <!-- IIntermediate Outcome LV2 Triwulan Tab -->
          <div v-if="activeTab === 'intermediateoutcomelv2triwulanan'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL INTERMEDIATE OUTCOME LV 2 - TAHUNAN</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">UNIT KERJA</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="SU">SU</option>
                    <option value="DEPUTI I">DEPUTI I</option>
                    <option value="DEPUTI II">DEPUTI II</option>
                    <option value="DEPUTI IV">DEPUTI IV</option>
                    <option value="P1">P1</option>
                    <option value="P2">P2</option>
                    <option value="P3">P3</option>
                  </select>
                </div>
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">PERIODE</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
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
                    <th class="px-4 py-3 border-b border-r border-gray-300">TARGET TW1</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">REALISASI TW1</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">TARGET TW2</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">REALISASI TW2</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">TARGET TW3</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">REALISASI TW3</th>
                    <th class="px-4 py-3 border-b border-gray-300">PENGAMPU</th>
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
                          {{ Array.isArray(item.tahun) ? item.tahun[i] : item.tahun }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.target) ? item.target[i] : item.target }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.realisasi) ? item.realisasi[i] : item.realisasi }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.target2) ? item.target2[i] : item.target2 }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.realisasi2) ? item.realisasi2[i] : item.realisasi2 }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.target3) ? item.target3[i] : item.target3 }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.realisasi3) ? item.realisasi3[i] : item.realisasi3 }}
                        </td>
                        <td class="px-4 py-3 border-b border-gray-300">
                          {{ Array.isArray(item.pengampu) ? item.pengampu[i] : item.pengampu }}
                        </td>
                      </tr>
                    </template>
                  </template>
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
                @click="openUploadModal('intermediateoutcomelv2triwulanan')"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors"
              >
                DOWNLOAD
              </button>
            </div>
          </div>
          
          <!-- Intermediate Outcome LV3 Tahunan Tab -->
          <div v-if="activeTab === 'intermediateoutcomelv3tahunan'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL INTERMEDIATE OUTCOME LV 3 - TAHUNAN</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">UNIT KERJA</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="SU">SU</option>
                    <option value="DEPUTI I">DEPUTI I</option>
                    <option value="DEPUTI II">DEPUTI II</option>
                    <option value="DEPUTI IV">DEPUTI IV</option>
                    <option value="P1">P1</option>
                    <option value="P2">P2</option>
                    <option value="P3">P3</option>
                  </select>
                </div>
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">PERIODE</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
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
                    <th class="px-4 py-3 border-b border-gray-300">PENGAMPU</th>
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
                          {{ Array.isArray(item.tahun) ? item.tahun[i] : item.tahun }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.target) ? item.target[i] : item.target }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.realisasi) ? item.realisasi[i] : item.realisasi }}
                        </td>
                        <td class="px-4 py-3 border-b border-gray-300">
                          {{ Array.isArray(item.pengampu) ? item.pengampu[i] : item.pengampu }}
                        </td>
                      </tr>
                    </template>
                  </template>
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
                @click="openUploadModal('intermediateoutcomelv2tahunan')"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors"
              >
                DOWNLOAD
              </button>
            </div>
          </div>

          <!-- IIntermediate Outcome LV3 Triwulan Tab -->
          <div v-if="activeTab === 'intermediateoutcomelv3triwulanan'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL INTERMEDIATE OUTCOME LV 2 - TAHUNAN</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">UNIT KERJA</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="SU">SU</option>
                    <option value="DEPUTI I">DEPUTI I</option>
                    <option value="DEPUTI II">DEPUTI II</option>
                    <option value="DEPUTI IV">DEPUTI IV</option>
                    <option value="P1">P1</option>
                    <option value="P2">P2</option>
                    <option value="P3">P3</option>
                  </select>
                </div>
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">PERIODE</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
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
                    <th class="px-4 py-3 border-b border-r border-gray-300">TARGET TW1</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">REALISASI TW1</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">TARGET TW2</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">REALISASI TW2</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">TARGET TW3</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">REALISASI TW3</th>
                    <th class="px-4 py-3 border-b border-gray-300">PENGAMPU</th>
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
                          {{ Array.isArray(item.tahun) ? item.tahun[i] : item.tahun }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.target) ? item.target[i] : item.target }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.realisasi) ? item.realisasi[i] : item.realisasi }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.target2) ? item.target2[i] : item.target2 }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.realisasi2) ? item.realisasi2[i] : item.realisasi2 }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.target3) ? item.target3[i] : item.target3 }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.realisasi3) ? item.realisasi3[i] : item.realisasi3 }}
                        </td>
                        <td class="px-4 py-3 border-b border-gray-300">
                          {{ Array.isArray(item.pengampu) ? item.pengampu[i] : item.pengampu }}
                        </td>
                      </tr>
                    </template>
                  </template>
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
                @click="openUploadModal('intermediateoutcomelv2triwulanan')"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors"
              >
                DOWNLOAD
              </button>
            </div>
          </div>

          <!-- PERKIN & NKO Tab -->
          <div v-if="activeTab === 'perkinnko'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL PERKIN & NKO</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">UNIT KERJA</label>
                  <select v-model="filterUnitKerja" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="SU">SU</option>
                    <option value="DEPUTI I">DEPUTI I</option>
                    <option value="DEPUTI II">DEPUTI II</option>
                    <option value="DEPUTI IV">DEPUTI IV</option>
                    <option value="P1">P1</option>
                    <option value="P2">P2</option>
                    <option value="P3">P3</option>
                  </select>
                </div>
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">PERIODE</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
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
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE KERJA</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">TAHUN</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">PERKIN</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">NKO TW1</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">NKO TW2</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">NKO TW3</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">NKO TW4</th>
                    <th class="px-4 py-3 border-b border-gray-300">NKO TAHUNAN</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loading">
                    <td colspan="9" class="px-4 py-8 text-center">
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
                    <td colspan="9" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
                  </tr>
                  <tr v-else v-for="(item, index) in paginated" :key="item.id" :class="[index % 2 === 0 ? 'bg-white' : 'bg-gray-50', 'hover:bg-gray-100']">
                    <td class="px-4 py-3 text-center border-b border-r border-gray-300">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                    <td class="px-4 py-3 border-b border-r border-gray-300">{{ item.kode_kerja }}</td>
                    <td class="px-4 py-3 border-b border-r border-gray-300">{{ item.tahun }}</td>
                    <td class="px-4 py-3 border-b border-r border-gray-300">
                      <button
                        @click="openPdfViewer('PERKIN - ' + item.kode_kerja + ' ' + item.tahun, item.perkin)"
                        class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition-colors"
                        title="Lihat PERKIN"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                      </button>
                    </td>
                    <td class="px-4 py-3 border-b border-r border-gray-300">
                      <button
                        @click="openPdfViewer('NKO TW1 - ' + item.kode_kerja + ' ' + item.tahun, item.nko_tw1)"
                        class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition-colors"
                        title="Lihat NKO TW1"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                      </button>
                    </td>
                    <td class="px-4 py-3 border-b border-r border-gray-300">
                      <button
                        @click="openPdfViewer('NKO TW2 - ' + item.kode_kerja + ' ' + item.tahun, item.nko_tw2)"
                        class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition-colors"
                        title="Lihat NKO TW2"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                      </button>
                    </td>
                    <td class="px-4 py-3 border-b border-r border-gray-300">
                      <button
                        @click="openPdfViewer('NKO TW3 - ' + item.kode_kerja + ' ' + item.tahun, item.nko_tw3)"
                        class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition-colors"
                        title="Lihat NKO TW3"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                      </button>
                    </td>
                    <td class="px-4 py-3 border-b border-r border-gray-300">
                      <button
                        @click="openPdfViewer('NKO TW4 - ' + item.kode_kerja + ' ' + item.tahun, item.nko_tw4)"
                        class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition-colors"
                        title="Lihat NKO TW4"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                      </button>
                    </td>
                    <td class="px-4 py-3 border-b border-gray-300">
                      <button
                        @click="openPdfViewer('NKO TAHUNAN - ' + item.kode_kerja + ' ' + item.tahun, item.nko_tahunan)"
                        class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition-colors"
                        title="Lihat NKO TAHUNAN"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
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
            </div>
          </div>

          <!-- Add other tabs similarly -->
        </div>
      </div>
    </div>
  </PageDefault>
</template>

<script setup lang="ts">
// Script tetap sama seperti sebelumnya
import { ref, computed, watch } from 'vue'
import PageDefault from '@/components/page/PageDefault.vue'
import ViewerFile from '@/components/viewer/ViewerFile.vue'
import { type PageDefaultExposed, type PageStatus } from '@/models'

const pageStatus = ref<PageStatus>('success')
const pageDefault = ref<PageDefaultExposed | null>(null)

// PDF Viewer states
const showPdfViewer = ref(false)
const pdfTitle = ref('')
const pdfUrl = ref('')

const showModal = ref(false)
const selectedFile = ref<File | null>(null)
const uploadFileName = ref('')
const currentUploadTab = ref('')

const activeTab = ref('intermediateoutcomelv1tahunan')
const loading = ref(false)
const search = ref('')
const filterTahun = ref('2016')
const filterUnitKerja = ref('SU')
const currentPage = ref(1)
const itemsPerPage = ref(5)

const intermediateoutcomelv2tahunan = ref([
  {
    id: 1,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.1',
    sasaran: 'Meningkatnya kematangan keamanan siber dan sandi PSE dan penyelenggara persandian',
    indikator_kinerja: [
      'Indeks keamanan dan ketahanan siber',
      'Indeks keamanan informasi'
    ],
    tahun: '2026',
    target: '100%',
    realisasi: '100%',
    target2: '100%',
    realisasi2: '100%',
    target3: '100%',
    realisasi3: '100%',
    pengampu: 'D3'
  },
  {
    id: 2,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.2',
    sasaran: 'Meningkatnya manfaat kebijakan dan kerja sama keamanan siber dan sandi dalam penanganan gangguan keamanan siber',
    indikator_kinerja: [
      'Indeks keamanan dan ketahanan siber',
      'Indeks keamanan informasi'
    ],
    tahun: '2026',
    target: '100%',
    realisasi: '100%',
    target2: '100%',
    realisasi2: '100%',
    target3: '100%',
    realisasi3: '100%',
    pengampu: 'D3'
  },
  {
    id: 3,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.3',
    sasaran: 'Meningkatnya kesadaran masyarakat terhadap keamanan siber dan sandi',
    indikator_kinerja: [
      'Indeks keamanan dan ketahanan siber',
      'Indeks keamanan informasi'
    ],
    tahun: '2026',
    target: '100%',
    realisasi: '100%',
    target2: '100%',
    realisasi2: '100%',
    target3: '100%',
    realisasi3: '100%',
    pengampu: 'D3'
  },
  {
    id: 4,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.4',
    sasaran: 'Meningkatnya kesiapsiagaan dan ketahanan siber nasional dalam mengantisipasi serangan siber dan gangguan keamanan informasi',
    indikator_kinerja: [
      'Indeks keamanan dan ketahanan siber',
      'Indeks keamanan informasi'
    ],
    tahun: '2026',
    target: '100%',
    realisasi: '100%',
    target2: '100%',
    realisasi2: '100%',
    target3: '100%',
    realisasi3: '100%',
    pengampu: 'D3'
  },
  {
    id: 5,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.5',
    sasaran: 'Terwujudnya penegakan hukum keamanan siber dan keamanan informasi',
    indikator_kinerja: [
      'Indeks keamanan dan ketahanan siber',
      'Indeks keamanan informasi'
    ],
    tahun: '2026',
    target: '100%',
    realisasi: '100%',
    target2: '100%',
    realisasi2: '100%',
    target3: '100%',
    realisasi3: '100%',
    pengampu: 'D3'
  },
])

const intermediateoutcomelv3tahunan = ref([
  {
    id: 1,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.1',
    kode_int_lv3: 'Int.0.1.1.1',
    sasaran: 'Meningkatnya kematangan penyelenggaraan keamanan siber dan sandi',
    indikator_kinerja: [
      'Nilai kematangan keamanan siber PSE',
      'Nilai kematangan penyelenggara persandian'
    ],
    tahun: '2026',
    target: '100%',
    realisasi: '100%',
    target2: '100%',
    realisasi2: '100%',
    target3: '100%',
    realisasi3: '100%',
    pengampu: 'D3'
  },
  {
    id: 2,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.2',
    kode_int_lv2: 'Int.0.2.1',
    kode_int_lv3: 'Int.0.1.2.1',
    sasaran: 'Meningkatnya implementasi kebijakan siber dan sandi nasional',
    indikator_kinerja: [
      'Persentase kebijakan yang diterapkan terhadap total kebijakan yang ditetapkan'
    ],
    tahun: '2026',
    target: '100%',
    realisasi: '100%',
    target2: '100%',
    realisasi2: '100%',
    target3: '100%',
    realisasi3: '100%',
    pengampu: 'D3'
  },
  {
    id: 3,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.2',
    kode_int_lv2: 'Int.0.2.2',
    kode_int_lv3: 'Int.0.1.1.1',
    sasaran: 'Meningkatnya kualitas kerjasama keamanan siber dan sandi',
    indikator_kinerja: [
      'Persentase kerjasama keamanan siber dan sandi yang ditindaklanjuti',
      'Persentase peran aktif BSSN dalam forum keamanan siber dan sandi'
    ],
    tahun: '2026',
    target: '100%',
    realisasi: '100%',
    target2: '100%',
    realisasi2: '100%',
    target3: '100%',
    realisasi3: '100%',
    pengampu: 'D3'
  },
])

const perkinnko = ref([
  {
    id: 1,
    kode_kerja: 'SU',
    tahun: '2026',
    perkin: 'https://ads.agungkdev.com/creatives/test.pdf',
    nko_tw1: 'https://ads.agungkdev.com/creatives/test.pdf',
    nko_tw2: 'https://ads.agungkdev.com/creatives/test.pdf',
    nko_tw3: 'https://ads.agungkdev.com/creatives/test.pdf',
    nko_tw4: 'https://ads.agungkdev.com/creatives/test.pdf',
    nko_tahunan: 'https://ads.agungkdev.com/creatives/test.pdf'
  },
  {
    id: 2,
    kode_kerja: 'DEPUTI I',
    tahun: '2026',
    perkin: 'https://ads.agungkdev.com/creatives/test.pdf',
    nko_tw1: 'https://ads.agungkdev.com/creatives/test.pdf',
    nko_tw2: 'https://ads.agungkdev.com/creatives/test.pdf',
    nko_tw3: 'https://ads.agungkdev.com/creatives/test.pdf',
    nko_tw4: 'https://ads.agungkdev.com/creatives/test.pdf',
    nko_tahunan: 'https://ads.agungkdev.com/creatives/test.pdf'
  },
])

const activeData = computed(() => {
  const dataMap: Record<string, any[]> = {
    'intermediateoutcomelv2tahunan': intermediateoutcomelv2tahunan.value,
    'intermediateoutcomelv2triwulanan': intermediateoutcomelv2tahunan.value,
    'intermediateoutcomelv3tahunan': intermediateoutcomelv3tahunan.value,
    'intermediateoutcomelv3triwulanan': intermediateoutcomelv3tahunan.value,
    'perkinnko': perkinnko.value,
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

const uploadLabel = computed(() => {
  const labels: Record<string, string> = {
    'intermediateoutcomelv2tahunan': 'UPLOAD INTERMEDIATE OUTCOME LV2 TAHUNAN',
    'intermediateoutcomelv2triwulanan': 'UPLOAD INTERMEDIATE OUTCOME LV2 TRIWULANAN',
    'intermediateoutcomelv3tahunan': 'UPLOAD INTERMEDIATE OUTCOME LV3 TAHUNAN',
    'intermediateoutcomelv3triwulanan': 'UPLOAD INTERMEDIATE OUTCOME LV3 TRIWULANAN',
  }
  return labels[currentUploadTab.value] || 'UPLOAD FILE'
})

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

const downloadData = (tabName: string) => {
  console.log(`Downloading data for ${tabName}`)
  // Implement download logic here
}

const openUploadModal = (tabName: string) => {
  currentUploadTab.value = tabName
  showModal.value = true
}

const closeUploadModal = () => {
  showModal.value = false
  selectedFile.value = null
  uploadFileName.value = ''
  currentUploadTab.value = ''
}

const handleFileSelect = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files[0]) {
    selectedFile.value = target.files[0]
    uploadFileName.value = target.files[0].name
  }
}

const handleUpload = () => {
  if (!selectedFile.value) return
  
  console.log(`Uploading file for ${currentUploadTab.value}:`, selectedFile.value.name)
  // Add your upload logic here
  
  closeUploadModal()
}

watch(activeTab, () => {
  currentPage.value = 1
})
</script>