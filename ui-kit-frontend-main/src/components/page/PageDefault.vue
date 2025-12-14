<template>
  <div class="w-full h-full">
    <template v-if="pageStatus === 'success'">
      <div v-show="!viewerFileVisible" class="w-full h-full">
        <slot></slot>
      </div>
      <div
        v-if="isViewerFile && viewerFileVisible"
        class="w-full h-full overflow-auto animate-fadeInScale"
      >
        <ViewerFile :title="viewerFileTitle" :url="viewerFileUrl" @close="closeViewerFile" />
      </div>
    </template>
    <template v-else-if="pageStatus === 'error'">
      <StateError />
    </template>
    <template v-else-if="pageStatus === 'loading'">
      <StateLoading />
    </template>
    <template v-else></template>
  </div>
  <DrawerDefault
    v-if="isDrawerDefault"
    ref="drawerDefault"
    :label="drawerDefaultLabel"
    :maxWidthClass="drawerDefaultMaxWidthClass"
    @isVisible="(visible: boolean) => !visible && resetDrawerDefault()"
  >
    <component
      v-if="drawerDefaultComponent"
      :is="drawerDefaultComponent"
      v-bind="drawerDefaultProps"
      @close="closeDrawerDefault"
    />
  </DrawerDefault>
  <ModalAlert
    v-if="isModalAlert"
    ref="modalAlert"
    :variant="modalAlertVariant"
    :placement="modalAlertPlacement"
    :maxWidthClass="modalAlertMaxWidthClass"
    @isVisible="(visible: boolean) => !visible && resetModalAlert()"
  />
  <ModalConfirmation
    v-if="isModalConfirmation"
    ref="modalConfirmation"
    :variant="modalConfirmationVariant"
    :confirmText="modalConfirmationConfirmText"
    :cancelText="modalConfirmationCancelText"
    :loading="modalConfirmationLoading"
    :placement="modalConfirmationPlacement"
    :maxWidthClass="modalConfirmationMaxWidthClass"
    @confirm="confirmModalConfirmation"
    @cancel="closeModalConfirmation"
    @isVisible="(visible: boolean) => !visible && resetModalConfirmation()"
  />
  <ModalDefault
    v-if="isModalDefault"
    ref="modalDefault"
    :label="modalDefaultLabel"
    :placement="modalDefaultPlacement"
    :isClosable="modalDefaultIsClosable"
    :maxWidthClass="modalDefaultMaxWidthClass"
    @isVisible="(visible: boolean) => !visible && resetModalDefault()"
  >
    <component
      v-if="modalDefaultComponent"
      :is="modalDefaultComponent"
      v-bind="modalDefaultProps"
      @close="closeModalDefault"
    />
  </ModalDefault>
</template>

<script setup lang="ts">
import { nextTick, onUnmounted, ref, shallowRef, type Component, type PropType } from 'vue'

import DrawerDefault from '../drawer/DrawerDefault.vue'
import ModalAlert from '../modal/ModalAlert.vue'
import ModalConfirmation from '../modal/ModalConfirmation.vue'
import ModalDefault from '../modal/ModalDefault.vue'
import StateError from '../state/StateError.vue'
import StateLoading from '../state/StateLoading.vue'
import ViewerFile from '../viewer/ViewerFile.vue'

import type {
  DrawerDefaultExposed,
  ModalAlertExposed,
  ModalAlertVariant,
  ModalConfirmationExposed,
  ModalConfirmationVariant,
  ModalDefaultExposed,
  ModalPlacement,
  PageStatus
} from '@/models'

