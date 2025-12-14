<template>
  <SectionDefault :sectionStatus="sectionStatus">
    <div class="w-full h-full p-4 bg-primary-50">
      <div class="w-full h-full max-w-md p-4 mx-auto bg-white rounded-sm">
        <form class="flex flex-col w-full h-full gap-4">
          <div class="flex flex-col flex-1 gap-4 p-1 overflow-auto">
            <!-- :initialValue="[{ value: 'a', label: 'Option 1' }]" -->
            <FieldSelectMultiple
              name="form.fieldSelectMultiple"
              label="Field Select Multiple"
              :optionsStatus="optionAll.status"
              :options="filteredOption"
              placeholder="Placeholder"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
              @load="onFieldSelectMultipleLoaded"
              @update:keyword="onFieldSelectMultipleKeywordUpdated"
              @update:value="onFieldSelectMultipleUpdated"
            />
            <!-- :initialValue="{ value: 'a', label: 'Option 1' }" -->
            <FieldSelectSingle
              name="form.fieldSelectSingle"
              label="Field Select Single"
              :optionsStatus="optionAll.status"
              :options="filteredOption"
              placeholder="Placeholder"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
              @load="onFieldSelectSingleLoaded"
              @update:keyword="onFieldSelectSingleKeywordUpdated"
              @update:value="onFieldSelectSingleUpdated"
            />
            <!-- initialValue="Initial Value" -->
            <FieldInput
              name="form.fieldInput"
              label="Field Input"
              placeholder="Placeholder"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
              @update:value="onFieldInputUpdated"
            />
            <!-- initialValue="Initial Value" -->
            <FieldTextarea
              name="form.fieldTextarea"
              label="Field Textarea"
              placeholder="Placeholder"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
              @update:value="onFieldTextareaUpdated"
            />
            <!-- initialValue="1" -->
            <FieldSelect
              name="form.fieldSelect"
              label="Field Select"
              :options="[
                { value: '1', label: 'Option 1' },
                { value: '2', label: 'Option 2' },
                { value: '3', label: 'Option 3' }
              ]"
              placeholder="Placeholder"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
              @update:value="onFieldSelectUpdated"
            />
            <!-- initialValue="1" -->
            <FieldRadio
              name="form.fieldRadio"
              label="Field Radio"
              :options="[
                { value: '1', label: 'Option 1' },
                { value: '2', label: 'Option 2' },
                { value: '3', label: 'Option 3' }
              ]"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
              @update:value="onFieldRadioUpdated"
            />
            <!-- :initialValue="['1']" -->
            <FieldCheckbox
              name="form.fieldCheckbox"
              label="Field Checkbox"
              :options="[
                { value: '1', label: 'Option 1' },
                { value: '2', label: 'Option 2' },
                { value: '3', label: 'Option 3' }
              ]"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
              @update:value="onFieldCheckboxUpdated"
            />
            <!-- initialValue="01-01-2025" -->
            <FieldDate
              name="form.fieldDate"
              label="Field Date"
              placeholder="Placeholder"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
              @update:value="onFieldDateUpdated"
            />
            <!-- initialValue="08:00" -->
            <FieldTime
              name="form.fieldTime"
              label="Field Time"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
              @update:value="onFieldTimeUpdated"
            />
            <div>
              <FieldLabel label="Field Date Range" />
              <div class="flex items-start w-full gap-x-2">
                <FieldDate
                  name="form.fieldDateRangeStart"
                  :minDate="fieldDateRangeStartMinDate"
                  :maxDate="fieldDateRangeStartMaxDate ?? fieldDateRangeEndMaxDate"
                  placeholder="Date Range Start Date"
                  :disabled="isSubmitting || false"
                  :submitCount="submitCount"
                  @update:value="onfieldDateRangeStartUpdated"
                  class="w-full"
                />
                <FieldDate
                  name="form.fieldDateRangeEnd"
                  :minDate="fieldDateRangeEndMinDate ?? fieldDateRangeStartMinDate"
                  :maxDate="fieldDateRangeEndMaxDate"
                  placeholder="Date Range End Date"
                  :disabled="isSubmitting || false"
                  :submitCount="submitCount"
                  @update:value="onfieldDateRangeEndUpdated"
                  class="w-full"
                />
              </div>
            </div>
            <div>
              <FieldLabel label="Field Date Times" />
              <div class="flex items-start w-full gap-x-2">
                <FieldDate
                  name="form.fieldDateTimesStart"
                  :minDate="fieldDateTimesStartMinDate"
                  :maxDate="fieldDateTimesStartMaxDate ?? fieldDateTimesEndMaxDate"
                  placeholder="Date Times Start Date"
                  :disabled="isSubmitting || false"
                  :submitCount="submitCount"
                  @update:value="onfieldDateTimesStartUpdated"
                  class="w-full"
                />
                <FieldDate
                  name="form.fieldDateTimesEnd"
                  :minDate="fieldDateTimesEndMinDate ?? fieldDateTimesStartMinDate"
                  :maxDate="fieldDateTimesEndMaxDate"
                  placeholder="Date Times End Date"
                  :disabled="isSubmitting || false"
                  :submitCount="submitCount"
                  @update:value="onfieldDateTimesEndUpdated"
                  class="w-full"
                />
              </div>
            </div>
            <div v-for="(item, index) in values.form.fieldDateTimesList" :key="index">
              <FieldLabel :label="`Time For ${item.tanggal}`" />
              <div class="flex items-start w-full gap-x-2">
                <FieldTime
                  :name="`form.fieldDateTimesList.${index}.waktu_mulai`"
                  :disabled="isSubmitting || false"
                  :submitCount="submitCount"
                  class="w-full"
                />
                <FieldTime
                  :name="`form.fieldDateTimesList.${index}.waktu_berakhir`"
                  :disabled="isSubmitting || false"
                  :submitCount="submitCount"
                  class="w-full"
                />
              </div>
            </div>
            <FieldFile
              name="form.fieldFilePdf"
              label="Field File Pdf"
              accept="application/pdf"
              info="Only pdf (Maks 25MB)"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
              @update:value="onFieldFilePdfUpdated"
            />
            <FieldFile
              name="form.fieldFileImage"
              label="Field File Image"
              accept="image/jpeg,image/png"
              info="Only jpeg or png (Maks 25MB)"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
              @update:value="onFieldFileImageUpdated"
            />
            <FieldFile
              name="form.fieldFileVideo"
              label="Field File Video"
              accept="video/mp4"
              info="Only mp4 (Max 100MB)"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
              @update:value="onFieldFileVideoUpdated"
            />
            <FieldFile
              name="form.fieldFileMediaSingle"
              label="Field File Media Single"
              accept="image/jpeg,image/png,video/mp4"
              info="Only jpeg, png or mp4 (Max 50MB)"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
              @update:value="onFieldFileMediaSingleUpdated"
            />
            <FieldFile
              name="form.fieldFileMediaMultiple"
              label="Field File Media Multiple"
              accept="image/jpeg,image/png,video/mp4"
              :multiple="true"
              info="Only jpeg, png or mp4 (Max 50MB)"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
              @update:value="onFieldFileMediaMultipleUpdated"
            />
            <!-- initialValue="Initial Value" -->
            <FieldSearch
              name="form.fieldSearch"
              placeholder="Placeholder"
              :disabled="isSubmitting || false"
              :submitCount="submitCount"
              @update:value="onFieldSearchUpdated"
            />
          </div>
          <div class="flex gap-4">
            <ButtonDefault
              @clicked="onSubmitPressed"
              type="submit"
              variant="warning"
              customClass="flex-1"
              :disabled="isSubmitting"
            >
              Simpan
            </ButtonDefault>
            <ButtonDefault
              @click="onReset"
              type="reset"
              customClass="flex-1"
              :disabled="isSubmitting"
            >
              Reset
            </ButtonDefault>
          </div>
        </form>
      </div>
    </div>
  </SectionDefault>
