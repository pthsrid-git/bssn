<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Login Page (No Layout) -->
    <router-view v-if="$route.meta.hideLayout" />

    <!-- Main Layout (with Sidebar and Header) -->
    <div v-else class="flex h-screen overflow-hidden">
      <!-- Sidebar -->
      <Sidebar
        :activeMenu="activeMenu"
        :mobileOpen="sidebarOpen"
        @menu-change="handleMenuChange"
        @close="sidebarOpen = false"
      />

      <!-- Main Content Area -->
      <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <Header
          :user="user"
          :activeMenu="activeMenu"
          @toggle-menu="sidebarOpen = !sidebarOpen"
        />

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto bg-gray-100">
          <router-view />
        </main>
      </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40"
    ></div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { authService } from './services/auth'
import Sidebar from './components/Sidebar.vue'
import Header from './components/Header.vue'

const route = useRoute()
const sidebarOpen = ref(false)
const activeMenu = ref('beranda')

const user = computed(() => authService.getUser())

// Update active menu based on current route
watch(() => route.path, (newPath) => {
  // Map routes to menu IDs
  const routeMenuMap = {
    '/beranda': 'beranda',
    '/logbook': 'logbook',
    '/keuangan': 'keuangan',
    '/pengetahuan': 'pengetahuan',
    '/kesehatan': 'kesehatan',
    '/halo-pusdaik': 'halo-pusdaik',
    '/logbook-katim': 'logbook-katim',
    '/logbook-atasan': 'logbook-atasan',
    '/admin-performa/pohon-kinerja': 'pohon-kinerja',
    '/admin-performa/petugas': 'petugas',
    '/admin-performa/rekapitulasi': 'rekapitulasi'
  }

  activeMenu.value = routeMenuMap[newPath] || 'beranda'
}, { immediate: true })

const handleMenuChange = (menuId) => {
  activeMenu.value = menuId
}

// Close sidebar on route change (mobile)
watch(() => route.path, () => {
  if (sidebarOpen.value) {
    sidebarOpen.value = false
  }
})
</script>

<style>
/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e0;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a0aec0;
}
</style>