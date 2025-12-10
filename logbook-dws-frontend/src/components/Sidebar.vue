<template>
  <!-- Desktop Sidebar -->
  <div 
    :class="[
      'bg-[#19203B] text-white flex flex-col transition-transform duration-300 ease-in-out z-50',
      'lg:relative lg:translate-x-0',
      'fixed inset-y-0 left-0',
      mobileOpen ? 'translate-x-0' : '-translate-x-full',
      'w-64 lg:w-64'
    ]"
  >
    <!-- Close Button (Mobile Only) -->
    <button
      @click="$emit('close')"
      class="lg:hidden absolute top-4 right-4 text-white hover:text-gray-300"
    >
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>

    <!-- Logo Section -->
    <div class="p-5 border-b border-[#263053]">
      <div class="flex items-center justify-center">
        <img 
          src="/src/assets/logo-bssn-light.png" 
          alt="BSSN Logo" 
          class="h-16 w-auto object-contain"
          @error="handleImageError"
        />
      </div>
    </div>

    <!-- Menu Items -->
    <div class="flex-1 py-4 overflow-y-auto">
      <!-- RUANG PRIBADI -->
      <div class="px-4 text-xs font-semibold text-gray-400 mb-3 tracking-wider">RUANG PRIBADI</div>
      
      <!-- Menu Items - RUANG PRIBADI -->
      <MenuItem 
        v-for="item in menuRuangPribadi" 
        :key="item.id"
        :item="item"
        :activeMenu="activeMenu"
        @menu-click="handleMenuClick"
      />

      <!-- RUANG KERJA - Only show if there are accessible menus -->
      <template v-if="menuRuangKerja.length > 0">
        <div class="px-4 text-xs font-semibold text-gray-400 mt-6 mb-3 tracking-wider">RUANG KERJA</div>

        <!-- Menu Items - RUANG KERJA -->
        <MenuItem 
          v-for="item in menuRuangKerja" 
          :key="item.id"
          :item="item"
          :activeMenu="activeMenu"
          @menu-click="handleMenuClick"
        />
      </template>

      <!-- Logout -->
      <div class="px-4 mt-4 pt-4 border-t border-[#263053]">
        <button
          @click="handleLogout"
          class="w-full flex items-center gap-3 px-4 py-2.5 text-sm transition-colors text-red-400 hover:bg-[#263053] rounded-lg"
        >
          <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          Logout
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { logbookService } from '../services/Logbook/logbookService'
import { useMenuPermissions } from '../composables/useMenuPermissions'
import MenuItem from './MenuItem.vue'

const router = useRouter()

const props = defineProps({
  activeMenu: {
    type: String,
    default: 'beranda'
  },
  mobileOpen: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['menu-change', 'close'])

// Use menu permissions composable
const { filterMenusByPermission } = useMenuPermissions()

// Menu Configuration - RUANG PRIBADI
const menuRuangPribadiRaw = ref([
  {
    id: 'beranda',
    label: 'Beranda',
    path: '/beranda',
    icon: 'home'
  },
  {
    id: 'kepegawaian',
    label: 'Kepegawaian',
    icon: 'users',
    hasSubmenu: true,
    submenu: [
      {
        id: 'logbook',
        label: 'Logbook',
        path: '/logbook',
        icon: 'square'
      }
    ]
  },
  {
    id: 'keuangan',
    label: 'Keuangan',
    path: '/keuangan',
    icon: 'credit-card'
  },
  {
    id: 'pengetahuan',
    label: 'Pengetahuan',
    path: '/pengetahuan',
    icon: 'book'
  },
  {
    id: 'kesehatan',
    label: 'Kesehatan',
    path: '/kesehatan',
    icon: 'heart'
  },
  {
    id: 'halo-pusdaik',
    label: 'Halo Pusdaik',
    path: '/halo-pusdaik',
    icon: 'chat'
  }
])

// Menu Configuration - RUANG KERJA
const menuRuangKerjaRaw = ref([
  {
    id: 'logbook-katim',
    label: 'Logbook Katim',
    path: '/logbook-katim',
    icon: 'clipboard'
  },
  {
    id: 'logbook-atasan',
    label: 'Logbook Atasan',
    path: '/logbook-atasan',
    icon: 'clipboard-check'
  }
])

// Apply permissions to menus
const menuRuangPribadi = computed(() => filterMenusByPermission(menuRuangPribadiRaw.value))
const menuRuangKerja = computed(() => filterMenusByPermission(menuRuangKerjaRaw.value))

const handleImageError = (event) => {
  console.warn('Logo image not found, using fallback')
  event.target.style.display = 'none'
}

const handleLogout = async () => {
  try {
    await logbookService.logout()
  } catch (error) {
    console.error('Logout error:', error)
  } finally {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    router.push('/login')
  }
}

const handleMenuClick = (menuId) => {
  emit('menu-change', menuId)
  // Close mobile sidebar after click
  if (props.mobileOpen) {
    emit('close')
  }
}
</script>