export const recordsPengajuanUrl = (keyword: string, page: number) => {
  return `/slipall?keyword=${keyword}&page=${page}`
}
export const editPengajuanUrl = (id: string) => {
  return `/slip/${id}`
}
export const diprosesAllPengajuanUrl = () => {
  return `/slip/prosesall`
}
export const diselesaikanAllPengajuanUrl = () => {
  return `/slip/selesaikanall`
}
export const downloadPengajuanUrl = (id: string) => {
  return `/generate/${id}`
}
