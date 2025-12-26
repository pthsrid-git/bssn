<template>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-3">
            <button 
                @click="$emit('back')"
                class="p-2 hover:bg-gray-100 rounded-full transition-colors"
            >
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <h1 class="text-2xl font-bold text-gray-900">Detail SKP</h1>
            <BadgeDefault variant="success">Persetujuan</BadgeDefault>
            <BadgeDefault variant="info">Kuantitatif</BadgeDefault>
        </div>

        <div class="h-px bg-gray-200 my-4"></div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Pegawai yang dinilai -->
            <CardDefault backgroundClass="bg-info-50" customClass="border border-info-200">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Pegawai yang dinilai</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex">
                        <span class="text-gray-600 w-32 flex-shrink-0">Nama</span>
                        <span class="font-medium text-gray-900">{{ userData?.fullname || userData?.name }}</span>
                    </div>
                    <div class="flex">
                        <span class="text-gray-600 w-32 flex-shrink-0">NIP</span>
                        <span class="font-medium text-gray-900">{{ userData?.nip }}</span>
                    </div>
                    <div class="flex">
                        <span class="text-gray-600 w-32 flex-shrink-0">Pangkat / Gol. Ruang</span>
                        <span class="font-medium text-gray-900">{{ userData?.pangkat }}</span>
                    </div>
                    <div class="flex">
                        <span class="text-gray-600 w-32 flex-shrink-0">Jabatan</span>
                        <span class="font-medium text-gray-900">{{ userData?.jabatan }}</span>
                    </div>
                    <div class="flex">
                        <span class="text-gray-600 w-32 flex-shrink-0">Unit Kerja</span>
                        <span class="font-medium text-gray-900">{{ userData?.unitkerja }}</span>
                    </div>
                </div>
            </CardDefault>

            <!-- Pejabat penilai -->
            <CardDefault backgroundClass="bg-info-50" customClass="border border-info-200">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Pejabat penilai kinerja</h3>
                
                <!-- Loading State -->
                <StateLoading v-if="loadingPenilai" info="Memuat data..." />
                
                <!-- Data Pejabat Penilai -->
                <div v-else-if="pejabatPenilai" class="space-y-2 text-xs">
                    <div class="flex">
                        <span class="text-gray-600 w-32 flex-shrink-0">Nama</span>
                        <span class="font-medium text-gray-900">{{ pejabatPenilai?.name }}</span>
                    </div>
                    <div class="flex">
                        <span class="text-gray-600 w-32 flex-shrink-0">NIP</span>
                        <span class="font-medium text-gray-900">{{ pejabatPenilai?.nip }}</span>
                    </div>
                    <div class="flex">
                        <span class="text-gray-600 w-32 flex-shrink-0">Pangkat / Gol. Ruang</span>
                        <span class="font-medium text-gray-900">{{ pejabatPenilai?.pangkat }}</span>
                    </div>
                    <div class="flex">
                        <span class="text-gray-600 w-32 flex-shrink-0">Jabatan</span>
                        <span class="font-medium text-gray-900">{{ pejabatPenilai?.jabatan }}</span>
                    </div>
                    <div class="flex">
                        <span class="text-gray-600 w-32 flex-shrink-0">Unit Kerja</span>
                        <span class="font-medium text-gray-900">{{ pejabatPenilai?.unitkerja }}</span>
                    </div>
                </div>
                
                <!-- No Data State -->
                <StateInfo v-else message="Tidak ada data pejabat penilai" />
            </CardDefault>
        </div>

        <!-- Hasil Kerja Section -->
        <div class="space-y-4">
            <h3 class="text-base font-semibold text-gray-900">Hasil Kerja</h3>

            <!-- Sub Tabs using TabDefault -->
            <TabDefault
                :tabs="detailTabs"
                :initial-tab="'utama'"
                @update:tab="$emit('update:tab', $event)"
            >
                <template #utama>
                    <SkpUtamaTab :skpDetail="skpDetail" />
                </template>

                <template #tambahan>
                    <SkpTambahanTab :skpDetail="skpDetail" />
                </template>

                <template #perilaku>
                    <SkpPerilakuTab :skpDetail="skpDetail" />
                </template>

                <template #lampiran>
                    <SkpLampiranTab :skpDetail="skpDetail" />
                </template>
            </TabDefault>
        </div>
    </div>
</template>

<script setup lang="ts">
import { 
    TabDefault, 
    CardDefault, 
    BadgeDefault,
    StateLoading,
    StateInfo
} from '@bssn/ui-kit-frontend'
import SkpUtamaTab from '../tabs/SkpUtamaTab.vue'
import SkpTambahanTab from '../tabs/SkpTambahanTab.vue'
import SkpPerilakuTab from '../tabs/SkpPerilakuTab.vue'
import SkpLampiranTab from '../tabs/SkpLampiranTab.vue'

defineProps({
    userData: {
        type: Object,
        required: true
    },
    pejabatPenilai: {
        type: Object,
        default: null
    },
    loadingPenilai: {
        type: Boolean,
        default: false
    },
    skpDetail: {
        type: Object,
        required: true
    },
    detailTabs: {
        type: Array,
        required: true
    }
})

defineEmits(['back', 'update:tab'])
</script>