<!-- logbook-dws-frontend/src/views/LogbookKatim.vue -->
<template>
  <div class="bg-white rounded-xl shadow-sm p-6">
    <!-- Header -->
    <div class="flex items-start gap-4 pb-6 border-b border-gray-200">
    <button 
        v-if="selectedMember"
        @click="handleBackToList"
        class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors mt-1"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>

    <div>
        <h1 class="text-xl font-semibold text-gray-900">
        {{ selectedMember ? selectedMember.nama_pegawai : 'Logbook Tim (Katim)' }}
        </h1>
        <div v-if="selectedMember" class="mt-1 text-sm text-gray-600 flex gap-2 flex-wrap">
            <span>{{ selectedMember.nip_nrp }}</span>
            <span> | </span>
            <span>{{ selectedMember.pangkat_golongan }}</span>
            <span> | </span>
            <span>{{ selectedMember.nama_jabatan }}</span>
            </div>
        </div>
    </div>

    <!-- View: Daftar Anggota Tim -->
    <div v-if="!selectedMember">
      <!-- Data Tim Section -->
      <div class="py-6 border-b border-gray-200">
        <h2 class="text-sm font-medium text-gray-500 mb-4">Data Tim</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div>
            <p class="text-xs text-gray-400 mb-1">Nama</p>
            <p class="text-sm text-gray-900">{{ userData?.nama_pegawai || '-' }}</p>
          </div>
          <div>
            <p class="text-xs text-gray-400 mb-1">Jumlah Anggota</p>
            <p class="text-sm text-gray-900">{{ teamMembers.length }}</p>
          </div>
          <div>
            <p class="text-xs text-gray-400 mb-1">Unit Kerja</p>
            <p class="text-sm text-gray-900">{{ userData?.nama_unit_organisasi || '-' }}</p>
          </div>
        </div>
      </div>

      <!-- Daftar Logbook Anggota Tim -->
      <div class="pt-6">
        <h2 class="text-sm font-medium text-gray-700 mb-4">Daftar Logbook Anggota Tim</h2>

        <!-- Loading State -->
        <div v-if="loading" class="min-h-[400px] flex items-center justify-center">
          <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-[#19203B] mb-4"></div>
            <p class="text-gray-600">Memuat data anggota tim...</p>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else-if="filteredMembers.length === 0" class="min-h-[400px] flex items-center justify-center">
          <div class="text-center">
            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Tidak Ada Anggota Tim</h2>
            <p class="text-gray-600">Belum ada anggota yang terdaftar di bawah supervisi Anda</p>
          </div>
        </div>

        <!-- Table -->
        <div v-else class="overflow-x-auto border border-gray-200 rounded-xl">
          <table class="w-full">
            <thead>
              <tr class="border-b border-gray-200 bg-gray-50">
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 w-16">No</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500">Nama</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500">NIP</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500">Pangkat</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500">Jabatan</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 w-40">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr 
                v-for="(member, index) in filteredMembers" 
                :key="member.id"
                class="hover:bg-gray-50 transition-colors"
              >
                <td class="px-6 py-4 text-sm text-gray-600">{{ index + 1 }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ member.nama_pegawai }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ member.nip_nrp }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ member.pangkat_golongan }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ member.nama_jabatan }}</td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center h-full">
                    <button 
                      @click="handleViewLogbook(member)"
                      type="button" 
                      class="btn btn-outline-primary btn-sm w-32"
                    >
                      Lihat logbook
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- View: Detail Logbook Anggota -->
    <div v-else>
      <!-- Filter dan Toolbar -->
      <div class="py-6 flex items-center gap-4">
        <!-- Date Picker -->
        <div class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg">
          <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          <input 
            v-model="selectedMonth"
            @change="handleMonthChange"
            type="month"
            class="text-sm focus:outline-none"
          />
        </div>

        <div class="flex-1"></div>

        <!-- Filter Button -->
        <button class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
          </svg>
          <span class="text-sm">Filter</span>
        </button>

        <!-- Search Button -->
        <button class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <span class="text-sm">Cari...</span>
        </button>
      </div>

      <!-- Logbook Entries -->
      <div class="space-y-3">
        <!-- Loading State -->
        <div v-if="loadingEntries" class="min-h-[400px] flex items-center justify-center">
          <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-[#19203B] mb-4"></div>
            <p class="text-gray-600">Memuat logbook...</p>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else-if="logbookEntries.length === 0" class="min-h-[200px] flex items-center justify-center">
          <div class="text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Data Logbook</h2>
            <p class="text-gray-600">Belum ada entri logbook untuk periode yang dipilih</p>
          </div>
        </div>

        <!-- Logbook Cards -->
        <LogbookKatimEntryCard
          v-for="entry in logbookEntries"
          :key="entry.id"
          :entry="entry"
          @detail="handleViewDetail"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { logbookKatimService } from '../services/Logbook/logbookKatimService'
import LogbookKatimEntryCard from '../components/Logbook/LogbookKatimEntryCard.vue'

const router = useRouter()

// ==================== STATE ====================
const loading = ref(false)
const loadingEntries = ref(false)
const userData = ref(null)
const teamMembers = ref([])
const searchQuery = ref('')
const selectedMember = ref(null)
const logbookEntries = ref([])
const selectedMonth = ref(getCurrentMonth())

// ==================== COMPUTED ====================
const filteredMembers = computed(() => {
  if (!searchQuery.value) {
    return teamMembers.value
  }
  
  const query = searchQuery.value.toLowerCase()
  return teamMembers.value.filter(member => 
    member.nama_pegawai?.toLowerCase().includes(query) ||
    member.nip_nrp?.toLowerCase().includes(query) ||
    member.nama_jabatan?.toLowerCase().includes(query)
  )
})

// ==================== METHODS ====================
function getCurrentMonth() {
  const now = new Date()
  const year = now.getFullYear()
  const month = String(now.getMonth() + 1).padStart(2, '0')
  return `${year}-${month}`
}

const fetchUserData = async () => {
  try {
    const storedUser = localStorage.getItem('user')
    if (storedUser) {
      userData.value = JSON.parse(storedUser)
    }
  } catch (error) {
    console.error('Error fetching user data:', error)
  }
}

const fetchTeamMembers = async () => {
  loading.value = true
  try {
    const response = await logbookKatimService.getTeamMembers()
    if (response.success) {
      teamMembers.value = response.data
    }
  } catch (error) {
    console.error('Error fetching team members:', error)
    teamMembers.value = []
  } finally {
    loading.value = false
  }
}

const fetchMemberLogbook = async (memberId) => {
  loadingEntries.value = true
  try {
    const [year, month] = selectedMonth.value.split('-')
    
    const response = await logbookKatimService.getMemberLogs(memberId, {
      bulan: parseInt(month),
      tahun: parseInt(year)
    })
    
    if (response.success) {
      // Transform data dari API ke format yang dibutuhkan card
      logbookEntries.value = transformLogbookData(response.data)
    }
  } catch (error) {
    console.error('Error fetching member logbook:', error)
    logbookEntries.value = []
  } finally {
    loadingEntries.value = false
  }
}

const transformLogbookData = (data) => {
  if (!data || data.length === 0) return []
  
  return data.map(log => {
    // Cek apakah hari libur atau tidak ada aktivitas
    if (log.is_weekend || log.is_holiday || !log.id) {
      return {
        id: log.tanggal,
        date: log.tanggal,
        isEmpty: true,
        message: log.keterangan || 'Tidak ada aktivitas'
      }
    }
    
    // Format waktu
    const timeStart = log.waktu_mulai ? log.waktu_mulai.substring(0, 5) : '00:00'
    const timeEnd = log.waktu_selesai ? log.waktu_selesai.substring(0, 5) : '00:00'
    
    return {
      id: log.id,
      date: log.tanggal,
      isEmpty: false,
      time: `${timeStart} - ${timeEnd}`,
      files: log.jumlah_file ? `${log.jumlah_file} file` : '0 file',
      status: log.status || 'Draft',
      rencana_hasil_kinerja: log.rencana_hasil_kinerja || '-',
      indikator_hasil: log.indikator_hasil || '-',
      aktivitas_harian: log.aktivitas_harian || '-',
      keterangan: log.keterangan || '-',
      catatan_katim: log.catatan_katim || '-',
      catatan_atasan: log.catatan_atasan || '-'
    }
  })
}

const handleViewLogbook = (member) => {
  selectedMember.value = member
  fetchMemberLogbook(member.id)
}

const handleBackToList = () => {
  selectedMember.value = null
  logbookEntries.value = []
  selectedMonth.value = getCurrentMonth()
}

const handleMonthChange = () => {
  if (selectedMember.value) {
    fetchMemberLogbook(selectedMember.value.id)
  }
}

const handleViewDetail = (entry) => {
  // TODO: Implementasi view detail - bisa buka modal atau navigate ke halaman detail
  console.log('View detail:', entry)
  // Contoh navigasi ke detail page
  // router.push({ name: 'LogbookDetail', params: { id: entry.id } })
}

// ==================== LIFECYCLE ====================
onMounted(() => {
  fetchUserData()
  fetchTeamMembers()
})
</script>

<style scoped>
.btn {
  @apply px-4 py-2 rounded-lg font-medium transition-all;
}

.btn-outline-primary {
  @apply border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white;
}

.btn-sm {
  @apply text-xs px-3 py-1.5;
}
</style>