<template>
  <!-- Modal Overlay -->
  <div 
    v-if="show" 
    class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center overflow-y-auto py-8"
    @click.self="closeModal"
  >
    <!-- Modal Content -->
    <div class="bg-white rounded-xl shadow-xl w-full max-w-6xl mx-4 my-8">
      <!-- Modal Header -->
      <div class="flex items-center justify-between px-6 py-4 border-b">
        <h2 class="text-xl font-semibold text-gray-900">Detail SKP</h2>
        <button 
          @click="closeModal"
          class="text-gray-400 hover:text-gray-600 transition-colors"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- Status Badges -->
      <div class="px-6 py-4 flex gap-3">
        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-green-50 text-green-700 border border-green-200">
          Persetujuan
        </span>
        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
          Kuantitatif
        </span>
      </div>

      <!-- Modal Body -->
      <div class="px-6 py-4">
        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
          <!-- Pegawai yang dinilai -->
          <div class="bg-blue-50 rounded-lg p-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Pegawai yang dinilai</h3>
            <div class="space-y-2">
              <div class="flex">
                <span class="text-xs text-gray-600 w-32">Nama</span>
                <span class="text-xs text-gray-900 font-medium">{{ userData.nama_pegawai }}</span>
              </div>
              <div class="flex">
                <span class="text-xs text-gray-600 w-32">NIP</span>
                <span class="text-xs text-gray-900 font-medium">{{ userData.nip_nrp }}</span>
              </div>
              <div class="flex">
                <span class="text-xs text-gray-600 w-32">Pangkat / Gol. Ruang</span>
                <span class="text-xs text-gray-900 font-medium">{{ userData.pangkat_golongan || '-' }}</span>
              </div>
              <div class="flex">
                <span class="text-xs text-gray-600 w-32">Jabatan</span>
                <span class="text-xs text-gray-900 font-medium">{{ userData.nama_jabatan || '-' }}</span>
              </div>
              <div class="flex">
                <span class="text-xs text-gray-600 w-32">Unit Kerja</span>
                <div class="flex-1">
                  <span class="text-xs text-gray-900 font-medium">{{ userData.nama_unit_organisasi || '-' }}</span>
                  <div class="text-xs text-gray-500 mt-1">ID : {{ userData.guid || '-' }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Pejabat penilai kinerja -->
          <div class="bg-blue-50 rounded-lg p-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Pejabat penilai kinerja</h3>
            <div v-if="userData.parent" class="space-y-2">
              <div class="flex">
                <span class="text-xs text-gray-600 w-32">Nama</span>
                <span class="text-xs text-gray-900 font-medium">{{ userData.parent.nama_pegawai }}</span>
              </div>
              <div class="flex">
                <span class="text-xs text-gray-600 w-32">NIP</span>
                <span class="text-xs text-gray-900 font-medium">{{ userData.parent.nip_nrp || '-' }}</span>
              </div>
              <div class="flex">
                <span class="text-xs text-gray-600 w-32">Pangkat / Gol. Ruang</span>
                <span class="text-xs text-gray-900 font-medium">{{ userData.parent.pangkat_golongan || '-' }}</span>
              </div>
              <div class="flex">
                <span class="text-xs text-gray-600 w-32">Jabatan</span>
                <span class="text-xs text-gray-900 font-medium">{{ userData.parent.nama_jabatan || '-' }}</span>
              </div>
              <div class="flex">
                <span class="text-xs text-gray-600 w-32">Unit Kerja</span>
                <div class="flex-1">
                  <span class="text-xs text-gray-900 font-medium">{{ userData.parent.nama_unit_organisasi || '-' }}</span>
                  <div class="text-xs text-gray-500 mt-1">ID : {{ userData.parent.kode_unit_organisasi || '-' }}</div>
                </div>
              </div>
            </div>
            <div v-else class="text-xs text-gray-500 text-center py-4">
              Tidak ada pejabat penilai
            </div>
          </div>
        </div>

        <!-- Hasil Kerja Section -->
        <div class="mb-6">
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
          <div v-if="activeTab === 'utama'" class="overflow-x-auto">
            <table class="w-full border border-gray-200 rounded-lg">
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
              <tbody class="divide-y divide-gray-200">
                <tr v-for="(item, index) in skpDetail.hasilKerja" :key="index" class="hover:bg-gray-50">
                  <td class="px-4 py-3 text-sm text-gray-900">{{ index + 1 }}</td>
                  <td class="px-4 py-3 text-sm text-gray-900">{{ item.rencanaPimpinan }}</td>
                  <td class="px-4 py-3 text-sm text-gray-900">{{ item.rencanaHasil }}</td>
                  <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700">
                      {{ item.aspek }}
                    </span>
                  </td>
                  <td class="px-4 py-3 text-sm text-gray-900">{{ item.indikator }}</td>
                  <td class="px-4 py-3 text-sm text-blue-600 font-medium">{{ item.target }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Other tabs content placeholder -->
          <div v-if="activeTab === 'tambahan'" class="text-center py-8 text-gray-500">
            Tidak ada data tambahan
          </div>
          <div v-if="activeTab === 'perilaku'" class="text-center py-8 text-gray-500">
            Tidak ada data perilaku kerja
          </div>
          <div v-if="activeTab === 'lampiran'" class="text-center py-8 text-gray-500">
            Tidak ada lampiran
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="px-6 py-4 border-t bg-gray-50 rounded-b-xl flex justify-end gap-3">
        <button 
          @click="closeModal"
          class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors"
        >
          Tutup
        </button>
        <button 
          class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors"
        >
          Cetak SKP
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  skpId: {
    type: Number,
    default: null
  }
})

const emit = defineEmits(['close'])

const activeTab = ref('utama')

// Get user data from localStorage
const userData = computed(() => {
  const userStr = localStorage.getItem('user')
  if (userStr) {
    try {
      return JSON.parse(userStr)
    } catch (e) {
      console.error('Error parsing user data:', e)
      return {}
    }
  }
  return {}
})

// Dummy data untuk hasil kerja
const skpDetail = ref({
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
    }
  ]
})

const getRoleName = (role) => {
  const roleNames = {
    'ka-unit': 'Kepala Unit',
    'pmk': 'Pejabat Manajemen Kinerja',
    'pko': 'Pegawai Karier/Operasional',
    'admin': 'Administrator'
  }
  return roleNames[role] || role
}

const closeModal = () => {
  emit('close')
}

// Optional: Load SKP data dari API jika diperlukan
onMounted(() => {
  if (props.skpId) {
    // Fetch SKP detail dari API
    // await fetchSKPDetail(props.skpId)
  }
})
</script>