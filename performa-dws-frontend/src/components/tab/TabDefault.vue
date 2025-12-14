<template>
  <div class="flex flex-col gap-4 xl:gap-6">
    <CardDefault paddingClass="py-0">
      <ul class="flex flex-row overflow-x-auto text-gray-900 gap-x-3">
        <li v-for="tab in tabs" :key="tab.name">
          <a
            :href="'#' + tab.name"
            class="flex flex-row items-center w-full gap-2 p-4 uppercase border-b-3 text-nowrap"
            :class="{
              'font-semibold border-warning-500': activeTab === tab.name,
              'cursor-pointer hover:text-gray-700 hover:border-gray-300 border-transparent':
                !loading && activeTab !== tab.name,
              'cursor-not-allowed hover:text-gray-400 text-gray-400 border-transparent':
                loading && activeTab !== tab.name
            }"
            @click.prevent="setActiveTab(tab.name)"
          >
            <div class="text-sm">{{ tab.label }}</div>
            <div :class="loading && activeTab !== tab.name ? 'opacity-50' : ''">
              <BadgeNotification v-if="tab.badge" :value="tab.badge" variant="danger" />
            </div>
          </a>
        </li>
      </ul>
    </CardDefault>
    <template v-if="activeTab && $slots[activeTab]">
      <component :is="wrapper ? CardDefault : 'div'">
        <slot :name="activeTab"></slot>
      </component>
    </template>
  </div>
</template>

<script setup lang="ts">
import { ref, type PropType } from 'vue'

import BadgeNotification from '../badge/BadgeNotification.vue'
import CardDefault from '../card/CardDefault.vue'

import type { TabData } from '@/models'

const props = defineProps({
  tabs: {
    type: Array as PropType<TabData[]>,
    required: true,
    default: () => []
  },
  initialTab: {
    type: String,
    default: ''
  },
  wrapper: {
    type: Boolean,
    default: true
  },
  loading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['show'])

const activeTab = ref<string>(props.initialTab)

const setActiveTab = (value: string) => {
  if (!props.loading && activeTab.value !== value) {
    activeTab.value = value
    emit('show', value)
  }
}

defineExpose({
  setActiveTab
})
</script>
