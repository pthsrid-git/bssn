// router/index.js
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  // Login route (guest only)
  {
    path: '/login',
    name: 'Login',
    component: () => import('../components/Auth/Login.vue'),
    meta: { guest: true }
  },
  
  // Protected routes (authenticated only)
  {
    path: '/',
    component: () => import('../layouts/MainLayout.vue'),
    meta: { requiresAuth: true },
    redirect: '/beranda',
    children: [
      {
        path: 'beranda',
        name: 'Beranda',
        component: () => import('../views/Beranda.vue'),
        meta: { 
          requiresAuth: true,
          allowedRoles: ['all'] // Semua user bisa akses
        }
      },
      {
        path: 'logbook',
        name: 'Logbook',
        component: () => import('../views/Logbook.vue'),
        meta: { 
          requiresAuth: true,
          allowedRoles: ['all'] // Semua user bisa akses
        }
      },
      {
        path: 'logbook-katim',
        name: 'LogbookKatim',
        component: () => import('../views/LogbookKatim.vue'),
        meta: { 
          requiresAuth: true,
          allowedRoles: ['pmk', 'katim'], // Hanya PMK (Ketua Tim)
          requiredPermission: 'logbook-katim'
        }
      },
      {
        path: 'logbook-atasan',
        name: 'LogbookAtasan',
        component: () => import('../views/LogbookAtasan.vue'),
        meta: { 
          requiresAuth: true,
          allowedRoles: ['ka-unit', 'kaunit'], // Hanya Ka-unit
          requiredPermission: 'logbook-atasan'
        }
      }
    ]
  },
  
  // Catch all - redirect ke login atau beranda
  {
    path: '/:pathMatch(.*)*',
    redirect: (to) => {
      const token = localStorage.getItem('token')
      return token ? '/beranda' : '/login'
    }
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

// Helper function to check permissions
const checkPermission = (user, requiredPermission) => {
  if (!requiredPermission) return true
  
  // Helper functions untuk role detection
  const isPKO = (user) => {
    if (user.role === 'pko' || user.role === 'staff') return true
    if (user.kode_jabatan === 'PKO') return true
    if (user.level === 1 || user.level === 'pko') return true
    
    if (user.parent_id !== null && user.parent_id !== undefined) {
      const jabatan = user.nama_jabatan?.toLowerCase() || ''
      if (!jabatan.includes('kepala') && !jabatan.includes('ketua')) {
        return true
      }
    }
    return false
  }
  
  const isPMK = (user) => {
    if (user.role === 'pmk' || user.role === 'katim' || user.role === 'ketua_tim') return true
    if (user.kode_jabatan === 'PMK' || user.kode_jabatan === 'KATIM') return true
    if (user.level === 2 || user.level === 'pmk') return true
    if (user.is_pmk === true || user.is_katim === true) return true
    
    const jabatan = user.nama_jabatan?.toLowerCase() || ''
    if (jabatan.includes('ketua tim') || jabatan.includes('kepala tim')) {
      return true
    }
    return false
  }
  
  const isKaUnit = (user) => {
    if (user.role === 'ka-unit' || user.role === 'kaunit' || user.role === 'atasan' || user.role === 'kepala_unit') return true
    if (user.kode_jabatan === 'KA-UNIT' || user.kode_jabatan === 'KAUNIT') return true
    if (user.level === 3 || user.level === 'ka-unit') return true
    if (user.is_ka_unit === true || user.is_atasan === true) return true
    
    if (user.parent_id === null || user.parent_id === undefined) {
      const jabatan = user.nama_jabatan?.toLowerCase() || ''
      if (jabatan.includes('kepala') || jabatan.includes('direktur') || jabatan.includes('kabag')) {
        return true
      }
    }
    return false
  }
  
  const menuPermissions = {
    'logbook-katim': () => {
      // Hanya PMK (Ketua Tim)
      return isPMK(user)
    },
    'logbook-atasan': () => {
      // Hanya Ka-unit
      return isKaUnit(user)
    }
  }
  
  const permissionCheck = menuPermissions[requiredPermission]
  return permissionCheck ? permissionCheck() : true
}

// Navigation Guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  const isAuthenticated = !!token

  console.log('Navigation Guard:', { 
    to: to.path, 
    from: from.path, 
    isAuthenticated 
  })

  // Jika route memerlukan autentikasi
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
      console.log('Not authenticated, redirecting to login')
      next({
        path: '/login',
        query: { redirect: to.fullPath }
      })
      return
    }
    
    // Check permission jika ada requiredPermission
    const requiredPermission = to.meta.requiredPermission
    if (requiredPermission) {
      try {
        const userData = localStorage.getItem('user')
        const user = userData ? JSON.parse(userData) : null
        
        if (!user) {
          console.log('User data not found, redirecting to beranda')
          next('/beranda')
          return
        }
        
        const hasPermission = checkPermission(user, requiredPermission)
        
        if (!hasPermission) {
          console.log('Permission denied, redirecting to beranda')
          alert('Anda tidak memiliki akses ke halaman ini')
          next('/beranda')
          return
        }
      } catch (error) {
        console.error('Error checking permission:', error)
        next('/beranda')
        return
      }
    }
    
    console.log('Authenticated and authorized, proceeding')
    next()
  }
  // Jika route untuk guest (login) dan user sudah login
  else if (to.matched.some(record => record.meta.guest)) {
    if (isAuthenticated) {
      console.log('Already logged in, redirecting to beranda')
      next({ path: '/beranda' })
    } else {
      console.log('Not logged in, showing login page')
      next()
    }
  }
  // Route lainnya
  else {
    console.log('Other route, proceeding')
    next()
  }
})

export default router