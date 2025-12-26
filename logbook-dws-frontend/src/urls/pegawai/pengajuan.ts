export const recordsPengajuanUrl = (keyword: string, page: number) => {
  return `/slip?keyword=${keyword}&page=${page}`
}
export const addPengajuanUrl = () => {
  return `/slip`
}
