import { logbookRuangPribadiKepagawaianSubRoute } from './kepegawaianSub'

export const kepegawaianRoute = {
  path: 'ruang-pribadi/kepagawaian',
  name: 'ruang-pribadi.kepagawaian',
  meta: {
    menu: true,
    title: 'Kepegawaian',
    icon: 'fa-duotone fa-users',
    guard: 'dws.kepegawaian.index'
  },
  redirect: { name: 'ruang-pribadi.kepagawaian.logbook' },
  children: [
    logbookRuangPribadiKepagawaianSubRoute
  ]
}
