<template>
  <div class="flex flex-col">
    <ul v-if="items.length > 0" class="flex flex-col">
      <template v-for="(menu, index) in items" :key="index">
        <ButtonMenuSidebar
          :route="menu"
          v-if="menu.meta.guard && userDefaultStore.isAuthorized(menu.meta.guard)"
        />
      </template>
    </ul>
  </div>
</template>

<script setup lang="ts">
import type { RouteRecordRaw } from 'vue-router'

import ButtonMenuSidebar from './ButtonMenuSidebar.vue'

import type { RouteMetaData } from '@/models'
import router from '@/router'
import { useUserDefaultStore } from '@/stores/userDefaultStore'

type MenuRoute = RouteRecordRaw & {
  meta: RouteMetaData
}

const userDefaultStore = useUserDefaultStore()

const isMenuRoute = (route: RouteRecordRaw): route is MenuRoute => {
  return !!route.meta && route.meta.menu === true
}

const items =
  router
    .getRoutes()
    .find((route) => route.name === 'dashboard')
    ?.children?.filter(isMenuRoute) ?? []
</script>
