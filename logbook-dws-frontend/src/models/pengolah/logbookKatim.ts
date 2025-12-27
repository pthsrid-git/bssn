type LogbookKatimStatus = 'Disubmit' | 'Disetujui' | 'Ditolak'

export interface TeamMemberData {
  id: number
  nama: string
  nip: string
  pangkat: string
  golongan?: string
  jabatan: string
  email: string
  unit_kerja: string
  role: string
}

export interface LogbookKatimData {
  id: number
  user_id: number
  tanggal: string
  jam_mulai: string
  jam_selesai: string
  rencana_hasil_kinerja_skp: string
  indikator_hasil_rencana_kerja: string
  aktivitas_kegiatan_harian: string
  keterangan: string | null
  file_path: string | null
  file_name: string | null
  file_size: number | null
  file_url: string | null
  status: LogbookKatimStatus
  catatan_katim: string | null
  catatan_atasan: string | null
  created_at: string
  updated_at: string
}

export interface LogbookKatimFormData {
  tanggal: string
  jam_mulai: string
  jam_selesai: string
  rencana_hasil_kinerja_skp: string
  indikator_hasil_rencana_kerja: string
  aktivitas_kegiatan_harian: string
  keterangan?: string
  file?: File
}

export const teamMemberDataFromJson = (json: any): TeamMemberData => {
  return {
    id: json.id,
    nama: json.nama || json.fullname || json.name,
    nip: json.nip,
    pangkat: json.pangkat || '-',
    golongan: json.golongan,
    jabatan: json.jabatan || '-',
    email: json.email,
    unit_kerja: json.unit_kerja || json.kode_unit_organisasi,
    role: json.role
  }
}

export const logbookKatimDataFromJson = (json: any): LogbookKatimData => {
  return {
    id: json.id,
    user_id: json.user_id,
    tanggal: json.tanggal,
    jam_mulai: json.jam_mulai,
    jam_selesai: json.jam_selesai,
    rencana_hasil_kinerja_skp: json.rencana_hasil_kinerja_skp,
    indikator_hasil_rencana_kerja: json.indikator_hasil_rencana_kerja,
    aktivitas_kegiatan_harian: json.aktivitas_kegiatan_harian,
    keterangan: json.keterangan,
    file_path: json.file_path,
    file_name: json.file_name,
    file_size: json.file_size,
    file_url: json.file_url,
    status: json.status,
    catatan_katim: json.catatan_katim,
    catatan_atasan: json.catatan_atasan,
    created_at: json.created_at,
    updated_at: json.updated_at
  }
}
