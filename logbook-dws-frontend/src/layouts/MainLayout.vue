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
      <!-- Header -->
      <Header 
        :user="user" 
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
import { ref, onMounted } from 'vue'
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

const handleMenuChange = (menu) => {
  activeMenu.value = menu
  mobileMenuOpen.value = false
  
  // Navigate based on menu
  if (menu === 'beranda') {
    router.push('/beranda')
  } else if (menu === 'logbook') {
    router.push('/logbook')
  }
  // Tambahkan routing lain sesuai kebutuhan
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

// Update active menu based on current route
const updateActiveMenu = () => {
  const path = route.path
  if (path.includes('/beranda')) {
    activeMenu.value = 'beranda'
  } else if (path.includes('/logbook')) {
    activeMenu.value = 'logbook'
  } else if (path.includes('/keuangan')) {
    activeMenu.value = 'keuangan'
  } else if (path.includes('/pengetahuan')) {
    activeMenu.value = 'pengetahuan'
  } else if (path.includes('/kesehatan')) {
    activeMenu.value = 'kesehatan'
  } else if (path.includes('/halo-pusdaik')) {
    activeMenu.value = 'halo-pusdaik'
  }
}

onMounted(() => {
  loadProfile()
  updateActiveMenu()
})
</script>