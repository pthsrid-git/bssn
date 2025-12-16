import LogbookAdminView from '@/views/ruang-kerja/ProformaAdminView.vue'

export const logbookAdminRoute = {
  path: 'logbook-admin',
  name: 'ruang-kerja.logbook-admin',
  component: LogbookAdminView,
  meta: {
    title: 'Pohon Kinerja',
    menu: true,
    guard: 'project.role.kerja.admin',
    icon: 'fa-duotone fa-book',
    order: 2
  }
}
