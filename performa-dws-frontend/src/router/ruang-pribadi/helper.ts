import HelperView from '@/views/demo/HelperView.vue'

export const helperRoute = {
  path: 'ruang-pribadi/helper',
  name: 'ruang-pribadi.helper',
  component: HelperView,
  meta: {
    title: 'Helpers',
    menu: true,
    guard: 'project.role.pribadi',
    icon: 'fa-duotone fa-circle-o'
  }
}
