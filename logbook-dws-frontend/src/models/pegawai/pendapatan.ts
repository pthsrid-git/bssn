export interface GajiData {
  gajiDiterima: number
  gajiPokok: number
  tunjanganIstri: number
  tunjanganAnak: number
  tunjanganUmumPns: number
  tunjanganStruktural: number
  tunjanganFungsional: number
  tunjanganDaerah: number
  tunjanganDaerahTerpencil: number
  tunjanganLain: number
  tpp: number
  pembulatan: number
  tunjanganBeras: number
  tunjanganPph: number
  potonganIuranWajibPns: number
  potonganPph: number
  potonganTabunganRumah: number
  potonganBpjs: number
  potonganBpjsKeluargaLainnya: number
}

export const gajiDataFromJson = (json: any): GajiData => {
  return {
    gajiDiterima: Number(json.bersih),
    gajiPokok: Number(json.gjpokok),
    tunjanganIstri: Number(json.tjistri),
    tunjanganAnak: Number(json.tjanak),
    tunjanganUmumPns: Number(json.tjupns),
    tunjanganStruktural: Number(json.tjstruk),
    tunjanganFungsional: Number(json.tjfungs),
    tunjanganDaerah: Number(json.tjdaerah),
    tunjanganDaerahTerpencil: Number(json.tjpencil),
    tunjanganLain: Number(json.tjlain),
    tpp: Number(1234567890),
    pembulatan: Number(json.pembul),
    tunjanganBeras: Number(json.tjberas),
    tunjanganPph: Number(json.tjpph),
    potonganIuranWajibPns: Number(1234567890),
    potonganPph: Number(json.potpph),
    potonganTabunganRumah: Number(1234567890),
    potonganBpjs: Number(1234567890),
    potonganBpjsKeluargaLainnya: Number(1234567890)
  }
}

export interface TunjanganKinerjaData {
  tunjanganKinerjaDiterima: number
}

export const tunjanganKinerjaDataFromJson = (json: any): TunjanganKinerjaData => {
  return {
    tunjanganKinerjaDiterima: Number(json.tunjangan_kinerja_bersih)
  }
}

export interface TunjanganMakanData {
  tunjanganMakanDiterima: number
}

export const tunjanganMakanDataFromJson = (json: any): TunjanganMakanData => {
  return {
    tunjanganMakanDiterima: Number(json.bersih)
  }
}

export interface PotonganData {
  jumlahPotongan: number
}

export const potonganDataFromJson = (json: any): PotonganData => {
  return {
    jumlahPotongan: Number(json.bersih)
  }
}

export interface PendapatanData {
  nama: string
  nip: string
  pangkatGolongan: string
  jabatan: string
  statusPernikahan: string
  periode: string
  gaji: GajiData
  tunjanganKinerja: TunjanganKinerjaData
  tunjanganMakan: TunjanganMakanData
  potongan: PotonganData
  pendapatanBersih: number
}

export const pendapatanDataFromJson = (json: any): PendapatanData => {
  return {
    nama: json.pegawai.name,
    nip: json.pegawai.nip_nrp,
    pangkatGolongan: json.pegawai.pangkat_golongan,
    jabatan: json.pegawai.nama_jabatan,
    statusPernikahan: json.status_nikah,
    periode: json.periode,
    gaji: gajiDataFromJson(json.detail_gaji),
    tunjanganKinerja: tunjanganKinerjaDataFromJson(json.detail_tunjangan_kinerja),
    tunjanganMakan: tunjanganMakanDataFromJson(json.detail_tunjangan_makan),
    potongan: potonganDataFromJson(json.detail_potongan),
    pendapatanBersih: Number(json.total_pendapatan)
  }
}
