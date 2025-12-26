<template>
  <div class="antialiased">
    <!-- Nav -->
    <nav
      class="fixed top-0 left-0 right-0 z-10 px-2 ml-0 bg-white border-b border-gray-200 lg:ml-64 h-14 lg:px-4"
    >
      <div class="flex flex-wrap items-center justify-between h-full">
        <div class="flex items-center justify-start">
          <ButtonMenu @click="openDrawer" customClass="lg:hidden"></ButtonMenu>
          <TitlePage>Title Page</TitlePage>
        </div>
      </div>
    </nav>

    <!-- Drawer Menu -->
    <DrawerMenu ref="drawerMenu">
      <div class="flex flex-col h-full gap-8">
        <div class="p-4">
          <LogoBssnLight />
        </div>
        <div class="flex-1 overflow-auto">
          <MenuSidebarDefault />
        </div>
        <div class="p-4 text-xs text-left text-gray-500">
          Hak Cipta © 2025
          <a href="https://www.bssn.go.id/" class="hover:underline">Badan Siber dan Sandi Negara</a>
          · Pusat Data dan Teknologi Informasi Komunikasi
        </div>
      </div>
    </DrawerMenu>

    <!-- Main -->
    <main class="min-w-80 ml-0 lg:ml-64 mt-14 h-[calc(100vh-3.5rem)]">
      <div class="flex flex-col w-full h-full">
        <div class="flex-1 p-4 xl:p-6">
          <RouterView />
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

import LogoBssnLight from '../logo/LogoBssnLight.vue'
import MenuSidebarDefault from '../menu/MenuSidebarDefault.vue'

import ButtonMenu from '@/components/button/ButtonMenu.vue'
import DrawerMenu from '@/components/drawer/DrawerMenu.vue'
import TitlePage from '@/components/title/TitlePage.vue'

const drawerMenu = ref()
const openDrawer = () => {
  drawerMenu.value.open()
}
</script>