</template>

<script setup lang="ts">
import { useForm } from 'vee-validate'
import { computed, onMounted, onUnmounted, ref } from 'vue'
import * as yup from 'yup'

import ButtonDefault from '@/components/button/ButtonDefault.vue'
import FieldCheckbox from '@/components/form/FieldCheckbox.vue'
import FieldDate from '@/components/form/FieldDate.vue'
import FieldFile from '@/components/form/FieldFile.vue'
import FieldInput from '@/components/form/FieldInput.vue'
import FieldLabel from '@/components/form/FieldLabel.vue'
import FieldRadio from '@/components/form/FieldRadio.vue'
import FieldSearch from '@/components/form/FieldSearch.vue'
import FieldSelect from '@/components/form/FieldSelect.vue'
import FieldSelectMultiple from '@/components/form/FieldSelectMultiple.vue'
import FieldSelectSingle from '@/components/form/FieldSelectSingle.vue'
import FieldTextarea from '@/components/form/FieldTextarea.vue'
import FieldTime from '@/components/form/FieldTime.vue'
import SectionDefault from '@/components/section/SectionDefault.vue'
import {
  generateDateTimes,
  log,
  mapObjectToOptionData,
  searchOptions,
  validateCheckbox,
  validateDate,
  validateDateTimes,
  validateFileMultiple,
  validateFileSingle,
  validateInput,
  validateRadio,
  validateSelect,
  validateSelectMultiple,
  validateSelectSingle,
  validateTextarea,
  validateTime
} from '@/helpers'
import type { DateTimeData, OptionData, SectionStatus } from '@/models'
import { useOptionStore } from '@/stores/optionStore'

