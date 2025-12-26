<template>
    <form class="p-0.5 w-full h-full flex flex-col gap-4 overflow-hidden">
        <div class="flex-1 overflow-hidden">
            <SectionDefault :sectionStatus="sectionStatus">
                <div class="w-full h-full flex flex-col gap-4 p-0.5 overflow-auto">
                    <!-- Subtitle -->
                    <p class="text-sm text-gray-500 mb-2">
                        Tambahkan aktivitas atau kegiatan harian anda di sini
                    </p>

                    <!-- Tanggal -->
                    <FieldDate
                        name="form.tanggal"
                        label="Pilih tanggal"
                        :placeholder="todayDate"
                        :disabled="isSubmitting || false"
                        :submitCount="submitCount"
                    />

                    <!-- Jam -->
                    <div class="grid grid-cols-2 gap-4">
                        <FieldTime
                            name="form.jam_mulai"
                            label="Jam Mulai"
                            :placeholder="'00:00'"
                            :disabled="isSubmitting || false"
                            :submitCount="submitCount"
                        />
                        <FieldTime
                            name="form.jam_selesai"
                            label="Jam Selesai"
                            :placeholder="'00:00'"
                            :disabled="isSubmitting || false"
                            :submitCount="submitCount"
                        />
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-6"></div>

                    <!-- SKP & Indikator -->
                    <div class="grid grid-cols-2 gap-4">
                        <FieldSelectSingle
                            name="form.rencana_hasil_kinerja_skp"
                            label="Rencana Hasil Kinerja SKP"
                            :options="skpOptions"
                            optionsStatus="success"
                            :placeholder="'Pilih rencana hasil kinerja SKP'"
                            :disabled="isSubmitting || false"
                            :submitCount="submitCount"
                        />
                        <FieldSelectSingle
                            name="form.indikator_hasil_rencana_kerja"
                            label="Indikator Hasil Rencana Kerja"
                            :options="indikatorOptions"
                            optionsStatus="success"
                            :placeholder="'Pilih indikator hasil rencana kerja SKP'"
                            :disabled="isSubmitting || false"
                            :submitCount="submitCount"
                        />
                    </div>

                    <!-- Aktivitas -->
                    <FieldSelectSingle
                        name="form.aktivitas_kegiatan_harian"
                        label="Aktivitas / Kegiatan Harian"
                        :options="aktivitasOptions"
                        optionsStatus="success"
                        :placeholder="'Pilih aktivitas / kegiatan harian sesuai jabatan'"
                        :disabled="isSubmitting || false"
                        :submitCount="submitCount"
                    />

                    <!-- Keterangan -->
                    <FieldInput
                        name="form.keterangan"
                        label="Keterangan"
                        :required="true"
                        :placeholder="'Tambahkan keterangan'"
                        :disabled="isSubmitting || false"
                        :submitCount="submitCount"
                    />

                    <!-- Upload -->
                    <FieldFile
                        name="form.file"
                        label="Bukti Aktivitas Harian"
                        :required="true"
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        :disabled="isSubmitting || false"
                        info="PDF, DOC, atau gambar (max 5MB)"
                        :submitCount="submitCount"
                    />

                    <!-- Error Message -->
                    <StateResponseError
                        v-if="logbookAdd.errorMessage"
                        :message="logbookAdd.errorMessage"
                    />
                </div>
            </SectionDefault>
        </div>

        <!-- Buttons -->
        <div class="flex gap-4">
            <ButtonDefault
                @click="onSubmitPressed"
                type="submit"
                variant="warning"
                customClass="flex-1"
                :disabled="isSubmitting"
                :loading="isSubmitting"
            >
                Tambah Kegiatan Harian
            </ButtonDefault>
            <ButtonDefault
                @click="onClose(false)"
                type="reset"
                customClass="flex-1"
                :disabled="isSubmitting"
            >
                Kembali
            </ButtonDefault>
        </div>
    </form>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import {
    ButtonDefault,
    SectionDefault,
    FieldDate,
    FieldTime,
    FieldSelectSingle,
    FieldInput,
    FieldFile,
    StateResponseError,
    validateInput,
    validateSelect,
    type SectionStatus
} from '@bssn/ui-kit-frontend'
import { useForm } from 'vee-validate'
import * as yup from 'yup'
import { useLogbookStore } from '@/stores/pegawai/logbookStore'

const emit = defineEmits(['close', 'done'])

const props = defineProps({
  onLoad: {
    type: Function,
    default: null
  }
})

const sectionStatus = ref<SectionStatus>('success')
const logbookStore = useLogbookStore()
const logbookAdd = computed(() => logbookStore.add)

// Function to get today's date in YYYY-MM-DD format
const getTodayDate = (): string => {
  const today = new Date()
  const dd = String(today.getDate()).padStart(2, '0')
  const mm = String(today.getMonth() + 1).padStart(2, '0') // Months are 0-based
  const yyyy = today.getFullYear()
  return `${dd}-${mm}-${yyyy}`
}

