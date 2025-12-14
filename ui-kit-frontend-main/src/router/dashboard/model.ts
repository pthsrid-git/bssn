import ModelView from '@/views/demo/ModelView.vue'

export const modelRoute = {
  path: 'model',
  name: 'dashboard.model',
  component: ModelView,
  meta: {
    title: 'Models',
    menu: true,
    guard: 'project.role.default',
    icon: 'fa-duotone fa-circle-o'
  }
}
