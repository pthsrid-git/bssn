import PetugasLogbookView from '@/views/admin/petugaslogbook/PetugasLogbookView.vue'

export const petugasLogbookRoute = {
  path: 'ruang-kerja/logbook/petugas-logbook',
  name: 'ruang-kerja.logbook.petugas-logbook',
  component: PetugasLogbookView,
  meta: {
    menu: true,
    title: 'Admin Performa - Petugas',
    icon: 'fa-duotone fa-users-gear',
    guard: 'logbook.admin.index'
  }
}
