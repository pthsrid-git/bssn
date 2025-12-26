// router/ruang-pribadi/logbook.ts
import LogbookView from '@/views/ruang-pribadi/LogbookView.vue'

export const logbookRoute = {
  path: 'logbook',
  name: 'ruang-pribadi.logbook',
  component: LogbookView,
  meta: {
    title: 'Logbook',
    menu: true,
    guard: 'project.role.pribadi.logbook',
    icon: 'fa-duotone fa-book'
  }
}
