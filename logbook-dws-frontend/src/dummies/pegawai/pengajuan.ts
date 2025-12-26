export const recordsPengajuanDummy = (): any => {
  return {
    data: [
      {
        id: 5,
        tanggal_pengajuan: '2025-09-28',
        periode_gaji: '2025-09',
        keperluan: 'Pengajuan slip gaji terbaru',
        catatan: null,
        status: 'dibuat',
        keterangan: null
      },
      {
        id: 4,
        tanggal_pengajuan: '2025-07-21',
        periode_gaji: '2025-07',
        keperluan: 'Coba aplikasi',
        catatan: 'Mohon bisa ditolak',
        status: 'ditolak',
        keterangan: 'Pengajuan tidak sesuai kebutuhan'
      },
      {
        id: 3,
        tanggal_pengajuan: '2024-11-18',
        periode_gaji: '2024-09',
        keperluan: 'Melihat pendapatan pribadi',
        catatan: null,
        status: 'diproses',
        keterangan:
          'Data gaji dapat dilihat pada Kolom Keuangan, sub Data Pendapatan pada Ruang Kerja Pribadi'
      },
      {
        id: 2,
        tanggal_pengajuan: '2024-11-18',
        periode_gaji: '2024-11',
        keperluan: 'Melihat pendapatan pribadi',
        catatan: null,
        status: 'selesai',
        keterangan:
          'Telah selesai dicetak dan ditandatangani. Berkas dapat diambil di CS Keuangan BSSN, Lt. 6, Gedung Sekretariat Utama. Terima kasih atas kesediaan menunggu.'
      },
      {
        id: 1,
        tanggal_pengajuan: '2024-11-18',
        periode_gaji: '2024-10',
        keperluan: 'Melihat pendapatan pribadi',
        catatan: null,
        status: 'ditolak',
        keterangan:
          'Data gaji dapat dilihat pada Kolom Keuangan, sub Data Pendapatan pada Ruang Kerja Pribadi'
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
