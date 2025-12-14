import ModelView from '@/views/demo/ModelView.vue'

export const modelRoute = {
  path: 'ruang-kerja/model',
  name: 'ruang-kerja.model',
  component: ModelView,
  meta: {
    title: 'Models',
    menu: true,
    guard: 'project.role.kerja',
    icon: 'fa-duotone fa-circle-o'
  }
}
