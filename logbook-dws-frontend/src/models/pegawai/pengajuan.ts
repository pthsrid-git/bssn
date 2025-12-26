type PengajuanStatus = 'dibuat' | 'diproses' | 'ditolak' | 'selesai'

export interface PengajuanData {
  id: string
  tanggalPengajuan: string
  periodeGaji: string
  keperluan: string | null
  catatan: string | null
  status: PengajuanStatus
  keterangan: string | null
}

export const pengajuanDataFromJson = (json: any): PengajuanData => {
  return {
    id: String(json.id),
    tanggalPengajuan: json.tanggal_pengajuan,
    periodeGaji: json.periode_gaji,
    keperluan: json.keperluan === '' || json.keperluan == null ? null : json.keperluan,
    catatan: json.catatan === '' || json.catatan == null ? null : json.catatan,
    status: json.status,
    keterangan: json.keterangan === '' || json.keterangan == null ? null : json.keterangan
  }
}
