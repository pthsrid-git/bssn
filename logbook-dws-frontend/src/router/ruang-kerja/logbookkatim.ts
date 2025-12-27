import LogbookKatimView from '@/views/pengolah/logbookkatim/LogbookKatimView.vue'

export const logbookKatimRoute = {
  path: 'ruang-kerja/logbook/logbook-katim',
  name: 'ruang-kerja.logbook.logbook-katim',
  component: LogbookKatimView,
  meta: {
    menu: true,
    title: 'Logbook Katim',
    icon: 'fa-duotone fa-receipt',
    guard: 'logbook.pengolah.index'
  }
}
