import PetugasLogbookView from '@/views/admin/petugaslogbook/PetugasLogbookView.vue'

export const petugasLogbookRoute = {
  path: 'ruang-kerja/logbook/petugas-logbook',
  name: 'ruang-kerja.logbook.petugas-logbook',
  component: PetugasLogbookView,
  meta: {
    menu: true,
    title: 'Admin Logbook - Petugas',
    icon: 'fa-duotone fa-book',
    guard: 'logbook.admin.index'
  }
}
