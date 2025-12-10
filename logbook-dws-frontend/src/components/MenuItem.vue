<!-- logbook-dws-frontend/src/components/MenuItem.vue -->
<template>
  <div>
    <!-- Menu with Submenu -->
    <div v-if="item.hasSubmenu">
      <button
        @click="toggleSubmenu"
        :class="[
          'w-full flex items-center justify-between px-4 py-3 text-sm transition-colors',
          isSubmenuActive ? 'bg-[#263053]' : 'hover:bg-[#263053]'
        ]"
      >
        <div class="flex items-center gap-3">
          <component :is="getIcon(item.icon)" class="w-5 h-5 flex-shrink-0" />
          {{ item.label }}
        </div>
        <svg 
          class="w-4 h-4 transition-transform flex-shrink-0" 
          :class="{ 'rotate-180': submenuOpen }"
          fill="none" 
          stroke="currentColor" 
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      
      <!-- Submenu Items -->
      <div v-show="submenuOpen" class="bg-[#0f1420] py-1">
        <router-link
          v-for="subitem in item.submenu"
          :key="subitem.id"
          :to="subitem.path"
          :class="[
            'flex items-center gap-3 py-2.5 pl-12 pr-4 text-sm transition-colors relative',
            activeMenu === subitem.id ? 'text-[#FBC143]' : 'text-gray-300 hover:text-white'
          ]"
          @click="$emit('menu-click', subitem.id)"
        >
          <!-- Yellow indicator bar -->
          <div 
            v-if="activeMenu === subitem.id"
            class="absolute left-0 top-0 bottom-0 w-1 bg-[#FBC143]"
          ></div>
          
          <component 
            :is="getIcon(subitem.icon)" 
            :class="[
              'w-4 h-4 flex-shrink-0',
              activeMenu === subitem.id ? 'text-[#FBC143]' : 'text-gray-500'
            ]"
          />
          {{ subitem.label }}
        </router-link>
      </div>
    </div>

    <!-- Regular Menu Item -->
    <router-link
      v-else
      :to="item.path"
      :class="[
        'flex items-center gap-3 px-4 py-3 text-sm transition-colors relative',
        activeMenu === item.id ? 'bg-[#263053] text-[#FBC143]' : 'text-white hover:bg-[#263053]'
      ]"
      @click="$emit('menu-click', item.id)"
    >
      <!-- Yellow indicator bar -->
      <div 
        v-if="activeMenu === item.id"
        class="absolute left-0 top-0 bottom-0 w-1 bg-[#FBC143]"
      ></div>
      
      <component 
        :is="getIcon(item.icon)" 
        :class="[
          'w-5 h-5 flex-shrink-0',
          activeMenu === item.id ? 'text-[#FBC143]' : ''
        ]"
      />
      {{ item.label }}
    </router-link>
  </div>
</template>

<script setup>
import { ref, computed, watch, h } from 'vue'

const props = defineProps({
  item: {
    type: Object,
    required: true
  },
  activeMenu: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['menu-click'])

const submenuOpen = ref(false)

// Check if any submenu item is active
const isSubmenuActive = computed(() => {
  if (!props.item.submenu) return false
  return props.item.submenu.some(sub => sub.id === props.activeMenu)
})

// Auto-open submenu if any item is active
watch(() => props.activeMenu, (newMenu) => {
  if (props.item.submenu) {
    const isActive = props.item.submenu.some(sub => sub.id === newMenu)
    if (isActive) {
      submenuOpen.value = true
    }
  }
}, { immediate: true })

const toggleSubmenu = () => {
  submenuOpen.value = !submenuOpen.value
}

// Icon mapping function
const getIcon = (iconName) => {
  const icons = {
    'home': () => h('svg', { 
      fill: 'none', 
      stroke: 'currentColor', 
      viewBox: '0 0 24 24' 
    }, [
      h('path', { 
        'stroke-linecap': 'round', 
        'stroke-linejoin': 'round', 
        'stroke-width': '2', 
        d: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' 
      })
    ]),
    'users': () => h('svg', { 
      fill: 'none', 
      stroke: 'currentColor', 
      viewBox: '0 0 24 24' 
    }, [
      h('path', { 
        'stroke-linecap': 'round', 
        'stroke-linejoin': 'round', 
        'stroke-width': '2', 
        d: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' 
      })
    ]),
    'square': () => h('svg', { 
      fill: 'currentColor', 
      viewBox: '0 0 24 24' 
    }, [
      h('rect', { 
        x: '4', 
        y: '4', 
        width: '16', 
        height: '16', 
        rx: '2' 
      })
    ]),
    'credit-card': () => h('svg', { 
      fill: 'none', 
      stroke: 'currentColor', 
      viewBox: '0 0 24 24' 
    }, [
      h('path', { 
        'stroke-linecap': 'round', 
        'stroke-linejoin': 'round', 
        'stroke-width': '2', 
        d: 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z' 
      })
    ]),
    'book': () => h('svg', { 
      fill: 'none', 
      stroke: 'currentColor', 
      viewBox: '0 0 24 24' 
    }, [
      h('path', { 
        'stroke-linecap': 'round', 
        'stroke-linejoin': 'round', 
        'stroke-width': '2', 
        d: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253' 
      })
    ]),
    'heart': () => h('svg', { 
      fill: 'none', 
      stroke: 'currentColor', 
      viewBox: '0 0 24 24' 
    }, [
      h('path', { 
        'stroke-linecap': 'round', 
        'stroke-linejoin': 'round', 
        'stroke-width': '2', 
        d: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z' 
      })
    ]),
    'chat': () => h('svg', { 
      fill: 'none', 
      stroke: 'currentColor', 
      viewBox: '0 0 24 24' 
    }, [
      h('path', { 
        'stroke-linecap': 'round', 
        'stroke-linejoin': 'round', 
        'stroke-width': '2', 
        d: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z' 
      })
    ]),
    'clipboard': () => h('svg', { 
      fill: 'none', 
      stroke: 'currentColor', 
      viewBox: '0 0 24 24' 
    }, [
      h('path', { 
        'stroke-linecap': 'round', 
        'stroke-linejoin': 'round', 
        'stroke-width': '2', 
        d: 'M6 2v20l6-2 6 2V2H6z' 
      })
    ]),
    'clipboard-check': () => h('svg', { 
      fill: 'none', 
      stroke: 'currentColor', 
      viewBox: '0 0 24 24' 
    }, [
      h('path', { 
        'stroke-linecap': 'round', 
        'stroke-linejoin': 'round', 
        'stroke-width': '2', 
        d: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4' 
      })
    ]),
    'monitor': () => h('svg', { 
      fill: 'none', 
      stroke: 'currentColor', 
      viewBox: '0 0 24 24' 
    }, [
      h('path', { 
        'stroke-linecap': 'round', 
        'stroke-linejoin': 'round', 
        'stroke-width': '2', 
        d: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' 
      })
    ])
  }

  return icons[iconName] || icons['home']
}
</script>