// ================================================================================================
// Properties
// ================================================================================================
const sectionStatus = ref<SectionStatus>('initial')

const optionStore = useOptionStore()
const optionAll = computed(() => optionStore.all)

const unfilteredOption = ref<OptionData[]>([])
const filteredOption = ref<OptionData[]>([])

const schema = yup.object({
  form: yup.object({})
})
const validationSchema = ref(schema)
const formData = {
  form: {
    fieldSelectMultiple: [], // also default select multiple should have initial value in <FieldSelectMultiple>
    fieldSelectSingle: '', // also default select single should have initial value in <FieldSelectSingle>
    fieldInput: '',
    fieldTextarea: '',
    fieldSelect: '',
    fieldRadio: '',
    fieldCheckbox: [],
    fieldDate: '',
    fieldTime: '',
    fieldDateRangeStart: '',
    fieldDateRangeEnd: '',
    fieldDateTimesStart: '',
    fieldDateTimesEnd: '',
    fieldDateTimesList: [] as DateTimeData[],
    fieldFilePdf: null,
    fieldFileImage: null,
    fieldFileVideo: null,
    fieldFileMediaSingle: null,
    fieldFileMediaMultiple: [],
    fieldSearch: ''
  }
}

const { handleSubmit, isSubmitting, resetForm, setFieldValue, submitCount, values } = useForm({
  validationSchema: validationSchema,
  initialValues: formData
})

// ================================================================================================
// Methods
// ================================================================================================
const onSubmitPressed = () => {
  validationSchema.value = yup.object({
    form: yup.object({
      fieldSelectMultiple: validateSelectMultiple({ required: true }),
      fieldSelectSingle: validateSelectSingle({ required: true }),
      fieldInput: validateInput({ required: true }),
      fieldTextarea: validateTextarea({ required: true }),
      fieldSelect: validateSelect({ required: true }),
      fieldRadio: validateRadio({ required: true }),
      fieldCheckbox: validateCheckbox({ required: true }),
      fieldDate: validateDate({ required: true }),
      fieldTime: validateTime({ required: true }),
      fieldDateRangeStart: validateDate({
        required: true,
        requiredMessage: 'Field Date Range Start is required'
      }),
      fieldDateRangeEnd: validateDate({
        required: true,
        requiredMessage: 'Field Date Range End is required'
      }),
      fieldDateTimesStart: validateDate({
        required: true,
        requiredMessage: 'Field Date Times Start is required'
      }),
      fieldDateTimesEnd: validateDate({
        required: true,
        requiredMessage: 'Field Date Times End is required'
      }),
      fieldDateTimesList: validateDateTimes(),
      fieldFilePdf: validateFileSingle({
        required: true,
        fileSize: 25,
        fileSizeMessage: 'File must be less than 25MB',
        fileTypes: ['application/pdf'],
        fileTypesMessage: 'Only PDF files are allowed'
      }),
      fieldFileImage: validateFileSingle({
        required: true,
        fileSize: 25,
        fileSizeMessage: 'File must be less than 25MB',
        fileTypes: ['image/jpeg', 'image/png'],
        fileTypesMessage: 'Only JPEG or PNG image files are allowed'
      }),
      fieldFileVideo: validateFileSingle({
        required: true,
        fileSize: 100,
        fileSizeMessage: 'File must be less than 100MB',
        fileTypes: ['video/mp4'],
        fileTypesMessage: 'Only MP4 video files are allowed'
      }),
      fieldFileMediaSingle: validateFileSingle({
        required: true,
        fileSize: 50,
        fileSizeMessage: 'File must be less than 50MB',
        fileTypes: ['image/jpeg', 'image/png', 'video/mp4'],
        fileTypesMessage: 'Only JPEG, PNG or MP4 files are allowed'
      }),
      fieldFileMediaMultiple: validateFileMultiple({
        required: true,
        fileSize: 50,
        fileSizeMessage: 'File must be less than 50MB',
        fileTypes: ['image/jpeg', 'image/png', 'video/mp4'],
        fileTypesMessage: 'Only JPEG, PNG or MP4 files are allowed'
      }),
      fieldSearch: yup.string().required('Field Search is required')
    })
  })

  onSubmit()
}
const onSubmit = handleSubmit((values) => {
  return new Promise((resolve) => {
    setTimeout(() => {
      resolve(JSON.stringify(values, null, 2))
      log(values)
    }, 1000)
  })
})

const onReset = () => {
  resetForm({
    values: formData,
    errors: {},
    touched: {},
    submitCount: 0
  })
}

