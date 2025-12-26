<template>
  <form class="p-0.5 w-full h-full flex flex-col gap-4 overflow-hidden">
    <div class="flex-1 overflow-hidden">
      <SectionDefault :sectionStatus="sectionStatus">
        <div class="w-full h-full flex flex-col gap-4 p-0.5 overflow-auto">
          <div class="relative flex flex-col gap-4 p-3 rounded-sm bg-gray-50">
            <FieldSelect
              name="form.jenis"
              :label="formData.label.jenis"
              :options="jenisOptions"
              :placeholder="`Pilih ${formData.label.jenis}`"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
            />
            <FieldFile
              name="form.file"
              :label="formData.label.file"
              accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
              info="CSV / Excel (Maks 50MB)"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
            />
          </div>
          <StateResponseError
            v-if="pendapatanAdd.errorMessage"
            :message="pendapatanAdd.errorMessage"
          />
        </div>
      </SectionDefault>
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
  FieldFile,
  FieldSelect,
  SectionDefault,
  StateResponseError,
  validateFileSingle,
  validateSelect,
  type SectionStatus
} from '@bssn/ui-kit-frontend'
import { useForm } from 'vee-validate'
import { computed, onMounted, onUnmounted, ref } from 'vue'
import * as yup from 'yup'

import { usePendapatanStore } from '@/stores/admin/pendapatanStore'

const emit = defineEmits(['close', 'done'])

const sectionStatus = ref<SectionStatus>('initial')
const jenisOptions = [
  { value: 'gaji', label: 'Gaji' },
  { value: 'tunjangankinerja', label: 'Tunjangan Kinerja' },
  { value: 'tunjanganmakan', label: 'Tunjangan Makan' },
  { value: 'potongan', label: 'Potongan' }
]

const pendapatanStore = usePendapatanStore()
const pendapatanAdd = computed(() => pendapatanStore.add)

const schema = yup.object({
  form: yup.object({})
})
const validationSchema = ref(schema)
const formData = {
  label: {
    jenis: 'Jenis Pendapatan',
    file: 'File Pendapatan'
  },
  form: {
    jenis: '',
    file: null
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
      jenis: validateSelect({
        required: true,
        requiredMessage: `${formData.label.jenis} harus diisi`
      }),
      file: validateFileSingle({
        required: true,
        requiredMessage: `${formData.label.file} harus diisi`,
        fileSize: 50,
        fileSizeMessage: 'Ukuran file harus kurang dari 50MB',
        fileTypes: [
          'text/csv',
          'application/vnd.ms-excel',
          'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ],
        fileTypesMessage: 'Hanya file PDF, Excel, atau CSV yang diperbolehkan'
      })
    })
  })

  onSubmit()
}
const onSubmit = handleSubmit(async (values) => {
  await pendapatanStore.callAdd(values.form)
  if (pendapatanAdd.value.status === 'success') {
    pendapatanStore.clearAdd()
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
  sectionStatus.value = 'success'
})
onUnmounted(() => {
  sectionStatus.value = 'initial'
  pendapatanStore.clearAdd()
  resetForm()
})
</script>
