type LogbookStatus = 'Disubmit' | 'Disetujui' | 'Ditolak'

export interface LogbookData {
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
  status: LogbookStatus
  catatan_katim: string | null
  catatan_atasan: string | null
  created_at: string
  updated_at: string
}

export interface LogbookFormData {
  tanggal: string
  jam_mulai: string
  jam_selesai: string
  rencana_hasil_kinerja_skp: string
  indikator_hasil_rencana_kerja: string
  aktivitas_kegiatan_harian: string
  keterangan?: string
  file?: File
}

export const logbookDataFromJson = (json: any): LogbookData => {
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
