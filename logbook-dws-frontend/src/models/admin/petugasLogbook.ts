// Admin ePerforma
export interface AdminEperformaData {
  guid: string
  name: string
}

export const adminEperformaDataFromJson = (json: any): AdminEperformaData => {
  return {
    guid: String(json.guid),
    name: json.name || json.nama || ''
  }
}

// Pengelola Kinerja Organisasi (PKO)
export interface PengelolaKinerjaOrganisasiData {
  guid: string
  name: string
  unitKerja: string
}

export const pengelolaKinerjaOrganisasiDataFromJson = (json: any): PengelolaKinerjaOrganisasiData => {
  return {
    guid: String(json.guid),
    name: json.name || json.nama || '',
    unitKerja: json.unit_kerja || json.unitKerja || ''
  }
}

// PMK (Pengelola Manajemen Kinerja)
export interface PmkData {
  guid: string
  name: string
  unitKerjaPko: string
}

export const pmkDataFromJson = (json: any): PmkData => {
  return {
    guid: String(json.guid),
    name: json.name || json.nama || '',
    unitKerjaPko: json.unit_kerja_pko || json.unitKerjaPko || ''
  }
}
