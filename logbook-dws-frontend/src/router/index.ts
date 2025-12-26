import { createRouter, createWebHistory } from 'vue-router'

import { logbookRuangKerjaRoutes } from './ruang-kerja'
import { logbookRuangPribadiRoutes } from './ruang-pribadi'

import LayoutDashboardDws from '@/views/foundation/layout/LayoutDashboardDws.vue'

const routes = [
  {
    path: '/',
    name: 'ruang-pribadi',
    component: LayoutDashboardDws,
    children: [...logbookRuangPribadiRoutes]
  },
  {
    path: '/',
    name: 'ruang-kerja',
    component: LayoutDashboardDws,
    children: [...logbookRuangKerjaRoutes]
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes
})

export default router
