<template>
  <PageDefault
    ref="pageDefault"
    :pageStatus="pageStatus"
    :isDrawerDefault="true"
  >
    <!-- Main Content -->
    <div class="flex flex-col w-full h-full p-4 overflow-auto gap-y-4 xl:gap-y-6 xl:p-6 bg-primary-50">
      <!-- Main Card Container with Tabs Inside -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Tabs Inside Card -->
        <div class="border-b border-gray-200 px-6">
          <div class="flex overflow-x-auto">
            <button 
              @click="activeTab = 'finaloutcome'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'finaloutcome' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              FINAL OUTCOME
            </button>
            <button 
              @click="activeTab = 'intermediateoutcomelv1'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'intermediateoutcomelv1' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              INTERMEDIATE OUTCOME LV1
            </button>
            <button 
              @click="activeTab = 'intermediateoutcomelv2'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'intermediateoutcomelv2' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              INTERMEDIATE OUTCOME LV2
            </button>
            <button 
              @click="activeTab = 'intermediateoutcomelv3'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'intermediateoutcomelv3' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              INTERMEDIATE OUTCOME LV3
            </button>
            <button 
              @click="activeTab = 'output'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'output' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              OUTPUT
            </button>
            <button 
              @click="activeTab = 'penerjemahintermediateoutcomelv2'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'penerjemahintermediateoutcomelv2' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              PENERJEMAH INTERMEDIATE OUTCOME LV2
            </button>
            <button 
              @click="activeTab = 'penerjemahintermediateoutcomelv3'"
              :class="[
                'py-4 px-4 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide whitespace-nowrap',
                activeTab === 'penerjemahintermediateoutcomelv3' 
                  ? 'border-yellow-500 text-gray-900' 
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              ]"
            >
              PENERJEMAH INTERMEDIATE OUTCOME LV3
            </button>
          </div>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
          <!-- Final Outcome Tab -->
          <div v-if="activeTab === 'finaloutcome'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL FINAL OUTCOME</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">PERIODE</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="2025-2029">2025-2029</option>
                    <option value="2030-2034">2030-2034</option>
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
                    <th class="px-4 py-3 border-b border-r border-gray-300">SASARAN</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">INDIKATOR KINERJA</th>
                    <th class="px-4 py-3 border-b border-gray-300">PERIODE</th>
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
                    <td class="px-4 py-3 border-b border-r border-gray-300">{{ item.kode_uo }}</td>
                    <td class="px-4 py-3 border-b border-r border-gray-300">{{ item.sasaran }}</td>
                    <td class="px-4 py-3 border-b border-r border-gray-300">{{ item.indikator_kinerja }}</td>
                    <td class="px-4 py-3 border-b border-gray-300">{{ item.periode }}</td>
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
                @click="openUploadDrawer('finaloutcome')"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors"
              >
                Upload Final Outcome
              </button>
            </div>
          </div>

          <!-- Intermediate Outcome LV1 Tab -->
          <div v-if="activeTab === 'intermediateoutcomelv1'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL INTERMEDIATE OUTCOME LV1</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">PERIODE</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="2025-2029">2025-2029</option>
                    <option value="2030-2034">2030-2034</option>
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
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE Int LV1</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">SASARAN</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">INDIKATOR KINERJA</th>
                    <th class="px-4 py-3 border-b border-gray-300">PERIODE</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loading">
                    <td colspan="6" class="px-4 py-8 text-center">
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
                      <tr v-for="(indikator, i) in (Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja : [item.indikator_kinerja])" :key="item.id + '-' + i" :class="[index % 2 === 0 ? 'bg-white' : 'bg-gray-50', 'hover:bg-gray-100']">
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
                          {{ item.sasaran }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">{{ indikator }}</td>
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 align-top border-b border-gray-300">
                          {{ item.periode }}
                        </td>
                      </tr>
                    </template>
                  </template>
                  <tr v-else>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
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
                @click="openUploadDrawer('intermediateoutcomelv1')"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors"
              >
                Upload Intermediate Outcome LV1
              </button>
            </div>
          </div>

          <!-- Intermediate Outcome LV2 Tab -->
          <div v-if="activeTab === 'intermediateoutcomelv2'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL INTERMEDIATE OUTCOME LV2</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">PERIODE</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="2025-2029">2025-2029</option>
                    <option value="2030-2034">2030-2034</option>
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
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE Int LV1</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE Int LV2</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">SASARAN PROGRAM</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">IKSP</th>
                    <th class="px-4 py-3 border-b border-gray-300">PERIODE</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loading">
                    <td colspan="6" class="px-4 py-8 text-center">
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
                      <tr v-for="(indikator, i) in (Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja : [item.indikator_kinerja])" :key="item.id + '-' + i" :class="[index % 2 === 0 ? 'bg-white' : 'bg-gray-50', 'hover:bg-gray-100']">
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
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 align-top border-b border-gray-300">
                          {{ item.periode }}
                        </td>
                      </tr>
                    </template>
                  </template>
                  <tr v-else>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
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
                @click="openUploadDrawer('intermediateoutcomelv2')"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors"
              >
                Upload Intermediate Outcome LV2
              </button>
            </div>
          </div>

          <!-- Intermediate Outcome LV3 Tab -->
          <div v-if="activeTab === 'intermediateoutcomelv3'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL INTERMEDIATE OUTCOME LV3</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">PERIODE</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="2025-2029">2025-2029</option>
                    <option value="2030-2034">2030-2034</option>
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
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE Int LV1</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE Int LV2</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE Imm.O LV3</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">SASARAN PROGRAM</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">IKSK</th>
                    <th class="px-4 py-3 border-b border-gray-300">PERIODE</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loading">
                    <td colspan="6" class="px-4 py-8 text-center">
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
                      <tr v-for="(indikator, i) in (Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja : [item.indikator_kinerja])" :key="item.id + '-' + i" :class="[index % 2 === 0 ? 'bg-white' : 'bg-gray-50', 'hover:bg-gray-100']">
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
                          {{ item.kode_int_lv3 }}
                        </td>
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 align-top border-b border-r border-gray-300">
                          {{ item.sasaran }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">{{ indikator }}</td>
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 align-top border-b border-gray-300">
                          {{ item.periode }}
                        </td>
                      </tr>
                    </template>
                  </template>
                  <tr v-else>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
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
                @click="openUploadDrawer('intermediateoutcomelv3')"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors"
              >
                Upload Intermediate Outcome LV3
              </button>
            </div>
          </div>

          <!-- Output Tab -->
          <div v-if="activeTab === 'output'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL OUTPUT</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">PERIODE</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="2025-2029">2025-2029</option>
                    <option value="2030-2034">2030-2034</option>
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
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE Int LV1</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE Int LV2</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE Imm.O LV3</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE IO</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">DESKRIPSI</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">INDIKATOR OUTPUT</th>
                    <th class="px-4 py-3 border-b border-gray-300">PERIODE</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loading">
                    <td colspan="6" class="px-4 py-8 text-center">
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
                      <tr v-for="(indikator, i) in (Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja : [item.indikator_kinerja])" :key="item.id + '-' + i" :class="[index % 2 === 0 ? 'bg-white' : 'bg-gray-50', 'hover:bg-gray-100']">
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
                          {{ item.kode_int_lv2 }}
                        </td>
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 align-top border-b border-r border-gray-300">
                          {{ item.kode_int_io }}
                        </td>
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 align-top border-b border-r border-gray-300">
                          {{ item.deskripsi }}
                        </td>
                        <td class="px-4 py-3 border-b border-r border-gray-300">{{ indikator }}</td>
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 align-top border-b border-gray-300">
                          {{ item.periode }}
                        </td>
                      </tr>
                    </template>
                  </template>
                  <tr v-else>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
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
                @click="openUploadDrawer('output')"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors"
              >
                Upload Output
              </button>
            </div>
          </div>

          <!-- Penerjemah Intermediate Outcome LV2 Tab -->
          <div v-if="activeTab === 'penerjemahintermediateoutcomelv2'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL PENERJEMAH INTERMEDIATE OUTCOME LV2</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">UNIT KERJA</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="D1">D1</option>
                    <option value="D2">D2</option>
                    <option value="D3">D3</option>
                    <option value="D4">D4</option>
                    <option value="D5">D5</option>
                  </select>
                </div>
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
                    <th class="px-4 py-3 border-b border-gray-300">PENGAMPU</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loading">
                    <td colspan="8" class="px-4 py-8 text-center">
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
                        <!-- IKSP - per row -->
                        <td class="px-4 py-3 border-b border-r border-gray-300">{{ indikator }}</td>
                        <!-- TAHUN - per row, ambil dari array periode -->
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.periode) ? item.periode[i] : item.periode }}
                        </td>
                        <!-- PENGAMPU - per row, ambil dari array pengampuh -->
                        <td class="px-4 py-3 border-b border-gray-300">
                          {{ Array.isArray(item.pengampuh) ? item.pengampuh[i] : item.pengampuh }}
                        </td>
                      </tr>
                    </template>
                  </template>
                  <tr v-else>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500 border-b border-gray-300">Tidak ada data</td>
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
                @click="openUploadDrawer('penerjemahintermediateoutcomelv2')"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors"
              >
                Upload Penerjemah Int.O LV2
              </button>
            </div>
          </div>

          <!-- Penerjemah Intermediate Outcome LV3 Tab -->
          <div v-if="activeTab === 'penerjemahintermediateoutcomelv3'" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL PENERJEMAH INTERMEDIATE OUTCOME LV3</h2>
              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">UNIT KERJA</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    <option value="D1">D1</option>
                    <option value="D2">D2</option>
                    <option value="D3">D3</option>
                    <option value="D4">D4</option>
                    <option value="D5">D5</option>
                  </select>
                </div>
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
                    <th class="px-4 py-3 border-b border-r border-gray-300">KODE Int.O LV3</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">SASARAN KEGIATAN</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">IKSK</th>
                    <th class="px-4 py-3 border-b border-r border-gray-300">TAHUN</th>
                    <th class="px-4 py-3 border-b border-gray-300">PENGAMPU</th>
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
                          {{ item.kode_int_lv3 }}
                        </td>
                        <td v-if="i === 0" :rowspan="Array.isArray(item.indikator_kinerja) ? item.indikator_kinerja.length : 1" class="px-4 py-3 align-top border-b border-r border-gray-300">
                          {{ item.sasaran }}
                        </td>
                        <!-- IKSK - per row -->
                        <td class="px-4 py-3 border-b border-r border-gray-300">{{ indikator }}</td>
                        <!-- TAHUN - per row, ambil dari array periode -->
                        <td class="px-4 py-3 border-b border-r border-gray-300">
                          {{ Array.isArray(item.periode) ? item.periode[i] : item.periode }}
                        </td>
                        <!-- PENGAMPU - per row, ambil dari array pengampuh -->
                        <td class="px-4 py-3 border-b border-gray-300">
                          {{ Array.isArray(item.pengampuh) ? item.pengampuh[i] : item.pengampuh }}
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
                @click="openUploadDrawer('penerjemahintermediateoutcomelv3')"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors"
              >
                Upload Penerjemah Int.O LV3
              </button>
            </div>
          </div>

          <!-- Dan seterusnya untuk tab lainnya... -->
        </div>
      </div>
    </div>
  </PageDefault>
