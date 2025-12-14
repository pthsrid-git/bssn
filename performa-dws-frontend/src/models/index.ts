// ================================================================================================
// Badge Variant
// ================================================================================================
export type BadgeVariant = 'default' | 'primary' | 'info' | 'warning' | 'success' | 'danger'

// ================================================================================================
// Button Type
// ================================================================================================
export type ButtonType = 'button' | 'submit' | 'reset'

// ================================================================================================
// Button Variant
// ================================================================================================
export type ButtonVariant = 'default' | 'primary' | 'info' | 'warning' | 'success' | 'danger'

// ================================================================================================
// Modal Placement
// ================================================================================================
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

// ================================================================================================
// Modal Affirmation Variant
// ================================================================================================
export type ModalAffirmationVariant =
  | 'default'
  | 'primary'
  | 'info'
  | 'warning'
  | 'success'
  | 'danger'

// ================================================================================================
// Modal Alert Variant
// ================================================================================================
export type ModalAlertVariant = 'info' | 'warning' | 'success' | 'danger'

// ================================================================================================
// Modal Confirmation Variant
// ================================================================================================
export type ModalConfirmationVariant =
  | 'default'
  | 'primary'
  | 'info'
  | 'warning'
  | 'success'
  | 'danger'

// ================================================================================================
// Title Variant
// ================================================================================================
export type TitleVariant = 'default' | 'primary' | 'info' | 'warning' | 'success' | 'danger'

// ================================================================================================
// Page Status
// ================================================================================================
export type PageStatus = 'initial' | 'loading' | 'error' | 'success'

// ================================================================================================
// Section Status
// ================================================================================================
export type SectionStatus = 'initial' | 'loading' | 'error' | 'success'

// ================================================================================================
// Action Status
// ================================================================================================
export type ActionStatus = 'initial' | 'loading' | 'error' | 'success'

// ================================================================================================
// Request Status
// ================================================================================================
export type RequestStatus = 'initial' | 'loading' | 'error' | 'success'

// ================================================================================================
// Tab Data
// ================================================================================================
export interface TabData {
  name: string
  label: string
  key?: string
  badge?: number
}

// ================================================================================================
// Response Data (for Object)
// ================================================================================================
export interface ResponseDataObject<T> {
  item: T
}
export const responseDataObjectFromJson = <T>(
  json: any,
  mapFunction: (item: any) => T
): ResponseDataObject<T> => {
  return {
    item: mapFunction(json.data)
  }
}

// ================================================================================================
// Response Data (for Array)
// ================================================================================================
export interface ResponseDataArray<T> {
  items: T[]
}
export const responseDataArrayFromJson = <T>(
  json: any,
  mapFunction: (item: any) => T
): ResponseDataArray<T> => {
  return {
    items: json.data.map(mapFunction)
  }
}

// ================================================================================================
// Response Data (for Pagination)
// ================================================================================================
export interface ResponseDataPagination<T> {
  items: T[]
  meta: MetaData
}
export const responseDataPaginationFromJson = <T>(
  json: any,
  mapFunction: (item: any) => T
): ResponseDataPagination<T> => {
  return {
    items: json.data.map(mapFunction),
    meta: metaDataFromJson(json.meta)
  }
}

// ================================================================================================
// Meta Data
// ================================================================================================
export interface MetaData {
  currentPage: number
  lastPage: number
  perPage: number
  total: number
}
export const metaDataFromJson = (json: any): MetaData => {
  return {
    currentPage: json.current_page,
    lastPage: json.last_page,
    perPage: json.per_page,
    total: json.total
  }
}

// ================================================================================================
// Error Message
// ================================================================================================
export type ErrorMessage = string | null

// ================================================================================================
// Action State
// ================================================================================================
export interface ActionState {
  status: ActionStatus
  errorMessage: ErrorMessage
}
export const actionState = (): ActionState => {
  return {
    status: 'initial',
    errorMessage: null
  }
}

// ================================================================================================
// Request State
// ================================================================================================
export interface RequestState<T> {
  data: T | null
  status: RequestStatus
  errorMessage: ErrorMessage
}
export const requestState = <T>(data: T | null = null): RequestState<T> => {
  return {
    data,
    status: 'initial',
    errorMessage: null
  }
}

// ================================================================================================
// Paginated Request State
// ================================================================================================
export interface PaginatedRequestState<T> extends RequestState<T> {
  keyword: string
  page: number
  meta: MetaData
}
export const paginatedRequestState = <T>(data: T | null = null): PaginatedRequestState<T> => {
  return {
    ...requestState(data),
    keyword: '',
    page: 1,
    meta: {
      currentPage: 0,
      lastPage: 0,
      perPage: 0,
      total: 0
    }
  }
}

// ================================================================================================
// Option Raw Data
// ================================================================================================
export interface OptionRawData {
  id: string
  name: string
}
export const optionRawDataFromJson = (json: any): OptionRawData => {
  return {
    id: String(json.id),
    name: json.name
  }
}

