<template>
  <aside :id="id" :class="computedClasses" tabindex="-1" :aria-labelledby="`${id}-label`">
    <div class="flex flex-col h-full">
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

const id = 'DrawerMenu-' + uuidv4()

const props = defineProps({
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
    placement: 'left',
    backdrop: true,
    bodyScrolling: false,
    edge: false,
    edgeOffset: '',
    backdropClasses: 'z-300 bg-gray-900/60 fixed inset-0',
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

  blurElement(targetEl)
})

const blurElement = (targetEl: HTMLElement | null = null) => {
  targetEl?.removeAttribute('aria-hidden')
  const active = document.activeElement
  if (targetEl && active instanceof HTMLElement && targetEl.contains(active)) {
    active.blur()
  }
}

const computedClasses = computed(() => {
  return [
    'top-0 transition-transform h-screen fixed left-0 z-400 -translate-x-full lg:translate-x-0 w-64 bg-primary-500',
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
