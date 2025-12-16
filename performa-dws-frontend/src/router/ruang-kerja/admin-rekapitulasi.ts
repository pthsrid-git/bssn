import AdminRekapitulasiView from '@/views/ruang-kerja/ProformaAdminRekapitulasiView.vue'

export const adminRekapitulasiRoute = {
  path: 'admin-rekapitulasi',
  name: 'ruang-kerja.admin-rekapitulasi',
  component: AdminRekapitulasiView,
  meta: {
    title: 'REKAPITULASI',
    menu: true,
    guard: 'project.role.kerja.admin',
    icon: 'fa-duotone fa-user',
    order: 2
  }
}
