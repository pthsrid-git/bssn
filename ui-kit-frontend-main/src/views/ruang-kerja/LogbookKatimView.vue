<template>
  <Card>
    <!-- Header -->
    <div class="border-b border-gray-200 pb-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Logbook Tim (Katim)</h1>

        <hr class="my-4 border-gray-200" />

        <div class="mt-4 inline-grid grid-cols-1 md:grid-flow-col md:auto-cols-max gap-8">
            <div class="flex flex-col whitespace-nowrap">
            <span class="text-sm font-medium text-gray-500">Nama:</span>
            <span class="text-sm text-gray-900">{{ user?.fullname }}</span>
            </div>

            <div class="flex flex-col whitespace-nowrap">
            <span class="text-sm font-medium text-gray-500">Jumlah Anggota:</span>
            <span class="text-sm text-gray-900">{{ teamMembers.length }}</span>
            </div>

            <div class="flex flex-col whitespace-nowrap">
            <span class="text-sm font-medium text-gray-500">Unit Kerja:</span>
            <span class="text-sm text-gray-900">
                Biro Organisasi dan Sumber Daya Manusia
            </span>
            </div>
        </div>
    </div>

    <!-- Section Title -->
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Daftar Logbook Anggota Tim</h2>
    
    <!-- Table -->
    <TableDefault>
      <template #header>
        <tr>
          <TableHeader alignment="center" custom-class="w-16">No</TableHeader>
          <TableHeader>Nama</TableHeader>
          <TableHeader>NIP</TableHeader>
          <TableHeader>Pangkat</TableHeader>
          <TableHeader>Jabatan</TableHeader>
          <TableHeader alignment="center" custom-class="w-32">Aksi</TableHeader>
        </tr>
      </template>
      <template #body>
        <tr v-if="loading">
          <TableData colspan="6" alignment="center">
            <div class="flex items-center justify-center py-4">
              <svg class="animate-spin h-6 w-6 text-warning-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span class="text-gray-600">Memuat data...</span>
            </div>
          </TableData>
        </tr>
        <template v-else-if="teamMembers.length > 0">
          <tr v-for="(member, index) in teamMembers" :key="member.id" class="hover:bg-gray-50">
            <TableData alignment="center">{{ index + 1 }}</TableData>
            <TableData>{{ member.nama }}</TableData>
            <TableData>{{ member.nip }}</TableData>
            <TableData>{{ member.pangkat }}</TableData>
            <TableData>{{ member.jabatan }}</TableData>
            <TableData alignment="center">
              <button 
                class="px-3 py-1 h-fit border border-info-300 text-info-600 rounded text-xs hover:bg-info-50 whitespace-nowrap"
                @click="viewMemberLogbook(member.id)"
              >
                Lihat Logbook
              </button>
            </TableData>
          </tr>
        </template>
        <tr v-else>
          <TableDataNone label="Tidak ada data anggota tim" />
        </tr>
      </template>
    </TableDefault>
  </Card>

  <!-- Modal untuk melihat logbook anggota -->
  <div v-if="showMemberModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-5xl max-h-[80vh] flex flex-col">
      <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-900">Logbook - {{ selectedMember?.nama || 'Anggota' }}</h3>
        <button class="text-gray-400 hover:text-gray-600 text-2xl leading-none" @click="closeModal">×</button>
      </div>
      
      <div class="p-6 overflow-y-auto flex-1">
        <!-- Filter periode -->
        <div class="mb-6 pb-6 border-b border-gray-200">
          <div class="flex gap-4 flex-wrap">
            <div class="flex flex-col min-w-[200px]">
              <label class="text-sm font-medium text-gray-700 mb-2">Dari Tanggal:</label>
              <input 
                type="date" 
                v-model="filterStartDate"
                @change="loadMemberLogs"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-warning-500 focus:border-warning-500"
              />
            </div>
            <div class="flex flex-col min-w-[200px]">
              <label class="text-sm font-medium text-gray-700 mb-2">Sampai Tanggal:</label>
              <input 
                type="date" 
                v-model="filterEndDate"
                @change="loadMemberLogs"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-warning-500 focus:border-warning-500"
              />
            </div>
          </div>
        </div>

        <!-- Tabel logbook anggota -->
        <TableDefault>
          <template #header>
            <tr>
              <TableHeader>Tanggal</TableHeader>
              <TableHeader>Kegiatan</TableHeader>
              <TableHeader alignment="center">Status</TableHeader>
              <TableHeader alignment="center" custom-class="w-48">Aksi</TableHeader>
            </tr>
          </template>
          <template #body>
            <tr v-if="loadingLogs">
              <TableData colspan="4" alignment="center">
                <div class="flex items-center justify-center py-4">
                  <svg class="animate-spin h-6 w-6 text-warning-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <span class="text-gray-600">Memuat logbook...</span>
                </div>
              </TableData>
            </tr>
            <template v-else-if="memberLogs.length > 0">
              <tr v-for="log in memberLogs" :key="log.id" class="hover:bg-gray-50">
                <TableData>{{ formatDate(log.tanggal) }}</TableData>
                <TableData>
                  <div class="flex flex-col gap-1">
                    <strong class="text-sm text-gray-900">{{ log.aktivitas_kegiatan_harian || log.judul_kegiatan }}</strong>
                    <small class="text-xs text-gray-500" v-if="log.keterangan || log.deskripsi">
                      {{ (log.keterangan || log.deskripsi || '').substring(0, 100) }}...
                    </small>
                  </div>
                </TableData>
                <TableData alignment="center">
                  <span :class="getStatusClass(log.status)" class="text-xs px-3 py-1 rounded-full font-medium">
                    {{ getStatusText(log.status) }}
                  </span>
                </TableData>
                <TableData alignment="center">
                  <div class="flex gap-2 flex-wrap justify-center">
                    <button 
                      class="px-3 py-1 border border-gray-300 text-gray-700 rounded text-xs hover:bg-gray-50"
                      @click="viewLogDetail(log.id)"
                    >
                      Detail
                    </button>
                    <button 
                      v-if="log.status === 'pending' || log.status === 'Disubmit'"
                      class="px-3 py-1 bg-green-500 text-white rounded text-xs hover:bg-green-600"
                      @click="approveLog(log.id)"
                    >
                      Approve
                    </button>
                    <button 
                      v-if="log.status === 'pending' || log.status === 'Disubmit'"
                      class="px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600"
                      @click="rejectLog(log.id)"
                    >
                      Reject
                    </button>
                  </div>
                </TableData>
              </tr>
            </template>
            <tr v-else>
              <TableDataNone label="Tidak ada logbook untuk periode ini" />
            </tr>
          </template>
        </TableDefault>
      </div>
    </div>
  </div>

  <!-- Modal detail log -->
  <div v-if="showDetailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl">
      <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-900">Detail Logbook</h3>
        <button class="text-gray-400 hover:text-gray-600 text-2xl leading-none" @click="showDetailModal = false">×</button>
      </div>
      
      <div class="p-6" v-if="logDetail">
        <div class="space-y-4">
          <div class="flex">
            <span class="font-semibold text-gray-700 w-48">Tanggal:</span>
            <span class="text-gray-900 flex-1">{{ formatDate(logDetail.tanggal) }}</span>
          </div>
          <div class="flex" v-if="logDetail.jam_mulai && logDetail.jam_selesai">
            <span class="font-semibold text-gray-700 w-48">Waktu:</span>
            <span class="text-gray-900 flex-1">{{ logDetail.jam_mulai }} - {{ logDetail.jam_selesai }}</span>
          </div>
          <div class="flex" v-if="logDetail.rencana_hasil_kinerja_skp">
            <span class="font-semibold text-gray-700 w-48">Rencana Hasil Kinerja SKP:</span>
            <span class="text-gray-900 flex-1">{{ logDetail.rencana_hasil_kinerja_skp }}</span>
          </div>
          <div class="flex" v-if="logDetail.indikator_hasil_rencana_kerja">
            <span class="font-semibold text-gray-700 w-48">Indikator Hasil:</span>
            <span class="text-gray-900 flex-1">{{ logDetail.indikator_hasil_rencana_kerja }}</span>
          </div>
          <div class="flex">
            <span class="font-semibold text-gray-700 w-48">Aktivitas Harian:</span>
            <span class="text-gray-900 flex-1">{{ logDetail.aktivitas_kegiatan_harian || logDetail.judul_kegiatan }}</span>
          </div>
          <div class="flex" v-if="logDetail.keterangan || logDetail.deskripsi">
            <span class="font-semibold text-gray-700 w-48">Keterangan:</span>
            <span class="text-gray-900 flex-1">{{ logDetail.keterangan || logDetail.deskripsi }}</span>
          </div>
          <div class="flex">
            <span class="font-semibold text-gray-700 w-48">Status:</span>
            <span :class="getStatusClass(logDetail.status)" class="text-xs px-3 py-1 rounded-full font-medium w-fit">
              {{ getStatusText(logDetail.status) }}
            </span>
          </div>
          <div v-if="logDetail.catatan_katim" class="flex">
            <span class="font-semibold text-gray-700 w-48">Catatan Katim:</span>
            <span class="text-gray-900 flex-1">{{ logDetail.catatan_katim }}</span>
          </div>
          <div v-if="logDetail.catatan_atasan" class="flex">
            <span class="font-semibold text-gray-700 w-48">Catatan Atasan:</span>
            <span class="text-gray-900 flex-1">{{ logDetail.catatan_atasan }}</span>
          </div>
          <div v-if="logDetail.file_name" class="flex">
            <span class="font-semibold text-gray-700 w-48">File:</span>
            <a :href="logDetail.file_url" target="_blank" class="text-blue-600 hover:underline">{{ logDetail.file_name }}</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal approve -->
  <div v-if="showApproveModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
      <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-900">Approve Logbook</h3>
        <button class="text-gray-400 hover:text-gray-600 text-2xl leading-none" @click="showApproveModal = false">×</button>
      </div>
      
      <div class="p-6">
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional):</label>
          <textarea 
            v-model="approveNotes" 
            rows="3" 
            placeholder="Masukkan catatan jika diperlukan..."
            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-warning-500 focus:border-warning-500"
          ></textarea>
        </div>
        <div class="flex justify-end gap-3">
          <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50" @click="showApproveModal = false">Batal</button>
          <button class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600" @click="confirmApprove">Approve</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal reject -->
  <div v-if="showRejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
      <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-900">Reject Logbook</h3>
        <button class="text-gray-400 hover:text-gray-600 text-2xl leading-none" @click="showRejectModal = false">×</button>
      </div>
      
      <div class="p-6">
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Reject:</label>
          <textarea 
            v-model="rejectReason" 
            rows="4" 
            placeholder="Masukkan alasan penolakan..."
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-warning-500 focus:border-warning-500"
          ></textarea>
        </div>
        <div class="flex justify-end gap-3">
          <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50" @click="showRejectModal = false">Batal</button>
          <button class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600" @click="confirmReject">Reject</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { LogbookKatimService, type TeamMember, type MemberLog, type LogDetail } from '@/services/logbookKatimService'