// ================================================================================================
// Defines
// ================================================================================================
const props = defineProps({
  pageStatus: {
    type: String as PropType<PageStatus>,
    required: true
  },
  isViewerFile: {
    type: Boolean,
    default: false
  },
  isDrawerDefault: {
    type: Boolean,
    default: false
  },
  isModalDefault: {
    type: Boolean,
    default: false
  },
  isModalConfirmation: {
    type: Boolean,
    default: false
  },
  isModalAlert: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['confirmModalConfirmation'])

// ================================================================================================
// Properties
// ================================================================================================
const scrollPosition = ref(0)

const drawerDefault = ref<DrawerDefaultExposed | null>(null)
const drawerDefaultLabel = ref<string>('')
const drawerDefaultComponent = shallowRef<Component | null>(null)
const drawerDefaultProps = ref<Record<string, any>>({})
const drawerDefaultMaxWidthClass = ref<string | undefined>(undefined)
const modalAlert = ref<ModalAlertExposed | null>(null)
const modalAlertVariant = ref<ModalAlertVariant>('info')
const modalAlertPlacement = ref<ModalPlacement | undefined>(undefined)
const modalAlertMaxWidthClass = ref<string | undefined>(undefined)
const modalConfirmation = ref<ModalConfirmationExposed | null>(null)
const modalConfirmationVariant = ref<ModalConfirmationVariant>('default')
const modalConfirmationConfirmText = ref<string>('')
const modalConfirmationCancelText = ref<string>('')
const modalConfirmationLoading = ref<boolean>(false)
const modalConfirmationPlacement = ref<ModalPlacement | undefined>(undefined)
const modalConfirmationMaxWidthClass = ref<string | undefined>(undefined)
const modalDefault = ref<ModalDefaultExposed | null>(null)
const modalDefaultLabel = ref<string>('')
const modalDefaultComponent = shallowRef<Component | null>(null)
const modalDefaultProps = ref<Record<string, any>>({})
const modalDefaultPlacement = ref<ModalPlacement | undefined>(undefined)
const modalDefaultIsClosable = ref<boolean | undefined>(undefined)
const modalDefaultMaxWidthClass = ref<string | undefined>(undefined)
const viewerFileVisible = ref<boolean>(false)
const viewerFileTitle = ref<string>('')
const viewerFileUrl = ref<string>('')

// ================================================================================================
// Methods
// ================================================================================================
const openDrawerDefault = (
  label: string,
  component: Component,
  componentProps: Record<string, any> = {},
  maxWidthClass: string | undefined = undefined
) => {
  if (props.isDrawerDefault) {
    drawerDefaultLabel.value = label
    drawerDefaultComponent.value = component
    drawerDefaultProps.value = componentProps
    drawerDefaultMaxWidthClass.value = maxWidthClass
    nextTick(() => {
      requestAnimationFrame(() => {
        drawerDefault.value?.open()
      })
    })
  }
}
const closeDrawerDefault = () => {
  if (props.isDrawerDefault) {
    drawerDefault.value?.close()
  }
}
const resetDrawerDefault = () => {
  if (props.isDrawerDefault) {
    drawerDefaultLabel.value = ''
    drawerDefaultComponent.value = null
    drawerDefaultProps.value = {}
    drawerDefaultMaxWidthClass.value = undefined
  }
}
const openModalAlert = (
  variant: ModalAlertVariant,
  title: string,
  info: string,
  duration: number = 0,
  placement: ModalPlacement | undefined = undefined,
  maxWidthClass: string | undefined = undefined
) => {
  if (props.isModalAlert) {
    modalAlertVariant.value = variant
    modalAlertPlacement.value = placement
    modalAlertMaxWidthClass.value = maxWidthClass
    nextTick(() => {
      requestAnimationFrame(() => {
        modalAlert.value?.open(title, info, duration)
      })
    })
  }
}
const closeModalAlert = () => {
  if (props.isModalAlert) {
    modalAlert.value?.close()
  }
}
const resetModalAlert = () => {
  if (props.isModalAlert) {
    modalAlertVariant.value = 'info'
    modalAlertPlacement.value = undefined
    modalAlertMaxWidthClass.value = undefined
  }
}
const openModalConfirmation = (
  variant: ModalConfirmationVariant,
  confirmText: string,
  cancelText: string,
  id: string,
  title: string,
  info: string,
  data: Record<string, any> = {},
  placement: ModalPlacement | undefined = undefined,
  maxWidthClass: string | undefined = undefined
) => {
  if (props.isModalConfirmation) {
    modalConfirmationVariant.value = variant
    modalConfirmationConfirmText.value = confirmText
    modalConfirmationCancelText.value = cancelText
    modalConfirmationPlacement.value = placement
    modalConfirmationMaxWidthClass.value = maxWidthClass
    nextTick(() => {
      requestAnimationFrame(() => {
        modalConfirmation.value?.open(id, title, info, data)
      })
    })
  }
}
const confirmModalConfirmation = (id: string, data: Record<string, any>) => {
  if (props.isModalConfirmation) {
    modalConfirmationLoading.value = true
    emit('confirmModalConfirmation', id, data)
  }
}
const closeModalConfirmation = () => {
  if (props.isModalConfirmation) {
    modalConfirmation.value?.close()
  }
}
const resetModalConfirmation = () => {
  if (props.isModalConfirmation) {
    modalConfirmationVariant.value = 'default'
    modalConfirmationConfirmText.value = ''
    modalConfirmationCancelText.value = ''
    modalConfirmationLoading.value = false
    modalConfirmationPlacement.value = undefined
    modalConfirmationMaxWidthClass.value = undefined
  }
}
const stopModalConfirmation = () => {
  if (props.isModalConfirmation) {
    modalConfirmationLoading.value = false
  }
}
const openModalDefault = (
  label: string,
  component: Component,
  componentProps: Record<string, any> = {},
  placement: ModalPlacement | undefined = undefined,
  isClosable: boolean | undefined = undefined,
  maxWidthClass: string | undefined = undefined
) => {
  if (props.isModalDefault) {
    modalDefaultLabel.value = label
    modalDefaultComponent.value = component
    modalDefaultProps.value = componentProps
    modalDefaultPlacement.value = placement
    modalDefaultIsClosable.value = isClosable
    modalDefaultMaxWidthClass.value = maxWidthClass
    nextTick(() => {
      requestAnimationFrame(() => {
        modalDefault.value?.open()
      })
    })
  }
}
const closeModalDefault = () => {
  if (props.isModalDefault) {
    modalDefault.value?.close()
  }
}
const resetModalDefault = () => {
  if (props.isModalDefault) {
    modalDefaultLabel.value = ''
    modalDefaultComponent.value = null
    modalDefaultProps.value = {}
    modalDefaultPlacement.value = undefined
    modalDefaultIsClosable.value = undefined
    modalDefaultMaxWidthClass.value = undefined
  }
}
const openViewerFile = (title: string, url: string) => {
  if (props.isViewerFile) {
    scrollPosition.value = document.documentElement.scrollTop
    nextTick(() => {
      requestAnimationFrame(() => {
        viewerFileVisible.value = true
        viewerFileTitle.value = title
        viewerFileUrl.value = url
      })
    })
  }
}
const closeViewerFile = () => {
  if (props.isViewerFile) {
    viewerFileVisible.value = false
    viewerFileTitle.value = ''
    viewerFileUrl.value = ''
    nextTick(() => {
      requestAnimationFrame(() => {
        document.documentElement.scrollTop = scrollPosition.value
      })
    })
  }
}

// ================================================================================================
// Define Expose
// ================================================================================================
defineExpose({
  openDrawerDefault,
  closeDrawerDefault,
  openModalAlert,
  closeModalAlert,
  openModalConfirmation,
  closeModalConfirmation,
  stopModalConfirmation,
  openModalDefault,
  closeModalDefault,
  openViewerFile,
  closeViewerFile
})

// ================================================================================================
// Lifecycle
// ================================================================================================
const destruction = () => {
  if (props.isDrawerDefault) {
    closeDrawerDefault()
  }
  if (props.isModalAlert) {
    closeModalAlert()
  }
  if (props.isModalConfirmation) {
    closeModalConfirmation()
  }
  if (props.isModalDefault) {
    closeModalDefault()
  }
  if (props.isViewerFile) {
    closeViewerFile()
  }
}

onUnmounted(() => {
  destruction()
})
</script>
