<template>
    <div class="flex flex-col w-full gap-4">
        <!-- Each Day Card -->
        <template v-for="dateGroup in groupedLogbooks" :key="dateGroup.date">
            <CardDefault :backgroundClass="isWeekend(dateGroup.date) ? 'bg-gray-100' : 'bg-white'">
                <div class="flex gap-4 min-h-[80px]">
                    <!-- Date Column (Left) -->
                    <div class="flex-shrink-0 w-16 flex flex-col items-center justify-center text-center">
                        <div class="text-xs text-gray-900">{{ dateGroup.dayName }}</div>
                        <div class="text-2xl font-bold text-gray-900 leading-none">
                            {{ dateGroup.day }}
                        </div>
                    </div>

                    <!-- Separator Line -->
                    <div class="w-px" :class="isWeekend(dateGroup.date) ? 'bg-gray-300' : 'bg-gray-200'"></div>

                    <!-- Content Column (Right) -->
                    <div class="flex-1 flex items-center">
                        <!-- No activities (including weekends) -->
                        <div v-if="dateGroup.activities.length === 0" class="py-2 text-gray-900 text-sm">
                            Tidak ada aktivitas
                        </div>

                        <!-- Has activities -->
                        <div v-else class="space-y-3 w-full">
                            <div
                                v-for="(activity, activityIndex) in dateGroup.activities"
                                :key="activity.id"
                            >
                                <!-- Divider between activities -->
                                <div v-if="Number(activityIndex) > 0" class="border-t border-gray-100 pt-3"></div>

                                <!-- Activity Item -->
                                <div class="flex gap-4">
                                    <!-- Time & Status Column -->
                                    <div class="flex-shrink-0 w-28 space-y-1.5">
                                        <div class="flex items-center gap-2 text-sm text-gray-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ activity.jam_mulai }} - {{ activity.jam_selesai }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-sm text-gray-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span>{{ getFileCount(activity) }} file</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="flex items-center justify-center w-5 h-5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                            </span>
                                            <span
                                                :class="getLogbookStatusClass(activity.status)"
                                                class="text-xs px-3 py-1 rounded-full font-medium"
                                            >
                                                {{ getLogbookStatusLabel(activity.status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Details Grid -->
                                    <div class="flex-1 grid grid-cols-[1fr_1fr_1fr_1fr_1fr_1fr_auto] gap-x-3 gap-y-1.5 text-sm">
                                        <div>
                                            <div class="text-xs font-semibold text-gray-900 mb-1">Rencana Hasil Kinerja SKP</div>
                                            <div class="text-gray-700 text-xs">{{ activity.rencana_hasil_kinerja_skp }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs font-semibold text-gray-900 mb-1">Indikator Hasil Rencana Kerja</div>
                                            <div class="text-gray-700 text-xs">{{ activity.indikator_hasil_rencana_kerja }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs font-semibold text-gray-900 mb-1">Aktivitas Harian</div>
                                            <div class="text-gray-700 text-xs">{{ activity.aktivitas_kegiatan_harian }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs font-semibold text-gray-900 mb-1">Keterangan</div>
                                            <div class="text-gray-700 text-xs">{{ activity.keterangan || '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs font-semibold text-gray-900 mb-1">Catatan Katim</div>
                                            <div class="text-gray-700 text-xs">{{ activity.catatan_katim || '-' }}</div>
                                        </div>

                                        <div>
                                            <div class="text-xs font-semibold text-gray-900 mb-1">Catatan Atasan</div>
                                            <div class="text-gray-700 text-xs">{{ activity.catatan_atasan || '-' }}</div>
                                        </div>

                                        <div class="flex items-center justify-center">
                                            <ButtonDefault variant="info" @click="viewClick(activity)">
                                                Detail
                                            </ButtonDefault>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </CardDefault>
        </template>

        <!-- Empty State -->
        <div v-if="groupedLogbooks.length === 0" class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <p class="text-gray-600 mb-2">Belum ada aktivitas yang tercatat</p>
            <p class="text-sm text-gray-500">Mulai tambahkan aktivitas harian Anda</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ButtonDefault, CardDefault, type RequestState } from '@bssn/ui-kit-frontend';
import { computed, type PropType } from 'vue';
import { getLogbookStatusClass, getLogbookStatusLabel } from '@/helpers/custom';

import { type LogbookData } from '@/models/pegawai/logbook';

//============================================================================
// Props
//============================================================================
const props = defineProps({
    records: {
        type: Object as PropType<RequestState<LogbookData[]>>,
        required: true
    }
});

const emit = defineEmits(['viewClick']);

//============================================================================
// Computed
//============================================================================
const groupedLogbooks = computed(() => {
    const groups: any[] = []
    const dateMap = new Map()

    // Get current month
    const now = new Date()
    const year = now.getFullYear()
    const month = now.getMonth() + 1
    const daysInMonth = new Date(year, month, 0).getDate()

    // Create entries for all days in current month
    for (let day = 1; day <= daysInMonth; day++) {
        const date = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`
        const dateObj = new Date(date)
        const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']

        dateMap.set(date, {
            date,
            day: String(day).padStart(2, '0'),
            dayName: dayNames[dateObj.getDay()],
            activities: []
        })
    }

    // Group logbooks by date
    if (props.records.data) {
        props.records.data.forEach(logbook => {
            const tanggalOnly = logbook.tanggal.split('T')[0]
            if (dateMap.has(tanggalOnly)) {
                dateMap.get(tanggalOnly).activities.push(logbook)
            }
        })
    }

    dateMap.forEach(value => groups.push(value))
    return groups
})

//============================================================================
// Methods
//============================================================================
const viewClick = (record: LogbookData) => {
    emit('viewClick', record);
};

const getFileCount = (activity: LogbookData) => {
    return activity.file_path ? 1 : 0
}

const isWeekend = (date: string) => {
    const dateObj = new Date(date)
    const day = dateObj.getDay()
    return day === 0 || day === 6 // 0 = Sunday, 6 = Saturday
}

</script>
