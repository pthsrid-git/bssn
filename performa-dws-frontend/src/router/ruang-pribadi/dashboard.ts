// router/ruang-pribadi/dashboard.ts
import DashboardView from '@/views/ruang-pribadi/DashboardView.vue'

export const dashboardRoute = {
  path: 'dashboard',
  name: 'ruang-pribadi.dashboard',
  component: DashboardView,
  meta: {
    title: 'Dashboard',
    menu: true,
    guard: 'project.role.pribadi.dashboard',
    icon: 'fa-duotone fa-home'
  }
}
