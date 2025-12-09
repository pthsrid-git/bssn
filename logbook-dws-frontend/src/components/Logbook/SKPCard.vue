<template>
  <div>
    <!-- List View -->
    <div v-if="!showDetail">
      <!-- Title -->
      <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-6">
        Sasaran Kerja Pegawai (SKP)
      </h1>

      <!-- Divider -->
      <div class="border-t border-gray-200 my-6"></div>

      <!-- Data Profil Section -->
      <div class="mb-8">
        <h2 class="text-base font-semibold text-gray-900 mb-4">Data Profil</h2>
        
        <!-- Desktop Profile Grid - Horizontal Layout -->
        <div class="hidden md:flex items-center justify-between bg-white border-b border-gray-200 pb-6">
          <div class="flex gap-8">
            <div>
              <div class="text-xs text-gray-500 mb-1">Nama</div>
              <div class="text-sm font-medium text-gray-900">{{ userData?.name || 'Moh. Kemal Hibatullah Ammar' }}</div>
            </div>
            <div>
              <div class="text-xs text-gray-500 mb-1">NIP</div>
              <div class="text-sm font-medium text-gray-900">{{ userData?.nip_nrp || '199908292022031001' }}</div>
            </div>
            <div>
              <div class="text-xs text-gray-500 mb-1">Jenis Pegawai</div>
              <div class="text-sm font-medium text-gray-900">{{ userData?.jenis_pegawai || 'Pegawai' }}</div>
            </div>
            <div>
              <div class="text-xs text-gray-500 mb-1">Unit Kerja</div>
              <div class="text-sm font-medium text-gray-900">{{ userData?.nama_unit_organisasi || 'Biro Organisasi dan Sumber Daya Manusia' }}</div>
            </div>
          </div>
          <div>
            <button 
              @click="syncEkinerja"
              class="flex items-center gap-2 bg-yellow-50 text-yellow-700 px-4 py-2 rounded-lg text-sm font-medium border border-yellow-200 hover:bg-yellow-100 transition-colors whitespace-nowrap"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
              Sinkronasi ekinerja BKN
            </button>
          </div>
        </div>

        <!-- Mobile Profile Cards -->
        <div class="md:hidden space-y-3">
          <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Nama</div>
            <div class="text-sm font-medium text-gray-900">{{ userData?.name || 'Moh. Kemal Hibatullah Ammar' }}</div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">NIP</div>
            <div class="text-sm font-medium text-gray-900">{{ userData?.nip || '199908292022031001' }}</div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Jenis Pegawai</div>
            <div class="text-sm font-medium text-gray-900">{{ userData?.jenis_pegawai || 'Pegawai' }}</div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="text-xs text-gray-500 mb-1">Unit Kerja</div>
            <div class="text-sm font-medium text-gray-900">{{ userData?.unit_kerja || 'Biro Organisasi dan Sumber Daya Manusia' }}</div>
          </div>
          <button 
            @click="syncEkinerja"
            class="w-full flex items-center justify-center gap-2 bg-yellow-50 text-yellow-700 px-3 py-2.5 rounded-lg text-sm font-medium border border-yellow-200"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Sinkronasi ekinerja BKN
          </button>
        </div>
      </div>

      <!-- Daftar Sasaran Kerja Pegawai -->
      <div>
        <h2 class="text-base font-semibold text-gray-900 mb-4">
          Daftar Sasaran Kerja Pegawai (SKP)
        </h2>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-12">
          <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-gray-900"></div>
          <p class="mt-3 text-gray-600">Memuat data...</p>
        </div>

        <!-- Desktop Table -->
        <div v-else class="hidden lg:block overflow-x-auto bg-white border border-gray-200 rounded-lg">
          <table class="w-full">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Periode</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Pendekatan</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Unit Kerja</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Status Pegawai</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Status</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Jabatan</th>
                <th class="px-4 py-3 text-center text-xs font-medium text-gray-600">Keterangan</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Jenis Pegawai</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="skp in skpData" :key="skp.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 text-sm text-gray-900">{{ skp.periode }}</td>
                <td class="px-4 py-3">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                    {{ skp.pendekatan }}
                  </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ skp.unitKerja }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ skp.statusPegawai }}</td>
                <td class="px-4 py-3">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700">
                    {{ skp.status }}
                  </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ skp.jabatan }}</td>
                <td class="px-4 py-3 text-sm text-gray-500 text-center">{{ skp.keterangan }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ skp.jenisPegawai }}</td>
                <td class="px-4 py-3">
                  <button 
                    @click="viewDetail(skp.id)"
                    class="px-4 py-1.5 border-2 border-blue-500 text-blue-500 rounded-xl text-xs font-medium hover:bg-blue-500 hover:text-white transition-colors whitespace-nowrap"
                  >
                    Lihat detail
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Mobile/Tablet Cards -->
        <div v-if="!loading" class="lg:hidden space-y-4">
          <div 
            v-for="skp in skpData" 
            :key="skp.id"
            class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
          >
            <div class="space-y-3">
              <div>
                <div class="text-xs text-gray-500 mb-1">Periode</div>
                <div class="text-sm font-medium text-gray-900">{{ skp.periode }}</div>
              </div>
              
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <div class="text-xs text-gray-500 mb-1">Pendekatan</div>
                  <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-blue-50 text-blue-700">
                    {{ skp.pendekatan }}
                  </span>
                </div>
                <div>
                  <div class="text-xs text-gray-500 mb-1">Status</div>
                  <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-green-50 text-green-700">
                    {{ skp.status }}
                  </span>
                </div>
              </div>

              <div>
                <div class="text-xs text-gray-500 mb-1">Unit Kerja</div>
                <div class="text-sm text-gray-900">{{ skp.unitKerja }}</div>
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <div class="text-xs text-gray-500 mb-1">Status Pegawai</div>
                  <div class="text-sm text-gray-900">{{ skp.statusPegawai }}</div>
                </div>
                <div>
                  <div class="text-xs text-gray-500 mb-1">Jenis Pegawai</div>
                  <div class="text-sm text-gray-900">{{ skp.jenisPegawai }}</div>
                </div>
              </div>

              <div>
                <div class="text-xs text-gray-500 mb-1">Jabatan</div>
                <div class="text-sm text-gray-900">{{ skp.jabatan }}</div>
              </div>

              <button 
                @click="viewDetail(skp.id)"
                class="w-full mt-2 px-4 py-2 border-2 border-blue-500 text-blue-500 rounded-xl text-sm font-medium hover:bg-blue-500 hover:text-white transition-colors whitespace-nowrap"
              >
                Lihat detail
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Detail View -->
    <div v-else>
      <!-- Header -->
      <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
          <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Detail SKP</h1>
          <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-green-50 text-green-700 border border-green-200">
            Persetujuan
          </span>
          <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
            Kuantitatif
          </span>
        </div>
      </div>

      <!-- Info Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <!-- Pegawai yang dinilai -->
        <div class="bg-blue-50 rounded-lg p-4">
          <h3 class="text-sm font-semibold text-gray-900 mb-3">Pegawai yang dinilai</h3>
          <div class="space-y-2">
            <div class="flex">
              <span class="text-xs text-gray-600 w-32">Nama</span>
              <span class="text-xs text-gray-900 font-medium">{{ skpDetail.pegawai.nama }}</span>
            </div>
            <div class="flex">
              <span class="text-xs text-gray-600 w-32">NIP</span>
              <span class="text-xs text-gray-900 font-medium">{{ skpDetail.pegawai.nip }}</span>
            </div>
            <div class="flex">
              <span class="text-xs text-gray-600 w-32">Pangkat / Gol. Ruang</span>
              <span class="text-xs text-gray-900 font-medium">{{ skpDetail.pegawai.pangkat }}</span>
            </div>
            <div class="flex">
              <span class="text-xs text-gray-600 w-32">Jabatan</span>
              <span class="text-xs text-gray-900 font-medium">{{ skpDetail.pegawai.jabatan }}</span>
            </div>
            <div class="flex">
              <span class="text-xs text-gray-600 w-32">Unit Kerja</span>
              <div class="flex-1">
                <span class="text-xs text-gray-900 font-medium">{{ skpDetail.pegawai.unitKerja }}</span>
                <div class="text-xs text-gray-500 mt-1">ID : {{ skpDetail.pegawai.unitKerjaId }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pejabat penilai kinerja -->
        <div class="bg-blue-50 rounded-lg p-4">
          <h3 class="text-sm font-semibold text-gray-900 mb-3">Pejabat penilai kinerja</h3>
          <div class="space-y-2">
            <div class="flex">
              <span class="text-xs text-gray-600 w-32">Nama</span>
              <span class="text-xs text-gray-900 font-medium">{{ skpDetail.penilai.nama }}</span>
            </div>
            <div class="flex">
              <span class="text-xs text-gray-600 w-32">NIP</span>
              <span class="text-xs text-gray-900 font-medium">{{ skpDetail.penilai.nip }}</span>
            </div>
            <div class="flex">
              <span class="text-xs text-gray-600 w-32">Pangkat / Gol. Ruang</span>
              <span class="text-xs text-gray-900 font-medium">{{ skpDetail.penilai.pangkat }}</span>
            </div>
            <div class="flex">
              <span class="text-xs text-gray-600 w-32">Jabatan</span>
              <span class="text-xs text-gray-900 font-medium">{{ skpDetail.penilai.jabatan }}</span>
            </div>
            <div class="flex">
              <span class="text-xs text-gray-600 w-32">Unit Kerja</span>
              <div class="flex-1">
                <span class="text-xs text-gray-900 font-medium">{{ skpDetail.penilai.unitKerja }}</span>
                <div class="text-xs text-gray-500 mt-1">ID : {{ skpDetail.penilai.unitKerjaId }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Hasil Kerja Section -->
      <div>
        <h3 class="text-base font-semibold text-gray-900 mb-4">Hasil Kerja</h3>
        
        <!-- Tabs -->
        <div class="border-b border-gray-200 mb-4">
          <div class="flex gap-6">
            <button 
              @click="activeTab = 'utama'"
              :class="[
                'pb-3 text-sm font-medium border-b-2 transition-colors',
                activeTab === 'utama' 
                  ? 'border-blue-500 text-blue-600' 
                  : 'border-transparent text-gray-600 hover:text-gray-900'
              ]"
            >
              Utama
            </button>
            <button 
              @click="activeTab = 'tambahan'"
              :class="[
                'pb-3 text-sm font-medium border-b-2 transition-colors',
                activeTab === 'tambahan' 
                  ? 'border-blue-500 text-blue-600' 
                  : 'border-transparent text-gray-600 hover:text-gray-900'
              ]"
            >
              Tambahan
            </button>
            <button 
              @click="activeTab = 'perilaku'"
              :class="[
                'pb-3 text-sm font-medium border-b-2 transition-colors',
                activeTab === 'perilaku' 
                  ? 'border-blue-500 text-blue-600' 
                  : 'border-transparent text-gray-600 hover:text-gray-900'
              ]"
            >
              Perilaku Kerja
            </button>
            <button 
              @click="activeTab = 'lampiran'"
              :class="[
                'pb-3 text-sm font-medium border-b-2 transition-colors',
                activeTab === 'lampiran' 
                  ? 'border-blue-500 text-blue-600' 
                  : 'border-transparent text-gray-600 hover:text-gray-900'
              ]"
            >
              Lampiran
            </button>
          </div>
        </div>

        <!-- Table Content -->
        <div v-if="activeTab === 'utama'">
          <!-- Desktop Table -->
          <div class="hidden lg:block overflow-hidden rounded-lg border border-gray-200">
            <table class="w-full">
              <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 w-12">No</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Rencana Hasil Kerja Pimpinan yang Diintervensi</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Rencana Hasil Kerja</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Aspek</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Indikator Kinerja Individu</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Target Tahunan</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="(item, index) in skpDetail.hasilKerja" :key="index" class="hover:bg-gray-50">
                  <td class="px-4 py-4 text-sm text-gray-900">{{ index + 1 }}</td>
                  <td class="px-4 py-4 text-sm text-gray-900">{{ item.rencanaPimpinan }}</td>
                  <td class="px-4 py-4 text-sm text-gray-900">{{ item.rencanaHasil }}</td>
                  <td class="px-4 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700">
                      {{ item.aspek }}
                    </span>
                  </td>
                  <td class="px-4 py-4 text-sm text-gray-900">{{ item.indikator }}</td>
                  <td class="px-4 py-4 text-sm text-blue-600 font-medium">{{ item.target }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Mobile/Tablet Cards -->
          <div class="lg:hidden space-y-4">
            <div 
              v-for="(item, index) in skpDetail.hasilKerja" 
              :key="index"
              class="bg-white border border-gray-200 rounded-lg p-4"
            >
              <div class="flex justify-between items-start mb-3">
                <span class="text-sm font-semibold text-gray-900">No. {{ index + 1 }}</span>
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-green-50 text-green-700">
                  {{ item.aspek }}
                </span>
              </div>
              
              <div class="space-y-3">
                <div>
                  <div class="text-xs text-gray-500 mb-1">Rencana Hasil Kerja Pimpinan yang Diintervensi</div>
                  <div class="text-sm text-gray-900">{{ item.rencanaPimpinan }}</div>
                </div>
                
                <div>
                  <div class="text-xs text-gray-500 mb-1">Rencana Hasil Kerja</div>
                  <div class="text-sm text-gray-900">{{ item.rencanaHasil }}</div>
                </div>
                
                <div>
                  <div class="text-xs text-gray-500 mb-1">Indikator Kinerja Individu</div>
                  <div class="text-sm text-gray-900">{{ item.indikator }}</div>
                </div>
                
                <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                  <div class="text-xs text-gray-500">Target Tahunan</div>
                  <div class="text-sm text-blue-600 font-semibold">{{ item.target }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tambahan Tab -->
        <div v-if="activeTab === 'tambahan'">
          <!-- Desktop Table -->
          <div class="hidden lg:block overflow-hidden rounded-lg border border-gray-200">
            <table class="w-full">
              <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 w-12">No</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Rencana Hasil Kerja Pimpinan yang Diintervensi</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Rencana Hasil Kerja</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Aspek</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Indikator Kinerja Individu</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Target Tahunan</th>
                </tr>
              </thead>
              <tbody class="bg-white">
                <tr>
                  <td colspan="6" class="px-4 py-12 text-center text-sm text-gray-500">
                    Tidak ada data
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Mobile/Tablet View -->
          <div class="lg:hidden bg-white border border-gray-200 rounded-lg">
            <div class="px-4 py-12 text-center text-sm text-gray-500">
              Tidak ada data
            </div>
          </div>
        </div>

        <!-- Perilaku Kerja Tab -->
        <div v-if="activeTab === 'perilaku'">
          <!-- Desktop Table -->
          <div class="hidden lg:block overflow-hidden rounded-lg border border-gray-200">
            <table class="w-full">
              <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 w-12">No</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Aspek</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Rincian</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">Ekspektasi Khusus Pimpinan</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="(item, index) in skpDetail.perilakuKerja" :key="index" class="hover:bg-gray-50">
                  <td class="px-4 py-4 text-sm text-gray-900">{{ index + 1 }}</td>
                  <td class="px-4 py-4 text-sm text-gray-900">{{ item.aspek }}</td>
                  <td class="px-4 py-4 text-sm text-gray-900">
                    <ol class="list-decimal list-inside space-y-1">
                      <li v-for="(rincian, idx) in item.rincian" :key="idx">{{ rincian }}</li>
                    </ol>
                  </td>
                  <td class="px-4 py-4 text-sm text-gray-500 text-center">{{ item.ekspektasi }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Mobile/Tablet Cards -->
          <div class="lg:hidden space-y-4">
            <div 
              v-for="(item, index) in skpDetail.perilakuKerja" 
              :key="index"
              class="bg-white border border-gray-200 rounded-lg p-4"
            >
              <div class="mb-3">
                <span class="text-sm font-semibold text-gray-900">{{ index + 1 }}. {{ item.aspek }}</span>
              </div>
              
              <div class="space-y-3">
                <div>
                  <div class="text-xs text-gray-500 mb-1">Rincian</div>
                  <ol class="list-decimal list-inside space-y-1 text-sm text-gray-900">
                    <li v-for="(rincian, idx) in item.rincian" :key="idx">{{ rincian }}</li>
                  </ol>
                </div>
                
                <div>
                  <div class="text-xs text-gray-500 mb-1">Ekspektasi Khusus Pimpinan</div>
                  <div class="text-sm text-gray-500">{{ item.ekspektasi }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Lampiran Tab -->
        <div v-if="activeTab === 'lampiran'">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Dukungan Sumber Daya -->
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
              <div class="bg-gray-100 px-4 py-3 border-b border-gray-200">
                <h4 class="text-sm font-medium text-gray-700">Dukungan Sumber Data</h4>
              </div>
              <div class="p-4 space-y-2">
                <div v-for="(item, index) in skpDetail.lampiran.dukunganSumberDaya" :key="index" class="text-sm text-gray-600">
                  {{ item }}
                </div>
              </div>
            </div>

            <!-- Skema Pertanggungjawaban -->
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
              <div class="bg-gray-100 px-4 py-3 border-b border-gray-200">
                <h4 class="text-sm font-medium text-gray-700">Skema Pertanggungjawaban</h4>
              </div>
              <div class="p-4 h-32 flex items-center justify-center">
                <span class="text-sm text-gray-400">-</span>
              </div>
            </div>

            <!-- Konsekuensi -->
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
              <div class="bg-gray-100 px-4 py-3 border-b border-gray-200">
                <h4 class="text-sm font-medium text-gray-700">Konsekuensi</h4>
              </div>
              <div class="p-4 h-32 flex items-center justify-center">
                <span class="text-sm text-gray-400">-</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

// Props
const props = defineProps({
  loading: {
    type: Boolean,
    default: false
  }
})

// State
const userData = ref(null)
const showDetail = ref(false)
const selectedSkpId = ref(null)
const activeTab = ref('utama')

const skpData = ref([
  {
    id: 1,
    periode: '1 Januari 2025 - 31 Desember 2025',
    pendekatan: 'Kuantitatif',
    unitKerja: 'Biro Organisasi dan Sumber Daya Manusia',
    statusPegawai: 'Definitif',
    status: 'Persetujuan',
    jabatan: 'Pranata Komputer Terampil',
    keterangan: '-',
    jenisPegawai: 'Pegawai'
  },
  {
    id: 2,
    periode: '1 November 2024 - 31 Desember 2024',
    pendekatan: 'Kuantitatif',
    unitKerja: 'Biro Organisasi dan Sumber Daya Manusia',
    statusPegawai: 'Definitif',
    status: 'Persetujuan',
    jabatan: 'Pranata Komputer Terampil',
    keterangan: '-',
    jenisPegawai: 'Pegawai'
  },
  {
    id: 3,
    periode: '1 Januari 2024 - 31 Oktober 2024',
    pendekatan: 'Kuantitatif',
    unitKerja: 'Biro Organisasi dan Sumber Daya Manusia',
    statusPegawai: 'Definitif',
    status: 'Persetujuan',
    jabatan: 'Pranata Komputer Terampil',
    keterangan: '-',
    jenisPegawai: 'Pegawai'
  },
  {
    id: 4,
    periode: '1 Januari 2023 - 31 Desember 2023',
    pendekatan: 'Kuantitatif',
    unitKerja: 'Biro Organisasi dan Sumber Daya Manusia',
    statusPegawai: 'Definitif',
    status: 'Persetujuan',
    jabatan: 'Pranata Komputer Terampil',
    keterangan: '-',
    jenisPegawai: 'Pegawai'
  }
])

const skpDetail = ref({
  pegawai: {
    nama: userData.value?.nama_pegawai,
    nip: userData.value?.nip_nrp,
    pangkat: userData.value?.pangkat_golongan,
    jabatan: userData.value?.nama_jabatan,
    unitKerja: userData.value?.nama_unit_organisasi,
    unitKerjaId: userData.value?.guid
  },
  penilai: {
    nama: 'ANTON MARTIN, S.E., M.H.',
    nip: '197006132000121001',
    pangkat: 'Pembina Utama Muda (IVc)',
    jabatan: 'KEPALA BIRO ORGANISASI DAN SUMBER DAYA MANUSIA',
    unitKerja: 'Biro Organisasi dan Sumber Daya Manusia',
    unitKerjaId: '8ae483c67b04ddcd017bf1aa8ec56dc2'
  },
  hasilKerja: [
    {
      rencanaPimpinan: 'Pengangkatan pegawai BSSN yang sesuai kebutuhan',
      rencanaHasil: 'Terpenuhinya KepKA BSSN tentang Pengangkatan CPNS Umum Tahun 2025',
      aspek: 'Kuantitas',
      indikator: 'Terpenuhinya Surat pengangkatan CPNS Umum Tahun 2025',
      target: '100%'
    },
    {
      rencanaPimpinan: 'Pengangkatan pegawai BSSN yang sesuai kebutuhan',
      rencanaHasil: 'Terpenuhinya KepKA BSSN tentang Pengangkatan CPNS Umum Tahun 2025',
      aspek: 'Kuantitas',
      indikator: 'Terpenuhinya Surat pengangkatan CPNS Umum Tahun 2025',
      target: '100%'
    },
    {
      rencanaPimpinan: 'Pengangkatan pegawai BSSN yang sesuai kebutuhan',
      rencanaHasil: 'Terpenuhinya KepKA BSSN tentang Pengangkatan CPNS Umum Tahun 2025',
      aspek: 'Kuantitas',
      indikator: 'Terpenuhinya Surat pengangkatan CPNS Umum Tahun 2025',
      target: '100%'
    },
    {
      rencanaPimpinan: 'Pengangkatan pegawai BSSN yang sesuai kebutuhan',
      rencanaHasil: 'Terpenuhinya KepKA BSSN tentang Pengangkatan CPNS Umum Tahun 2025',
      aspek: 'Kuantitas',
      indikator: 'Terpenuhinya Surat pengangkatan CPNS Umum Tahun 2025',
      target: '100%'
    },
    {
      rencanaPimpinan: 'Pengangkatan pegawai BSSN yang sesuai kebutuhan',
      rencanaHasil: 'Terpenuhinya KepKA BSSN tentang Pengangkatan CPNS Umum Tahun 2025',
      aspek: 'Kuantitas',
      indikator: 'Terpenuhinya Surat pengangkatan CPNS Umum Tahun 2025',
      target: '100%'
    },
    {
      rencanaPimpinan: 'Pengangkatan pegawai BSSN yang sesuai kebutuhan',
      rencanaHasil: 'Terpenuhinya KepKA BSSN tentang Pengangkatan CPNS Umum Tahun 2025',
      aspek: 'Kuantitas',
      indikator: 'Terpenuhinya Surat pengangkatan CPNS Umum Tahun 2025',
      target: '100%'
    }
  ],
  perilakuKerja: [
    {
      aspek: 'Berorientasi Pelayanan',
      rincian: [
        'Memahami dan memenuhi kebutuhan masyarakat',
        'Ramah, cekatan, solutif, dan dapat diandalkan',
        'Melakukan perbaikan tiada henti'
      ],
      ekspektasi: '-'
    },
    {
      aspek: 'Akuntabel',
      rincian: [
        'Melaksanakan tugas dengan jujur bertanggung jawab cermat disiplin dan berintegritas tinggi',
        'Menggunakan kekayaan dan barang milik negara secara bertanggung jawab efektif dan efisien',
        'Tidak menyalahgunakan kewenangan jabatan'
      ],
      ekspektasi: '-'
    },
    {
      aspek: 'Kompeten',
      rincian: [
        'Meningkatkan kompetensi diri untuk menjawab tantangan yang selalu berubah',
        'Membantu orang lain belajar',
        'Melaksanakan tugas dengan kualitas terbaik'
      ],
      ekspektasi: '-'
    },
    {
      aspek: 'Harmonis',
      rincian: [
        'Menghargai setiap orang apapun latar belakangnya',
        'Suka menolong orang lain',
        'Membangun lingkungan kerja yang kondusif'
      ],
      ekspektasi: '-'
    },
    {
      aspek: 'Loyal',
      rincian: [
        'Memegang teguh ideologi Pancasila, Undang-Undang Dasar Negara Republik Indonesia Tahun 1945, setia pada NKRI serta pemerintahan yang sah',
        'Menjaga nama baik sesama ASN, Pimpinan, Instansi dan Negara',
        'Menjaga rahasia jabatan dan negara'
      ],
      ekspektasi: '-'
    },
    {
      aspek: 'Adaptif',
      rincian: [
        'Cepat menyesuaikan diri menghadapi perubahan',
        'Terus berinovasi dan mengembangkan kreativitas',
        'Bertindak proaktif'
      ],
      ekspektasi: '-'
    },
    {
      aspek: 'Kolaboratif',
      rincian: [
        'Memberi kesempatan kepada berbagai pihak untuk berkontribusi',
        'Terbuka dalam bekerja sama untuk menghasilkan nilai tambah',
        'Menggerakkan pemanfaatan berbagai sumberdaya untuk tujuan bersama'
      ],
      ekspektasi: '-'
    }
  ],
  lampiran: {
    dukunganSumberDaya: [
      'POK Biro OSDM Tahun 2025',
      'Program Kerja Biro OSDM Tahun 2025'
    ]
  }
})

// Methods
const syncEkinerja = () => {
  console.log('Sinkronisasi dengan ekinerja BKN')
}

const viewDetail = (id) => {
  selectedSkpId.value = id
  showDetail.value = true
  activeTab.value = 'utama'
}

const backToList = () => {
  showDetail.value = false
  selectedSkpId.value = null
}

// Lifecycle
onMounted(() => {
  // Note: In a real application with API integration, you would fetch user data like this:
  // Example data structure from database:
  // userData.value = {
  //   id: 2,
  //   name: "MASRUHAN KHOTIB, S.Tr.TP",
  //   nama_pegawai: "MASRUHAN KHOTIB, S.Tr.TP",
  //   nip_nrp: "199908292022031001",
  //   email: "yyyy.yyyy@bssn.go.id",
  //   fpid: "yyyy",
  //   golongan: "II/c",
  //   pangkat_golongan: "Pengatur (II/c)",
  //   kelas: "6",
  //   nama_jabatan: "Pengolah Data dan Informasi",
  //   nama_lengkap_peta_jabatan: "Pengolah Data dan Informasi pada Pusat Data dan Teknologi Informasi Komunikasi, Badan Siber dan Sandi Negara",
  //   kode_peta_jabatan: "LAK-LAHDATIN.P2",
  //   nama_unit_organisasi: "Pusat Data dan Teknologi Informasi Komunikasi, Badan Siber dan Sandi Negara",
  //   kode_unit_organisasi: "P2",
  //   guid: "f66f0271-628c-4f66-990c-4672663e1626",
  //   created_at: "2025-12-06T18:21:14.000000Z",
  //   updated_at: "2025-12-06T18:21:14.000000Z"
  // }
  
  // For demo purposes, using default data
  userData.value = {
    name: "MASRUHAN KHOTIB, S.Tr.TP",
    nama_pegawai: "MASRUHAN KHOTIB, S.Tr.TP",
    nip_nrp: "199908292022031001",
    pangkat_golongan: "Pengatur (II/c)",
    nama_jabatan: "Pengolah Data dan Informasi",
    nama_unit_organisasi: "Pusat Data dan Teknologi Informasi Komunikasi, Badan Siber dan Sandi Negara",
    guid: "f66f0271-628c-4f66-990c-4672663e1626"
  }
  
  // Update skpDetail with userData
  skpDetail.value.pegawai = {
    nama: userData.value.nama_pegawai || userData.value.name,
    nip: userData.value.nip_nrp,
    pangkat: userData.value.pangkat_golongan,
    jabatan: userData.value.nama_jabatan,
    unitKerja: userData.value.nama_unit_organisasi,
    unitKerjaId: userData.value.guid
  }
})
</script>