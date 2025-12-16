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
        class="w-full max-w-5xl h-[85vh] bg-white rounded-lg shadow-2xl overflow-hidden"
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
              @click="activeTab = 'targetkinerjatahunan'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'targetkinerjatahunan' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              TARGET KINERJA TAHUNAN
            </button>
            <button 
              @click="activeTab = 'targetkinerjatriwulan'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'targetkinerjatriwulan' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              TARGET KINERJA TRIWULAN
            </button>
            <button 
              @click="activeTab = 'uploadperkin'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'uploadperkin' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              UPLOAD PERKIN
            </button>
          </div>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
          <!-- Target Kinerja Tahunan Tab -->
          <div v-if="activeTab === 'targetkinerjatahunan'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL TARGET KINERJA - TAHUNAN</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">TAHUN</label>
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
                    <th class="px-4 py-3 border-b border-r border-gray-300">PENGAMPU</th>
                    <th class="px-4 py-3 border-b border-gray-300">AKSI</th>
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
                          {{ Array.isArray(item.pengampuh) ? item.pengampuh[i] : item.pengampuh }}
                        </td>
                        <td class="px-4 py-3 border-b border-gray-300">
                          <button
                            @click="openEditTahunanDrawer(item, i)"
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
                    <td colspan="9" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
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

          <!-- Target Kinerja Triwulan Tab -->
          <div v-if="activeTab === 'targetkinerjatriwulan'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL TARGET KINERJA - TRIWULAN</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">TAHUN</label>
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
                    <th class="px-4 py-3 border-b border-r border-gray-300">TARGET TW2</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">TARGET TW3</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">TARGET TW4</th>
                    <th class="px-4 py-3 border-b border-gray-300">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loading">
                    <td colspan="12" class="px-4 py-8 text-center">
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
                          {{ Array.isArray(item.target1) ? item.target1[i] : item.target1 }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.target2) ? item.target2[i] : item.target2 }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.target3) ? item.target3[i] : item.target3 }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.target4) ? item.target4[i] : item.target4 }}
                        </td>
                        <td class="px-4 py-3 border-b border-gray-300">
                          <button
                            @click="openEditTriwulanDrawer(item, i)"
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
                    <td colspan="12" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
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
                @click="generateAndViewPerkin" 
                :disabled="isGenerating"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
              >
                <svg v-if="isGenerating" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ isGenerating ? 'GENERATING...' : 'GENERATE PERKIN' }}
              </button>
            </div>
          </div>

          <!-- Upload Perkin Tab -->
          <div v-if="activeTab === 'uploadperkin'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">DOKUMEN PERKIN</h2>
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
                    <th class="px-4 py-3 border-b border-r border-gray-300">TAHUN</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">NAMA FILE</th>
                    <th class="px-4 py-3 border-b border-gray-300">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loading">
                    <td colspan="4" class="px-4 py-8 text-center">
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
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
                  </tr>
                  <tr v-else v-for="(item, index) in paginated" :key="item.id" :class="[index % 2 === 0 ? 'bg-white' : 'bg-gray-50', 'hover:bg-gray-100']">
                    <td class="px-4 py-3 text-center border-b border-r border-gray-300">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                    <td class="px-4 py-3 border-b border-r border-gray-300">{{ item.tahun }}</td>
                    <td class="px-4 py-3 border-b border-r border-gray-300">{{ item.nama_file }}</td>
                    <td class="px-4 py-3 border-b border-gray-300">
                      <button
                        @click="openPdfViewer(item.nama_file, item.file)"
                        class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition-colors"
                        title="Lihat PDF"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                      </button>
                    </td>
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
              <button @click="openUploadPerkinDrawer" class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors">UPLOAD PERKIN</button>
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

const activeTab = ref('targetkinerjatahunan')
const loading = ref(false)
const search = ref('')
const filterTahun = ref('2016')
const currentPage = ref(1)
const itemsPerPage = ref(5)

// Ref untuk drawer form
const selectedFile = ref<File | null>(null)
const uploadFileName = ref('')

const targetkinerjatahunan = ref([
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
    pengampuh: ['D3', 'D4'],
  }
])

const targetkinerjatriwulan = ref([
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
  }
])

const uploadperkin = ref([
  {
    id: 1,
    nama_file: 'PERKIN SU 2026',
    tahun: '2026',
    file: 'https://ads.agungkdev.com/creatives/test.pdf',
  },
  {
    id: 2,
    nama_file: 'PERKIN SU 2027',
    tahun: '2027',
    file: 'https://ads.agungkdev.com/creatives/test.pdf',
  }
])

