import HelperView from '@/views/demo/HelperView.vue'

export const helperRoute = {
  path: 'ruang-kerja/helper',
  name: 'ruang-kerja}.helper',
  component: HelperView,
  meta: {
    title: 'Helpers',
    menu: true,
    guard: 'project.role.kerja',
    icon: 'fa-duotone fa-circle-o'
  }
}
