<template>
  <div :id="id" :class="computedClasses" tabindex="-1" aria-hidden="true">
    <div class="flex flex-col w-full max-h-full p-4 bg-white rounded-sm shadow-sm">
      <div class="flex items-start justify-between w-full gap-4 mb-6">
        <h1 class="flex-1 pt-1 font-semibold text-gray-600">
          <span v-if="confirmationTitle">{{ confirmationTitle }}</span>
        </h1>
        <ButtonClose :disabled="loading" @click="close" />
      </div>
      <div class="w-full">
        <div class="flex flex-col items-center w-full">
          <font-awesome-icon
            :icon="['fad', 'circle-exclamation']"
            class="w-16 h-16 mb-4 text-warning-500"
          />
          <h3 class="px-4 mb-8 font-normal text-center text-gray-600">
            {{ confirmationInfo }}
          </h3>
          <div class="flex flex-row items-center justify-center w-full gap-4">
            <ButtonDefault
              class="flex-1"
              @click="confirm"
              :variant="variant"
              :disabled="loading"
              :loading="loading"
            >
              {{ confirmText }}
            </ButtonDefault>
            <ButtonDefault class="flex-1" @click="cancel" :disabled="loading">
              {{ cancelText }}
            </ButtonDefault>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Modal, type ModalOptions } from 'flowbite'
import { v4 as uuidv4 } from 'uuid'
import { computed, onMounted, ref } from 'vue'

import ButtonClose from '../button/ButtonClose.vue'
import ButtonDefault from '../button/ButtonDefault.vue'

import type { ModalConfirmationVariant, ModalPlacement } from '@/models'

const id = 'ModalConfirmation-' + uuidv4()

const props = defineProps({
  variant: {
    type: String as () => ModalConfirmationVariant,
    default: 'primary'
  },
  confirmText: {
    type: String,
    default: ''
  },
  cancelText: {
    type: String,
    default: ''
  },
  loading: {
    type: Boolean,
    default: false
  },
  placement: {
    type: String as () => ModalPlacement,
    default: 'center'
  },
  maxWidthClass: {
    type: String,
    default: 'max-w-lg'
  },
  customClass: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['confirm', 'cancel', 'isVisible'])

let modal: Modal

onMounted(() => {
  const targetEl = document.getElementById(id)

  const options: ModalOptions = {
    placement: props.placement,
    backdrop: 'static',
    backdropClasses: 'bg-gray-900/60 fixed inset-0 z-500',
    closable: false,
    onShow: () => {
      isVisible(true)
    },
    onHide: () => {
      isVisible(false)
      blurElement(targetEl)
    }
  }

  const instanceOptions = {
    id: id,
    override: true
  }

  modal = new Modal(targetEl, options, instanceOptions)
})

const blurElement = (targetEl: HTMLElement | null = null) => {
  const active = document.activeElement
  if (targetEl && active instanceof HTMLElement && targetEl.contains(active)) {
    active.blur()
  }
}

const computedPlacementClass = computed(() => {
  const placement = props.placement
  if (placement === 'top-left') {
    return 'left-0 top-0 bottom-0 justify-start items-start'
  } else if (placement === 'top-center') {
    return 'left-0 top-0 right-0 bottom-0 justify-center items-start'
  } else if (placement === 'top-right') {
    return 'top-0 right-0 bottom-0 justify-end items-start'
  } else if (placement === 'center-left') {
    return 'left-0 top-0 bottom-0 justify-start items-center'
  } else if (placement === 'center') {
    return 'left-0 top-0 right-0 bottom-0 justify-center items-center'
  } else if (placement === 'center-right') {
    return 'top-0 right-0 bottom-0 justify-end items-center'
  } else if (placement === 'bottom-left') {
    return 'left-0 top-0 bottom-0 justify-start items-end'
  } else if (placement === 'bottom-center') {
    return 'left-0 top-0 right-0 bottom-0 justify-center items-end'
  } else if (placement === 'bottom-right') {
    return 'top-0 right-0 bottom-0 justify-end items-end'
  } else {
    return 'left-0 top-0 right-0 bottom-0'
  }
})

const computedClasses = computed(() =>
  [
    'fixed m-auto p-4 z-600 hidden w-full overflow-x-hidden animate-fadeInScale',
    computedPlacementClass.value,
    props.maxWidthClass,
    props.customClass
  ].join(' ')
)

const isVisible = (value: boolean) => {
  emit('isVisible', value)
}

const confirmationId = ref<string>('')
const confirmationTitle = ref<string>('')
const confirmationInfo = ref<string>('')
const confirmationData = ref<Record<string, any>>({})

const open = (id: string, title: string, info: string, data: Record<string, any> = {}) => {
  modal.show()
  confirmationId.value = id
  confirmationTitle.value = title
  confirmationInfo.value = info
  confirmationData.value = data
}

const close = () => {
  modal.hide()
  confirmationId.value = ''
  confirmationTitle.value = ''
  confirmationInfo.value = ''
  confirmationData.value = {}
}

const confirm = () => {
  emit('confirm', confirmationId.value, confirmationData.value)
}

const cancel = () => {
  emit('cancel')
}

defineExpose({
  open,
  close
})
</script>
