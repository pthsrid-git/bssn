<!-- logbook-dws-frontend/src/components/Logbook/LogbookAtasanDetail.vue -->
<template>
  <div class="space-y-6">
    <!-- Divider -->
    <div class="border-t border-gray-200"></div>

    <!-- Filter dan Toolbar -->
    <div class="flex items-center gap-4">
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

      <!-- Logbook Cards -->
      <LogbookAtasanEntryCard
        v-for="entry in logbookEntries"
        :key="entry.id"
        :entry="entry"
        @detail="handleViewDetail"
        @approve="handleApprove"
        @reject="handleReject"
      />
    </div>

    <!-- Detail Modal -->
    <LogbookAtasanDetailModal
      :show="showDetailModal"
      :entry="selectedEntry"
      @close="handleCloseModal"
      @saved="handleSaved"
    />
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { logbookAtasanService } from '@/services/Logbook/logbookAtasanService'
import LogbookAtasanEntryCard from './LogbookAtasanEntryCard.vue'
import LogbookAtasanDetailModal from './LogbookAtasanDetailModal.vue'

const props = defineProps({
  pegawaiData: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['back'])

// ==================== STATE ====================
const loadingEntries = ref(false)
const logbookEntries = ref([])
const selectedMonth = ref(getCurrentMonth())
const showDetailModal = ref(false)
const selectedEntry = ref(null)

// ==================== METHODS ====================
function getCurrentMonth() {
  const now = new Date()
  const year = now.getFullYear()
  const month = String(now.getMonth() + 1).padStart(2, '0')
  return `${year}-${month}`
}

const fetchPegawaiLogbook = async () => {
  loadingEntries.value = true
  try {
    const [year, month] = selectedMonth.value.split('-')
    
    const response = await logbookAtasanService.getPegawaiLogs(props.pegawaiData.id, {
      bulan: parseInt(month),
      tahun: parseInt(year)
    })
    
    if (response.success) {
      logbookEntries.value = transformLogbookData(response.data)
    }
  } catch (error) {
    console.error('Error fetching pegawai logbook:', error)
    logbookEntries.value = []
  } finally {
    loadingEntries.value = false
  }
}

const transformLogbookData = (data) => {
  const [year, month] = selectedMonth.value.split('-')
  const daysInMonth = new Date(parseInt(year), parseInt(month), 0).getDate()
  const allDates = []
  
  for (let day = 1; day <= daysInMonth; day++) {
    const dateStr = `${year}-${month}-${String(day).padStart(2, '0')}`
    const date = new Date(dateStr)
    const dayOfWeek = date.getDay()
    
    const logForDate = data && data.length > 0 
      ? data.find(log => {
          if (!log.tanggal) return false
          const logDate = new Date(log.tanggal)
          return logDate.getDate() === day
        })
      : null
    
    const isWeekend = dayOfWeek === 0 || dayOfWeek === 6
    
    if (isWeekend) {
      allDates.push({
        id: dateStr,
        date: dateStr,
        isEmpty: true,
        message: dayOfWeek === 0 ? 'Hari Minggu' : 'Hari Sabtu'
      })
    } else if (!logForDate || !logForDate.id) {
      allDates.push({
        id: dateStr,
        date: dateStr,
        isEmpty: true,
        message: 'Belum ada aktivitas'
      })
    } else {
      const timeStart = logForDate.jam_mulai ? logForDate.jam_mulai.substring(0, 5) : '00:00'
      const timeEnd = logForDate.jam_selesai ? logForDate.jam_selesai.substring(0, 5) : '00:00'
      
      allDates.push({
        id: logForDate.id,
        date: dateStr,
        isEmpty: false,
        time: `${timeStart} - ${timeEnd}`,
        files: logForDate.file_name ? '1 file' : '0 file',
        status: logForDate.status || 'Draft',
        rencana_hasil_kinerja: logForDate.rencana_hasil_kinerja_skp || '-',
        indikator_hasil: logForDate.indikator_hasil_rencana_kerja || '-',
        aktivitas_harian: logForDate.aktivitas_kegiatan_harian || '-',
        keterangan: logForDate.keterangan || '-',
        catatan_katim: logForDate.catatan_katim || '-',
        catatan_atasan: logForDate.catatan_atasan || '-',
        file_path: logForDate.file_path,
        file_name: logForDate.file_name,
        file_size: logForDate.file_size
      })
    }
  }
  
  return allDates
}

const handleMonthChange = () => {
  fetchPegawaiLogbook()
}

const handleViewDetail = (entry) => {
  if (entry.isEmpty) return
  
  selectedEntry.value = entry
  showDetailModal.value = true
}

const handleApprove = async (entry) => {
  if (!confirm('Apakah Anda yakin ingin menyetujui logbook ini?')) {
    return
  }

  try {
    const response = await logbookAtasanService.approveLog(entry.id)
    
    if (response.success) {
      alert('Logbook berhasil disetujui')
      fetchPegawaiLogbook()
    }
  } catch (error) {
    console.error('Error approving log:', error)
    alert('Gagal menyetujui logbook')
  }
}

const handleReject = async (entry) => {
  const alasan = prompt('Masukkan alasan penolakan:')
  
  if (!alasan || alasan.trim() === '') {
    alert('Alasan penolakan harus diisi')
    return
  }

  try {
    const response = await logbookAtasanService.rejectLog(entry.id, {
      catatan_atasan: alasan
    })
    
    if (response.success) {
      alert('Logbook berhasil ditolak')
      fetchPegawaiLogbook()
    }
  } catch (error) {
    console.error('Error rejecting log:', error)
    alert('Gagal menolak logbook')
  }
}

const handleCloseModal = () => {
  showDetailModal.value = false
  selectedEntry.value = null
}

const handleSaved = () => {
  fetchPegawaiLogbook()
}

// Watch pegawaiData changes
watch(() => props.pegawaiData, () => {
  if (props.pegawaiData) {
    fetchPegawaiLogbook()
  }
}, { immediate: true })

// ==================== LIFECYCLE ====================
onMounted(() => {
  fetchPegawaiLogbook()
})
</script>