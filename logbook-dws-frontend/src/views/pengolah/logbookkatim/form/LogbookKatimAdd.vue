<template>
  <form class="p-0.5 w-full h-full flex flex-col gap-4 overflow-hidden">
    <div class="flex-1 overflow-hidden">
      <SectionDefault sectionStatus="success">
        <div class="w-full h-full flex flex-col gap-4 p-0.5 overflow-auto">
          <!-- Subtitle -->
          <div class="flex items-center gap-2">
            <p class="text-sm text-gray-500">Berikut adalah detail logbook anda</p>
            <span
                :class="getLogbookStatusClass(record.status)"
                class="px-2 py-0.5 text-xs font-medium rounded-full"
            >
                {{ getLogbookStatusLabel(record.status) }}
            </span>
        </div>

          <!-- Tanggal & Waktu -->
          <!-- <div class="grid grid-cols-2 gap-4">
            <FieldInput
              name="display.tanggal"
              label="Tanggal"
              :initialValue="formatDate(record.tanggal)"
              :disabled="true"
              :submitCount="0"
            />
            <FieldInput
              name="display.waktu"
              label="Waktu"
              :initialValue="`${record.jam_mulai} - ${record.jam_selesai}`"
              :disabled="true"
              :submitCount="0"
            />
          </div> -->
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
          </div>

          <!-- Divider -->
          <div class="border-t border-gray-200 my-2"></div>

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
                @click="downloadFile"
                class="border border-gray-200 rounded-lg p-3 flex items-center justify-between hover:bg-gray-50 transition-colors cursor-pointer"
              >
                <div class="flex items-center gap-3 flex-1 min-w-0">
                  <div class="w-10 h-10 bg-red-50 rounded flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                    </svg>
                  </div>
                  <div class="min-w-0 flex-1">
                    <div class="text-sm font-medium text-gray-900 truncate">{{ getFileName() }}</div>
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

          <!-- Form Catatan Katim -->
          <FieldInput
            name="form.catatan_katim"
            label="Catatan Katim"
            :initialValue="catatanKatim"
            :placeholder="'Masukkan catatan untuk logbook ini...'"
            :readonly="saving"
            :submitCount="submitCount"
            @update:value="catatanKatim = $event"
          />

          <!-- Catatan Atasan (Read Only) -->
          <FieldInput
            name="display.catatan_atasan"
            label="Catatan Atasan"
            :initialValue="record.catatan_atasan || '-'"
            :disabled="true"
            :submitCount="0"
          />
        </div>
      </SectionDefault>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-4">
      <ButtonDefault
        variant="warning"
        customClass="flex-1"
        @click="handleSave"
        :disabled="saving"
        :loading="saving"
      >
        Simpan Catatan
      </ButtonDefault>
      <ButtonDefault
        customClass="flex-1"
        @click="onClose"
        :disabled="saving"
      >
        Kembali
      </ButtonDefault>
    </div>
  </form>
</template>

<script setup lang="ts">
import { ref, type PropType, watch } from 'vue';
import { ButtonDefault, SectionDefault, FieldInput, validateInput } from '@bssn/ui-kit-frontend';
import { useForm } from 'vee-validate';
import * as yup from 'yup';
import type { LogbookKatimData } from '@/models/pengolah/logbookKatim';
import { formatDateIndonesian } from '@/helpers/custom';
import { getLogbookStatusClass, getLogbookStatusLabel } from '@/helpers/custom';

//============================================================================
// Props
//============================================================================
const props = defineProps({
  record: {
    type: Object as PropType<LogbookKatimData>,
    required: true
  },
  memberName: {
    type: String,
    default: '-'
  },
  memberNip: {
    type: String,
    default: '-'
  },
  onSave: {
    type: Function as PropType<(catatan: string, logId: number) => Promise<void>>,
    default: undefined
  }
});

//============================================================================
// Emits
//============================================================================
const emit = defineEmits<{
  close: [];
  done: [];
}>();

//============================================================================
// State & Form Validation
//============================================================================
const catatanKatim = ref('');
const saving = ref(false);

const schema = yup.object({
  form: yup.object({})
});
const validationSchema = ref(schema);

const formData = {
  label: {
    catatan_katim: 'Catatan Katim'
  },
  form: {
    catatan_katim: ''
  }
};

const { handleSubmit, submitCount } = useForm({
  validationSchema,
  initialValues: formData
});

// Watch for record changes to update catatanKatim
watch(() => props.record, (newRecord) => {
  if (newRecord) {
    catatanKatim.value = newRecord.catatan_katim || '';
  }
}, { immediate: true });

//============================================================================
// Methods
//============================================================================
const formatDate = formatDateIndonesian;

const getFileName = (): string => {
  if (props.record.file_name) {
    return props.record.file_name;
  }

  if (props.record.file_path) {
    const path = props.record.file_path;
    const parts = path.split('/');
    const fullFileName = parts[parts.length - 1];
    const fileName = fullFileName.replace(/^\d+_/, '');
    return fileName;
  }

  return 'File.pdf';
};

const formatFileSize = (bytes: number | string | null | undefined): string => {
  if (!bytes || bytes === 0) return '0 KB';

  const size = typeof bytes === 'string' ? parseInt(bytes) : bytes;

  if (size < 1024) {
    return `${size} B`;
  } else if (size < 1024 * 1024) {
    return `${(size / 1024).toFixed(2)} KB`;
  } else {
    return `${(size / (1024 * 1024)).toFixed(2)} MB`;
  }
};

const downloadFile = () => {
  if (!props.record.file_path) {
    console.warn('File path tidak ditemukan');
    alert('File tidak tersedia');
    return;
  }

  const baseUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000';
  const cleanBaseUrl = baseUrl.replace(/\/api$/, '');
  const fileUrl = `${cleanBaseUrl}/storage/${props.record.file_path}`;

  const link = document.createElement('a');
  link.href = fileUrl;
  link.download = getFileName();
  link.target = '_blank';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

const onSubmitPressed = () => {
  validationSchema.value = yup.object({
    form: yup.object({
      catatan_katim: validateInput({
        required: true,
        requiredMessage: `${formData.label.catatan_katim} harus diisi`
      })
    })
  });

  onSubmit();
};

const onSubmit = handleSubmit(async () => {
  if (props.onSave) {
    saving.value = true;
    try {
      await props.onSave(catatanKatim.value, props.record.id);
      emit('done');
    } finally {
      saving.value = false;
    }
  }
});

const handleSave = () => {
  onSubmitPressed();
};

const onClose = () => {
  emit('close');
};
</script>
