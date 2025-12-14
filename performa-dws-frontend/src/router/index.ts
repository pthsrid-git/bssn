import { createRouter, createWebHistory } from 'vue-router'

import { dashboardRoutes } from './dashboard'
import { mainRoutes } from './main'
import { ruangKerjaRoutes } from './ruang-kerja'
import { ruangPribadiRoutes } from './ruang-pribadi'

import LayoutDashboardDefault from '@/views/foundation/layout/LayoutDashboardDefault.vue'
import LayoutDashboardDws from '@/views/foundation/layout/LayoutDashboardDws.vue'
import LayoutMain from '@/views/foundation/layout/LayoutMain.vue'

const routes = [
  {
    path: '/',
    name: 'main',
    component: LayoutMain,
    redirect: { name: 'main.component' },
    children: [...mainRoutes]
  },
  {
    path: '/dashboard-default',
    name: 'dashboard',
    component: LayoutDashboardDefault,
    redirect: { name: 'dashboard.component' },
    children: [...dashboardRoutes]
  },
  {
    path: '/dashboard-dws',
    name: 'ruang-pribadi',
    component: LayoutDashboardDws,
    redirect: { name: 'ruang-pribadi.component' },
    children: [...ruangPribadiRoutes]
  },
  {
    path: '/dashboard-dws',
    name: 'ruang-kerja',
    component: LayoutDashboardDws,
    children: [...ruangKerjaRoutes]
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes
})

export default router
