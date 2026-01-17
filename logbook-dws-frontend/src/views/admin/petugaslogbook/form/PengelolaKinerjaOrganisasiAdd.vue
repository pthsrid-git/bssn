<template>
  <form class="p-0.5 w-full h-full flex flex-col gap-4 overflow-hidden">
    <div class="flex-1 overflow-hidden">
      <SectionDefault :sectionStatus="sectionStatus">
        <div class="w-full h-full flex flex-col gap-4 p-0.5 overflow-auto">
          <div class="relative flex flex-col gap-4 p-3 rounded-sm bg-gray-50">
            <FieldSelectSingle
              name="form.guid"
              label="Nama Pegawai"
              :optionsStatus="pegawaiAll.status"
              :options="filteredPegawai"
              placeholder="Pilih Pegawai"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
              @load="onPegawaiLoaded"
              @update:keyword="onPegawaiKeywordUpdated"
            />
          </div>
          <StateResponseError v-if="pengelolaKinerjaOrganisasiAdd.errorMessage" :message="pengelolaKinerjaOrganisasiAdd.errorMessage" />
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
  FieldSelectSingle,
  searchOptions,
  SectionDefault,
  StateResponseError,
  validateSelectSingle,
  type OptionData,
  type SectionStatus
} from '@bssn/ui-kit-frontend'
import { useForm } from 'vee-validate'
import { computed, onMounted, onUnmounted, ref } from 'vue'
import * as yup from 'yup'

import { usePegawaiStore } from '@/stores/pegawaiStore'
import { usePengelolaKinerjaOrganisasiStore } from '@/stores/admin/pengelolaKinerjaOrganisasiStore'

const emit = defineEmits(['close', 'done'])

const sectionStatus = ref<SectionStatus>('initial')
const filteredPegawai = ref<OptionData[]>([])

const pegawaiStore = usePegawaiStore()
const pegawaiAll = computed(() => pegawaiStore.all)
const pegawaiOptions = computed(() => pegawaiStore.options)

const pengelolaKinerjaOrganisasiStore = usePengelolaKinerjaOrganisasiStore()
const pengelolaKinerjaOrganisasiAdd = computed(() => pengelolaKinerjaOrganisasiStore.add)

const schema = yup.object({
  form: yup.object({})
})
const validationSchema = ref(schema)
const formData = {
  form: {
    guid: ''
  }
}
const { handleSubmit, isSubmitting, resetForm, submitCount } = useForm({
  validationSchema,
  initialValues: formData
})

const onSubmitPressed = () => {
  validationSchema.value = yup.object({
    form: yup.object({
      guid: validateSelectSingle({ required: true, requiredMessage: 'Pegawai harus diisi' })
    })
  })

  onSubmit()
}

const onSubmit = handleSubmit(async (values) => {
  const payload = {
    guid: values.form.guid,
    nama: pegawaiStore.options.find((pegawai) => pegawai.value === values.form.guid)?.label
  }
  await pengelolaKinerjaOrganisasiStore.callAdd(payload)
  if (pengelolaKinerjaOrganisasiAdd.value.status === 'success') {
    pengelolaKinerjaOrganisasiStore.clearAdd()
    onClose(true)
  }
})

const onClose = (isDone: boolean = false) => {
  emit('close')
  if (isDone) emit('done')
}

const onPegawaiLoaded = () => {
  filteredPegawai.value = pegawaiOptions.value
}

const onPegawaiKeywordUpdated = (value: string) => {
  filteredPegawai.value = value ? searchOptions(pegawaiOptions.value, value) : pegawaiOptions.value
}

const initialization = async () => {
  sectionStatus.value = 'loading'
  await pegawaiStore.callAll()
  if (pegawaiAll.value.status === 'success') {
    sectionStatus.value = 'success'
  } else {
    sectionStatus.value = 'error'
  }
}

const destruction = () => {
  sectionStatus.value = 'initial'
  pegawaiStore.clearAll()
  pengelolaKinerjaOrganisasiStore.clearAdd()
  resetForm({
    values: formData,
    errors: {},
    touched: {},
    submitCount: 0
  })
}

onMounted(() => {
  initialization()
})

onUnmounted(() => {
  destruction()
})
</script>
