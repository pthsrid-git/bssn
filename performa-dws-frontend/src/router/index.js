import { createRouter, createWebHistory } from 'vue-router'
import { authService } from '../services/auth'

// Auth Views
import Login from '../views/auth/Login.vue'

// Main Views
import Beranda from '../views/Beranda.vue'

// Admin Performa Views
import PohonKinerja from '../views/admin-performa/PohonKinerja.vue'
import Petugas from '../views/admin-performa/Petugas.vue'
import Rekapitulasi from '../views/admin-performa/Rekapitulasi.vue'

// Placeholder Views (untuk menu lain)
import ComingSoon from '../views/ComingSoon.vue'

const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { 
      requiresAuth: false,
      hideLayout: true
    }
  },
  {
    path: '/beranda',
    name: 'Beranda',
    component: Beranda,
    meta: { 
      requiresAuth: true,
      title: 'Beranda'
    }
  },
  
  // ========== ADMIN PERFORMA ROUTES ==========
  {
    path: '/admin-performa',
    meta: { 
      requiresAuth: true,
      requiredRoles: ['admin_eperforma']
    },
    children: [
      {
        path: 'pohon-kinerja',
        name: 'PohonKinerja',
        component: PohonKinerja,
        meta: { 
          title: 'Pohon Kinerja',
          requiredRoles: ['admin_eperforma']
        }
      },
      {
        path: 'petugas',
        name: 'Petugas',
        component: Petugas,
        meta: { 
          title: 'Petugas',
          requiredRoles: ['admin_eperforma']
        }
      },
      {
        path: 'rekapitulasi',
        name: 'Rekapitulasi',
        component: Rekapitulasi,
        meta: { 
          title: 'Rekapitulasi',
          requiredRoles: ['admin_eperforma']
        }
      }
    ]
  },

  // ========== KEPEGAWAIAN ROUTES ==========
  {
    path: '/logbook',
    name: 'Logbook',
    component: ComingSoon,
    meta: { 
      requiresAuth: true,
      title: 'Logbook'
    }
  },

  // ========== RUANG PRIBADI ROUTES ==========
  {
    path: '/keuangan',
    name: 'Keuangan',
    component: ComingSoon,
    meta: { 
      requiresAuth: true,
      title: 'Keuangan'
    }
  },
  {
    path: '/pengetahuan',
    name: 'Pengetahuan',
    component: ComingSoon,
    meta: { 
      requiresAuth: true,
      title: 'Pengetahuan'
    }
  },
  {
    path: '/kesehatan',
    name: 'Kesehatan',
    component: ComingSoon,
    meta: { 
      requiresAuth: true,
      title: 'Kesehatan'
    }
  },
  {
    path: '/halo-pusdaik',
    name: 'HaloPusdaik',
    component: ComingSoon,
    meta: { 
      requiresAuth: true,
      title: 'Halo Pusdaik'
    }
  },

  // ========== RUANG KERJA ROUTES ==========
  {
    path: '/logbook-katim',
    name: 'LogbookKatim',
    component: ComingSoon,
    meta: { 
      requiresAuth: true,
      title: 'Logbook Katim',
      requiredRoles: ['pko', 'pmk']
    }
  },
  {
    path: '/logbook-atasan',
    name: 'LogbookAtasan',
    component: ComingSoon,
    meta: { 
      requiresAuth: true,
      title: 'Logbook Atasan',
      requiredRoles: ['pko']
    }
  },

  // ========== 404 NOT FOUND ==========
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: ComingSoon,
    meta: { 
      requiresAuth: true,
      title: '404 - Not Found'
    }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navigation Guards
router.beforeEach((to, from, next) => {
  const isAuthenticated = authService.isAuthenticated()
  const user = authService.getUser()

  // Set page title
  document.title = to.meta.title 
    ? `${to.meta.title} - ePerforma BSSN` 
    : 'ePerforma BSSN'

  // Check if route requires authentication
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login')
    return
  }

  // Redirect to beranda if authenticated user tries to access login
  if (to.path === '/login' && isAuthenticated) {
    next('/beranda')
    return
  }

  // Check role-based access
  if (to.meta.requiredRoles && user) {
    const hasRequiredRole = Array.isArray(to.meta.requiredRoles)
      ? to.meta.requiredRoles.includes(user.role)
      : to.meta.requiredRoles === user.role

    if (!hasRequiredRole) {
      // Redirect to beranda if user doesn't have required role
      next('/beranda')
      return
    }
  }

  next()
})

export default router