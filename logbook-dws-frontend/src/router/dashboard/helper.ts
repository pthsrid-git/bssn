import HelperView from '@/views/demo/HelperView.vue'

export const helperRoute = {
  path: 'helper',
  name: 'dashboard.helper',
  component: HelperView,
  meta: {
    title: 'Helpers',
    menu: true,
    guard: 'project.role.default',
    icon: 'fa-duotone fa-circle-o'
  }
}
