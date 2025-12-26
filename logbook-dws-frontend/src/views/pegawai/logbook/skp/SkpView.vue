<template>
    <div class="flex flex-col gap-4">
        <!-- List View -->
        <div v-if="!showDetail">
            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-900">Sasaran Kerja Pegawai (SKP)</h1>

            <div class="h-px bg-gray-200 my-4"></div>

            <!-- Data Profil Section -->
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <div class="flex-1">
                        <h2 class="text-base font-semibold text-gray-900 mb-4">Data Profil</h2>
                        
                        <!-- Profile Grid -->
                        <div class="flex flex-wrap gap-x-8 gap-y-4">
                            <div>
                                <p class="text-xs text-gray-500">Nama</p>
                                <p class="text-sm font-medium text-gray-900">{{ userData?.name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">NIP</p>
                                <p class="text-sm font-medium text-gray-900">{{ userData?.nip }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Jabatan</p>
                                <p class="text-sm font-medium text-gray-900">{{ userData?.jabatan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Unit Kerja</p>
                                <p class="text-sm font-medium text-gray-900">{{ userData?.unitkerja }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <button 
                        @click="syncEkinerja"
                        class="flex items-center gap-2 bg-warning-50 text-warning-700 px-4 py-2 rounded-lg text-sm font-medium border border-warning-200 hover:bg-warning-100 transition-colors flex-shrink-0"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Sinkronasi ekinerja BKN
                    </button>
                </div>
            </div>

            <div class="h-px bg-gray-200 my-4"></div>

            <!-- Daftar SKP -->
            <div class="space-y-4">
                <h2 class="text-base font-semibold text-gray-900">Daftar Sasaran Kerja Pegawai (SKP)</h2>
                <SkpTable 
                    :skpData="skpData" 
                    :isLoading="isLoading"
                    @viewDetail="viewDetail"
                />
            </div>
        </div>

        <!-- Detail View -->
        <SkpDetailView
            v-else
            :userData="userData"
            :pejabatPenilai="pejabatPenilai"
            :loadingPenilai="loadingPenilai"
            :skpDetail="skpDetail"
            :detailTabs="detailTabs"
            @back="backToList"
            @update:tab="detailTab = $event"
        />
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import SkpTable from './SkpTable.vue'
import SkpDetailView from './form/SkpDetailView.vue'
import { skpTableListDummy } from '@/dummies/pegawai/skpTable'
import { skpViewPejabatPenilaiDummy, skpViewDetailDummy } from '@/dummies/pegawai/skpView'
import { getTestUserDwsData } from '@/helpers/test'

const userData = ref(getTestUserDwsData()) // Gunakan helper untuk get data user

const showDetail = ref(false)
const detailTab = ref('utama')
const selectedSkpId = ref<number | null>(null)
const isLoading = ref(false)

// Detail tabs configuration
const detailTabs = ref([
    { name: 'utama', label: 'Utama' },
    { name: 'tambahan', label: 'Tambahan' },
    { name: 'perilaku', label: 'Perilaku Kerja' },
    { name: 'lampiran', label: 'Lampiran' }
])

// Pejabat Penilai
const pejabatPenilai = ref(skpViewPejabatPenilaiDummy)
const loadingPenilai = ref(false)

// Dummy data
const skpData = ref(skpTableListDummy)
const skpDetail = ref(skpViewDetailDummy)

const fetchPejabatPenilai = async () => {
    // Untuk sekarang menggunakan dummy data
    loadingPenilai.value = false
    pejabatPenilai.value = skpViewPejabatPenilaiDummy
}

const syncEkinerja = () => {
    alert('Fitur sinkronisasi ekinerja BKN akan segera tersedia')
}

const viewDetail = (id: number) => {
    selectedSkpId.value = id
    showDetail.value = true
    detailTab.value = 'utama'
    fetchPejabatPenilai()
}

const backToList = () => {
    showDetail.value = false
    selectedSkpId.value = null
    pejabatPenilai.value = null
}

onMounted(() => {
    // Load initial data jika diperlukan
})
</script>