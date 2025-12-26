export interface PegawaiData {
  guid: string
  nama: string
}

export const pegawaiDataFromJson = (json: any): PegawaiData => {
  return {
    guid: json.guid,
    nama: json.nama
  }
}
