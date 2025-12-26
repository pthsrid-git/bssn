import LogbookAtasanView from '@/views/ruang-kerja/LogbookAtasanView.vue'

export const logbookAtasanRoute = {
  path: 'logbook-atasan',
  name: 'ruang-kerja.logbook-atasan',
  component: LogbookAtasanView,
  meta: {
    title: 'Logbook KA Unit Kerja',
    menu: true,
    guard: 'project.role.kerja.kaunit',
    icon: 'fa-duotone fa-book',
    order: 2
  }
}
