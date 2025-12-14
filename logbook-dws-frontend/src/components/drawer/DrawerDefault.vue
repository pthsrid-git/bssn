<template>
  <aside :id="id" :class="computedClasses" tabindex="-1" :aria-labelledby="`${id}-label`">
    <div class="flex flex-col h-full">
      <div class="flex items-start justify-between gap-4">
        <h4 :id="`${id}-label`" class="pt-1 font-semibold text-gray-600">
          {{ label }}
        </h4>
        <ButtonClose @click="close" />
      </div>
      <hr class="h-px my-4 bg-gray-200 border-0" />
      <div class="flex-1 w-full h-full overflow-hidden">
        <slot></slot>
      </div>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { Drawer, type DrawerOptions } from 'flowbite'
import { v4 as uuidv4 } from 'uuid'
import { computed, onMounted } from 'vue'
import { onBeforeRouteUpdate } from 'vue-router'

import ButtonClose from '../button/ButtonClose.vue'

const id = 'DrawerDefault-' + uuidv4()

const props = defineProps({
  label: {
    type: String,
    default: ''
  },
  backdrop: {
    type: Boolean,
    default: true
  },
  bodyScrolling: {
    type: Boolean,
    default: false
  },
  maxWidthClass: {
    type: String,
    default: 'max-w-md'
  },
  customClass: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['isVisible'])

let drawer: Drawer

onMounted(() => {
  const targetEl = document.getElementById(id)

  const options: DrawerOptions = {
    placement: 'right',
    backdrop: props.backdrop,
    bodyScrolling: props.bodyScrolling,
    edge: false,
    edgeOffset: '',
    backdropClasses: 'z-400 bg-gray-900/60 fixed inset-0',
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

  drawer = new Drawer(targetEl, options, instanceOptions)
})

const blurElement = (targetEl: HTMLElement | null = null) => {
  const active = document.activeElement
  if (targetEl && active instanceof HTMLElement && targetEl.contains(active)) {
    active.blur()
  }
}

const computedClasses = computed(() => {
  return [
    'top-0 transition-transform h-screen fixed right-0 z-500 translate-x-full w-full bg-white p-4',
    props.maxWidthClass,
    props.customClass
  ].join(' ')
})

const isVisible = (value: boolean) => {
  emit('isVisible', value)
}

const open = () => {
  drawer.show()
}

const close = () => {
  drawer.hide()
}

onBeforeRouteUpdate(() => {
  if (drawer.isVisible()) {
    close()
  }
})

defineExpose({
  open,
  close
})
</script>
