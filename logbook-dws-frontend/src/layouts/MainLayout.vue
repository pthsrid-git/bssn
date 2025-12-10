<template>
  <div class="flex h-screen bg-[#ECF1F9] overflow-hidden fixed inset-0">
    <!-- Mobile Menu Overlay -->
    <div 
      v-if="mobileMenuOpen" 
      @click="mobileMenuOpen = false"
      class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
    ></div>

    <!-- Sidebar -->
    <Sidebar 
      :active-menu="activeMenu" 
      :mobile-open="mobileMenuOpen"
      @menu-change="handleMenuChange" 
      @close="mobileMenuOpen = false"
    />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Header with activeMenu prop -->
      <Header 
        :user="user"
        :active-menu="activeMenu"
        @toggle-menu="mobileMenuOpen = !mobileMenuOpen"
      />

      <!-- Content Area -->
      <div class="flex-1 overflow-auto p-6">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import Sidebar from '../components/Sidebar.vue'
import Header from '../components/Header.vue'
import { logbookService } from '../services/Logbook/logbookService'

const router = useRouter()
const route = useRoute()

const user = ref({
  nama_pegawai: 'Loading...',
  nama_jabatan: 'Loading...'
})

const activeMenu = ref('beranda')
const mobileMenuOpen = ref(false)

// Route to Menu mapping
const getMenuIdFromPath = (path) => {
  // Remove trailing slash for consistency
  const normalizedPath = path.endsWith('/') && path !== '/' 
    ? path.slice(0, -1) 
    : path

  const menuMap = {
    '/beranda': 'beranda',
    '/logbook': 'logbook',
    '/logbook-katim': 'logbook-katim',
    '/logbook-atasan': 'logbook-atasan',
    '/monitoring': 'monitoring',
    '/keuangan': 'keuangan',
    '/pengetahuan': 'pengetahuan',
    '/kesehatan': 'kesehatan',
    '/halo-pusdaik': 'halo-pusdaik'
  }
  
  return menuMap[normalizedPath] || 'beranda'
}

// Update active menu based on route
const updateActiveMenu = () => {
  activeMenu.value = getMenuIdFromPath(route.path)
}

// Watch route changes
watch(() => route.path, () => {
  updateActiveMenu()
}, { immediate: true })

const handleMenuChange = (menu) => {
  activeMenu.value = menu
  mobileMenuOpen.value = false
  
  // Navigate based on menu (optional - router-link in sidebar already handles this)
  const routeMap = {
    'beranda': '/beranda',
    'logbook': '/logbook',
    'logbook-katim': '/logbook-katim',
    'logbook-atasan': '/logbook-atasan',
    'monitoring': '/monitoring',
    'keuangan': '/keuangan',
    'pengetahuan': '/pengetahuan',
    'kesehatan': '/kesehatan',
    'halo-pusdaik': '/halo-pusdaik'
  }
  
  if (routeMap[menu]) {
    router.push(routeMap[menu])
  }
}

const loadProfile = async () => {
  try {
    const response = await logbookService.getProfile()
    const profileData = response.data || response
    user.value = {
      nama_pegawai: profileData.nama_pegawai || 'User',
      nama_jabatan: profileData.nama_jabatan || 'Staff'
    }
  } catch (error) {
    console.error('Error loading profile:', error)
    user.value = {
      nama_pegawai: 'User',
      nama_jabatan: 'Staff'
    }
  }
}

onMounted(() => {
  loadProfile()
  updateActiveMenu()
})
</script>