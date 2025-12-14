import ComponentView from '@/views/demo/ComponentView.vue'

export const componentRoute = {
  path: 'component',
  name: 'dashboard.component',
  component: ComponentView,
  meta: {
    title: 'Components',
    menu: true,
    guard: 'project.role.default',
    icon: 'fa-duotone fa-circle-o'
  }
}
