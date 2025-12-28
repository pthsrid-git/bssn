export interface UnitData {
  kode_unit: string
  nama_unit: string
  total_pegawai: number
  total_logbook: number
  pending: number
  disetujui: number
  ditolak: number
}

export interface PegawaiAdminData {
  id: number
  nama: string
  nip: string
  pangkat: string
  golongan: string
  jabatan: string
  email: string
  unit_kerja: string
  role: string
  tim?: string
}

export interface LogbookAdminData {
  id: number
  user_id: number
  tanggal: string
  jam_mulai: string
  jam_selesai: string
  rencana_hasil_kinerja_skp: string
  indikator_hasil_rencana_kerja: string
  aktivitas_kegiatan_harian: string
  keterangan: string
  status: string
  catatan_katim: string
  catatan_atasan: string
  file_path: string | null
  file_name: string | null
  file_size: number | null
  created_at: string
  updated_at: string
}

export interface SummaryAdminData {
  total_units: number
  total_pegawai: number
  total_logbook: number
  pending: number
  disetujui: number
  ditolak: number
}

export const unitDataFromJson = (json: any): UnitData => {
  return {
    kode_unit: json.kode_unit || '-',
    nama_unit: json.nama_unit || json.kode_unit || '-',
    total_pegawai: json.total_pegawai || 0,
    total_logbook: json.total_logbook || 0,
    pending: json.pending || 0,
    disetujui: json.disetujui || 0,
    ditolak: json.ditolak || 0
  }
}

export const pegawaiAdminDataFromJson = (json: any): PegawaiAdminData => {
  return {
    id: json.id,
    nama: json.nama || json.fullname || json.name || '-',
    nip: json.nip || '-',
    pangkat: json.pangkat || '-',
    golongan: json.golongan || '-',
    jabatan: json.jabatan || '-',
    email: json.email || '-',
    unit_kerja: json.unit_kerja || json.kode_unit_organisasi || '-',
    role: json.role || '-',
    tim: json.tim || json.team || undefined
  }
}

export const logbookAdminDataFromJson = (json: any): LogbookAdminData => {
  return {
    id: json.id,
    user_id: json.user_id,
    tanggal: json.tanggal,
    jam_mulai: json.jam_mulai,
    jam_selesai: json.jam_selesai,
    rencana_hasil_kinerja_skp: json.rencana_hasil_kinerja_skp || '-',
    indikator_hasil_rencana_kerja: json.indikator_hasil_rencana_kerja || '-',
    aktivitas_kegiatan_harian: json.aktivitas_kegiatan_harian || '-',
    keterangan: json.keterangan || '-',
    status: json.status,
    catatan_katim: json.catatan_katim || '',
    catatan_atasan: json.catatan_atasan || '',
    file_path: json.file_path || null,
    file_name: json.file_name || null,
    file_size: json.file_size || null,
    created_at: json.created_at,
    updated_at: json.updated_at
  }
}

export const summaryAdminDataFromJson = (json: any): SummaryAdminData => {
  return {
    total_units: json.total_units || 0,
    total_pegawai: json.total_pegawai || 0,
    total_logbook: json.total_logbook || 0,
    pending: json.pending || 0,
    disetujui: json.disetujui || 0,
    ditolak: json.ditolak || 0
  }
}
