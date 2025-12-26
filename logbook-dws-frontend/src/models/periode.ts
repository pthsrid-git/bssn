export interface PeriodeData {
  id: string
  periode: string
  waktu: string
  gaji: string | null
  tunkin: string | null
  tunmak: string | null
  potongan: string | null
}

export const periodeDataFromJson = (json: any): PeriodeData => {
  return {
    id: String(json.id),
    periode: json.periode,
    waktu: json.waktu,
    gaji: json.gaji ?? null,
    tunkin: json.tunkin ?? null,
    tunmak: json.tunmak ?? null,
    potongan: json.potongan ?? null
  }
}
