<template>
  <div class="flex flex-col">
    <label v-if="label" :for="name + '-' + 'search'" :class="labelComputedClasses">
      {{ label }} <span v-if="required" class="text-danger-500">*</span>
    </label>
    <div class="relative" ref="wrapperContainer">
      <div :class="inputComputedClasses">
        <div class="flex flex-wrap gap-1">
          <div
            v-for="selected in selectedOptions"
            :key="selected.value"
            class="text-xs bg-primary-500 text-white rounded-full px-1.5 py-0.5 flex items-center"
          >
            {{ selected.label }}
            <div
              v-if="!disabled"
              class="ml-1.5 cursor-pointer"
              @mousedown.prevent="removeValue(selected)"
            >
              <font-awesome-icon :icon="['fas', 'xmark']" class="w-3 h-3 text-white" />
            </div>
          </div>
          <input
            :id="name + '-' + 'search'"
            v-model="searchKeyword"
            v-on="validationListeners"
            :placeholder="placeholder"
            :disabled="disabled"
            autocomplete="off"
            class="flex-1 bg-transparent focus:outline-hidden"
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
                :class="[
                  'text-sm px-3 py-2 cursor-pointer hover:bg-primary-100',
                  selectedOptions.some((selected) => selected.value === option.value)
                    ? 'bg-primary-200'
                    : ''
                ]"
                @mousedown.prevent="toggleValue(option)"
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
    type: Array as PropType<OptionData[] | undefined>,
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
const selectedOptions = ref<OptionData[]>([])

const labelComputedClasses = computed(() =>
  ['text-sm font-medium text-gray-900 mb-[8px]', props.labelCustomClass].join(' ')
)

const inputComputedClasses = computed(() =>
  [
    'min-w-40 block w-full px-2.5 py-2 text-sm text-gray-900 border border-gray-300 rounded-sm bg-white',
    props.disabled ? 'cursor-not-allowed opacity-50' : '',
    showOptions.value ? 'ring-1 ring-primary-400 border-primary-400' : '',
    props.customClass
  ].join(' ')
)

const { errorMessage, value, handleChange } = useField(() => props.name, undefined, {
  validateOnValueUpdate: false,
  initialValue: props.initialValue?.map((option) => option.value)
})

if (props.initialValue?.length) {
  selectedOptions.value = props.initialValue
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
  if (!props.disabled) {
    showOptions.value = true
    emitInitDebounced()
  }
}

const closeOptions = () => {
  searchKeyword.value = ''
  showOptions.value = false
  emit('close')
}

const toggleValue = (option: OptionData) => {
  const isSelected = selectedOptions.value.some((selected) => selected.value === option.value)

  if (isSelected) {
    removeValue(option)
  } else {
    addValue(option)
  }
}

const addValue = (option: OptionData) => {
  selectedOptions.value.push(option)
  updateValue()
}

const removeValue = (option: OptionData) => {
  selectedOptions.value = selectedOptions.value.filter(
    (selected) => selected.value !== option.value
  )
  updateValue()
}

const updateValue = () => {
  value.value = selectedOptions.value.map((option) => option.value)
  handleChange(value.value, props.submitCount > 0)
  emit('update:value', value.value)

  searchKeyword.value = ''
  emitInitDebounced()
}

watch(value, (newValue) => {
  if (!newValue.length) {
    selectedOptions.value = []
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
