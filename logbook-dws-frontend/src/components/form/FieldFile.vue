<template>
  <div class="flex flex-col">
    <label v-if="label" :for="name" :class="labelComputedClasses">
      {{ label }} <span v-if="required" class="text-danger-500">*</span>
    </label>
    <input
      :id="name"
      :name="name"
      type="file"
      :accept="accept"
      :multiple="multiple"
      :disabled="disabled"
      :class="fileInputComputedClasses"
      @change="updateValue"
    />
    <p v-if="info" class="text-xs text-gray-400 mt-[8px]">{{ info }}</p>
    <p v-if="errorMessage" class="text-sm text-danger-500 mt-[8px]">{{ errorMessage }}</p>
  </div>
</template>

<script setup lang="ts">
import { useField } from 'vee-validate'
import { computed } from 'vue'

const props = defineProps({
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
  accept: {
    type: String,
    default: ''
  },
  multiple: {
    type: Boolean,
    default: false
  },
  info: {
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

const fileInputComputedClasses = computed(() =>
  [
    'min-w-40 block w-full text-sm text-gray-900 border border-gray-300 rounded-sm bg-white focus:ring-primary-400 focus:border-primary-400 overflow-hidden leading-[16px]',
    props.disabled ? 'cursor-not-allowed opacity-50' : '',
    props.customClass
  ].join(' ')
)

const { errorMessage, value, handleChange } = useField(() => props.name, undefined, {
  validateOnValueUpdate: false
})

const updateValue = (evt: Event) => {
  const target = evt.target as HTMLInputElement | null
  const targetFiles = target?.files

  if (!targetFiles) return

  if (props.multiple) {
    const files = Array.from(targetFiles)
    value.value = files
    handleChange(files, props.submitCount > 0)
    emit('update:value', files)
  } else {
    const file = targetFiles[0] || null
    value.value = file
    handleChange(file, props.submitCount > 0)
    emit('update:value', file)
  }
}
</script>
