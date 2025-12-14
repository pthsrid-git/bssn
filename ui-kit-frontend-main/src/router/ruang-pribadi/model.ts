import ModelView from '@/views/demo/ModelView.vue'

export const modelRoute = {
  path: 'ruang-pribadi/model',
  name: 'ruang-pribadi.model',
  component: ModelView,
  meta: {
    title: 'Models',
    menu: true,
    guard: 'project.role.pribadi',
    icon: 'fa-duotone fa-circle-o'
  }
}
