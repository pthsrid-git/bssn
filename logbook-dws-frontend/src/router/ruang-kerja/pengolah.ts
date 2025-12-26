import PengajuanView from '@/views/pengolah/pengajuan/PengajuanView.vue'

export const pengolahRoute = {
  path: 'ruang-kerja/logbook/pengolah-logbook',
  name: 'ruang-kerja.logbook.pengolah-logbook',
  component: PengajuanView,
  meta: {
    menu: true,
    title: 'Pengolah Logbook',
    icon: 'fa-duotone fa-receipt',
    guard: 'logbook.pengolah.index'
  }
}
