import LogbookAtasanView from '@/views/ruang-kerja/LogbookAtasanView.vue'

export const logbookAtasanRoute = {
  path: 'logbook-atasan',
  name: 'ruang-kerja.logbook-atasan',
  component: LogbookAtasanView,
  meta: {
    title: 'Logbook Atasan',
    menu: true,
    guard: 'project.role.kerja.atasan',
    icon: 'fa-duotone fa-book',
    order: 2
  }
}