// ================================================================================================
// Option Data
// ================================================================================================
export interface OptionData {
  value: string
  label: string
  disabled?: boolean
}
export const optionDataFromJson = (json: any): OptionData => {
  return {
    value: String(json.value),
    label: json.label,
    disabled: json.disabled
  }
}

// ================================================================================================
// Route Meta Data
// ================================================================================================
export interface RouteMetaData {
  menu: boolean
  title?: string
  icon?: string
  guard?: string
  badge?: number
}
export const routeMetaDataFromJson = (json: any): RouteMetaData => {
  return {
    menu: json.menu,
    title: typeof json.title === 'string' ? json.title : undefined,
    icon: typeof json.icon === 'string' ? json.icon : undefined,
    guard: typeof json.guard === 'string' ? json.guard : undefined,
    badge: typeof json.badge === 'number' ? json.badge : undefined
  }
}

// ================================================================================================
// Bar Chart Point
// ================================================================================================
export interface BarChartPoint {
  x: string
  y: string
}
export const barChartPointFromJson = (json: any): BarChartPoint => {
  return {
    x: json.x,
    y: json.y
  }
}

// ================================================================================================
// Bar Chart Series
// ================================================================================================
export interface BarChartSeries {
  name: string
  data: BarChartPoint[]
}
export const barChartSeriesFromJson = (json: any): BarChartSeries => {
  return {
    name: json.name,
    data: json.data.map((item: any) => barChartPointFromJson(item))
  }
}

// ================================================================================================
// Bar Chart Data
// ================================================================================================
export interface BarChartData {
  series: BarChartSeries[]
  colors?: string[]
}
export const barChartDataFromJson = (json: any): BarChartData => {
  return {
    series: json.series.map((item: any) => barChartSeriesFromJson(item)),
    colors: json.colors
  }
}

// ================================================================================================
// Pie Chart Data
// ================================================================================================
export interface PieChartData {
  labels: string[]
  values: number[]
  colors?: string[]
}
export const pieChartDataFromJson = (json: any): PieChartData => {
  return {
    labels: json.labels,
    values: json.values,
    colors: json.colors
  }
}

// ================================================================================================
// Auth Temporary Data
// ================================================================================================
export interface AuthTemporaryData {
  accessToken: string
  '2faStatus': string
}
export const authTemporaryDataFromJson = (json: any): AuthTemporaryData => {
  return {
    accessToken: json.access_token,
    '2faStatus': json['2fa_status']
  }
}

// ================================================================================================
// Auth Data
// ================================================================================================
export interface AuthData {
  accessToken: string
  '2faStatus': string
}
export const authDataFromJson = (json: any): AuthData => {
  return {
    accessToken: json.access_token,
    '2faStatus': json['2fa_status']
  }
}

// ================================================================================================
// User Default Data
// ================================================================================================
export interface UserDefaultData {
  guid: string
  name: string
  email: string
  roles: string[]
  permissions: string[]
}
export const userDefaultDataFromJson = (json: any): UserDefaultData => {
  return {
    guid: json.guid,
    name: json.name,
    email: json.email,
    roles: json.roles,
    permissions: json.permissions
  }
}

// ================================================================================================
// Permission Dws Data
// ================================================================================================
export interface PermissionDwsData {
  ruangPribadi: string[]
  ruangKerja: string[]
}
export const permissionDwsDataFromJson = (json: any, roles: string[]): PermissionDwsData => {
  const ruangKerjaJson = json['ruang-kerja'] ?? []
  const isSuperAdmin = roles.includes('super.admin')

  const ruangKerja = isSuperAdmin ? Object.values(ruangKerjaJson).flat() : ruangKerjaJson

  return {
    ruangPribadi: json['ruang-pribadi'] ?? [],
    ruangKerja
  }
}

// ================================================================================================
// User Dws Data
// ================================================================================================
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
export const userDwsDataFromJson = (json: any): UserDwsData => {
  return {
    guid: json.guid,
    name: json.name,
    fullname: json.fullname,
    email: json.email,
    fpid: json.fpid,
    uuid: json.uuid,
    nip: json.nip,
    pangkat: json.pangkat,
    jabatan: json.jabatan,
    roles: json.roles ?? [],
    permissions: permissionDwsDataFromJson(json.permissions, json.roles)
  }
}

// ================================================================================================
// Component Exposed
// ================================================================================================
export type ChartBarExposed = {
  printChart: (title?: string) => void
}
export type ChartPieExposed = {
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

// ================================================================================================
// Date Time
// ================================================================================================
export interface DateTimeData {
  tanggal: string
  waktu_mulai: string
  waktu_berakhir: string | null
}
export const dateTimeDataFromJson = (json: any): DateTimeData => {
  return {
    tanggal: json.tanggal,
    waktu_mulai: json.waktu_mulai,
    waktu_berakhir: json.waktu_berakhir
  }
}
