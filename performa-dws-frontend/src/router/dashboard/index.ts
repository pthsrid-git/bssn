// router/dashboard/index.ts
import DashboardView from '@/views/foundation/layout/LayoutDashboardDws.vue'

export const dashboardRoutes = [
  {
    path: 'dashboard',
    name: 'dashboard',
    component: DashboardView,
    meta: {
      title: 'Dashboard',
      menu: true,
      guard: 'all',
      icon: 'fa-duotone fa-home'
    }
  }
]
