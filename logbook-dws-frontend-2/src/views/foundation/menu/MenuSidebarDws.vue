<template>
  <div class="flex flex-col gap-8">
    <ul v-if="ruangPribadiItems.length > 0" class="flex flex-col">
      <li class="px-6 mb-2 font-bold text-gray-500 text-2xs">RUANG PRIBADI</li>
      <template v-for="(menu, index) in ruangPribadiItems" :key="index">
        <ButtonMenuSidebar
          :route="menu"
          v-if="!menu.meta.guard || userDwsStore.isRuangPribadiAuthorized(menu.meta.guard)"
        />
      </template>
    </ul>
    <ul v-if="ruangKerjaItems.length > 0" class="flex flex-col">
      <li class="px-6 mb-2 font-bold text-gray-500 text-2xs">RUANG KERJA</li>
      <template v-for="(menu, index) in ruangKerjaItems" :key="index">
        <ButtonMenuSidebar
          :route="menu"
          v-if="!menu.meta.guard || userDwsStore.isRuangKerjaAuthorized(menu.meta.guard)"
        />
      </template>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import type { RouteRecordRaw } from 'vue-router'

import ButtonMenuSidebar from './ButtonMenuSidebar.vue'

import type { RouteMetaData } from '@/models'
import router from '@/router'
import { useUserDwsStore } from '@/stores/userDwsStore'

type MenuRoute = RouteRecordRaw & {
  meta: RouteMetaData
}

const userDwsStore = useUserDwsStore()

const isMenuRoute = (route: RouteRecordRaw): route is MenuRoute => {
  return !!route.meta && route.meta.menu === true
}

const ruangPribadiItems = computed(() => 
  router
    .getRoutes()
    .find((route) => route.name === 'ruang-pribadi')
    ?.children?.filter(isMenuRoute) ?? []
)

const ruangKerjaItems = computed(() =>
  router
    .getRoutes()
    .find((route) => route.name === 'ruang-kerja')
    ?.children?.filter(isMenuRoute) ?? []
)
</script>