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
        <!-- Logo PNG -->
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
      <div class="px-4 text-xs font-semibold text-gray-400 mb-3 tracking-wider">RUANG PRIBADI</div>
      
      <!-- Beranda -->
      <router-link
        to="/beranda"
        :class="[
          'flex items-center gap-3 px-4 py-3 text-sm transition-colors relative',
          activeMenu === 'beranda' ? 'bg-[#263053] text-[#FBC143]' : 'text-white hover:bg-[#263053]'
        ]"
        @click="handleMenuClick('beranda')"
      >
        <!-- Yellow indicator bar -->
        <div 
          v-if="activeMenu === 'beranda'"
          class="absolute left-0 top-0 bottom-0 w-1 bg-[#FBC143]"
        ></div>
        
        <svg 
          class="w-5 h-5 flex-shrink-0" 
          :class="activeMenu === 'beranda' ? 'text-[#FBC143]' : ''"
          fill="none" 
          stroke="currentColor" 
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Beranda
      </router-link>
      
      <!-- Kepegawaian with Submenu -->
      <div>
        <button
          @click="toggleSubmenu('kepegawaian')"
          :class="[
            'w-full flex items-center justify-between px-4 py-3 text-sm transition-colors',
            (activeMenu === 'kepegawaian' || activeMenu === 'logbook') ? 'bg-[#263053]' : 'hover:bg-[#263053]'
          ]"
        >
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Kepegawaian
          </div>
          <svg 
            class="w-4 h-4 transition-transform flex-shrink-0" 
            :class="{ 'rotate-180': openSubmenus.kepegawaian }"
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        
        <!-- Submenu: Only Logbook -->
        <div v-show="openSubmenus.kepegawaian" class="bg-[#0f1420] py-1">
          <router-link
            to="/logbook"
            :class="[
              'flex items-center gap-3 py-2.5 pl-12 pr-4 text-sm transition-colors relative',
              activeMenu === 'logbook' ? 'text-[#FBC143]' : 'text-gray-300 hover:text-white'
            ]"
            @click="handleMenuClick('logbook')"
          >
            <!-- Yellow indicator bar -->
            <div 
              v-if="activeMenu === 'logbook'"
              class="absolute left-0 top-0 bottom-0 w-1 bg-[#FBC143]"
            ></div>
            
            <!-- Yellow square icon -->
            <svg 
              class="w-4 h-4 flex-shrink-0" 
              :class="activeMenu === 'logbook' ? 'text-[#FBC143]' : 'text-gray-500'"
              fill="currentColor" 
              viewBox="0 0 24 24"
            >
              <rect x="4" y="4" width="16" height="16" rx="2"/>
            </svg>
            Logbook
          </router-link>
        </div>
      </div>
      
      <!-- Keuangan -->
      <a 
        href="#" 
        @click.prevent="handleMenuClick('keuangan')"
        :class="[
          'flex items-center gap-3 px-4 py-3 text-sm transition-colors relative',
          activeMenu === 'keuangan' ? 'bg-[#263053] text-[#FBC143]' : 'text-white hover:bg-[#263053]'
        ]"
      >
        <!-- Yellow indicator bar -->
        <div 
          v-if="activeMenu === 'keuangan'"
          class="absolute left-0 top-0 bottom-0 w-1 bg-[#FBC143]"
        ></div>
        
        <svg 
          class="w-5 h-5 flex-shrink-0"
          :class="activeMenu === 'keuangan' ? 'text-[#FBC143]' : ''"
          fill="none" 
          stroke="currentColor" 
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
        </svg>
        Keuangan
      </a>
      
      <!-- Pengetahuan -->
      <a 
        href="#" 
        @click.prevent="handleMenuClick('pengetahuan')"
        :class="[
          'flex items-center gap-3 px-4 py-3 text-sm transition-colors relative',
          activeMenu === 'pengetahuan' ? 'bg-[#263053] text-[#FBC143]' : 'text-white hover:bg-[#263053]'
        ]"
      >
        <!-- Yellow indicator bar -->
        <div 
          v-if="activeMenu === 'pengetahuan'"
          class="absolute left-0 top-0 bottom-0 w-1 bg-[#FBC143]"
        ></div>
        
        <svg 
          class="w-5 h-5 flex-shrink-0"
          :class="activeMenu === 'pengetahuan' ? 'text-[#FBC143]' : ''"
          fill="none" 
          stroke="currentColor" 
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        Pengetahuan
      </a>
      
      <!-- Kesehatan -->
      <a 
        href="#" 
        @click.prevent="handleMenuClick('kesehatan')"
        :class="[
          'flex items-center gap-3 px-4 py-3 text-sm transition-colors relative',
          activeMenu === 'kesehatan' ? 'bg-[#263053] text-[#FBC143]' : 'text-white hover:bg-[#263053]'
        ]"
      >
        <!-- Yellow indicator bar -->
        <div 
          v-if="activeMenu === 'kesehatan'"
          class="absolute left-0 top-0 bottom-0 w-1 bg-[#FBC143]"
        ></div>
        
        <svg 
          class="w-5 h-5 flex-shrink-0"
          :class="activeMenu === 'kesehatan' ? 'text-[#FBC143]' : ''"
          fill="none" 
          stroke="currentColor" 
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
        Kesehatan
      </a>
      
      <!-- Halo Pusdaik -->
      <a 
        href="#" 
        @click.prevent="handleMenuClick('halo-pusdaik')"
        :class="[
          'flex items-center gap-3 px-4 py-3 text-sm transition-colors relative',
          activeMenu === 'halo-pusdaik' ? 'bg-[#263053] text-[#FBC143]' : 'text-white hover:bg-[#263053]'
        ]"
      >
        <!-- Yellow indicator bar -->
        <div 
          v-if="activeMenu === 'halo-pusdaik'"
          class="absolute left-0 top-0 bottom-0 w-1 bg-[#FBC143]"
        ></div>
        
        <svg 
          class="w-5 h-5 flex-shrink-0"
          :class="activeMenu === 'halo-pusdaik' ? 'text-[#FBC143]' : ''"
          fill="none" 
          stroke="currentColor" 
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        Halo Pusdaik
      </a>

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
import { ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import { logbookService } from '../services/Logbook/logbookService'

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

const openSubmenus = ref({
  kepegawaian: false
})

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
    router.push('/login')
  }
}

const handleMenuClick = (menu) => {
  emit('menu-change', menu)
  
  // Jika klik logbook, pastikan submenu kepegawaian terbuka
  if (menu === 'logbook') {
    openSubmenus.value.kepegawaian = true
  }
}

// Watch activeMenu untuk menjaga submenu tetap terbuka jika ada submenu yang aktif
watch(() => props.activeMenu, (newMenu) => {
  if (newMenu === 'logbook') {
    openSubmenus.value.kepegawaian = true
  }
}, { immediate: true })

const toggleSubmenu = (menu) => {
  openSubmenus.value[menu] = !openSubmenus.value[menu]
}
</script>