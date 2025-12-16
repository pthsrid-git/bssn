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
      <!-- Intermediate Outcome LV2 Tab Content -->
      <div v-if="activeTab === 'intermediateoutcomelv2'" class="space-y-4">
        <!-- Daftar Laporan Unit Kerja Card -->
        <Card>
          <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">TABEL INTERMEDIATE OUTCOME LV2</h2>

            <!-- Table -->
            <TableDefault>
              <template #header>
                <tr>
                  <TableHeader alignment="center" custom-class="w-16">No</TableHeader>
                  <TableHeader>KODE UO</TableHeader>
                  <TableHeader>KODE Int LV1</TableHeader>
                  <TableHeader>KODE Int LV2</TableHeader>
                  <TableHeader>SASARAN PROGRAM</TableHeader>
                  <TableHeader>IKSP</TableHeader>
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
                <template v-else-if="intermediateOutcomeLv2List.length > 0">
                  <template v-for="(item, index) in paginated" :key="item.id">
                    <tr v-for="(iksp, i) in (Array.isArray(item.iksp) ? item.iksp : [item.iksp])" :key="item.id + '-' + i" class="hover:bg-gray-50">
                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="center" custom-class="align-top">
                        {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                      </TableData>
                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
                        {{ item.kode_uo }}
                      </TableData>
                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
                        {{ item.kode_int_lv1 }}
                      </TableData><TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
                        {{ item.kode_int_lv2 }}
                      </TableData>
                       <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
                        {{ item.sasaran }}
                      </TableData>
                      <TableData>
                        {{ iksp }}
                      </TableData>
                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
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
      <div v-if="activeTab === 'intermediateoutcomelv3'" class="space-y-4">
        <!-- Daftar Laporan Unit Kerja Card -->
        <Card>
          <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">TABEL INTERMEDIATE OUTCOME LV3</h2>
            <!-- Table -->
            <TableDefault>
              <template #header>
                <tr>
                  <TableHeader alignment="center" custom-class="w-16">No</TableHeader>
                  <TableHeader>KODE UO</TableHeader>
                  <TableHeader>KODE Int LV1</TableHeader>
                  <TableHeader>KODE Int LV2</TableHeader>
                  <TableHeader>KODE Int LV3</TableHeader>
                  <TableHeader>SASARAN KEGIATAN</TableHeader>
                  <TableHeader>IKSP</TableHeader>
                  <TableHeader>PERIODE</TableHeader>
                </tr>
              </template>
              <template #body>
                <tr v-if="loading">
                  <TableData colspan="8" alignment="center">
                    <div class="flex items-center justify-center py-4">
                      <svg class="animate-spin h-6 w-6 text-warning-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      <span class="text-gray-600">Memuat data...</span>
                    </div>
                  </TableData>
                </tr>
                <template v-else-if="intermediateOutcomeLv3List.length > 0">
                  <template v-for="(item, index) in paginated" :key="item.id">
                    <tr v-for="(iksp, i) in (Array.isArray(item.iksp) ? item.iksp : [item.iksp])" :key="item.id + '-' + i" class="hover:bg-gray-50">
                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="center" custom-class="align-top">
                        {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                      </TableData>

                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
                        {{ item.kode_uo }}
                      </TableData>

                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
                        {{ item.kode_int_lv1 }}
                      </TableData>

                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
                        {{ item.kode_int_lv2 }}
                      </TableData>

                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
                        {{ item.kode_int_lv3 }}
                      </TableData>
                      
                       <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
                        {{ item.sasaran }}
                      </TableData>
                      
                      <TableData>
                        {{ iksp }}
                      </TableData>

                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
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
      <div v-if="activeTab === 'output'" class="space-y-4">
        <!-- Daftar Laporan Unit Kerja Card -->
        <Card>
          <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">TABEL OUTPUT</h2>
            <!-- Table -->
            <TableDefault>
              <template #header>
                <tr>
                  <TableHeader alignment="center" custom-class="w-16">No</TableHeader>
                  <TableHeader>KODE UO</TableHeader>
                  <TableHeader>KODE Int.O LV1</TableHeader>
                  <TableHeader>KODE Int.O LV2</TableHeader>
                  <TableHeader>KODE Int.O LV3</TableHeader>
                  <TableHeader>KODE IO</TableHeader>
                  <TableHeader>DESKRIPSI</TableHeader>
                  <TableHeader>INDIKATOR OUTPUT</TableHeader>
                  <TableHeader>PERIODE</TableHeader>
                </tr>
              </template>
              <template #body>
                <tr v-if="loading">
                  <TableData colspan="8" alignment="center">
                    <div class="flex items-center justify-center py-4">
                      <svg class="animate-spin h-6 w-6 text-warning-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      <span class="text-gray-600">Memuat data...</span>
                    </div>
                  </TableData>
                </tr>
                <template v-else-if="outputList.length > 0">
                  <template v-for="(item, index) in paginated" :key="item.id">
                    <tr v-for="(iksp, i) in (Array.isArray(item.iksp) ? item.iksp : [item.iksp])" :key="item.id + '-' + i" class="hover:bg-gray-50">
                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="center" custom-class="align-top">
                        {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                      </TableData>

                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
                        {{ item.kode_uo }}
                      </TableData>

                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
                        {{ item.kode_int_lv1 }}
                      </TableData>

                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
                        {{ item.kode_int_lv2 }}
                      </TableData>

                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
                        {{ item.kode_int_lv3 }}
                      </TableData>
                      
                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
                        {{ item.kode_int_io }}
                      </TableData>
                      
                       <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
                        {{ item.deskripsi }}
                      </TableData>

                      <TableData>
                        {{ iksp }}
                      </TableData>

                      <TableData v-if="i === 0" :rowspan="Array.isArray(item.iksp) ? item.iksp.length : 1" alignment="left" custom-class="align-top">
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
      <div v-if="activeTab === 'penerjemahintermediateoutcomelv2'" class="space-y-4">
        <!-- Daftar Laporan Unit Kerja Card -->
        <Card>
          <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">TABEL PENERJAMAH INTERMEDIATE OUTCOME LV2</h2>
            <!-- Table -->
            <TableDefault>
              <template #header>
                <tr>
                  <TableHeader alignment="center" custom-class="w-16">No</TableHeader>
                  <TableHeader>KODE UO</TableHeader>
                  <TableHeader>KODE Int.O LV1</TableHeader>
                  <TableHeader>KODE Int.O LV2</TableHeader>
                  <TableHeader>SASARAN PROGRAM</TableHeader>
                  <TableHeader>IKSP</TableHeader>
                  <TableHeader>TAHUN</TableHeader>
                  <TableHeader>PENGAMPUH</TableHeader>
                </tr>
              </template>
              <template #body>
                <tr v-if="loading">
                  <TableData colspan="8" alignment="center">
                    <div class="flex items-center justify-center py-4">
                      <svg class="animate-spin h-6 w-6 text-warning-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      <span class="text-gray-600">Memuat data...</span>
                    </div>
                  </TableData>
                </tr>
                <template v-else-if="penerjemahintermediateoutcomelv2List.length > 0">
                  <template v-for="(item, index) in paginated" :key="item.id">
                    <tr
                      v-for="(iksp, i) in (Array.isArray(item.iksp) ? item.iksp : [item.iksp])"
                      :key="item.id + '-' + i"
                      class="hover:bg-gray-50"
                    >
                      <TableData v-if="i === 0" :rowspan="item.iksp.length" alignment="center" custom-class="align-top" >
                        {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                      </TableData>

                      <TableData
                        v-if="i === 0"
                        :rowspan="item.iksp.length"
                        custom-class="align-top"
                      >
                        {{ item.kode_uo }}
                      </TableData>

                      <TableData
                        v-if="i === 0"
                        :rowspan="item.iksp.length"
                        custom-class="align-top"
                      >
                        {{ item.kode_int_lv1 }}
                      </TableData>

                      <TableData
                        v-if="i === 0"
                        :rowspan="item.iksp.length"
                        custom-class="align-top"
                      >
                        {{ item.kode_int_lv2 }}
                      </TableData>

                      <TableData
                        v-if="i === 0"
                        :rowspan="item.iksp.length"
                        custom-class="align-top"
                      >
                        {{ item.sasaran }}
                      </TableData>

                      <TableData>
                        {{ iksp }}
                      </TableData>

                      <TableData>
                        {{ item.periode?.[i] ?? '-' }}
                      </TableData>

                      <TableData>
                        {{ item.pengampuh?.[i] ?? '-' }}
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
      <div v-if="activeTab === 'penerjemahintermediateoutcomelv3'" class="space-y-4">
        <!-- Daftar Laporan Unit Kerja Card -->
        <Card>
          <div class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
              <h2 class="text-lg font-semibold text-gray-900">TABEL PENERJAMAH INTERMEDIATE OUTCOME LV3</h2>

              <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">UNIT KERJA</label>
                  <select v-model="filterUnitKerja" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-primary-500" >
                    <option value="">ALL</option>
                    <option value="UO 1">UO 1</option>
                    <option value="UO 2">UO 2</option>
                  </select>
                </div>

                <div class="flex items-center gap-2">
                  <label class="text-sm text-gray-600 whitespace-nowrap">TAHUN</label>
                  <select v-model="filterTahun" class="border rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-primary-500" >
                    <option value="2026">2026</option>
                    <option value="2027">2027</option>
                  </select>
                </div>

                <!-- SEARCH -->
                <div class="relative">
                  <input v-model="search" type="text" placeholder="search" class="border rounded-md pl-9 pr-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-primary-500" />
                  <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
              </div>
            </div>
            <!-- Table -->
            <TableDefault>
              <template #header>
                <tr>
                  <TableHeader alignment="center" custom-class="w-16">No</TableHeader>
                  <TableHeader>KODE UO</TableHeader>
                  <TableHeader>KODE Int.O LV1</TableHeader>
                  <TableHeader>KODE Int.O LV2</TableHeader>
                  <TableHeader>KODE Int.O LV3</TableHeader>
                  <TableHeader>SASARAN KEGIATAN</TableHeader>
                  <TableHeader>IKSP</TableHeader>
                  <TableHeader>TAHUN</TableHeader>
                  <TableHeader>PENGAMPUH</TableHeader>
                </tr>
              </template>
              <template #body>
                <tr v-if="loading">
                  <TableData colspan="8" alignment="center">
                    <div class="flex items-center justify-center py-4">
                      <svg class="animate-spin h-6 w-6 text-warning-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      <span class="text-gray-600">Memuat data...</span>
                    </div>
                  </TableData>
                </tr>
                <template v-else-if="penerjemahintermediateoutcomelv2List.length > 0">
                  <template v-for="(item, index) in paginated" :key="item.id">
                    <tr v-for="(iksp, i) in (Array.isArray(item.iksp) ? item.iksp : [item.iksp])" :key="item.id + '-' + i" class="hover:bg-gray-50" >
                      <TableData v-if="i === 0" :rowspan="item.iksp.length" alignment="center" custom-class="align-top" >
                        {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                      </TableData>

                      <TableData v-if="i === 0" :rowspan="item.iksp.length" custom-class="align-top" >
                        {{ item.kode_uo }}
                      </TableData>

                      <TableData v-if="i === 0" :rowspan="item.iksp.length" custom-class="align-top" >
                        {{ item.kode_int_lv1 }}
                      </TableData>

                      <TableData v-if="i === 0" :rowspan="item.iksp.length" custom-class="align-top" >
                        {{ item.kode_int_lv2 }}
                      </TableData>
                      
                      <TableData v-if="i === 0" :rowspan="item.iksp.length" custom-class="align-top" >
                        {{ item.kode_int_lv3 }}
                      </TableData>

                      <TableData v-if="i === 0" :rowspan="item.iksp.length" custom-class="align-top" >
                        {{ item.sasaran }}
                      </TableData>

                      <TableData>
                        {{ iksp }}
                      </TableData>

                      <TableData>
                        {{ item.periode?.[i] ?? '-' }}
                      </TableData>

                      <TableData>
                        {{ item.pengampuh?.[i] ?? '-' }}
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
const intermediateOutcomeLv2List = ref([
  {
    id: 1,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.1',
    sasaran: 'Meningkatnya kematangan keamanan siber dan sandi PSE dan penyelenggara persandian',
    iksp: [
      'Indeks keamanan dan ketahanan siber',
      'Indeks keamanan informasi'
    ],
    periode: '2025 - 2029'
  },
  {
    id: 2,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.2',
    sasaran: 'Meningkatnya manfaat kebijakan dan kerja sama keamanan siber dan sandi dalam penanganan gangguan keamanan siber',
    iksp: [
      'Indeks keamanan dan ketahanan siber',
      'Indeks keamanan informasi'
    ],
    periode: '2025 - 2029'
  },
  {
    id: 3,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.3',
    sasaran: 'Meningkatnya kesadaran masyarakat terhadap keamanan siber dan sandi',
    iksp: [
      'Indeks keamanan dan ketahanan siber',
      'Indeks keamanan informasi'
    ],
    periode: '2025 - 2029'
  },
  {
    id: 4,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.4',
    sasaran: 'Meningkatnya kesiapsiagaan dan ketahanan siber nasional dalam mengantisipasi serangan siber dan gangguan keamanan informasi',
    iksp: [
      'Indeks keamanan dan ketahanan siber',
      'Indeks keamanan informasi'
    ],
    periode: '2025 - 2029'
  },
  {
    id: 5,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.5',
    sasaran: 'Terwujudnya penegakan hukum keamanan siber dan keamanan informasi',
    iksp: [
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
    iksp: [
      'Nilai kematangan keamanan siber PSE',
      'Nilai kematangan penyelenggara persandian'
    ],
    periode: '2025 - 2029'
  },
  {
    id: 2,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.2',
    kode_int_lv2: 'Int.0.2.1',
    kode_int_lv3: 'Int.0.1.2.1',
    sasaran: 'Meningkatnya implementasi kebijakan siber dan sandi nasional',
    iksp: [
      'Persentase kebijakan yang diterapkan terhadap total kebijakan yang ditetapkan'
    ],
    periode: '2025 - 2029'
  },
  {
    id: 3,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.2',
    kode_int_lv2: 'Int.0.2.2',
    kode_int_lv3: 'Int.0.1.1.1',
    sasaran: 'Meningkatnya kualitas kerjasama keamanan siber dan sandi',
    iksp: [
      'Persentase kerjasama keamanan siber dan sandi yang ditindaklanjuti',
      'Persentase peran aktif BSSN dalam forum keamanan siber dan sandi'
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
    iksp: [
      'Jumlah lembaga pemerintahan (IPPD) yang mendapat pembinaan kematangan keamanan siber'
    ],
    periode: '2025 - 2029'
  },
  {
    id: 2,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.1',
    kode_int_lv3: 'Int.0.1.1.1',
    kode_int_io: 'Int.0.1.1.1.a.2',
    deskripsi: 'Fasilitasi dan pembinaan kematangan keamanan siber dan sandi pemerintah',
    iksp: [
      'Jumlah lembaga pemerintahan (IPPD) yang mendapat pembinaan kematangan penyelenggaraan persandian'
    ],
    periode: '2025 - 2029'
  },
  {
    id: 3,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.1',
    kode_int_lv3: 'Int.0.1.1.1',
    kode_int_io: 'Int.0.1.1.1.b.1',
    deskripsi: 'Fasilitasi dan pembinaan kematangan keamanan siber dan sandi sektor privat (pembangunan manusia dan perekonomian)',
    iksp: [
      'Jumlah lembaga sektor privat (pembangunan manusia dan perekonomian) yang mendapat pembinaan kematangan keamanan siber'
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
    sasaran : 'Meningkatnya kematangan PSE dan penyelenggara persandian sektor dalam menyelenggarakan keamanan siber dsan sandi',
    iksp: [
      'Persentase PSE sektor Pemerintahan dan Pembangunan manusia dengan tingkat kematangan keamanan siber minimum level 3 (terdefinisi)',
      'Persentase PSE sektor Perekonomian manusia dengan tingkat kematangan keamanan siber minimum level 3 (terdefinisi)',
      'Persentase penyelenggara persandiansektor Pemerintahan dengan tingkat kematangan persandian minimal level III',
      'Nilai kematangan keamanan siber PSE (amanah RPJMN)',
      'Nilai kematangan keamanan siber PSE (amanah RPJMN)',
      'Nilai kematangan penyelenggaraan persandian (amanah RPJMN)'
    ],
    periode: ['2026','2026','2026','2026','2026','2026'],
    pengampuh: ['D3', 'D4', 'D3', 'D4', 'D3', 'D4', 'D4'],
  }
])

const penerjemahintermediateoutcomelv3List = ref([
  {
    id: 1,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.1',
    kode_int_lv3: 'Int.0.1.1.1',
    sasaran : 'Meningkatnya kematangan keamanan siber dan sandi PSE sektor Pemerintah',
    iksp: [
      'Nilai kematangan keamanan siber PSE sektor pemerintahan pusat',
      'Nilai kematangan penyelenggara persandian PSE sektor pemerintahan pusat'
    ],
    periode: ['2026','2026'],
    pengampuh: ['D31', 'D31'],
  },
  {
    id: 2,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.1',
    kode_int_lv3: 'Int.0.1.1.2',
    sasaran : 'Meningkatnya kematangan keamanan siber dan sandi PSE sektor Pemerintah',
    iksp: [
      'Nilai kematangan keamanan siber PSE sektor pemerintahan pusat',
      'Nilai kematangan penyelenggara persandian PSE sektor pemerintahan pusat'
    ],
    periode: ['2026','2026'],
    pengampuh: ['D31', 'D31'],
  },
  {
    id: 3,
    kode_uo: 'UO 1',
    kode_int_lv1: 'Int.0.1',
    kode_int_lv2: 'Int.0.1.1',
    kode_int_lv3: 'Int.0.1.1.3',
    sasaran : 'Meningkatnya kematangan keamanan siber dan sandi PSE sektor pembangunan manusia',
    iksp: [
      'Nilai kematangan keamanan siber PSE sektor pemerintahan pusat',
      'Nilai kematangan penyelenggara persandian PSE sektor pemerintahan pusat'
    ],
    periode: ['2026','2026'],
    pengampuh: ['D31', 'D31'],
  },
])

const activeList = computed(() => {
  switch (activeTab.value) {
    case 'finaloutcome':
      return finaloutcome.value
    case 'intermediateoutcomelv1':
      return intermediateOutcomeLv1List.value
    case 'intermediateoutcomelv2':
      return intermediateOutcomeLv2List.value
    case 'intermediateoutcomelv3':
      return intermediateOutcomeLv3List.value
    case 'output':
      return outputList.value
    case 'penerjemahintermediateoutcomelv2':
      return penerjemahintermediateoutcomelv2List.value
    case 'penerjemahintermediateoutcomelv3':
      return penerjemahintermediateoutcomelv3List.value
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