export const recordsLogbookKaunitUrl = (keyword: string, page: number) => {
  return `/logbook?keyword=${keyword}&page=${page}`
}

export const pegawaiListUrl = () => {
  return `/kaunit/pegawai`
}

export const summaryUrl = () => {
  return `/kaunit/summary`
}

export const pegawaiLogsUrl = (
  pegawaiId: number,
  params?: {
    start_date?: string
    end_date?: string
  }
) => {
  let url = `/kaunit/pegawai/${pegawaiId}/logs`
  const queryParams = new URLSearchParams()

  if (params?.start_date) queryParams.append('start_date', params.start_date)
  if (params?.end_date) queryParams.append('end_date', params.end_date)

  const queryString = queryParams.toString()
  return queryString ? `${url}?${queryString}` : url
}

export const detailLogUrl = (logId: number) => {
  return `/kaunit/logs/${logId}`
}

export const updateCatatanKaunitUrl = (logId: number) => {
  return `/kaunit/logs/${logId}/catatan`
}

export const approveLogKaunitUrl = (logId: number) => {
  return `/kaunit/logs/${logId}/approve`
}

export const rejectLogKaunitUrl = (logId: number) => {
  return `/kaunit/logs/${logId}/reject`
}
