import PendapatanView from '@/views/admin/pendapatan/PendapatanView.vue'
import PetugasView from '@/views/admin/petugas/PetugasView.vue'

export const adminRoute = {
  path: 'ruang-kerja/logbook/admin-keuangan',
  name: 'ruang-kerja.logbook.admin-keuangan',
  meta: {
    menu: true,
    title: 'Admin Keuangan',
    icon: 'fa-duotone fa-money-bills',
    guard: 'logbook.admin.index'
  },
  redirect: { name: 'ruang-kerja.logbook.admin-keuangan.petugas' },
  children: [
    {
      path: 'petugas',
      name: 'ruang-kerja.logbook.admin-keuangan.petugas',
      component: PetugasView,
      meta: {
        menu: true,
        title: 'Petugas',
        icon: 'fa-duotone fa-user'
      }
    },
    {
      path: 'pendapatan',
      name: 'ruang-kerja.logbook.admin-keuangan.pendapatan',
      component: PendapatanView,
      meta: {
        menu: true,
        title: 'Pendapatan',
        icon: 'fa-duotone fa-money-from-bracket'
      }
    }
  ]
}
