// Admin ePerforma URLs
export const recordsAdminEperformaUrl = (keyword: string, page: number) => {
  return `/logbook/admin-eperforma?keyword=${keyword}&page=${page}`
}

export const addAdminEperformaUrl = () => {
  return `/logbook/admin-eperforma`
}

export const deleteAdminEperformaUrl = (guid: string) => {
  return `/logbook/admin-eperforma/${guid}`
}

// Pengelola Kinerja Organisasi (PKO) URLs
export const recordsPengelolaKinerjaOrganisasiUrl = (keyword: string, page: number) => {
  return `/logbook/pengelola-kinerja-organisasi?keyword=${keyword}&page=${page}`
}

export const addPengelolaKinerjaOrganisasiUrl = () => {
  return `/logbook/pengelola-kinerja-organisasi`
}

export const deletePengelolaKinerjaOrganisasiUrl = (guid: string) => {
  return `/logbook/pengelola-kinerja-organisasi/${guid}`
}

// PMK URLs
export const recordsPmkUrl = (keyword: string, page: number) => {
  return `/logbook/pmk?keyword=${keyword}&page=${page}`
}

export const addPmkUrl = () => {
  return `/logbook/pmk`
}

export const deletePmkUrl = (guid: string) => {
  return `/logbook/pmk/${guid}`
}
