export const recordsLogbookKatimUrl = (keyword: string, page: number) => {
  return `/logbook?keyword=${keyword}&page=${page}`
}
export const addLogbookUrl = () => {
  return `/logbook`
}
export const detailLogbookUrl = (id: number) => {
  return `/logbook/${id}`
}
export const updateLogbookUrl = (id: number) => {
  return `/logbook/${id}`
}
export const deleteLogbookUrl = (id: number) => {
  return `/logbook/${id}`
}
export const submitLogbookUrl = (id: number) => {
  return `/logbook/${id}/submit`
}

export const teamMemberLogbookUrl = (id: number) => {
  return `/logbook/${id}/team-member`
}

export const teamMembersUrl = () => {
  return `/katim/team-members`
}

export const summaryUrl = () => {
  return `/katim/summary`
}

export const memberLogsUrl = (
  memberId: number,
  params?: {
    keyword?: string
    page?: number
    start_date?: string
    end_date?: string
  }
) => {
  let url = `/katim/member/${memberId}/logs`
  const queryParams = new URLSearchParams()

  if (params?.keyword) queryParams.append('keyword', params.keyword)
  if (params?.page) queryParams.append('page', params.page.toString())
  if (params?.start_date) queryParams.append('start_date', params.start_date)
  if (params?.end_date) queryParams.append('end_date', params.end_date)

  const queryString = queryParams.toString()
  return queryString ? `${url}?${queryString}` : url
}

export const detailLogUrl = (logId: number) => {
  return `/katim/logs/${logId}`
}

export const updateCatatanKatimUrl = (logId: number) => {
  return `/katim/logs/${logId}/catatan`
}

export const approveLogKatimUrl = (logId: number) => {
  return `/katim/logs/${logId}/approve`
}

export const rejectLogKatimUrl = (logId: number) => {
  return `/katim/logs/${logId}/reject`
}