</template>

<script setup lang="ts">
import { ref, computed, watch, defineComponent, h } from 'vue'
import PageDefault from '@/components/page/PageDefault.vue'
import { type PageDefaultExposed, type PageStatus } from '@/models'

const pageStatus = ref<PageStatus>('success')
const pageDefault = ref<PageDefaultExposed | null>(null)

const activeTab = ref('finaloutcome')
const loading = ref(false)
const search = ref('')
const filterTahun = ref('2025-2029')
const currentPage = ref(1)
const itemsPerPage = ref(5)

// Ref untuk drawer form
const selectedFile = ref<File | null>(null)
const uploadFileName = ref('')

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
    indikator_kinerja: ['Indeks keamanan dan ketahanan siber', 'Indeks keamanan informasi'],
    periode: '2025 - 2029'
  },
])

const intermediateOutcomeLv2List = ref([
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
    periode: '2025 - 2029'
  },
])

const intermediateOutcomeLv3List = ref([
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
    periode: '2025 - 2029'
  },
])

const outputList = ref([
  {
    id: 1,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.1',
    kode_int_lv3: 'Int.0.1.1.1',
    kode_int_io: 'Int.0.1.1.1.a.1',
    deskripsi: 'Fasilitasi dan pembinaan kematangan keamanan siber dan sandi pemerintah',
    indikator_kinerja: [
      'Jumlah lembaga pemerintahan (IPPD) yang mendapat pembinaan kematangan keamanan siber'
    ],
    periode: '2025 - 2029'
  },
])

