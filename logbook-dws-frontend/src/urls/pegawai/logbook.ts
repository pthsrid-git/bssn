export const recordsLogbookUrl = (keyword: string, page: number, userId?: number) => {
  let url = `/logbook?keyword=${keyword}&page=${page}`
  if (userId) {
    url += `&user_id=${userId}`
  }
  return url
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