const todayDate = computed(() => getTodayDate())

const schema = yup.object({
  form: yup.object({})
})
const validationSchema = ref(schema)

const formData = {
  label: {
    tanggal: 'Tanggal',
    jam_mulai: 'Jam Mulai',
    jam_selesai: 'Jam Selesai',
    rencana_hasil_kinerja_skp: 'Rencana Hasil Kinerja SKP',
    indikator_hasil_rencana_kerja: 'Indikator Hasil Rencana Kerja',
    aktivitas_kegiatan_harian: 'Aktivitas / Kegiatan Harian',
    keterangan: 'Keterangan'
  },
  form: {
    tanggal: '',
    jam_mulai: '07:30',
    jam_selesai: '16:00',
    rencana_hasil_kinerja_skp: '',
    indikator_hasil_rencana_kerja: '',
    aktivitas_kegiatan_harian: '',
    keterangan: '',
    file: null as File | null,
    status: 'Disubmit'
  }
}

const { handleSubmit, isSubmitting, resetForm, submitCount } = useForm({
  validationSchema,
  initialValues: formData
})

const skpOptions = [
    { label: 'Pelayanan Administrasi', value: 'Pelayanan Administrasi' },
    { label: 'Pengembangan Sistem', value: 'Pengembangan Sistem' },
    { label: 'Koordinasi Tim', value: 'Koordinasi Tim' }
]

const indikatorOptions = [
    { label: 'Kecepatan Layanan', value: 'Kecepatan Layanan' },
    { label: 'Kualitas Output', value: 'Kualitas Output' },
    { label: 'Ketepatan Waktu', value: 'Ketepatan Waktu' }
]

const aktivitasOptions = [
    { label: 'Meeting Koordinasi', value: 'Meeting Koordinasi' },
    { label: 'Penyusunan Laporan', value: 'Penyusunan Laporan' },
    { label: 'Analisis Data', value: 'Analisis Data' },
    { label: 'Monitoring Proyek', value: 'Monitoring Proyek' },
    { label: 'Presentasi', value: 'Presentasi' },
    { label: 'Pelatihan/Training', value: 'Pelatihan/Training' }
]

// ================================================================================================
// Methods
// ================================================================================================
const onSubmitPressed = () => {
  validationSchema.value = yup.object({
    form: yup.object({
      tanggal: validateInput({
        required: true,
        requiredMessage: `${formData.label.tanggal} harus diisi`
      }),
      jam_mulai: validateInput({
        required: true,
        requiredMessage: `${formData.label.jam_mulai} harus diisi`
      }),
      jam_selesai: validateInput({
        required: true,
        requiredMessage: `${formData.label.jam_selesai} harus diisi`
      }),
      rencana_hasil_kinerja_skp: validateSelect({
        required: true,
        requiredMessage: `${formData.label.rencana_hasil_kinerja_skp} harus diisi`
      }),
      indikator_hasil_rencana_kerja: validateSelect({
        required: true,
        requiredMessage: `${formData.label.indikator_hasil_rencana_kerja} harus diisi`
      }),
      aktivitas_kegiatan_harian: validateSelect({
        required: true,
        requiredMessage: `${formData.label.aktivitas_kegiatan_harian} harus diisi`
      }),
      keterangan: validateInput({
        required: true,
        requiredMessage: `${formData.label.keterangan} harus diisi`
      }),
      file: yup.mixed()
        .required(`Bukti Aktivitas Harian harus diisi`)
        .typeError(`Bukti Aktivitas Harian harus berupa file`)
    })
  })

  onSubmit()
}

const onSubmit = handleSubmit(async (values) => {
  const payload = {
    tanggal: values.form.tanggal,
    jam_mulai: values.form.jam_mulai,
    jam_selesai: values.form.jam_selesai,
    rencana_hasil_kinerja_skp: values.form.rencana_hasil_kinerja_skp,
    indikator_hasil_rencana_kerja: values.form.indikator_hasil_rencana_kerja,
    aktivitas_kegiatan_harian: values.form.aktivitas_kegiatan_harian,
    keterangan: values.form.keterangan,
    file: values.form.file,
    status: 'Disubmit'
  }
  await logbookStore.callAdd(payload)
  if (logbookAdd.value.status === 'success') {
    logbookStore.clearAdd()
    // Reload data after successful submission
    if (props.onLoad) {
      await props.onLoad()
    }
    onClose(true)
  }
})

const onClose = (isDone: boolean = false) => {
  emit('close')
  if (isDone) emit('done')
}

// ================================================================================================
// Lifecycle
// ================================================================================================
import { onUnmounted } from 'vue'

onUnmounted(() => {
  logbookStore.clearAdd()
  resetForm()
})
</script>