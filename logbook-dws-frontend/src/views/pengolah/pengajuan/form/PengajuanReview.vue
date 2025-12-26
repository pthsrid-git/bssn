<template>
  <form class="p-0.5 w-full h-full flex flex-col gap-4 overflow-hidden">
    <div class="flex-1 overflow-hidden">
      <div class="w-full h-full flex flex-col gap-4 p-0.5 overflow-auto">
        <div class="relative flex flex-col gap-4 p-3 rounded-sm bg-gray-50">
          <EntryDefault label="Nama Pegawai">
            {{ record.nama }}
          </EntryDefault>
          <EntryDefault label="Tanggal Pengajuan">
            {{ readableDate(formatDate(record.tanggalPengajuan)) }}
          </EntryDefault>
          <EntryDefault label="Periode Gaji">
            {{ record.periodeGaji }}
          </EntryDefault>
          <EntryDefault label="Keperluan">
            {{ record.keperluan ?? '-' }}
          </EntryDefault>
          <EntryDefault label="Catatan Khusus">
            {{ record.catatan ?? '-' }}
          </EntryDefault>
          <EntryDefault label="Status">
            {{ capitalizeFirst(record.status) }}
          </EntryDefault>
        </div>
        <div class="relative flex flex-col gap-4 p-3 rounded-sm bg-gray-50">
          <FieldRadio
            name="form.status"
            :label="formData.label.status"
            :options="statusOptions"
            :disabled="isSubmitting || false"
            :submitCount="submitCount"
          />
          <FieldTextarea
            name="form.keterangan"
            :label="formData.label.keterangan"
            :required="false"
            :placeholder="`Masukkan ${formData.label.keterangan}`"
            :disabled="isSubmitting || false"
            :submitCount="submitCount"
          />
        </div>
        <StateResponseError
          v-if="pengajuanEdit.errorMessage"
          :message="pengajuanEdit.errorMessage"
        />
      </div>
    </div>
    <div class="flex gap-4">
      <ButtonDefault
        @click="onSubmitPressed"
        type="submit"
        variant="warning"
        customClass="flex-1"
        :disabled="isSubmitting"
        :loading="isSubmitting"
      >
        Simpan
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
import {
  ButtonDefault,
  capitalizeFirst,
  EntryDefault,
  FieldRadio,
  FieldTextarea,
  readableDate,
  StateResponseError,
  validateRadio,
  validateTextarea,
  type OptionData
} from '@bssn/ui-kit-frontend'
import { useForm } from 'vee-validate'
import { computed, onMounted, onUnmounted, ref, type PropType } from 'vue'
import * as yup from 'yup'

import { formatDate } from '@/helpers/custom'
import type { PengajuanData } from '@/models/pengolah/pengajuan'
import { usePengajuanStore } from '@/stores/pengolah/pengajuanStore'

const props = defineProps({
  record: {
    type: Object as PropType<PengajuanData>,
    required: true
  }
})

const emit = defineEmits(['close', 'done'])

const statusOptions = ref<OptionData[]>([])

const pengajuanStore = usePengajuanStore()
const pengajuanEdit = computed(() => pengajuanStore.edit)

const schema = yup.object({
  form: yup.object({})
})
const validationSchema = ref(schema)
const formData = {
  label: {
    status: 'Keputusan',
    keterangan: 'Keterangan'
  },
  form: {
    status: '',
    keterangan: ''
  }
}
const { handleSubmit, isSubmitting, resetForm, submitCount } = useForm({
  validationSchema,
  initialValues: formData
})

// ================================================================================================
// Methods
// ================================================================================================
const onSubmitPressed = () => {
  validationSchema.value = yup.object({
    form: yup.object({
      status: validateRadio({
        required: true,
        requiredMessage: `${formData.label.status} harus diisi`
      }),
      keterangan: validateTextarea({ required: false })
    })
  })

  onSubmit()
}
const onSubmit = handleSubmit(async (values) => {
  await pengajuanStore.callEdit(props.record.id, values.form)
  if (pengajuanEdit.value.status === 'success') {
    if (props.record.status === 'dibuat' && values.form.status === 'diproses') {
      pengajuanStore.callDownload(props.record.id)
    }
    pengajuanStore.clearEdit()
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
onMounted(() => {
  if (props.record.status === 'dibuat') {
    statusOptions.value = [
      { value: 'diproses', label: 'Diproses' },
      { value: 'ditolak', label: 'Ditolak' }
    ]
  } else if (props.record.status === 'diproses') {
    statusOptions.value = [
      { value: 'selesai', label: 'Diselesaikan' },
      { value: 'ditolak', label: 'Ditolak' }
    ]
  }
})
onUnmounted(() => {
  pengajuanStore.clearEdit()
  resetForm()
})
</script>
