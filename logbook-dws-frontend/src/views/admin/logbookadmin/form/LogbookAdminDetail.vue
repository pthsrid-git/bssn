<template>
  <div class="flex flex-col w-full gap-4">
    <!-- Info & Status Badge -->
    <div class="flex items-center gap-2">
      <p class="text-sm text-gray-500">Berikut adalah detail logbook pegawai</p>
      <span
        :class="getLogbookStatusClass(record.status)"
        class="px-2 py-0.5 text-xs font-medium rounded-full"
      >
        {{ getLogbookStatusLabel(record.status) }}
      </span>
    </div>

    <!-- Pegawai Info, Tanggal & Waktu -->
    <div class="space-y-3">
      <div class="space-y-2">
        <div class="flex gap-16">
          <label class="text-sm font-medium text-gray-700 w-20">Tanggal</label>
          <div class="text-sm text-gray-900 font-bold">{{ formatDate(record.tanggal) }}</div>
        </div>
        <div class="flex gap-16">
          <label class="text-sm font-medium text-gray-700 w-20">Waktu</label>
          <div class="text-sm text-gray-900 font-bold">{{ record.jam_mulai }} - {{ record.jam_selesai }}</div>
        </div>
      </div>
      <div class="border-t border-gray-200"></div>
    </div>

    <!-- Rencana Hasil Kinerja SKP -->
    <FieldInput
      name="display.rencana_hasil_kinerja_skp"
      label="Rencana Hasil Kinerja SKP"
      :initialValue="record.rencana_hasil_kinerja_skp"
      :readonly="true"
      :submitCount="0"
    />

    <!-- Indikator Hasil Rencana Kerja -->
    <FieldInput
      name="display.indikator_hasil_rencana_kerja"
      label="Indikator Hasil Rencana Kerja"
      :initialValue="record.indikator_hasil_rencana_kerja"
      :readonly="true"
      :submitCount="0"
    />

    <!-- Aktivitas / Kegiatan Harian -->
    <FieldInput
      name="display.aktivitas_kegiatan_harian"
      label="Aktivitas / Kegiatan Harian"
      :initialValue="record.aktivitas_kegiatan_harian"
      :readonly="true"
      :submitCount="0"
    />

    <!-- Keterangan -->
    <FieldInput
      name="display.keterangan"
      label="Keterangan"
      :initialValue="record.keterangan || '-'"
      :readonly="true"
      :submitCount="0"
    />

    <!-- Bukti Aktivitas Harian -->
    <div v-if="record.file_path || record.file_name">
      <label class="text-sm font-medium text-gray-900 block mb-2">Bukti Aktivitas Harian</label>
      <div class="w-auto">
        <div
          @click="handleDownload"
          class="border border-gray-200 rounded-lg p-3 flex items-center justify-between hover:bg-gray-50 transition-colors cursor-pointer"
        >
          <div class="flex items-center gap-3 flex-1 min-w-0">
            <div class="w-10 h-10 bg-red-50 rounded flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="min-w-0 flex-1">
              <div class="text-sm font-medium text-gray-900 truncate">{{ getFileName(record.file_path, record.file_name) }}</div>
              <div class="text-xs text-gray-500">{{ formatFileSize(record.file_size) }}</div>
            </div>
          </div>
          <div class="text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0 ml-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Catatan Katim -->
    <FieldInput
      name="display.catatan_katim"
      label="Catatan Katim"
      :initialValue="record.catatan_katim || '-'"
      :readonly="true"
      :submitCount="0"
    />

    <!-- Catatan Atasan -->
    <FieldInput
      name="display.catatan_atasan"
      label="Catatan Atasan"
      :initialValue="record.catatan_atasan || '-'"
      :readonly="true"
      :submitCount="0"
    />
  </div>
</template>

<script setup lang="ts">
import { FieldInput } from '@bssn/ui-kit-frontend';
import type { PropType } from 'vue';
import type { LogbookAdminData } from '@/models/admin/logbookAdmin';
import {
  getLogbookStatusClass,
  getLogbookStatusLabel,
  getFileName,
  formatFileSize,
  downloadFile
} from '@/helpers/custom';

//============================================================================
// Props
//============================================================================
const props = defineProps({
  record: {
    type: Object as PropType<LogbookAdminData>,
    required: true
  },
  pegawaiName: {
    type: String,
    default: '-'
  },
  pegawaiNip: {
    type: String,
    default: '-'
  }
});

//============================================================================
// Methods
//============================================================================
const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  });
};

const handleDownload = () => {
  if (props.record.file_path) {
    downloadFile(props.record.file_path, props.record.file_name || undefined);
  }
};
</script>
