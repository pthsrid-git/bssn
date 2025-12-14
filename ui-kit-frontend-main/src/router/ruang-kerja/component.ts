import ComponentView from '@/views/demo/ComponentView.vue'

export const componentRoute = {
  path: 'ruang-kerja/component',
  name: 'ruang-kerja.component',
  component: ComponentView,
  meta: {
    title: 'Components',
    menu: true,
    guard: 'project.role.kerja',
    icon: 'fa-duotone fa-circle-o'
  }
}
