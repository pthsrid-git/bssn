<template>
  <div class="flex flex-col">
    <label v-if="label" :for="name" :class="labelComputedClasses">
      {{ label }} <span v-if="required" class="text-danger-500">*</span>
    </label>
    <select
      v-model="value"
      :id="name"
      :name="name"
      :disabled="disabled"
      :class="selectComputedClasses"
      @change="updateValue"
    >
      <option :disabled="required" value="">
        {{ placeholder }}
      </option>
      <option v-for="(option, index) in options" :key="index" :value="option.value">
        {{ option.label }}
      </option>
    </select>
    <p v-if="errorMessage" class="text-sm text-danger-500 mt-[8px]">{{ errorMessage }}</p>
  </div>
</template>

<script setup lang="ts">
import { useField } from 'vee-validate'
import { computed, type PropType } from 'vue'

import type { OptionData } from '@/models'

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
  options: {
    type: Array as PropType<OptionData[]>,
    required: true
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

const labelComputedClasses = computed(() =>
  ['text-sm font-medium text-gray-900 mb-[8px]', props.labelCustomClass].join(' ')
)

const selectComputedClasses = computed(() =>
  [
    'min-w-40 block w-full ps-2.5 pe-8 py-2 text-sm text-gray-900 border border-gray-300 rounded-sm bg-white focus:ring-primary-400 focus:border-primary-400',
    props.disabled ? 'cursor-not-allowed opacity-50' : '',
    props.customClass
  ].join(' ')
)

const { errorMessage, value, handleChange } = useField(() => props.name, undefined, {
  validateOnValueUpdate: false,
  initialValue: props.initialValue
})

const updateValue = (evt: Event) => {
  handleChange(evt, props.submitCount > 0)
  const rawValue = (evt.target as HTMLSelectElement).value
  const optionValue = typeof props.initialValue === 'number' ? Number(rawValue) : rawValue
  emit('update:value', optionValue)
}
</script>
