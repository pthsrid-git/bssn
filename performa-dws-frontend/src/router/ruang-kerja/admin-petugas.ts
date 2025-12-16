import AdminPetugasView from '@/views/ruang-kerja/ProformaAdminPetugasView.vue'

export const adminPetugasRoute = {
  path: 'admin-petugas',
  name: 'ruang-kerja.admin-petugas',
  component: AdminPetugasView,
  meta: {
    title: 'Petugas',
    menu: true,
    guard: 'project.role.kerja.admin',
    icon: 'fa-duotone fa-user',
    order: 2
  }
}