// fieldSelectMultiple
const onFieldSelectMultipleLoaded = () => {
  log('Field Select Multiple Loaded')
  filteredOption.value = unfilteredOption.value
}
const onFieldSelectMultipleKeywordUpdated = (value: string) => {
  log('Field Select Multiple Keyword Updated:', value)
  filteredOption.value = value
    ? searchOptions(unfilteredOption.value, value)
    : unfilteredOption.value
}
const onFieldSelectMultipleUpdated = (value: string) => {
  log('Field Select Multiple Updated:', value)
}

// fieldSelectSingle
const onFieldSelectSingleLoaded = () => {
  log('Field Select Single Loaded')
  filteredOption.value = unfilteredOption.value
}
const onFieldSelectSingleKeywordUpdated = (value: string) => {
  log('Field Select Single Keyword Updated:', value)
  filteredOption.value = value
    ? searchOptions(unfilteredOption.value, value)
    : unfilteredOption.value
}
const onFieldSelectSingleUpdated = (value: string) => {
  log('Field Select Single Updated:', value)
}

// fieldInput
const onFieldInputUpdated = (value: string) => {
  log('Field Input Updated:', value)
}

// fieldTextarea
const onFieldTextareaUpdated = (value: string) => {
  log('Field Textarea Updated:', value)
}

// fieldSelect
const onFieldSelectUpdated = (value: string) => {
  log('Field Select Updated:', value)
}

// fieldRadio
const onFieldRadioUpdated = (value: string) => {
  log('Field Radio Updated:', value)
}

// fieldCheckbox
const onFieldCheckboxUpdated = (values: string[]) => {
  log('Field Checkbox Updated:', values)
}

// fieldDate
const onFieldDateUpdated = (value: string) => {
  log('Field Date Updated:', value)
}

// fieldTime
const onFieldTimeUpdated = (value: string) => {
  log('Field Time Updated:', value)
}

// fieldDateRangeStart | fieldDateRangeEnd
const fieldDateRangeStartMinDate = ref()
const fieldDateRangeStartMaxDate = ref()
const fieldDateRangeEndMinDate = ref()
const fieldDateRangeEndMaxDate = ref()
const onfieldDateRangeStartUpdated = (value: string) => {
  fieldDateRangeEndMinDate.value = value
}
const onfieldDateRangeEndUpdated = (value: string) => {
  fieldDateRangeStartMaxDate.value = value
}

// fieldDateTimesStart | fieldDateTimesEnd | fieldDateTimesList
const fieldDateTimesStartMinDate = ref()
const fieldDateTimesStartMaxDate = ref()
const fieldDateTimesEndMinDate = ref()
const fieldDateTimesEndMaxDate = ref()
const updateFieldDateTimes = (start: string, end: string) => {
  const list = generateDateTimes(start, end, values.form.fieldDateTimesList)
  setFieldValue('form.fieldDateTimesList', list)
}
const onfieldDateTimesStartUpdated = (value: string) => {
  fieldDateTimesEndMinDate.value = value
  if (fieldDateTimesStartMaxDate.value) {
    updateFieldDateTimes(value, fieldDateTimesStartMaxDate.value)
  }
}
const onfieldDateTimesEndUpdated = (value: string) => {
  fieldDateTimesStartMaxDate.value = value
  if (fieldDateTimesEndMinDate.value) {
    updateFieldDateTimes(fieldDateTimesEndMinDate.value, value)
  }
}

// fieldFilePdf
const onFieldFilePdfUpdated = (value: File) => {
  log('Field File Pdf Updated:', value)
}

// fieldFileImage
const onFieldFileImageUpdated = (value: File) => {
  log('Field File Image Updated:', value)
}

// fieldFileVideo
const onFieldFileVideoUpdated = (value: File) => {
  log('Field File Video Updated:', value)
}

// fieldFileMediaSingle
const onFieldFileMediaSingleUpdated = (value: File) => {
  log('Field File Media Single Updated:', value)
}

// fieldFileMediaMultiple
const onFieldFileMediaMultipleUpdated = (values: File[]) => {
  log('Field File Media Multiple Updated:', values)
}

// fieldSearch
const onFieldSearchUpdated = (value: string) => {
  log('Field Search Updated:', value)
}

// ================================================================================================
// Lifecycle
// ================================================================================================
const initialization = () => {
  sectionStatus.value = 'loading'
  Promise.all([optionStore.callAll()])
    .then(() => {
      if (optionAll.value.status == 'success') {
        unfilteredOption.value = mapObjectToOptionData(
          optionAll.value.data!,
          (item) => item.id,
          (item) => item.name
        )

        sectionStatus.value = 'success'
      } else {
        sectionStatus.value = 'error'
      }
    })
    .catch((error) => {
      log(error)
      sectionStatus.value = 'error'
    })
}
const destruction = () => {
  sectionStatus.value = 'initial'
  optionStore.clearAll()
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
