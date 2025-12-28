import LogbookAdminView from '@/views/admin/logbookadmin/LogbookAdminView.vue'

export const adminRoute = {
  path: 'ruang-kerja/logbook/logbook-admin',
  name: 'ruang-kerja.logbook.logbook-admin',
  component: LogbookAdminView,
  meta: {
    menu: true,
    title: 'Admin Logbook',
    icon: 'fa-duotone fa-book',
    guard: 'logbook.admin.index'
  }
}
