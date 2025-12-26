<template>
  <TabDefault
    :tabs="tabDefaultData"
    initialTab="one"
    @show="onShow"
    :loading="logicAll.status === 'loading'"
  >
    <template #one>
      <div class="min-h-24">
        <h1>One Tab</h1>
      </div>
    </template>
    <template #two>
      <div class="min-h-24">
        <h1>Two Tab</h1>
      </div>
    </template>
    <template #three>
      <div class="min-h-24">
        <h1>Three Tab</h1>
      </div>
    </template>
    <template #four>
      <div class="min-h-24">
        <h1>Four Tab</h1>
      </div>
    </template>
    <template #five>
      <div class="min-h-24">
        <h1>Five Tab</h1>
      </div>
    </template>
    <template #six>
      <div class="min-h-24">
        <h1>Six Tab</h1>
      </div>
    </template>
  </TabDefault>
</template>

<script setup lang="ts">
import { computed } from 'vue'

import TabDefault from '@/components/tab/TabDefault.vue'
import { log } from '@/helpers'
import { useLogicStore } from '@/stores/LogicStore'

const tabDefaultData = [
  { name: 'one', label: 'One', badge: 1 },
  { name: 'two', label: 'Two' },
  { name: 'three', label: 'Three' },
  { name: 'four', label: 'Four' },
  { name: 'five', label: 'Five' },
  { name: 'six', label: 'Six' }
]

const onShow = (tabName: string) => {
  log(`Tab ${tabName} show`)
  logicStore.callAll()
}

const logicStore = useLogicStore()
const logicAll = computed(() => logicStore.all)
</script>
