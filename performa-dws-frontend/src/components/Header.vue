<template>
  <div class="bg-gray-200 px-4 sm:px-6 py-2.5 flex items-center justify-between">
    <div class="flex items-center gap-3 sm:gap-4">
      <!-- Mobile Menu Button -->
      <button
        @click="$emit('toggle-menu')"
        class="lg:hidden text-gray-700 hover:text-gray-900"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      
      <!-- Breadcrumb Navigation -->
      <div class="hidden sm:flex items-center gap-2 text-sm font-medium">
        <span class="text-gray-900">{{ currentSection }}</span>
        <span v-if="currentSubmenu" class="text-gray-500">›</span>
        <span v-if="currentSubmenu" class="text-gray-700">{{ currentSubmenu }}</span>
      </div>
    </div>

    <!-- User Info -->
    <div class="flex items-center gap-3">
      <div class="hidden sm:block text-right">
        <p class="text-xs font-medium text-gray-800">{{ user?.name || 'User' }}</p>
        <p class="text-xs text-gray-500">{{ getRoleLabel(user?.role) }}</p>
      </div>
      <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-semibold">
        {{ getInitials(user?.name) }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  user: {
    type: Object,
    required: true
  },
  activeMenu: {
    type: String,
    required: true
  }
})

defineEmits(['toggle-menu'])

// Menu structure untuk mapping
const menuStructure = {
  // RUANG PRIBADI
  'beranda': { section: 'Beranda', submenu: null },
  
  // RUANG KERJA - Admin ePerforma
  'pohon-kinerja': { section: 'Admin ePerforma', submenu: 'Pohon Kinerja' },
  'petugas': { section: 'Admin ePerforma', submenu: 'Petugas' },
  'rekapitulasi': { section: 'Admin ePerforma', submenu: 'Rekapitulasi' },
}

// Menentukan section dan submenu berdasarkan activeMenu
const currentSection = computed(() => {
  const menu = menuStructure[props.activeMenu]
  return menu?.section || 'ePerforma BSSN'
})

const currentSubmenu = computed(() => {
  const menu = menuStructure[props.activeMenu]
  return menu?.submenu || null
})

const getInitials = (name) => {
  if (!name) return 'U'
  return name
    .split(' ')
    .map(word => word[0])
    .join('')
    .toUpperCase()
    .substring(0, 2)
}

const getRoleLabel = (role) => {
  const roleLabels = {
    'admin_eperforma': 'Admin ePerforma',
    'pko': 'PKO',
    'pmk': 'PMK'
  }
  return roleLabels[role] || role
}
</script>