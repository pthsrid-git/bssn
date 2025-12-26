<template>
  <div class="flex flex-col">
    <label v-if="label" :for="name" :class="labelComputedClasses">
      {{ label }} <span v-if="required" class="text-danger-500">*</span>
    </label>
    <div class="relative">
      <div class="absolute inset-y-0 flex items-center pointer-events-none start-0 ps-2">
        <font-awesome-icon :icon="['fad', 'calendar-days']" class="w-4 h-4 text-gray-500" />
      </div>
      <input
        v-model="value"
        v-on="validationListeners"
        :id="name"
        :name="name"
        type="text"
        :placeholder="placeholder"
        :disabled="disabled"
        autocomplete="off"
        :class="inputComputedClasses"
        readonly
      />
    </div>
    <p class="text-xs text-gray-400 mt-[8px]">Format DD-MM-YYYY</p>
    <p v-if="errorMessage" class="text-sm text-danger-500 mt-[8px]">{{ errorMessage }}</p>
  </div>
</template>

<script setup lang="ts">
import { Datepicker, type DatepickerOptions } from 'flowbite'
import debounce from 'lodash.debounce'
import { useField } from 'vee-validate'
import { computed, nextTick, onBeforeUnmount, onMounted, watch, type PropType } from 'vue'

const props = defineProps({
  initialValue: {
    type: String as PropType<string | undefined>,
    default: undefined
  },
  name: {
    type: String,
    required: true
  },
  label: {
    type: String,
    default: ''
  },
  required: {
    type: Boolean,
    default: true
  },
  minDate: {
    type: String,
    required: false
  },
  maxDate: {
    type: String,
    required: false
  },
  placeholder: {
    type: String,
    default: ''
  },
  disabled: {
    type: Boolean,
    default: false
  },
  labelCustomClass: {
    type: String,
    default: ''
  },
  customClass: {
    type: String,
    default: ''
  },
  submitCount: {
    type: Number,
    default: 0
  }
})

const emit = defineEmits(['update:value'])

let datepickerInstance: Datepicker

const initDatepicker = () => {
  if (datepickerInstance) {
    destroyDatepicker()
  }

  const targetEl = document.getElementById(props.name)

  const options: DatepickerOptions = {
    autohide: true,
    format: 'dd-mm-yyyy',
    minDate: props.minDate,
    maxDate: props.maxDate,
    orientation: 'bottom',
    buttons: false,
    autoSelectToday: 0,
    title: null,
    rangePicker: false,
    onShow: () => {},
    onHide: () => {}
  }

  const instanceOptions = {
    id: props.name,
    override: true
  }

  datepickerInstance = new Datepicker(targetEl, options, instanceOptions)

  const datepickerElements = Array.from(
    document.querySelectorAll('body > .datepicker.datepicker-dropdown.dropdown')
  ) as HTMLElement[]

  if (datepickerElements.length > 0) {
    datepickerElements.forEach((element) => {
      element.style.setProperty('z-index', '600', 'important')
      element.classList.remove('top-0')
      element.classList.remove('left-0')
    })
  }
}

const destroyDatepicker = () => {
  if (datepickerInstance) {
    datepickerInstance.destroy()
  }
}

watch(
  () => [props.minDate, props.maxDate],
  () => {
    nextTick(() => initDatepicker())
  }
)

onMounted(() => {
  initDatepicker()
})

onBeforeUnmount(() => {
  destroyDatepicker()
})

const labelComputedClasses = computed(() =>
  ['text-sm font-medium text-gray-900 mb-[8px]', props.labelCustomClass].join(' ')
)

const inputComputedClasses = computed(() =>
  [
    'min-w-40 block w-full ps-8 pe-2.5 py-2 text-sm text-gray-900 border border-gray-300 rounded-sm bg-white focus:ring-primary-400 focus:border-primary-400',
    props.disabled ? 'cursor-not-allowed opacity-50' : '',
    props.customClass
  ].join(' ')
)

const { errorMessage, value, handleBlur, handleChange } = useField(() => props.name, undefined, {
  validateOnValueUpdate: false,
  initialValue: props.initialValue
})

const emitDebounced = debounce((value) => {
  emit('update:value', value)
}, 30)

const validationListeners = {
  blur: (evt: Event) => handleBlur(evt, props.submitCount > 0),
  change: (evt: Event) => handleChange(evt, props.submitCount > 0),
  input: (evt: Event) => {
    handleChange(evt, props.submitCount > 0)
    const { value } = evt.target as HTMLInputElement
    emitDebounced(value)
  },
  changeDate: (evt: Event) => {
    handleChange(evt, props.submitCount > 0)
    const { value } = evt.target as HTMLInputElement
    emitDebounced(value)
  }
}
</script>
