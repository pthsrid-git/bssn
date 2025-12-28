import LogbookKaunitView from '@/views/kaunit/logbookkaunit/LogbookKaunitView.vue'

export const logbookKaunitRoute = {
  path: 'ruang-kerja/logbook/logbook-kaunit',
  name: 'ruang-kerja.logbook.logbook-kaunit',
  component: LogbookKaunitView,
  meta: {
    menu: true,
    title: 'Logbook KA Unit Kerja',
    icon: 'fa-duotone fa-receipt',
    guard: 'logbook.kaunit.index'
  }
}
