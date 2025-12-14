<template>
  <div :id="id" :class="computedClasses" tabindex="-1" aria-hidden="true">
    <div class="flex flex-col w-full max-h-full p-4 bg-white rounded-sm shadow-sm">
      <div class="flex items-start justify-between w-full gap-4 mb-6">
        <h1 class="flex-1 pt-1 font-semibold text-gray-600">
          <span v-if="confirmationTitle">{{ confirmationTitle }}</span>
        </h1>
        <ButtonClose v-if="confirmationDuration === 0" @click="close" />
        <BadgeDefault v-else :variant="variant">
          <div class="flex items-center p-1 gap-x-2">
            <font-awesome-icon :icon="['fad', 'hourglass-start']" class="w-3 h-3" />
            <div>{{ countdown }}</div>
          </div>
        </BadgeDefault>
      </div>
      <div class="w-full">
        <div class="flex flex-col items-center w-full">
          <font-awesome-icon
            v-if="variant === 'info'"
            :icon="['fad', 'circle-info']"
            class="w-16 h-16 mb-4 text-info-500"
          />
          <font-awesome-icon
            v-if="variant === 'warning'"
            :icon="['fad', 'circle-exclamation']"
            class="w-16 h-16 mb-4 text-warning-500"
          />
          <font-awesome-icon
            v-if="variant === 'success'"
            :icon="['fad', 'circle-check']"
            class="w-16 h-16 mb-4 text-success-500"
          />
          <font-awesome-icon
            v-if="variant === 'danger'"
            :icon="['fad', 'triangle-exclamation']"
            class="w-16 h-16 mb-4 text-danger-500"
          />
          <h3 class="px-4 mb-2 font-normal text-center text-gray-500">
            {{ confirmationInfo }}
          </h3>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Modal, type ModalOptions } from 'flowbite'
import { v4 as uuidv4 } from 'uuid'
import { computed, onMounted, ref } from 'vue'

import BadgeDefault from '../badge/BadgeDefault.vue'
import ButtonClose from '../button/ButtonClose.vue'

import type { ModalAlertVariant, ModalPlacement } from '@/models'

const id = 'ModalAlert-' + uuidv4()

const props = defineProps({
  variant: {
    type: String as () => ModalAlertVariant,
    default: 'info'
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

const emit = defineEmits(['isVisible'])

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
      confirmationTitle.value = ''
      confirmationInfo.value = ''
      confirmationDuration.value = 0
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

const countdown = ref(0)
let timer: number | null = null

const confirmationTitle = ref<string>('')
const confirmationInfo = ref<string>('')
const confirmationDuration = ref<number>(0)

const open = (title: string, info: string, duration: number = 0) => {
  modal.show()
  confirmationTitle.value = title
  confirmationInfo.value = info
  confirmationDuration.value = duration
  if (confirmationDuration.value > 0) {
    startCountdown()
  }
}

const close = () => {
  modal.hide()
  confirmationTitle.value = ''
  confirmationInfo.value = ''
  confirmationDuration.value = 0
}

const startCountdown = () => {
  countdown.value = confirmationDuration.value / 1000
  timer = window.setInterval(() => {
    if (countdown.value > 0) {
      countdown.value--
    } else {
      stopCountdown()
      close()
    }
  }, 1000)
}

const stopCountdown = () => {
  if (timer) {
    clearInterval(timer)
    timer = null
  }
}

defineExpose({
  open,
  close
})
</script>
