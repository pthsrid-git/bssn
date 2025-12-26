export const recordsLogbookDummy = (): any => {
  return {
    data: [
      {
        id: 1,
        user_id: 1,
        tanggal: '2024-01-15',
        jam_mulai: '09:00',
        jam_selesai: '11:00',
        rencana_hasil_kinerja_skp: 'Mengembangkan fitur baru',
        indikator_hasil_rencana_kerja: 'Fitur selesai 100%',
        aktivitas_kegiatan_harian: 'Membuat modul logbook',
        keterangan: 'Selesai tepat waktu',
        file_path: null,
        file_name: null,
        file_size: null,
        file_url: null,
        status: 'Disetujui',
        catatan_katim: null,
        catatan_atasan: 'Bagus',
        created_at: '2024-01-15T09:00:00.000000Z',
        updated_at: '2024-01-15T11:00:00.000000Z'
      },
      {
        id: 2,
        user_id: 1,
        tanggal: '2024-01-16',
        jam_mulai: '08:00',
        jam_selesai: '12:00',
        rencana_hasil_kinerja_skp: 'Testing aplikasi',
        indikator_hasil_rencana_kerja: 'Tidak ada bug',
        aktivitas_kegiatan_harian: 'Melakukan testing fitur logbook',
        keterangan: null,
        file_path: null,
        file_name: null,
        file_size: null,
        file_url: null,
        status: 'Disubmit',
        catatan_katim: null,
        catatan_atasan: null,
        created_at: '2024-01-16T08:00:00.000000Z',
        updated_at: '2024-01-16T12:00:00.000000Z'
      }
    ]
  }
}
