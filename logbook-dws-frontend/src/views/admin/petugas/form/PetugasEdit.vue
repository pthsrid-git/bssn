<template>
  <form class="p-0.5 w-full h-full flex flex-col gap-4 overflow-hidden">
    <div class="flex-1 overflow-hidden">
      <div class="w-full h-full flex flex-col gap-4 p-0.5 overflow-auto">
        <div class="relative flex flex-col gap-4 p-3 rounded-sm bg-gray-50">
          <EntryDefault label="Pegawai">
            {{ record.name }}
          </EntryDefault>
        </div>
        <div class="relative flex flex-col gap-4 p-3 rounded-sm bg-gray-50">
          <FieldSelect
            name="form.role"
            label="Role"
            :options="filteredRole"
            placeholder="Pilih Role"
            :disabled="isSubmitting || false"
            :submitCount="submitCount"
          />
        </div>
        <StateResponseError v-if="petugasEdit.errorMessage" :message="petugasEdit.errorMessage" />
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
  EntryDefault,
  FieldSelect,
  StateResponseError,
  validateSelect
} from '@bssn/ui-kit-frontend'
import { useForm } from 'vee-validate'
import { computed, onMounted, onUnmounted, ref, type PropType } from 'vue'
import * as yup from 'yup'

import type { PetugasData } from '@/models/admin/petugas'
import { usePetugasStore } from '@/stores/admin/petugasStore'

const props = defineProps({
  record: {
    type: Object as PropType<PetugasData>,
    required: true
  },
  role: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['close', 'done'])

const filteredRole = [
  { value: 'admin', label: 'Admin Keuangan' },
  { value: 'pengolah', label: 'Pengolah Logbook' }
]

const petugasStore = usePetugasStore()
const petugasEdit = computed(() => petugasStore.edit)

const schema = yup.object({
  form: yup.object({})
})
const validationSchema = ref(schema)
const formData = {
  form: {
    role: ''
  }
}
const { handleSubmit, isSubmitting, resetForm, setValues, submitCount } = useForm({
  validationSchema,
  initialValues: formData
})

// ================================================================================================
// Methods
// ================================================================================================
const onSubmitPressed = () => {
  validationSchema.value = yup.object({
    form: yup.object({
      role: validateSelect({ required: true, requiredMessage: 'Role harus diisi' })
    })
  })

  onSubmit()
}
const onSubmit = handleSubmit(async (values) => {
  const payload = {
    guid: props.record.guid,
    ...values.form
  }
  await petugasStore.callEdit(payload)
  if (petugasEdit.value.status === 'success') {
    petugasStore.clearEdit()
    onClose(true)
  }
})
const onClose = (isDone: boolean = false) => {
  emit('close')
  if (isDone) emit('done')
}

// ================================================================================================
// Initialization & Destruction
// ================================================================================================
const initialization = () => {
  formData.form.role = props.role
  setValues(formData, false)
}
const destruction = () => {
  petugasStore.clearEdit()
  resetForm({
    values: formData,
    errors: {},
    touched: {},
    submitCount: 0
  })
}

// ================================================================================================
// Lifecycle
// ================================================================================================
onMounted(() => {
  initialization()
})
onUnmounted(() => {
  destruction()
})
</script>
