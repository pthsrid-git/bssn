<template>
  <div class="antialiased">
    <!-- Nav with User Info & Logout - Gray Background -->
    <nav
      class="fixed top-0 left-0 right-0 z-10 px-2 ml-0 bg-gray-50 border-b border-gray-300 lg:ml-64 h-14 lg:px-4"
    >
      <div class="flex flex-wrap items-center justify-between h-full">
        <!-- Left: Menu Button + Title -->
        <div class="flex items-center justify-start">
          <ButtonMenu @click="openDrawer" customClass="lg:hidden"></ButtonMenu>
          <TitlePage>DWS | {{ sectionTitle }}</TitlePage>
        </div>

        <!-- Right: User Info + Logout -->
        <div v-if="userDwsStore.user && userDwsStore.user.name" class="flex items-center gap-3">
          <!-- User Info -->
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center">
              <span class="text-white font-semibold text-xs">{{ userInitials }}</span>
            </div>
            <div class="hidden md:block">
              <p class="font-semibold text-gray-900 text-sm">{{ userName }}</p>
              <p class="text-xs text-gray-600">{{ userPosition }}</p>
            </div>
          </div>

          <!-- Logout Button -->
          <button
            @click="handleLogout"
            class="ml-2 p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
            title="Keluar"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
          </button>
        </div>

        <!-- Loading state -->
        <div v-else class="flex items-center gap-2 text-gray-500 text-sm">
          <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span class="hidden md:inline">Memuat...</span>
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
          <MenuSidebarDws />
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
          <RouterView v-if="!isLoadingUser" />
          <div v-else class="flex items-center justify-center h-full">
            <div class="text-center">
              <svg class="animate-spin h-8 w-8 text-primary-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <p class="text-gray-600">Memuat data user...</p>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

import LogoBssnLight from '../logo/LogoBssnLight.vue'
import MenuSidebarDws from '../menu/MenuSidebarDws.vue'

import ButtonMenu from '@/components/button/ButtonMenu.vue'
import DrawerMenu from '@/components/drawer/DrawerMenu.vue'
import TitlePage from '@/components/title/TitlePage.vue'

import { useUserDwsStore } from '@/stores/userDwsStore'

const drawerMenu = ref()
const route = useRoute()
const router = useRouter()
const userDwsStore = useUserDwsStore()
const isLoadingUser = ref(false)

// User info from store
const userName = computed(() => {
  return userDwsStore.user?.fullname || userDwsStore.user?.name || ''
})

const userPosition = computed(() => {
  return userDwsStore.user?.jabatan || ''
})

const userInitials = computed(() => {
  const fullname = userDwsStore.user?.fullname || userDwsStore.user?.name || 'U'
  const names = fullname.split(' ')
  
  if (names.length >= 2) {
    return `${names[0][0]}${names[1][0]}`.toUpperCase()
  }
  
  return names[0][0].toUpperCase()
})

// Section title based on route path
const sectionTitle = computed(() => {
  const path = route.path
  
  if (path.includes('/ruang-pribadi')) {
    return 'Ruang Pribadi'
  } else if (path.includes('/ruang-kerja')) {
    return 'Ruang Kerja'
  }
  
  return 'DWS'
})

// Page title from route meta
const pageTitle = computed(() => {
  return route.meta?.title || 'Dashboard'
})

const openDrawer = () => {
  drawerMenu.value.open()
}

// Logout handler
const handleLogout = () => {
  if (confirm('Apakah Anda yakin ingin keluar?')) {
    localStorage.removeItem('auth_token')
    userDwsStore.removeUser()
    router.push('/login')
  }
}

// Load user from API
const loadUser = async () => {
  const token = localStorage.getItem('auth_token')  
  if (!token) {
    router.push('/login')
    return
  }
  
  isLoadingUser.value = true
  
  try {
    const apiUrl = `${import.meta.env.VITE_API_BASE_URL}/auth/me`
    
    const response = await fetch(apiUrl, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    })
    
    if (!response.ok) {
      localStorage.removeItem('auth_token')
      router.push('/login')
      return
    }
    
    const data = await response.json()
    
    if (data.success && data.data) {
      userDwsStore.setUser(data.data)
    } else {
      router.push('/login')
    }
  } catch (error) {
    localStorage.removeItem('auth_token')
    router.push('/login')
  } finally {
    isLoadingUser.value = false
  }
}

// Load user on mount
onMounted(async () => {
  await loadUser()
})
</script>