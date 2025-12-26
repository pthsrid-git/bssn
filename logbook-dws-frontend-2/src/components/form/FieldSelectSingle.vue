<template>
  <div class="flex flex-col">
    <label v-if="label" :for="name + '-' + 'search'" :class="labelComputedClasses">
      {{ label }} <span v-if="required" class="text-danger-500">*</span>
    </label>
    <div class="relative" ref="wrapperContainer">
      <div :class="inputComputedClasses">
        <div v-if="selectedOption">
          <div class="overflow-hidden">{{ selectedOption.label }}</div>
          <div
            v-if="!disabled"
            class="absolute inset-y-0 end-0 flex items-center p-2.5 cursor-pointer"
            @mousedown.prevent="removeValue"
          >
            <font-awesome-icon :icon="['fas', 'xmark']" class="w-3.5 h-3.5 text-gray-500" />
          </div>
        </div>
        <div v-else>
          <input
            :id="name + '-' + 'search'"
            v-model="searchKeyword"
            v-on="validationListeners"
            :placeholder="placeholder"
            :disabled="disabled"
            autocomplete="off"
            class="w-full bg-transparent focus:outline-hidden"
            @keydown.enter.prevent
          />
        </div>
      </div>
      <div
        v-show="showOptions"
        ref="optionsContainer"
        class="w-full mt-1 border border-gray-300 rounded-sm bg-white max-h-[182px] overflow-auto"
      >
        <ul>
          <template v-if="optionsStatus == 'success'">
            <template v-if="options.length > 0">
              <li
                v-for="option in options"
                :key="option.value"
                class="px-3 py-2 text-sm cursor-pointer hover:bg-primary-100"
                @mousedown.prevent="setValue(option)"
              >
                {{ option.label }}
              </li>
            </template>
            <template v-else>
              <li class="px-3 py-2 text-sm text-gray-500" @click="closeOptions()">
                Data tidak ditemukan
              </li>
            </template>
          </template>
          <template v-else-if="optionsStatus == 'error'">
            <StateError />
          </template>
          <template v-else-if="optionsStatus == 'loading'">
            <StateLoading />
          </template>
          <template v-else>
            <li class="px-3 py-2 text-sm text-gray-500" @click="closeOptions()">Data kosong</li>
          </template>
        </ul>
      </div>
    </div>
    <p v-if="errorMessage" class="text-sm text-danger-500 mt-[8px]">{{ errorMessage }}</p>
  </div>
</template>

<script setup lang="ts">
import debounce from 'lodash.debounce'
import { useField } from 'vee-validate'
import { computed, nextTick, onMounted, onUnmounted, ref, watch, type PropType } from 'vue'

import StateError from '../state/StateError.vue'
import StateLoading from '../state/StateLoading.vue'

import type { OptionData } from '@/models'

const props = defineProps({
  initialValue: {
    type: Object as PropType<OptionData | undefined>,
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
  optionsStatus: {
    type: String,
    default: 'initial'
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

const emit = defineEmits(['load', 'update:keyword', 'update:value', 'close'])

const searchKeyword = ref<string>('')
const showOptions = ref<boolean>(false)
const optionsContainer = ref<HTMLElement | null>(null)
const wrapperContainer = ref<HTMLElement | null>(null)
const selectedOption = ref<OptionData | null>(null)

const labelComputedClasses = computed(() =>
  ['text-sm font-medium text-gray-900 mb-[8px]', props.labelCustomClass].join(' ')
)

const inputComputedClasses = computed(() =>
  [
    'min-w-40 block w-full px-2.5 pe-8 ps-2.5 py-2 text-sm text-gray-900 border border-gray-300 rounded-sm bg-white',
    props.disabled ? 'cursor-not-allowed opacity-50' : '',
    showOptions.value ? 'ring-1 ring-primary-400 border-primary-400' : '',
    props.customClass
  ].join(' ')
)

const { errorMessage, value, handleChange } = useField(() => props.name, undefined, {
  validateOnValueUpdate: false,
  initialValue: props.initialValue?.value
})

if (props.initialValue) {
  selectedOption.value = props.initialValue
}

const emitTypeDebounced = debounce((value) => {
  emit('update:keyword', value)
}, 300)

const emitInitDebounced = debounce(() => {
  emit('load')
}, 30)

const validationListeners = {
  focus: () => openOptions(),
  input: (evt: Event) => {
    const { value } = evt.target as HTMLInputElement
    emitTypeDebounced(value)
  }
}

const openOptions = () => {
  if (!props.disabled && !selectedOption.value) {
    showOptions.value = true
    emitInitDebounced()
  }
}

const closeOptions = () => {
  searchKeyword.value = ''
  showOptions.value = false
  emit('close')
}

const setValue = (option: OptionData) => {
  selectedOption.value = option
  updateValue()
}

const removeValue = () => {
  selectedOption.value = null
  updateValue()
}

const updateValue = () => {
  value.value = selectedOption.value !== null ? selectedOption.value.value : ''
  handleChange(value.value, props.submitCount > 0)
  emit('update:value', value.value)

  closeOptions()
}

watch(value, (newValue) => {
  if (!newValue) {
    selectedOption.value = null
  }
})

watch(showOptions, (newVal) => {
  if (newVal) {
    nextTick(() => {
      requestAnimationFrame(() => {
        if (optionsContainer.value) {
          optionsContainer.value.scrollTop = 0
        }
      })
    })
  }
})

const clickOutsideHandler = (evt: Event) => {
  const target = evt.target as Node | null
  if (wrapperContainer.value && target && !wrapperContainer.value.contains(target)) {
    closeOptions()
  }
}

onMounted(() => {
  document.addEventListener('click', clickOutsideHandler)
})

onUnmounted(() => {
  document.removeEventListener('click', clickOutsideHandler)
})
</script>
