<template>
  <!-- Main Card Container with Tabs -->
  <div class="bg-white rounded-xl shadow-sm">
    <!-- Tabs Inside Card -->
    <div class="border-b px-6">
      <div class="flex gap-8">
        <button 
          @click="activeTab = 'pengisian'"
          :class="[
            'py-4 px-2 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide',
            activeTab === 'pengisian' 
              ? 'border-[#FBC143] text-gray-900' 
              : 'border-transparent text-gray-500 hover:text-gray-700'
          ]"
        >
          PENGISIAN LOGBOOK
        </button>
        <button 
          @click="activeTab = 'skp'"
          :class="[
            'py-4 px-2 text-sm font-semibold border-b-2 transition-colors uppercase tracking-wide',
            activeTab === 'skp' 
              ? 'border-[#FBC143] text-gray-900' 
              : 'border-transparent text-gray-500 hover:text-gray-700'
          ]"
        >
          SKP
        </button>
      </div>
    </div>

    <!-- Tab Content Inside Card -->
    <div class="p-6">
      <!-- SKP Content -->
      <div v-if="activeTab === 'skp'">
        <div class="text-center py-20">
          <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <h3 class="text-xl font-semibold text-gray-700 mb-2">Halaman SKP</h3>
          <p class="text-gray-500">Konten SKP akan ditampilkan di sini</p>
        </div>
      </div>

      <!-- Pengisian Logbook Content -->
      <div v-if="activeTab === 'pengisian'">
        <!-- Title and Action Button -->
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-2xl font-semibold text-gray-900">Pengisian Logbook</h1>
          <button 
            @click="showAddModal = true"
            class="flex items-center gap-2 bg-[#FBC143] hover:bg-[#f5b835] px-5 py-2.5 rounded-lg font-medium text-sm text-gray-900 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Aktivitas Harian
          </button>
        </div>

        <!-- Date Filter and Actions -->
        <div class="flex items-center justify-between gap-3 mb-6">
          <div class="flex items-center gap-3 bg-gray-50 px-4 py-2.5 rounded-lg border border-gray-200">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <input 
              v-model="selectedDate" 
              type="text" 
              class="text-sm outline-none bg-transparent min-w-[200px]"
              placeholder="Juli (24 Juli 2025)"
            />
          </div>
          
          <div class="flex gap-3">
            <button 
              class="flex items-center gap-2 px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium transition-colors bg-white"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
              </svg>
              Filter
            </button>
            <button class="flex items-center gap-2 px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium transition-colors bg-white">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              Cari...
            </button>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-12">
          <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-[#19203B]"></div>
          <p class="mt-3 text-gray-600">Memuat data...</p>
        </div>

        <!-- Logbook Entries -->
        <div v-else class="space-y-4">
          <LogbookCard 
            v-for="entry in logbookEntries" 
            :key="entry.id"
            :entry="entry"
            @detail="showDetail"
          />
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Aktivitas -->
  <ModalTambahAktivitas
    :show="showAddModal"
    @close="showAddModal = false"
    @success="handleSuccess"
  />

  <!-- Modal Detail Aktivitas -->
  <ModalDetailAktivitas
    :show="showDetailModal"
    :entry="selectedEntry"
    @close="showDetailModal = false"
  />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import LogbookCard from '../components/Logbook/LogbookCard.vue'
import ModalTambahAktivitas from '../components/Logbook/ModalTambahAktivitas.vue'
import ModalDetailAktivitas from '../components/Logbook/ModalDetailAktivitas.vue'
import { logbookService } from '../services/Logbook/logbookService'

const activeTab = ref('pengisian')
const selectedDate = ref('Desember (07 Desember 2025)')
const loading = ref(false)
const showAddModal = ref(false)
const showDetailModal = ref(false)
const selectedEntry = ref(null)

const logbookEntries = ref([])

const showDetail = (entry) => {
  selectedEntry.value = entry
  showDetailModal.value = true
}

// PERBAIKAN: Ganti dari handleSubmit ke handleSuccess
const handleSuccess = (data) => {
  console.log('✅ Logbook berhasil ditambahkan:', data)
  showAddModal.value = false
  fetchLogbook()
}

const fetchLogbook = async () => {
  loading.value = true
  try {
    const response = await logbookService.getLogbooks()
    const logbooks = response.data || []
    
    console.log('📦 Raw logbooks from API:', logbooks)
    
    // Dapatkan bulan dan tahun saat ini
    const now = new Date()
    const currentMonth = now.getMonth()
    const currentYear = now.getFullYear()
    
    // Dapatkan jumlah hari dalam bulan
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate()
    
    // Buat map dari logbook yang ada berdasarkan tanggal (key: YYYY-MM-DD)
    const logbookMap = {}
    logbooks.forEach(log => {
      // Pastikan format tanggal konsisten (YYYY-MM-DD)
      let dateKey = log.tanggal
      
      // Jika tanggal dalam format lain, convert ke YYYY-MM-DD
      if (dateKey && !dateKey.match(/^\d{4}-\d{2}-\d{2}$/)) {
        const d = new Date(dateKey)
        dateKey = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`
      }
      
      console.log(`📅 Mapping logbook: ${dateKey}`, log)
      logbookMap[dateKey] = log
    })
    
    console.log('🗺️ Logbook map:', logbookMap)
    
    // Generate entries untuk setiap hari dalam bulan
    const entries = []
    for (let day = 1; day <= daysInMonth; day++) {
      // Gunakan format YYYY-MM-DD langsung tanpa timezone conversion
      const dateKey = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`
      
      // Buat date object untuk cek day of week
      const currentDate = new Date(currentYear, currentMonth, day)
      const dayOfWeek = currentDate.getDay() // 0 = Minggu, 6 = Sabtu
      
      console.log(`🔍 Day ${day}: dateKey=${dateKey}, dayOfWeek=${dayOfWeek}, hasLogbook=${!!logbookMap[dateKey]}`)
      
      // Cek apakah Minggu (0) atau Sabtu (6)
      if (dayOfWeek === 0 || dayOfWeek === 6) {
        entries.push({
          id: `empty-${dateKey}`,
          date: dateKey,
          isEmpty: true,
          message: 'Tidak ada aktivitas'
        })
      } else if (logbookMap[dateKey]) {
        // Ada logbook untuk hari ini
        const log = logbookMap[dateKey]
        entries.push({
          id: log.id,
          date: dateKey,
          fullDate: new Date(log.tanggal).toLocaleDateString('id-ID', { 
            day: '2-digit', 
            month: 'long', 
            year: 'numeric' 
          }),
          time: `${log.jam_mulai} - ${log.jam_selesai}`,
          files: log.file_path ? '1 File' : '0 File',
          status: log.status || 'Draft',
          statusColor: log.status === 'Disetujui' ? 'blue' : 'yellow',
          rencanaKerja: 'Rencana Hasil Kinerja SKP',
          rencanaDesc: log.rencana_hasil_kinerja_skp || '-',
          indikator: 'Indikator',
          indikatorDesc: log.indikator_hasil_rencana_kerja || '-',
          aktivitas: 'Aktivitas Harian',
          aktivitasDesc: log.aktivitas_kegiatan_harian || '-',
          keterangan: 'Keterangan',
          keteranganDesc: log.keterangan || '-',
          catatanKatim: log.catatan_katim || '-',
          catatanAtasan: log.catatan_atasan || '-',
          file_path: log.file_path,
          file_name: log.file_name,
          file_size: log.file_size,
          isEmpty: false
        })
      } else {
        // Tidak ada logbook untuk hari kerja ini
        entries.push({
          id: `empty-${dateKey}`,
          date: dateKey,
          isEmpty: true,
          message: 'Tidak ada aktivitas'
        })
      }
    }
    
    console.log('✅ Final entries:', entries)
    logbookEntries.value = entries
  } catch (error) {
    console.error('Error fetching logbook:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchLogbook()
})
</script>