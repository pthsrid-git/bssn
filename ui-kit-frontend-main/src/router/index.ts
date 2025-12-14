// router/index.ts
import { createRouter, createWebHistory } from 'vue-router'
import { ruangPribadiRoutes } from './ruang-pribadi'
import { ruangKerjaRoutes } from './ruang-kerja'
import LayoutDashboardDws from '@/views/foundation/layout/LayoutDashboardDws.vue'
import LoginView from '@/views/auth/LoginView.vue'

const routes = [
  {
    path: '/login',
    name: 'login',
    component: LoginView,
    meta: { requiresAuth: false }
  },
  {
    path: '/',
    redirect: (to: any) => {
      const token = localStorage.getItem('auth_token')
      return token ? '/dashboard-dws/ruang-pribadi/beranda' : '/login'
    }
  },
  {
    path: '/dashboard-dws',
    component: LayoutDashboardDws,
    redirect: '/dashboard-dws/ruang-pribadi/beranda',
    meta: { requiresAuth: true },
    children: [
      {
        path: 'ruang-pribadi',
        name: 'ruang-pribadi',
        redirect: { name: 'ruang-pribadi.beranda' },
        children: [...ruangPribadiRoutes]
      },
      {
        path: 'ruang-kerja',
        name: 'ruang-kerja',
        redirect: { name: 'ruang-kerja.logbook-katim' },
        children: [...ruangKerjaRoutes]
      }
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/'
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

// Helper function to get user permissions
function getUserPermissions(): string[] {
  const userStr = localStorage.getItem('user')
  if (!userStr) return []

  try {
    const user = JSON.parse(userStr)
    const permissions: string[] = []

    // Flatten permissions from ruangPribadi and ruangKerja
    if (user.permissions?.ruangPribadi) {
      permissions.push(...user.permissions.ruangPribadi)
    }
    if (user.permissions?.ruangKerja) {
      permissions.push(...user.permissions.ruangKerja)
    }

    return permissions
  } catch (e) {
    console.error('Error parsing user permissions:', e)
    return []
  }
}

// Navigation Guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('auth_token')
  const requiresAuth = to.meta.requiresAuth !== false

  // 1. Check authentication
  if (requiresAuth && !token) {
    console.log('❌ Not authenticated, redirecting to login')
    return next('/login')
  }

  if (to.path === '/login' && token) {
    console.log('✓ Already authenticated, redirecting to beranda')
    return next('/dashboard-dws/ruang-pribadi/beranda')
  }

  // 2. Check permissions (if route has guard)
  if (token && to.meta.guard) {
    const permissions = getUserPermissions()
    const requiredGuard = to.meta.guard as string

    if (!permissions.includes(requiredGuard)) {
      console.log('❌ Permission denied:', {
        required: requiredGuard,
        has: permissions
      })

      // Redirect to beranda if no permission
      return next('/dashboard-dws/ruang-pribadi/beranda')
    }
  }

  next()
})

export default router
