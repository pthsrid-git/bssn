export const unitsListUrl = () => {
  return `/logbook-admin/units`
}

export const allPegawaiUrl = () => {
  return `/logbook-admin/pegawai`
}

export const unitPegawaiUrl = (unitCode: string) => {
  return `/logbook-admin/units/${unitCode}/pegawai`
}

export const pegawaiLogsAdminUrl = (
  pegawaiId: number,
  params?: {
    start_date?: string
    end_date?: string
  }
) => {
  let url = `/logbook-admin/pegawai/${pegawaiId}/logs`
  const queryParams = new URLSearchParams()

  if (params?.start_date) queryParams.append('start_date', params.start_date)
  if (params?.end_date) queryParams.append('end_date', params.end_date)

  const queryString = queryParams.toString()
  return queryString ? `${url}?${queryString}` : url
}

export const detailLogAdminUrl = (logId: number) => {
  return `/logbook-admin/logs/${logId}`
}

export const summaryAdminUrl = () => {
  return `/logbook-admin/summary`
}

export const downloadAbkReportUrl = (year: number) => {
  return `/logbook-admin/abk/download?year=${year}`
}
