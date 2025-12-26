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
    name: 'root',
    redirect: { name: 'ruang-pribadi.dashboard' }
  },
  {
    path: '/dashboard-dws',
    component: LayoutDashboardDws,
    meta: { requiresAuth: true },
    children: [
      {
        path: 'ruang-pribadi',
        name: 'ruang-pribadi',
        redirect: { name: 'ruang-pribadi.dashboard' },
        children: [...ruangPribadiRoutes]
      },
      {
        path: 'ruang-kerja',
        name: 'ruang-kerja',
        redirect: { name: 'ruang-kerja.logbook-katim' },
        children: [...ruangKerjaRoutes] // katim, atasan, admin
      }
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    redirect: { name: 'ruang-pribadi.dashboard' }
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

// Navigation Guard dengan logging lebih detail
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('auth_token')
  const requiresAuth = to.meta.requiresAuth !== false

  console.log('🚦 Navigation Guard:', {
    from: from.name || from.path,
    to: to.name || to.path,
    requiresAuth,
    hasToken: !!token,
    meta: to.meta,
    matched: to.matched.map((route) => ({
      path: route.path,
      name: route.name,
      meta: route.meta
    }))
  })

  // 1. Handle authentication for login page
  if (to.path === '/login') {
    if (token) {
      console.log('🔁 Already authenticated, redirecting to dashboard')
      return next({ name: 'ruang-pribadi.dashboard' })
    }
    return next()
  }

  // 2. Handle authentication for protected routes
  if (requiresAuth && !token) {
    console.log('🔒 Not authenticated, redirecting to login')
    return next('/login')
  }

  // 3. Check permissions (if route has guard)
  if (token && to.meta.guard) {
    const permissions = getUserPermissions()
    const requiredGuard = to.meta.guard as string

    console.log('🔐 Permission Check:', {
      required: requiredGuard,
      userPermissions: permissions,
      hasPermission: permissions.includes(requiredGuard)
    })

    if (!permissions.includes(requiredGuard)) {
      console.log('⛔ Permission denied, redirecting to dashboard')
      return next({ name: 'ruang-pribadi.dashboard' })
    }
  }

  // 4. Special handling for root path
  if (to.path === '/' && token) {
    console.log('🏠 Root path with token, redirecting to dashboard')
    return next({ name: 'ruang-pribadi.dashboard' })
  }

  // 5. Continue navigation
  console.log('✅ Navigation allowed')
  next()
})

export default router
