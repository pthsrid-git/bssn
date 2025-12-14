import ComponentView from '@/views/demo/ComponentView.vue'

export const componentRoute = {
  path: 'ruang-pribadi/component',
  name: 'ruang-pribadi.component',
  component: ComponentView,
  meta: {
    title: 'Components',
    menu: true,
    guard: 'project.role.pribadi',
    icon: 'fa-duotone fa-circle-o'
  }
}
