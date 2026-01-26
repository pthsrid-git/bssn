export const recordsLogbookUrl = (keyword: string, page: number) => {
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
