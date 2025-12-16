import type { RouteRecordRaw } from 'vue-router'
import { h } from 'vue'
import { RouterView } from 'vue-router'
import LogbookAdminView from '@/views/ruang-kerja/ProformaAdminView.vue'
import AdminRekapitulasiView from '@/views/ruang-kerja/ProformaAdminRekapitulasiView.vue'
import AdminPetugasView from '@/views/ruang-kerja/ProformaAdminPetugasView.vue'

export const adminRoute: RouteRecordRaw = {
  path: 'admin',
  name: 'ruang-kerja.admin',
  component: () => h(RouterView), // Menggunakan h() function dari Vue
  redirect: { name: 'ruang-kerja.admin.pohon-kinerja' },
  meta: {
    title: 'Admin',
    menu: true,
    guard: 'project.role.kerja.admin',
    icon: 'fa-duotone fa-user',
    order: 2
  },
  children: [
    {
      path: 'pohon-kinerja',
      name: 'ruang-kerja.admin.pohon-kinerja',
      component: LogbookAdminView,
      meta: {
        title: 'Pohon Kinerja',
        menu: true,
        guard: 'project.role.kerja.admin',
        icon: 'fa-duotone fa-book',
        parent: 'ruang-kerja.admin'
      }
    },
    {
      path: 'petugas',
      name: 'ruang-kerja.admin.petugas',
      component: AdminPetugasView,
      meta: {
        title: 'Petugas',
        menu: true,
        guard: 'project.role.kerja.admin',
        icon: 'fa-duotone fa-user',
        parent: 'ruang-kerja.admin'
      }
    },
    {
      path: 'rekapitulasi',
      name: 'ruang-kerja.admin.rekapitulasi',
      component: AdminRekapitulasiView,
      meta: {
        title: 'Rekapitulasi',
        menu: true,
        guard: 'project.role.kerja.admin',
        icon: 'fa-duotone fa-user',
        parent: 'ruang-kerja.admin'
      }
    }
  ]
}