const penerjemahintermediateoutcomelv2List = ref([
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
    pengampuh: ['D3'],
  }
])

const penerjemahintermediateoutcomelv3List = ref([
  {
    id: 1,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.1',
    kode_int_lv3: 'Int.0.1.1.1',
    sasaran: 'Meningkatnya kematangan keamanan siber dan sandi PSE sektor Pemerintah',
    indikator_kinerja: [
      'Nilai kematangan keamanan siber PSE sektor pemerintahan pusat',
    ],
    periode: ['2026'],
    pengampuh: ['D31'],
  },
])

const activeData = computed(() => {
  const dataMap: Record<string, any[]> = {
    'finaloutcome': finaloutcome.value,
    'intermediateoutcomelv1': intermediateOutcomeLv1List.value,
    'intermediateoutcomelv2': intermediateOutcomeLv2List.value,
    'intermediateoutcomelv3': intermediateOutcomeLv3List.value,
    'output': outputList.value,
    'penerjemahintermediateoutcomelv2': penerjemahintermediateoutcomelv2List.value,
    'penerjemahintermediateoutcomelv3': penerjemahintermediateoutcomelv3List.value,
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
    'finaloutcome': 'UPLOAD FINAL OUTCOME',
    'intermediateoutcomelv1': 'UPLOAD INTERMEDIATE OUTCOME LV1',
    'intermediateoutcomelv2': 'UPLOAD INTERMEDIATE OUTCOME LV2',
    'intermediateoutcomelv3': 'UPLOAD INTERMEDIATE OUTCOME LV3',
    'output': 'UPLOAD OUTPUT',
    'penerjemahintermediateoutcomelv2': 'UPLOAD PENERJEMAH INTERMEDIATE OUTCOME LV2',
    'penerjemahintermediateoutcomelv3': 'UPLOAD PENERJEMAH INTERMEDIATE OUTCOME LV3'
  }
  return labels[activeTab.value] || 'UPLOAD FILE'
})