const activeData = computed(() => {
  const dataMap: Record<string, any[]> = {
    'targetkinerjatahunan': targetkinerjatahunan.value,
    'targetkinerjatriwulan': targetkinerjatriwulan.value,
    'uploadperkin': uploadperkin.value,
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

// Drawer for Edit Tahunan
const openEditTahunanDrawer = (item: any, index: number) => {
  const editTarget = ref('')
  const editSatuan = ref('%')

  const DrawerComponent = defineComponent({
    name: 'EditTahunanDrawer',
    setup() {
      const handleSave = () => {
        console.log('Saving Tahunan:', {
          id: item.id,
          index,
          target: editTarget.value,
          satuan: editSatuan.value
        })
        
        pageDefault.value?.closeDrawerDefault()
      }

      return () => h('div', { class: 'p-6 space-y-6' }, [
        // Title
        h('h2', { class: 'text-lg font-semibold text-gray-900 mb-6' }, 'TAMBAH TARGET TAHUNAN'),
        
        // Tahun
        h('div', { class: 'grid grid-cols-[120px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'TAHUN'),
          h('div', { class: 'text-sm text-gray-900' }, Array.isArray(item.periode) ? item.periode[index] : item.periode)
        ]),
        
        // IKSP
        h('div', { class: 'grid grid-cols-[120px_1fr] gap-4 items-start' }, [
          h('label', { class: 'text-sm font-medium text-gray-900 pt-2' }, 'IKSP'),
          h('div', { class: 'text-sm text-gray-900' }, Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja[index] : item.indikator_kinerja)
        ]),
        
        // Target
        h('div', { class: 'grid grid-cols-[120px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'TARGET'),
          h('input', {
            type: 'text',
            value: editTarget.value,
            placeholder: '50',
            onInput: (e: Event) => {
              editTarget.value = (e.target as HTMLInputElement).value
            },
            class: 'w-full border border-gray-900 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500'
          })
        ]),
        
        // Satuan
        h('div', { class: 'grid grid-cols-[120px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'SATUAN'),
          h('input', {
            type: 'text',
            value: editSatuan.value,
            placeholder: '%',
            onInput: (e: Event) => {
              editSatuan.value = (e.target as HTMLInputElement).value
            },
            class: 'w-full border border-gray-900 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500'
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

// Drawer for Edit Triwulan
const openEditTriwulanDrawer = (item: any, index: number) => {
  const editTargetTW1 = ref('')
  const editTargetTW2 = ref('')
  const editTargetTW3 = ref('')
  const editTargetTW4 = ref('')
  const editSatuan = ref('%')

  const DrawerComponent = defineComponent({
    name: 'EditTriwulanDrawer',
    setup() {
      const handleSave = () => {
        console.log('Saving Triwulan:', {
          id: item.id,
          index,
          targetTW1: editTargetTW1.value,
          targetTW2: editTargetTW2.value,
          targetTW3: editTargetTW3.value,
          targetTW4: editTargetTW4.value,
          satuan: editSatuan.value
        })
        
        pageDefault.value?.closeDrawerDefault()
      }

      return () => h('div', { class: 'p-6 space-y-6' }, [
        // Title
        h('h2', { class: 'text-lg font-semibold text-gray-900 mb-6' }, 'TAMBAH TARGET TAHUNAN'),
        
        // Tahun
        h('div', { class: 'grid grid-cols-[120px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'TAHUN'),
          h('div', { class: 'text-sm text-gray-900' }, Array.isArray(item.periode) ? item.periode[index] : item.periode)
        ]),
        
        // IKSP
        h('div', { class: 'grid grid-cols-[120px_1fr] gap-4 items-start' }, [
          h('label', { class: 'text-sm font-medium text-gray-900 pt-2' }, 'IKSP'),
          h('div', { class: 'text-sm text-gray-900' }, Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja[index] : item.indikator_kinerja)
        ]),
        
        // Target TW I
        h('div', { class: 'grid grid-cols-[120px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'TARGET TW I'),
          h('input', {
            type: 'text',
            value: editTargetTW1.value,
            placeholder: '50',
            onInput: (e: Event) => {
              editTargetTW1.value = (e.target as HTMLInputElement).value
            },
            class: 'w-full border border-gray-900 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500'
          })
        ]),
        
        // Target TW II
        h('div', { class: 'grid grid-cols-[120px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'TARGET TW II'),
          h('input', {
            type: 'text',
            value: editTargetTW2.value,
            placeholder: '50',
            onInput: (e: Event) => {
              editTargetTW2.value = (e.target as HTMLInputElement).value
            },
            class: 'w-full border border-gray-900 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500'
          })
        ]),
        
        // Target TW III
        h('div', { class: 'grid grid-cols-[120px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'TARGET TW III'),
          h('input', {
            type: 'text',
            value: editTargetTW3.value,
            placeholder: '50',
            onInput: (e: Event) => {
              editTargetTW3.value = (e.target as HTMLInputElement).value
            },
            class: 'w-full border border-gray-900 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500'
          })
        ]),
        
        // Target TW IV
        h('div', { class: 'grid grid-cols-[120px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'TARGET TW IV'),
          h('input', {
            type: 'text',
            value: editTargetTW4.value,
            placeholder: '50',
            onInput: (e: Event) => {
              editTargetTW4.value = (e.target as HTMLInputElement).value
            },
            class: 'w-full border border-gray-900 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500'
          })
        ]),
        
        // Satuan
        h('div', { class: 'grid grid-cols-[120px_1fr] gap-4 items-center' }, [
          h('label', { class: 'text-sm font-medium text-gray-900' }, 'SATUAN'),
          h('input', {
            type: 'text',
            value: editSatuan.value,
            placeholder: '%',
            onInput: (e: Event) => {
              editSatuan.value = (e.target as HTMLInputElement).value
            },
            class: 'w-full border border-gray-900 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500'
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

const isGenerating = ref(false)

const generateAndViewPerkin = async () => {
  isGenerating.value = true
  
  try {
    console.log('Generating PERKIN for year:', filterTahun.value)
    
    // Simulate API call to generate PERKIN
    // const response = await api.generatePerkin({ year: filterTahun.value })
    // const generatedPdfUrl = response.data.pdf_url
    
    // Simulate delay
    await new Promise(resolve => setTimeout(resolve, 1500))
    
    // Mock generated PDF URL
    const generatedPdfUrl = 'https://ads.agungkdev.com/creatives/test.pdf'
    const pdfTitle = `PERKIN - ${filterTahun.value}`
    
    // Open PDF viewer
    openPdfViewer(pdfTitle, generatedPdfUrl)
    
  } catch (error) {
    console.error('Error generating PERKIN:', error)
    alert('Gagal generate PERKIN')
  } finally {
    isGenerating.value = false
  }
}

// Drawer for Upload Perkin
const openUploadPerkinDrawer = () => {
  const selectedYear = ref('2026')
  selectedFile.value = null
  uploadFileName.value = ''

  const DrawerComponent = defineComponent({
    name: 'UploadPerkinDrawer',
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
        
        console.log('Uploading PERKIN:', {
          year: selectedYear.value,
          file: selectedFile.value.name
        })
        
        pageDefault.value?.closeDrawerDefault()
        
        selectedFile.value = null
        uploadFileName.value = ''
      }

      return () => h('div', { class: 'p-6 space-y-6' }, [
        // Title
        h('h2', { class: 'text-lg font-semibold text-gray-900 mb-6' }, 'UPLOAD PERKIN'),
        
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

// Drawer for Generate Perkin - tetap sama seperti sebelumnya
const openGeneratePerkinDrawer = () => {
  const selectedYear = ref('2026')
  
  const DrawerComponent = defineComponent({
    name: 'GeneratePerkinDrawer',
    setup() {
      const handleGenerate = () => {
        console.log('Generating PERKIN for year:', selectedYear.value)
        
        pageDefault.value?.closeDrawerDefault()
      }

      return () => h('div', { class: 'p-6 space-y-6' }, [
        // Title
        h('h2', { class: 'text-lg font-semibold text-gray-900 mb-6' }, 'GENERATE PERKIN'),
        
        // Info
        h('div', { class: 'bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6' }, [
          h('p', { class: 'text-sm text-yellow-800' }, 'Generate dokumen PERKIN berdasarkan data target kinerja triwulan yang telah diinput.')
        ]),
        
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
        
        // Buttons
        h('div', { class: 'flex justify-end gap-3 pt-6' }, [
          h('button', {
            class: 'px-6 py-2 bg-blue-500 text-white rounded text-sm font-medium hover:bg-blue-600 transition-colors',
            onClick: handleGenerate
          }, 'GENERATE'),
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