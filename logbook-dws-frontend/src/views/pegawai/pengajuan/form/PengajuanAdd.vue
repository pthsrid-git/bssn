<template>
  <form class="p-0.5 w-full h-full flex flex-col gap-4 overflow-hidden">
    <div class="flex-1 overflow-hidden">
      <SectionDefault :sectionStatus="sectionStatus">
        <div class="w-full h-full flex flex-col gap-4 p-0.5 overflow-auto">
          <div class="relative flex flex-col gap-4 p-3 rounded-sm bg-gray-50">
            <div class="flex items-start w-full gap-x-2">
              <FieldSelect
                name="form.tahun"
                :label="formData.label.tahun"
                :options="periodeTahunOptions"
                :placeholder="`Pilih ${formData.label.tahun}`"
                :disabled="isSubmitting || false"
                :submitCount="submitCount"
                class="w-full"
                @update:value="onTahunUpdated"
              />
              <FieldSelect
                name="form.periode_gaji"
                :label="formData.label.periode_gaji"
                :options="periodeBulanOptions"
                :placeholder="`Pilih ${formData.label.periode_gaji}`"
                :disabled="isSubmitting || false || periodeBulanOptions.length === 0"
                :submitCount="submitCount"
                class="w-full"
              />
            </div>
            <div class="flex flex-col gap-2">
              <FieldTextarea
                name="form.keperluan"
                :label="formData.label.keperluan"
                :required="false"
                :placeholder="`Masukkan ${formData.label.keperluan}`"
                :disabled="isSubmitting || false"
                :submitCount="submitCount"
              />
              <p class="text-xs text-right">*Pastikan menyertakan nama lembaga keuangan</p>
            </div>
            <FieldTextarea
              name="form.catatan"
              :label="formData.label.catatan"
              :required="false"
              :placeholder="`Masukkan ${formData.label.catatan}`"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
            />
          </div>
          <StateResponseError
            v-if="pengajuanAdd.errorMessage"
            :message="pengajuanAdd.errorMessage"
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
        Ajukan
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
  FieldSelect,
  FieldTextarea,
  SectionDefault,
  StateResponseError,
  validateInput,
  validateSelect,
  validateTextarea,
  type SectionStatus
} from '@bssn/ui-kit-frontend'
import { useForm } from 'vee-validate'
import { computed, onMounted, onUnmounted, ref } from 'vue'
import * as yup from 'yup'

import { usePengajuanStore } from '@/stores/pegawai/pengajuanStore'
import { usePeriodeStore } from '@/stores/periode'

const emit = defineEmits(['close', 'done'])

const sectionStatus = ref<SectionStatus>('initial')

const periodeStore = usePeriodeStore()
const periodeAll = computed(() => periodeStore.all)
const periodeTahunOptions = computed(() => periodeStore.tahunOptions)
const periodeBulanOptions = computed(() => periodeStore.bulanOptions)
const pengajuanStore = usePengajuanStore()
const pengajuanAdd = computed(() => pengajuanStore.add)

const schema = yup.object({
  form: yup.object({})
})
const validationSchema = ref(schema)
const formData = {
  label: {
    tahun: 'Tahun',
    periode_gaji: 'Bulan',
    keperluan: 'Keperluan',
    catatan: 'Catatan Khusus'
  },
  form: {
    tahun: '',
    periode_gaji: '',
    keperluan: '',
    catatan: ''
  }
}
const { handleSubmit, isSubmitting, resetField, resetForm, submitCount } = useForm({
  validationSchema,
  initialValues: formData
})

// ================================================================================================
// Methods
// ================================================================================================
const onSubmitPressed = () => {
  validationSchema.value = yup.object({
    form: yup.object({
      tahun: validateSelect({
        required: true,
        requiredMessage: `${formData.label.tahun} harus diisi`
      }),
      periode_gaji: validateSelect({
        required: true,
        requiredMessage: `${formData.label.periode_gaji} harus diisi`
      }),
      keperluan: validateInput({ required: false }),
      catatan: validateTextarea({ required: false })
    })
  })

  onSubmit()
}
const onSubmit = handleSubmit(async (values) => {
  const payload = { tanggal_pengajuan: getTodayDate(), ...values.form }
  await pengajuanStore.callAdd(payload)
  if (pengajuanAdd.value.status === 'success') {
    pengajuanStore.clearAdd()
    onClose(true)
  }
})
const onClose = (isDone: boolean = false) => {
  emit('close')
  if (isDone) emit('done')
}

const getTodayDate = (): string => {
  const today = new Date()
  const dd = String(today.getDate()).padStart(2, '0')
  const mm = String(today.getMonth() + 1).padStart(2, '0') // Months are 0-based
  const yyyy = today.getFullYear()
  return `${yyyy}-${mm}-${dd}`
}

const onTahunUpdated = (value: string) => {
  periodeStore.selectedTahun = value
  resetField('form.periode_gaji')
}

// ================================================================================================
// Lifecycle
// ================================================================================================
onMounted(async () => {
  sectionStatus.value = 'loading'
  await periodeStore.callAll()
  if (periodeAll.value.status === 'success') {
    sectionStatus.value = 'success'
  } else {
    sectionStatus.value = 'error'
  }
})
onUnmounted(() => {
  periodeStore.clearAll()
  pengajuanStore.clearAdd()
  resetForm()
})
</script>