// Function to open upload drawer
const openUploadDrawer = (tabName: string) => {
  // Reset form values
  selectedFile.value = null
  uploadFileName.value = ''

  const DrawerComponent = defineComponent({
    name: 'UploadDrawerForm',
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
        
        console.log(`Uploading file for ${tabName}:`, selectedFile.value.name)
        
        // Add your upload logic here
        
        pageDefault.value?.closeDrawerDefault()
        
        // Reset after upload
        selectedFile.value = null
        uploadFileName.value = ''
      }

      return () => h('div', { class: 'p-6 space-y-6' }, [
        // File Upload Field
        h('div', [
          h('label', { class: 'block text-sm font-medium text-gray-700 mb-2' }, uploadLabel.value),
          h('div', { class: 'flex gap-3' }, [
            h('input', {
              type: 'text',
              value: uploadFileName.value,
              readonly: true,
              placeholder: 'No file chosen',
              class: 'flex-1 border border-gray-300 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500'
            }),
            h('label', {
              class: 'px-6 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-200 cursor-pointer transition-colors'
            }, [
              'Browse',
              h('input', {
                type: 'file',
                onChange: handleFileSelect,
                class: 'hidden',
                accept: '.xlsx,.xls,.csv'
              })
            ])
          ])
        ]),
        
        // Buttons
        h('div', { class: 'flex justify-end gap-3 pt-4 border-t' }, [
          h('button', {
            class: 'px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50',
            onClick: () => pageDefault.value?.closeDrawerDefault()
          }, 'Cancel'),
          h('button', {
            class: `px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-medium hover:bg-yellow-600 transition-colors ${
              !selectedFile.value ? 'opacity-50 cursor-not-allowed' : ''
            }`,
            disabled: !selectedFile.value,
            onClick: handleUpload
          }, 'SUBMIT')
        ])
      ])
    }
  })

  const title = uploadLabel.value

  pageDefault.value?.openDrawerDefault(title, DrawerComponent)
}

watch(activeTab, () => {
  currentPage.value = 1
})
</script>