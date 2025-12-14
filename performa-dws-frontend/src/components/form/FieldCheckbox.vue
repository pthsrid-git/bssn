<template>
  <div class="flex flex-col">
    <label v-if="label" :class="labelComputedClasses">
      {{ label }} <span v-if="required" class="text-danger-500">*</span>
    </label>
    <div v-for="(option, index) in options" :key="option.value" class="flex items-center mb-2">
      <input
        :id="`${name}-${index}`"
        :name="name"
        :value="String(option.value)"
        type="checkbox"
        :disabled="disabled || option.disabled"
        :checked="value.includes(String(option.value))"
        :class="checkboxComputedClasses"
        @change="updateValue($event, String(option.value))"
      />
      <label
        :for="`${name}-${index}`"
        :class="`ml-2 block text-sm ${disabled || option.disabled ? 'cursor-not-allowed opacity-50' : 'cursor-pointer'}`"
      >
        {{ option.label }}
      </label>
    </div>
    <p v-if="errorMessage" class="text-sm text-danger-500 mt-[8px]">{{ errorMessage }}</p>
  </div>
</template>

<script setup lang="ts">
import { useField } from 'vee-validate'
import { computed, type PropType } from 'vue'

import type { OptionData } from '@/models'

const props = defineProps({
  initialValue: {
    type: Array as PropType<string[] | undefined>,
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
  options: {
    type: Array as PropType<OptionData[]>,
    required: true
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

const labelComputedClasses = computed(() =>
  ['text-sm font-medium text-gray-900 mb-[8px]', props.labelCustomClass].join(' ')
)

const checkboxComputedClasses = computed(() =>
  [
    'ml-2 h-4 w-4 text-primary-500 border-gray-300 rounded-sm focus:ring-primary-400',
    props.disabled ? 'cursor-not-allowed opacity-50' : '',
    props.customClass
  ].join(' ')
)

const { errorMessage, value, handleChange } = useField(() => props.name, undefined, {
  validateOnValueUpdate: false,
  initialValue: props.initialValue?.map(String)
})

const updateValue = (evt: Event, optionValue: string) => {
  const target = evt.target as HTMLInputElement | null
  const isChecked = target?.checked ?? false

  const newValue = isChecked
    ? Array.from(new Set([...(value.value || []), optionValue]))
    : (value.value || []).filter((item) => item !== optionValue)

  value.value = newValue
  handleChange(value.value, props.submitCount > 0)
  emit('update:value', value.value)
}
</script>
