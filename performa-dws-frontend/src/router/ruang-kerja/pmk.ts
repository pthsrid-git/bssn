// pko.ts
import type { RouteRecordRaw } from 'vue-router'
import { h } from 'vue'
import { RouterView } from 'vue-router'
import ProformaPMKView from '@/views/ruang-kerja/ProformaPMKView.vue'

export const pmkRoute: RouteRecordRaw = {
  path: 'pmk',
  name: 'ruang-kerja.pmk',
  component: () => h(RouterView),
  redirect: { name: 'ruang-kerja.pmk.kinerja' },
  meta: {
    title: 'TIM PENJAMIN MUTU KINERJA',
    menu: true,
    guard: 'project.role.kerja.pmk',
    icon: 'fa-duotone fa-user',
    order: 3
  },
  children: [
    {
      path: 'kinerja',
      name: 'ruang-kerja.pmk.kinerja',
      component: ProformaPMKView,
      meta: {
        title: 'VERIFIKASI CAPAIAN MUTU KINERJA',
        menu: true, // ✅ Pastikan menu: true
        guard: 'project.role.kerja.pmk',
        icon: 'fa-duotone fa-book',
        parent: 'ruang-kerja.pmk'
      }
    }
  ]
}
