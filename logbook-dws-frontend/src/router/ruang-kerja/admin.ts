import LogbookAdminView from '@/views/admin/logbookadmin/LogbookAdminView.vue'
import PetugasLogbookView from '@/views/admin/petugaslogbook/PetugasLogbookView.vue'

// export const adminRoute = {
//   path: 'ruang-kerja/logbook/logbook-admin',
//   name: 'ruang-kerja.logbook.logbook-admin',
//   component: LogbookAdminView,
//   meta: {
//     menu: true,
//     title: 'Admin Logbook',
//     icon: 'fa-duotone fa-book',
//     guard: 'logbook.admin.index'
//   }
// }

export const adminRoute = {
  path: 'ruang-kerja/logbook/logbook-admin',
  name: 'ruang-kerja.logbook.logbook-admin',
  meta: {
    menu: true,
    title: 'Admin Logbook',
    icon: 'fa-duotone fa-book',
    guard: 'logbook.admin.index'
  },
  redirect: { name: 'ruang-kerja.logbook.logbook-admin.petugas' },
  children: [
    {
      path: 'petugas',
      name: 'ruang-kerja.logbook.logbook-admin.petugas',
      component: PetugasLogbookView,
      meta: {
        menu: true,
        title: 'Petugas',
        icon: 'fa-duotone fa-user'
      }
    },
    {
      path: 'logbook',
      name: 'ruang-kerja.logbook.logbook-admin.logbook',
      component: LogbookAdminView,
      meta: {
        menu: true,
        title: 'Logbook',
        icon: 'fa-duotone fa-book'
      }
    }
  ]
}
