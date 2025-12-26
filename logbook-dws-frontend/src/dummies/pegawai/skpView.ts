// Dummy data untuk SkpView component
export const skpViewUserDataDummy = {
  name: 'Nama Pegawai',
  nip: '12345678901234567',
  position: 'Pranata Komputer Terampil',
  unitkerja: 'Pusat Data dan Teknologi Informasi Komunikasi'
}

export const skpViewPejabatPenilaiDummy = {
  name: 'Nama Pejabat Penilai',
  nip: '98765432109876543',
  pangkat: 'Pembina (IV/a)',
  position: 'Kepala Biro',
  jabatan: 'Kepala Biro',
  unitkerja: 'Biro Organisasi dan Sumber Daya Manusia'
}

export const skpViewDetailDummy = {
  hasilKerja: [
    {
      rencanaPimpinan: 'Pengangkatan pegawai BSSN yang sesuai kebutuhan',
      rencanaHasil: 'Terpenuhinya KepKA BSSN tentang Pengangkatan CPNS',
      aspek: 'Kuantitas',
      indikator: 'Terpenuhinya Surat pengangkatan CPNS',
      target: '100%'
    },
    {
      rencanaPimpinan: 'Pengangkatan pegawai BSSN yang sesuai kebutuhan',
      rencanaHasil: 'Terpenuhinya KepKA BSSN tentang Pengangkatan CPNS',
      aspek: 'Kuantitas',
      indikator: 'Terpenuhinya Surat pengangkatan CPNS',
      target: '100%'
    }
  ],
  perilakuKerja: [
    {
      aspek: 'Berorientasi Pelayanan',
      rincian: [
        'Memahami dan memenuhi kebutuhan masyarakat',
        'Ramah, cekatan, solutif, dan dapat diandalkan',
        'Melakukan perbaikan tiada henti'
      ],
      ekspektasi: '-'
    },
    {
      aspek: 'Akuntabel',
      rincian: [
        'Melaksanakan tugas dengan jujur bertanggung jawab',
        'Menggunakan kekayaan negara secara bertanggung jawab',
        'Tidak menyalahgunakan kewenangan jabatan'
      ],
      ekspektasi: '-'
    }
  ],
  lampiran: {
    dukunganSumberDaya: [
      { nama: 'POK Biro OSDM Tahun 2025', jenis: 'Dokumen', status: 'Aktif' },
      { nama: 'Program Kerja Biro OSDM Tahun 2025', jenis: 'Dokumen', status: 'Aktif' }
    ],
    pengembangan: [
      { nama: 'Pelatihan Kepemimpinan', jenis: 'Pelatihan', status: 'Selesai' },
      { nama: 'Workshop Excel Advanced', jenis: 'Pelatihan', status: 'Selesai' }
    ],
    penghargaan: [
      { nama: 'Penghargaan Kinerja Terbaik 2024', jenis: 'Penghargaan', status: 'Diterima' },
      { nama: 'Sertifikat Keahlian', jenis: 'Sertifikat', status: 'Diterima' }
    ]
  }
}
