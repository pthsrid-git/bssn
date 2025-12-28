<template>
  <div class="flex flex-col w-full gap-4">
    <!-- Header Section -->
    <div class="flex flex-col gap-4">
      <!-- Title and Pegawai Info -->
      <div class="flex items-start gap-3">
        <button
          @click="$emit('back')"
          class="p-2 hover:bg-gray-100 rounded-full transition-colors flex-shrink-0 mt-1"
        >
          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <div class="flex flex-col gap-1">
          <h1 class="text-xl font-bold text-gray-900">{{ pegawai.nama || '-' }}</h1>
          <div class="flex items-center gap-2 text-sm text-gray-500">
            <span>{{ pegawai.nip || '-' }}</span>
            <span v-if="pegawai.pangkat">|</span>
            <span v-if="pegawai.pangkat">{{ pegawai.pangkat }}</span>
            <span v-if="pegawai.jabatan">|</span>
            <span v-if="pegawai.jabatan">{{ pegawai.jabatan }}</span>
            <span v-if="pegawai.unit_kerja">|</span>
            <span v-if="pegawai.unit_kerja">{{ pegawai.unit_kerja }}</span>
          </div>
        </div>
      </div>

      <!-- Divider -->
      <div class="h-px bg-gray-200"></div>

      <!-- Filter Bar -->
      <div class="flex justify-between items-center">
        <!-- Left side - Month/Date -->
        <div class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-lg bg-white">
          <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span class="text-sm">{{ formattedMonth }}</span>
        </div>

        <!-- Right side - Search -->
        <div class="flex items-center gap-3">
          <FieldSearch
            name="field-keywords"
            placeholder="Cari..."
            @update:value="onSearchUpdate"
          />
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <StateLoading v-if="loadingLogs" info="Memuat logbook..." />

    <!-- Logbook Cards -->
    <div v-else class="space-y-4">
      <CardDefault
        v-for="dateGroup in groupedLogs"
        :key="dateGroup.date"
        :backgroundClass="isWeekend(dateGroup.date) ? 'bg-gray-100' : 'bg-white'"
      >
        <div class="flex gap-4 min-h-[80px]">
          <!-- Date Column -->
          <div class="flex-shrink-0 w-16 flex flex-col items-center justify-center text-center">
            <div class="text-xs text-gray-900">{{ dateGroup.dayName }}</div>
            <div class="text-2xl font-bold text-gray-900 leading-none">
              {{ dateGroup.day }}
            </div>
          </div>

          <!-- Separator -->
          <div class="w-px" :class="isWeekend(dateGroup.date) ? 'bg-gray-300' : 'bg-gray-200'"></div>

          <!-- Content -->
          <div class="flex-1 flex items-center">
            <div v-if="dateGroup.activities.length === 0" class="py-2 text-gray-900 text-sm">
              Tidak ada aktivitas
            </div>

            <div v-else class="space-y-3 w-full">
              <div
                v-for="(activity, index) in dateGroup.activities"
                :key="activity.id"
              >
                <div v-if="Number(index) > 0" class="border-t border-gray-100 pt-3"></div>

                <div class="flex gap-4">
                  <!-- Time & Status -->
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
                      <div class="text-xs font-semibold text-gray-900 mb-1">Indikator Hasil</div>
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

                    <div class="flex flex-col items-stretch gap-2">
                      <ButtonOutline variant="info" @click="viewDetailClick(activity)">
                        Detail
                      </ButtonOutline>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </CardDefault>

      <!-- Empty State -->
      <div v-if="groupedLogs.length === 0" class="text-center py-12">
        <div class="text-gray-400 mb-4">
          <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <p class="text-gray-600 mb-2">Belum ada aktivitas yang tercatat</p>
        <p class="text-sm text-gray-500">Tidak ada logbook untuk bulan ini</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, type PropType } from 'vue';
import { CardDefault, ButtonOutline, StateLoading, FieldSearch, type PageDefaultExposed } from '@bssn/ui-kit-frontend';
import type { PegawaiAdminData, LogbookAdminData } from '@/models/admin/logbookAdmin';
import { formatCurrentMonth, getLogbookStatusClass, getLogbookStatusLabel } from '@/helpers/custom';
import { useLogbookAdminStore } from '@/stores/admin/logbookAdminStore';
import LogbookAdminDetail from '../form/LogbookAdminDetail.vue';

//============================================================================
// Props
//============================================================================
const props = defineProps({
  pegawai: {
    type: Object as PropType<PegawaiAdminData>,
    required: true
  },
  pageDefault: {
    type: Object as PropType<PageDefaultExposed | null>,
    default: null
  }
});

//============================================================================
// Emits
//============================================================================
const emit = defineEmits<{
  back: [];
}>();

//============================================================================
// State
//============================================================================
const logbookAdminStore = useLogbookAdminStore();
const searchKeyword = ref('');
const selectedMonth = ref('');

// Initialize selected month
const now = new Date();
selectedMonth.value = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;

//============================================================================
// Computed
//============================================================================
const formattedMonth = computed(() => formatCurrentMonth());
const pegawaiLogs = computed(() => logbookAdminStore.pegawaiLogs.data || []);
const loadingLogs = computed(() => logbookAdminStore.pegawaiLogs.status === 'loading');

const groupedLogs = computed(() => {
  const groups: any[] = [];
  const dateMap = new Map();

  if (selectedMonth.value) {
    const [year, month] = selectedMonth.value.split('-');
    const daysInMonth = new Date(parseInt(year), parseInt(month), 0).getDate();

    for (let day = 1; day <= daysInMonth; day++) {
      const date = `${year}-${month}-${String(day).padStart(2, '0')}`;
      const dateObj = new Date(date);
      const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

      dateMap.set(date, {
        date,
        day: String(day).padStart(2, '0'),
        dayName: dayNames[dateObj.getDay()],
        activities: []
      });
    }
  }

  pegawaiLogs.value.forEach(log => {
    const tanggalOnly = log.tanggal.split('T')[0];
    if (dateMap.has(tanggalOnly)) {
      dateMap.get(tanggalOnly).activities.push(log);
    }
  });

  dateMap.forEach(value => groups.push(value));
  return groups;
});

//============================================================================
// Methods
//============================================================================
const onSearchUpdate = (value: string) => {
  searchKeyword.value = value;
  // TODO: Implement search filtering logic if needed
};

const viewDetailClick = (activity: LogbookAdminData) => {
  props.pageDefault?.openDrawerDefault(
    'Detail Logbook',
    LogbookAdminDetail,
    {
      record: activity,
      pegawaiName: props.pegawai.nama,
      pegawaiNip: props.pegawai.nip
    }
  );
};

const getFileCount = (activity: LogbookAdminData) => {
  return activity.file_path || activity.file_name ? 1 : 0;
};

const isWeekend = (date: string) => {
  const dateObj = new Date(date);
  const day = dateObj.getDay();
  return day === 0 || day === 6;
};

const loadPegawaiLogs = async () => {
  if (!props.pegawai?.id) {
    return;
  }

  const filters: { start_date?: string; end_date?: string } = {};
  if (selectedMonth.value) {
    const [year, month] = selectedMonth.value.split('-');
    const startDate = `${year}-${month}-01`;
    const daysInMonth = new Date(parseInt(year), parseInt(month), 0).getDate();
    filters.start_date = startDate;
    filters.end_date = `${year}-${month}-${String(daysInMonth).padStart(2, '0')}`;
  }

  await logbookAdminStore.callPegawaiLogs(props.pegawai.id, filters);
};

onMounted(() => {
  loadPegawaiLogs();
});
</script>
