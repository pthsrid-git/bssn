import LogbookView from '@/views/pegawai/logbook/LogbookView.vue'
import PendapatanView from '@/views/pegawai/pendapatan/PendapatanView.vue'
import PengajuanView from '@/views/pegawai/pengajuan/PengajuanView.vue'

export const keuanganRoute = {
  path: 'ruang-pribadi/keuangan',
  name: 'ruang-pribadi.keuangan',
  meta: {
    menu: true,
    title: 'Kepegawaian',
    icon: 'fa-duotone fa-money-bills',
    guard: 'logbook.pegawai.index'
  },
  redirect: { name: 'ruang-pribadi.keuangan.logbook' },
  children: [
    {
      path: 'logbook',
      name: 'ruang-pribadi.keuangan.logbook',
      component: LogbookView,
      meta: {
        menu: true,
        title: 'Logbook',
        icon: 'fa-duotone fa-receipt'
      }
    }
  ]
}
