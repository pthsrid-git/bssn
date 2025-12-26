export const recordsPengajuanDummy = (): any => {
  return {
    data: [
      {
        id: 5,
        nama: 'BASHIR ARROHMAN',
        tanggal_pengajuan: '2025-11-18',
        periode_gaji: '2025-09',
        keperluan: 'Pengajuan slip gaji terbaru',
        catatan: null,
        status: 'dibuat',
        keterangan: null
      },
      {
        id: 4,
        nama: 'BIMA SATRIA YUDHA MOHAMMAD, S.Si.',
        tanggal_pengajuan: '2025-11-16',
        periode_gaji: '2025-07',
        keperluan: 'Bank Rakyat Indonesia',
        catatan: '',
        status: 'ditolak',
        keterangan: 'Maksimal pengajuan 3 Periode Logbook'
      },
      {
        id: 3,
        nama: 'MARDIYONO, S.E.',
        tanggal_pengajuan: '2025-11-07',
        periode_gaji: '2025-11',
        keperluan: 'Persyaratan anak mendaftar sekolah di SMA Taruna Kemala Bhayangkari',
        catatan: 'Slip gaji 3 (tiga) bulan terakhir',
        status: 'diproses',
        keterangan:
          'Dalam proses verifikasi, yang dapat kami proses hanya untuk bulan November 2025, untuk bulan yang lain (September dan Oktober 2025), mohon untuk diajukan kembali tiap bulan yang diinginkan, terima kasih'
      },
      {
        id: 2,
        nama: 'RIZKY AINUR ROFIQ. S.Tr.Kom',
        tanggal_pengajuan: '2025-11-07',
        periode_gaji: '2025-11',
        keperluan: 'Permohonan pendaftaran BPJS',
        catatan: '',
        status: 'selesai',
        keterangan:
          'Telah selesai di Cetak dan di TTD, untuk berkasnya dapat diambil di CS Keuangan BSSN, Lt. 6, Gedung Sekretariat utama. Terima kasih atas kesedian menunggu.'
      },
      {
        id: 1,
        nama: 'ISA VARSYA AKMALIA HENDARIKA, S.AP.',
        tanggal_pengajuan: '2025-11-01',
        periode_gaji: '2025-06',
        keperluan: 'Pengajuan Kartu Kredit Bank BRI',
        catatan: '',
        status: 'ditolak',
        keterangan: 'Maksimal pengajuan 3 Periode Logbook'
      }
    ],
    meta: {
      current_page: 1,
      last_page: 2,
      per_page: 5,
      total: 10
    }
  }
}
