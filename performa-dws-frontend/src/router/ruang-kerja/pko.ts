// pko.ts
import type { RouteRecordRaw } from 'vue-router'
import { h } from 'vue'
import { RouterView } from 'vue-router'
import ProformaPKOView from '@/views/ruang-kerja/ProformaPKOView.vue'
import ProformaPKORealisasiView from '@/views/ruang-kerja/ProformaPKORealisasiView.vue'

export const pkoRoute: RouteRecordRaw = {
  path: 'pko',
  name: 'ruang-kerja.pko',
  component: () => h(RouterView),
  redirect: { name: 'ruang-kerja.pko.kinerja' },
  meta: {
    title: 'PKO SEKRETARIAT UTAMA',
    menu: true,
    guard: 'project.role.kerja.pko',
    icon: 'fa-duotone fa-user',
    order: 3
  },
  children: [
    {
      path: 'kinerja', // ✅ Path relatif tanpa slash
      name: 'ruang-kerja.pko.kinerja',
      component: ProformaPKOView,
      meta: {
        title: 'KINERJA',
        menu: true, // ✅ Pastikan menu: true
        guard: 'project.role.kerja.pko',
        icon: 'fa-duotone fa-book',
        parent: 'ruang-kerja.pko' // ✅ Parent harus sesuai dengan name parent
      }
    },
    {
      path: 'realisasi', // ✅ Path relatif tanpa slash
      name: 'ruang-kerja.pko.realisasi',
      component: ProformaPKORealisasiView,
      meta: {
        title: 'REALISASI',
        menu: true, // ✅ Pastikan menu: true
        guard: 'project.role.kerja.pko',
        icon: 'fa-duotone fa-chart-line',
        parent: 'ruang-kerja.pko' // ✅ Parent harus sesuai dengan name parent
      }
    }
  ]
}
