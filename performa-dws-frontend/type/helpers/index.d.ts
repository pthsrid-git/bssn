import * as yup from 'yup'

import { DateTimeData, OptionData } from '../models'

export declare const composeErrorMessage: (error: any) => string
export declare const monthNames: string[]
export declare const readableDate: (dateString: string) => string
export declare const capitalizeFirst: (str: string) => string
export declare const formatRupiah: (value: number) => string
export declare const getYears: (startYear: number, endYear: number) => string[]
export declare const integerToRoman: (num: number) => string
export declare const generateDummyId: () => string
export declare const generateDummyGuid: () => string
export declare const generateDummyDate: () => string
export declare const generateDummyYear: () => string
export declare const generateDummyDescription: (wordCount?: number) => string
export declare const generateSlug: (text?: string) => string
export declare const mapObjectToOptionData: <T>(
  list: T[],
  getValue: (item: T) => unknown,
  getLabel: (item: T) => unknown
) => OptionData[]
export declare const mapPrimitiveToOptionData: (list: Array<string | number>) => OptionData[]
export declare const validateSelectMultiple: ({
  required,
  requiredMessage,
  count,
  countMessage,
  min,
  minMessage,
  max,
  maxMessage
}?: {
  required?: boolean
  requiredMessage?: string
  count?: number
  countMessage?: string
  min?: number
  minMessage?: string
  max?: number
  maxMessage?: string
}) => yup.ArraySchema<string[] | undefined, yup.AnyObject, '', ''>
export declare const validateSelectSingle: ({
  required,
  requiredMessage
}: {
  required?: boolean
  requiredMessage?: string
}) => yup.StringSchema<string | null | undefined, yup.AnyObject, undefined, ''>
export declare const validateInput: ({
  required,
  requiredMessage,
  min,
  minMessage
}: {
  required?: boolean
  requiredMessage?: string
  min?: number
  minMessage?: string
}) => yup.StringSchema<string | null | undefined, yup.AnyObject, undefined, ''>
export declare const validateTextarea: ({
  required,
  requiredMessage,
  min,
  minMessage
}: {
  required?: boolean
  requiredMessage?: string
  min?: number
  minMessage?: string
}) => yup.StringSchema<string | null | undefined, yup.AnyObject, undefined, ''>
export declare const validateSelect: ({
  required,
  requiredMessage
}: {
  required?: boolean
  requiredMessage?: string
}) => yup.StringSchema<string | null | undefined, yup.AnyObject, undefined, ''>
export declare const validateRadio: ({
  required,
  requiredMessage
}: {
  required?: boolean
  requiredMessage?: string
}) => yup.StringSchema<string | null | undefined, yup.AnyObject, undefined, ''>
export declare const validateCheckbox: ({
  required,
  requiredMessage,
  count,
  countMessage,
  min,
  minMessage,
  max,
  maxMessage
}?: {
  required?: boolean
  requiredMessage?: string
  count?: number
  countMessage?: string
  min?: number
  minMessage?: string
  max?: number
  maxMessage?: string
}) => yup.ArraySchema<string[] | undefined, yup.AnyObject, '', ''>
export declare const validateDate: ({
  required,
  requiredMessage,
  formatMessage
}: {
  required?: boolean
  requiredMessage?: string
  formatMessage?: string
}) => yup.StringSchema<string | null | undefined, yup.AnyObject, undefined, ''>
export declare const validateTime: ({
  required,
  requiredMessage
}: {
  required?: boolean
  requiredMessage?: string
}) => yup.StringSchema<string | null | undefined, yup.AnyObject, undefined, ''>
export declare const validateDateTimes: ({
  startMessage,
  endMessage
}?: {
  startMessage?: string
  endMessage?: string
}) => yup.ArraySchema<
  | {
      waktu_mulai?: string | null | undefined
      waktu_berakhir: string
    }[]
  | undefined,
  yup.AnyObject,
  '',
  ''
>
export declare const validateFileSingle: ({
  required,
  requiredMessage,
  fileSize,
  fileSizeMessage,
  fileTypes,
  fileTypesMessage
}: {
  required?: boolean
  requiredMessage?: string
  fileSize: number
  fileSizeMessage: string
  fileTypes: string[]
  fileTypesMessage: string
}) => yup.MixedSchema<{} | null | undefined, yup.AnyObject, undefined, ''>
export declare const validateFileMultiple: ({
  required,
  requiredMessage,
  count,
  countMessage,
  min,
  minMessage,
  max,
  maxMessage,
  fileSize,
  fileSizeMessage,
  fileTypes,
  fileTypesMessage
}: {
  required?: boolean
  requiredMessage?: string
  count?: number
  countMessage?: string
  min?: number
  minMessage?: string
  max?: number
  maxMessage?: string
  fileSize: number
  fileSizeMessage: string
  fileTypes: string[]
  fileTypesMessage: string
}) => yup.ArraySchema<{}[] | undefined, yup.AnyObject, '', ''>
export declare const generateDateTimes: (
  startValue: string,
  endValue: string,
  values: DateTimeData[]
) => DateTimeData[]
export declare const mapFormData: (formData: FormData, values: Record<string, any>) => FormData
export declare const searchOptions: (options: OptionData[], value: string) => OptionData[]
export declare const mapRequestOptions: (options?: Record<string, any>) => {
  headers: any
  signal?: any
}
export declare const mapMultipartRequestOptions: (options?: Record<string, any>) => {
  headers: any
  signal?: any
}
export declare const openNewTab: (url: string) => void
export declare const getFilenameFromUrl: (url: string) => string
export declare const formatStatus: (url: string) => string
