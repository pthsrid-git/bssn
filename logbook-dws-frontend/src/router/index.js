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
    redirect: '/beranda', // Default redirect ke beranda
    children: [
      {
        path: 'beranda',
        name: 'Beranda',
        component: () => import('../views/Beranda.vue')
      },
      {
        path: 'logbook',
        name: 'Logbook',
        component: () => import('../views/Logbook.vue')
      },
      {
        path: 'logbook-katim',
        name: 'LogbookKatim',
        component: () => import('../views/LogbookKatim.vue')
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
    } else {
      console.log('Authenticated, proceeding')
      next()
    }
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