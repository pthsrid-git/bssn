import LogbookKatimView from '@/views/ruang-kerja/LogbookKatimView.vue'

export const logbookKatimRoute = {
  path: 'logbook-katim',
  name: 'ruang-kerja.logbook-katim',
  component: LogbookKatimView,
  meta: {
    title: 'Logbook Katim',
    menu: true,
    guard: 'project.role.kerja.katim',
    icon: 'fa-duotone fa-book',
    order: 1
  }
}
