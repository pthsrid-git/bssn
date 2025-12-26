<template>
  <div class="flex flex-col">
    <div class="relative">
      <div class="absolute inset-y-0 start-0 flex items-center p-2.5 pointer-events-none">
        <font-awesome-icon :icon="['fad', 'magnifying-glass']" class="w-4 h-4 text-gray-500" />
      </div>
      <input
        v-model="value"
        v-on="validationListeners"
        :id="name"
        :name="name"
        type="search"
        :placeholder="placeholder"
        :disabled="disabled"
        autocomplete="off"
        :class="inputComputedClasses"
      />
    </div>
    <p v-if="errorMessage" class="text-sm text-danger-500 mt-[8px]">{{ errorMessage }}</p>
  </div>
</template>

<script setup lang="ts">
import debounce from 'lodash.debounce'
import { useField } from 'vee-validate'
import { computed, type PropType } from 'vue'

const props = defineProps({
  initialValue: {
    type: String as PropType<string | undefined>,
    default: undefined
  },
  name: {
    type: String,
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
