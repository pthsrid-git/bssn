import LogbookView from '@/views/pegawai/logbook/LogbookView.vue'

export const logbookRuangPribadiKepagawaianSubRoute = {
  path: 'logbook',
  name: 'ruang-pribadi.kepagawaian.logbook',
  component: LogbookView,
  meta: {
    menu: true,
    title: 'Logbook',
    icon: 'fa-duotone fa-book'
  }
}
