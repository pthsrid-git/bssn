export type BadgeVariant = 'default' | 'primary' | 'info' | 'warning' | 'success' | 'danger'
export type ButtonType = 'button' | 'submit' | 'reset'
export type ButtonVariant = 'default' | 'primary' | 'info' | 'warning' | 'success' | 'danger'
export type ModalPlacement =
  | 'top-left'
  | 'top-center'
  | 'top-right'
  | 'center-left'
  | 'center'
  | 'center-right'
  | 'bottom-left'
  | 'bottom-center'
  | 'bottom-right'
export type ModalAffirmationVariant =
  | 'default'
  | 'primary'
  | 'info'
  | 'warning'
  | 'success'
  | 'danger'
export type ModalAlertVariant = 'info' | 'warning' | 'success' | 'danger'
export type ModalConfirmationVariant =
  | 'default'
  | 'primary'
  | 'info'
  | 'warning'
  | 'success'
  | 'danger'
export type TitleVariant = 'default' | 'primary' | 'info' | 'warning' | 'success' | 'danger'
export type PageStatus = 'initial' | 'loading' | 'error' | 'success'
export type SectionStatus = 'initial' | 'loading' | 'error' | 'success'
export type ActionStatus = 'initial' | 'loading' | 'error' | 'success'
export type RequestStatus = 'initial' | 'loading' | 'error' | 'success'
export interface TabData {
  name: string
  label: string
  key?: string
  badge?: number
}
export interface ResponseDataObject<T> {
  item: T
}
export declare const responseDataObjectFromJson: <T>(
  json: any,
  mapFunction: (item: any) => T
) => ResponseDataObject<T>
export interface ResponseDataArray<T> {
  items: T[]
}
export declare const responseDataArrayFromJson: <T>(
  json: any,
  mapFunction: (item: any) => T
) => ResponseDataArray<T>
export interface ResponseDataPagination<T> {
  items: T[]
  meta: MetaData
}
export declare const responseDataPaginationFromJson: <T>(
  json: any,
  mapFunction: (item: any) => T
) => ResponseDataPagination<T>
export interface MetaData {
  currentPage: number
  lastPage: number
  perPage: number
  total: number
}
export declare const metaDataFromJson: (json: any) => MetaData
export type ErrorMessage = string | null
export interface ActionState {
  status: ActionStatus
  errorMessage: ErrorMessage
}
export declare const actionState: () => ActionState
export interface RequestState<T> {
  data: T | null
  status: RequestStatus
  errorMessage: ErrorMessage
}
export declare const requestState: <T>(data?: T | null) => RequestState<T>
export interface PaginatedRequestState<T> extends RequestState<T> {
  keyword: string
  page: number
  meta: MetaData
}
export declare const paginatedRequestState: <T>(data?: T | null) => PaginatedRequestState<T>
export interface OptionRawData {
  id: string
  name: string
}
export declare const optionRawDataFromJson: (json: any) => OptionRawData
export interface OptionData {
  value: string
  label: string
  disabled?: boolean
}
export declare const optionDataFromJson: (json: any) => OptionData
export interface RouteMetaData {
  menu: boolean
  title?: string
  icon?: string
  guard?: string
}
export declare const routeMetaDataFromJson: (json: any) => RouteMetaData
export interface BarChartPoint {
  x: string
  y: string
}
export declare const barChartPointFromJson: (json: any) => BarChartPoint
export interface BarChartSeries {
  name: string
  data: BarChartPoint[]
}
export declare const barChartSeriesFromJson: (json: any) => BarChartSeries
export interface BarChartData {
  series: BarChartSeries[]
  colors?: string[]
}
export declare const barChartDataFromJson: (json: any) => BarChartData
export interface PieChartData {
  labels: string[]
  values: number[]
  colors?: string[]
}
export declare const pieChartDataFromJson: (json: any) => PieChartData
export interface AuthTemporaryData {
  accessToken: string
  '2faStatus': string
}
export declare const authTemporaryDataFromJson: (json: any) => AuthTemporaryData
export interface AuthData {
  accessToken: string
  '2faStatus': string
}
export declare const authDataFromJson: (json: any) => AuthData
export interface UserDefaultData {
  guid: string
  name: string
  email: string
  roles: string[]
  permissions: string[]
}
export declare const userDefaultDataFromJson: (json: any) => UserDefaultData
export interface PermissionDwsData {
  ruangPribadi: string[]
  ruangKerja: string[]
}
export declare const permissionDwsDataFromJson: (json: any) => PermissionDwsData
export interface UserDwsData {
  guid: string
  name: string
  fullname: string
  email: string
  fpid: string
  uuid: string
  nip: string
  pangkat: string
  jabatan: string
  roles: string[]
  permissions: PermissionDwsData
}
export declare const userDwsDataFromJson: (json: any) => UserDwsData
export interface ChartBarExposed {
  printChart: (title?: string) => void
}
export interface ChartPieExposed {
  printChart: (title?: string) => void
}
export interface DrawerDefaultExposed {
  open: () => void
  close: () => void
}
export interface DrawerMenuExposed {
  open: () => void
  close: () => void
}
export interface ModalAffirmationExposed {
  open: (title: string) => void
  close: () => void
}
export interface ModalAlertExposed {
  open: (title: string, info: string, duration?: number) => void
  close: () => void
}
export interface ModalConfirmationExposed {
  open: (id: string, title: string, info: string, data?: Record<string, any>) => void
  close: () => void
}
export interface ModalDefaultExposed {
  open: () => void
  close: () => void
}
export interface PageDefaultExposed {
  openDrawerDefault: (
    label: string,
    component: any,
    componentProps?: Record<string, any>,
    maxWidthClass?: string | undefined
  ) => void
  closeDrawerDefault: () => void
  openModalAlert: (
    variant: ModalAlertVariant,
    title: string,
    info: string,
    duration?: number,
    placement?: ModalPlacement | undefined,
    maxWidthClass?: string | undefined
  ) => void
  closeModalAlert: () => void
  openModalConfirmation: (
    variant: ModalConfirmationVariant,
    confirmText: string,
    cancelText: string,
    id: string,
    title: string,
    info: string,
    data?: Record<string, any>,
    placement?: ModalPlacement | undefined,
    maxWidthClass?: string | undefined
  ) => void
  closeModalConfirmation: () => void
  stopModalConfirmation: () => void
  openModalDefault: (
    label: string,
    component: any,
    componentProps?: Record<string, any>,
    placement?: ModalPlacement | undefined,
    isCLosable?: boolean | undefined,
    maxWidthClass?: string | undefined
  ) => void
  closeModalDefault: () => void
  openViewerFile: (title: string, url: string) => void
  closeViewerFile: () => void
}
export interface TabDefaultExposed {
  setActiveTab: (value: string) => void
}
export interface DateTimeData {
  tanggal: string
  waktu_mulai: string
  waktu_berakhir: string | null
}
export declare const dateTimeDataFromJson: (json: any) => DateTimeData