import Card from '@/components/card/CardDefault.vue'
import TableDefault from '@/components/table/TableDefault.vue'
import TableHeader from '@/components/table/TableHeader.vue'
import TableData from '@/components/table/TableData.vue'
import TableDataNone from '@/components/table/TableDataNone.vue'

const teamMembers = ref<TeamMember[]>([])
const loading = ref(false)
const showMemberModal = ref(false)
const showDetailModal = ref(false)
const showApproveModal = ref(false)
const showRejectModal = ref(false)

const selectedMember = ref<TeamMember | null>(null)
const memberLogs = ref<MemberLog[]>([])
const logDetail = ref<LogDetail | null>(null)
const loadingLogs = ref(false)

const filterStartDate = ref('')
const filterEndDate = ref('')
const approveNotes = ref('')
const rejectReason = ref('')
const selectedLogId = ref<number | null>(null)

onMounted(() => {
  loadTeamMembers()
})

const loadTeamMembers = async () => {
  loading.value = true
  
  try {
    const response = await LogbookKatimService.getTeamMembers()
    if (response?.data?.data && Array.isArray(response.data.data)) {
      teamMembers.value = response.data.data
    } else if (response?.data && Array.isArray(response.data)) {
      teamMembers.value = response.data
    } else {
      teamMembers.value = []
    }
  } catch (error) {
    teamMembers.value = []
  } finally {
    loading.value = false
  }
}

