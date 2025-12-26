<template>
  <div class="flex flex-col">
    <label v-if="label" :for="name" :class="labelComputedClasses">
      {{ label }} <span v-if="required" class="text-danger-500">*</span>
    </label>
    <input
      v-model="value"
      v-on="validationListeners"
      :id="name"
      :name="name"
      :type="type"
      :placeholder="placeholder"
      :disabled="disabled"
      :autocomplete="autocomplete"
      :class="inputComputedClasses"
    />
    <p v-if="errorMessage" class="text-sm text-danger-500 mt-[8px]">{{ errorMessage }}</p>
  </div>
</template>

<script setup lang="ts">
import debounce from 'lodash.debounce'
import { useField } from 'vee-validate'
import { computed, type PropType } from 'vue'

const props = defineProps({
  initialValue: {
    type: [String, Number] as PropType<string | number | undefined>,
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
  type: {
    type: String as PropType<'text' | 'email' | 'password' | 'number' | 'url' | 'tel'>,
    default: 'text'
  },
  placeholder: {
    type: String,
    default: ''
  },
  disabled: {
    type: Boolean,
    default: false
  },
  autocomplete: {
    type: String,
    default: 'off'
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

const labelComputedClasses = computed(() =>
  ['text-sm font-medium text-gray-900 mb-[8px]', props.labelCustomClass].join(' ')
)

const inputComputedClasses = computed(() =>
  [
    'min-w-40 block w-full px-2.5 py-2 text-sm text-gray-900 border border-gray-300 rounded-sm bg-white focus:ring-primary-400 focus:border-primary-400',
    props.disabled ? 'cursor-not-allowed opacity-50' : '',
    props.customClass
  ].join(' ')
)

const { errorMessage, value, handleBlur, handleChange } = useField(() => props.name, undefined, {
  validateOnValueUpdate: false,
  initialValue:
    props.initialValue === undefined
      ? undefined
      : props.type === 'number'
        ? Number.isNaN(Number(props.initialValue))
          ? undefined
          : Number(props.initialValue)
        : String(props.initialValue)
})

const emitDebounced = debounce((value) => {
  emit('update:value', value)
}, 300)

const validationListeners = {
  blur: (evt: Event) => handleBlur(evt, props.submitCount > 0),
  change: (evt: Event) => handleChange(evt, props.submitCount > 0),
  input: (evt: Event) => {
    handleChange(evt, props.submitCount > 0)
    const { value } = evt.target as HTMLInputElement
    emitDebounced(value)
  }
}
</script>