const viewMemberLogbook = async (memberId: number) => {
  selectedMember.value = teamMembers.value.find(m => m.id === memberId) || null
  showMemberModal.value = true
  
  const endDate = new Date()
  const startDate = new Date()
  startDate.setDate(startDate.getDate() - 30)
  
  filterStartDate.value = startDate.toISOString().split('T')[0]
  filterEndDate.value = endDate.toISOString().split('T')[0]
  
  await loadMemberLogs()
}

const loadMemberLogs = async () => {
  if (!selectedMember.value?.id) return
  
  loadingLogs.value = true
  try {
    const filters: any = {}
    if (filterStartDate.value) filters.start_date = filterStartDate.value
    if (filterEndDate.value) filters.end_date = filterEndDate.value
    
    const response = await LogbookKatimService.getMemberLogs(selectedMember.value.id, filters)
    
    if (response?.data?.data && Array.isArray(response.data.data)) {
      memberLogs.value = response.data.data
    } else if (response?.data && Array.isArray(response.data)) {
      memberLogs.value = response.data
    } else {
      memberLogs.value = []
    }
  } catch (error) {
    memberLogs.value = []
  } finally {
    loadingLogs.value = false
  }
}

const user = ref<any>(null)

onMounted(() => {
  const storedUser = localStorage.getItem('user')
  if (storedUser) {
    user.value = JSON.parse(storedUser)
  }
})

const viewLogDetail = async (logId: number) => {
  try {
    const response = await LogbookKatimService.getLogDetail(logId)
    
    if (response?.data?.data) {
      logDetail.value = response.data.data
    } else if (response?.data) {
      logDetail.value = response.data
    }
    
    showDetailModal.value = true
  } catch (error) {
    // Handle error silently
  }
}

const approveLog = (logId: number) => {
  selectedLogId.value = logId
  approveNotes.value = ''
  showApproveModal.value = true
}

const confirmApprove = async () => {
  if (!selectedLogId.value) return
  
  try {
    await LogbookKatimService.approveLog(selectedLogId.value, approveNotes.value)
    showApproveModal.value = false
    
    await loadMemberLogs()
    if (showDetailModal.value && selectedLogId.value) {
      await viewLogDetail(selectedLogId.value)
    }
  } catch (error) {
    // Handle error silently
  }
}

const rejectLog = (logId: number) => {
  selectedLogId.value = logId
  rejectReason.value = ''
  showRejectModal.value = true
}

const confirmReject = async () => {
  if (!rejectReason.value.trim()) {
    alert('Harap masukkan alasan reject')
    return
  }
  
  if (!selectedLogId.value) return
  
  try {
    await LogbookKatimService.rejectLog(selectedLogId.value, rejectReason.value)
    showRejectModal.value = false
    
    await loadMemberLogs()
    if (showDetailModal.value && selectedLogId.value) {
      await viewLogDetail(selectedLogId.value)
    }
  } catch (error) {
    // Handle error silently
  }
}

const closeModal = () => {
  showMemberModal.value = false
  selectedMember.value = null
  memberLogs.value = []
}

const formatDate = (dateString: string) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })
}

const getStatusText = (status: string) => {
  const statusMap: Record<string, string> = {
    'Disubmit': 'Disubmit',
    'Disetujui': 'Disetujui',
    'Ditolak': 'Ditolak'
  }
  return statusMap[status] || status
}

const getStatusClass = (status: string) => {
  const classes: Record<string, string> = {
    'Disubmit': 'bg-warning-100 text-warning-700',
    'Disetujui': 'bg-green-100 text-green-700',
    'Ditolak': 'bg-red-100 text-red-700'
  }
  return classes[status] || 'bg-gray-100 text-gray-700'
}
</script>