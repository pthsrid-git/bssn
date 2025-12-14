import { AxiosError } from 'axios'
import * as yup from 'yup'

import type { DateTimeData, OptionData } from '../models'

// ================================================================================================
// Log
// ================================================================================================
const debug = import.meta.env.VITE_DEBUG === 'true'
export const log = (...data: any[]): void => {
  if (debug) {
    console.log(...data)
  }
}

// ================================================================================================
// Compose Error Message
// ================================================================================================
export const composeErrorMessage = (error: any): string => {
  if (error instanceof AxiosError && error.response) {
    const responseData = error.response.data
    if (responseData?.result?.data) {
      const errorMessages: string[] = []
      Object.values(responseData.result.data).forEach((fieldErrors) => {
        if (Array.isArray(fieldErrors)) {
          errorMessages.push(...fieldErrors)
        }
      })
      return errorMessages.length ? errorMessages.join(' ') : 'Terjadi kesalahan.'
    }
    return responseData.message || 'Terjadi kesalahan.'
  }
  return 'Terjadi kesalahan. Silakan coba lagi.'
}

// ================================================================================================
// Readable Date
// ================================================================================================
export const monthNames: string[] = [
  'Januari',
  'Februari',
  'Maret',
  'April',
  'Mei',
  'Juni',
  'Juli',
  'Agustus',
  'September',
  'Oktober',
  'November',
  'Desember'
]
export const readableDate = (dateString: string): string => {
  const [day, month, year] = dateString.split('-')
  const monthIndex: number = parseInt(month, 10) - 1
  if (monthIndex < 0 || monthIndex > 11) {
    throw new Error('Invalid month in date string')
  }
  return `${day} ${monthNames[monthIndex]} ${year}`
}

// ================================================================================================
// Capitalize First
// ================================================================================================
export const capitalizeFirst = (str: string) => {
  if (!str) return ''
  return str.charAt(0).toUpperCase() + str.slice(1)
}

// ================================================================================================
// Format Rupiah
// ================================================================================================
export const formatRupiah = (value: number) => {
  return 'Rp' + value.toLocaleString('id-ID') + ',-'
}

// ================================================================================================
// Get Years
// ================================================================================================
export const getYears = (startYear: number, endYear: number): string[] => {
  const years: string[] = []

  for (let year = startYear; year <= endYear; year++) {
    years.push(year.toString())
  }

  return years
}

// ================================================================================================
// Integer To Roman
// ================================================================================================
export const integerToRoman = (num: number): string => {
  const digits = String(num).split('')
  const key: string[] = [
    '',
    'C',
    'CC',
    'CCC',
    'CD',
    'D',
    'DC',
    'DCC',
    'DCCC',
    'CM',
    '',
    'X',
    'XX',
    'XXX',
    'XL',
    'L',
    'LX',
    'LXX',
    'LXXX',
    'XC',
    '',
    'I',
    'II',
    'III',
    'IV',
    'V',
    'VI',
    'VII',
    'VIII',
    'IX'
  ]
  let romanNum = ''
  let i = 3
  while (i--) {
    romanNum = (key[+digits.pop()! + i * 10] || '') + romanNum
  }
  return 'M'.repeat(+digits.join('')) + romanNum
}

// ================================================================================================
// Generate Dummy ID
// ================================================================================================
let counterId = 1
export const generateDummyId = () => {
  const uniquePart = Math.floor(Math.random() * 9000) + 1000
  const incrementPart = counterId++
  return `${uniquePart}${incrementPart}`
}

// ================================================================================================
// Generate Dummy GUID
// ================================================================================================
export const generateDummyGuid = () => {
  return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (char) => {
    const random = (Math.random() * 16) | 0
    const value = char === 'x' ? random : (random & 0x3) | 0x8
    return value.toString(16)
  })
}

// ================================================================================================
// Generate Dummy Date
// ================================================================================================
export const generateDummyDate = (): string => {
  const year = Math.floor(Math.random() * (2024 - 2020 + 1)) + 2020
  const month = Math.floor(Math.random() * 12) + 1
  const day = Math.floor(Math.random() * new Date(year, month, 0).getDate()) + 1
  const formattedDay = String(day).padStart(2, '0')
  const formattedMonth = String(month).padStart(2, '0')
  return `${formattedDay}-${formattedMonth}-${year}`
}

// ================================================================================================
// Generate Dummy Year
// ================================================================================================
export const generateDummyYear = (): string => {
  return `${Math.floor(Math.random() * (2024 - 2020 + 1)) + 2020}`
}

// ================================================================================================
// Generate Dummy Description
// ================================================================================================
export const generateDummyDescription = (wordCount = 5): string => {
  const randomWordWords = [
    'lorem',
    'ipsum',
    'dolor',
    'sit',
    'amet',
    'consectetur',
    'adipiscing',
    'elit',
    'sed',
    'do',
    'eiusmod',
    'tempor',
    'incididunt',
    'ut',
    'labore',
    'et',
    'dolore',
    'magna',
    'aliqua',
    'ut',
    'enim',
    'ad',
    'minim',
    'veniam',
    'quis',
    'nostrud',
    'exercitation',
    'ullamco',
    'laboris',
    'nisi',
    'ut',
    'aliquip',
    'ex',
    'ea',
    'commodo',
    'consequat',
    'duis',
    'aute',
    'irure',
    'dolor',
    'in',
    'reprehenderit',
    'in',
    'voluptate',
    'velit',
    'esse',
    'cillum',
    'dolore',
    'eu',
    'fugiat',
    'nulla',
    'pariatur',
    'excepteur',
    'sint',
    'occaecat',
    'cupidatat',
    'non',
    'proident',
    'sunt',
    'in',
    'culpa',
    'qui',
    'officia',
    'deserunt',
    'mollit',
    'anim',
    'id',
    'est',
    'laborum'
  ]
  const words = []
  for (let i = 0; i < wordCount; i++) {
    const randomWord = randomWordWords[Math.floor(Math.random() * randomWordWords.length)]
    words.push(randomWord)
  }
  return words.join(' ').replace(/^\w/, (c) => c.toUpperCase()) + '.'
}

// ================================================================================================
// Generate Slug
// ================================================================================================
export const generateSlug = (text: string): string => {
  return text
    .toLowerCase()
    .normalize('NFD') // hapus aksen
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^a-z0-9\s-]/g, '') // buang karakter selain alfanumerik & spasi
    .trim()
    .replace(/\s+/g, '-') // spasi jadi -
    .replace(/-+/g, '-') // rapikan multiple -
}

// ================================================================================================
// Map Object To Option Data
// ================================================================================================
export const mapObjectToOptionData = <T>(
  list: T[],
  getValue: (item: T) => unknown,
  getLabel: (item: T) => unknown
): OptionData[] => {
  return list.map((item) => ({
    value: String(getValue(item)),
    label: String(getLabel(item))
  }))
}

// ================================================================================================
// Map Primitive To Option Data
// ================================================================================================
export const mapPrimitiveToOptionData = (list: Array<string | number>): OptionData[] => {
  return list.map((item) => {
    const str = String(item)
    return {
      value: str,
      label: str
    }
  })
}

// ================================================================================================
// Validate Form Data
// ================================================================================================
export const validateSelectMultiple = ({
  required = false,
  requiredMessage = 'Field Select Multiple is required at least 1',
  count,
  countMessage = 'Exactly ${count} option(s) must be selected',
  min,
  minMessage = 'Select at least ${min} options',
  max,
  maxMessage = 'Select no more than ${max} options'
}: {
  required?: boolean
  requiredMessage?: string
  count?: number
  countMessage?: string
  min?: number
  minMessage?: string
  max?: number
  maxMessage?: string
} = {}) => {
  let schema = yup.array().of(yup.string().required())
  if (typeof count === 'number' && count > 0) {
    const message = countMessage.replace('${count}', `${count}`)
    schema = schema.test('exact-length', message, (value) => {
      if (!required && (!value || value.length === 0)) return true
      return Array.isArray(value) && value.length === count
    })
    if (required) {
      schema = schema.required(message)
    }
  } else {
    if (typeof min === 'number' && min > 0) {
      const message = minMessage.replace('${min}', `${min}`)
      schema = schema.test('min-length', message, (value) => {
        if (!required && (!value || value.length === 0)) return true
        return Array.isArray(value) && value.length >= min
      })
    }
    if (typeof max === 'number' && max > 0) {
      const message = maxMessage.replace('${max}', `${max}`)
      schema = schema.test('max-length', message, (value) => {
        if (!required && (!value || value.length === 0)) return true
        return Array.isArray(value) && value.length <= max
      })
    }
    if (required) {
      schema = schema.min(1, requiredMessage).required()
    }
  }
  return schema
}

export const validateSelectSingle = ({
  required = false,
  requiredMessage = 'Field Select Single is required'
}: {
  required?: boolean
  requiredMessage?: string
}) => {
  let schema = yup.string().nullable()
  if (required) {
    schema = schema.required(requiredMessage)
  }
  return schema
}

export const validateInput = ({
  required = false,
  requiredMessage = 'Field Input is required',
  min,
  minMessage = 'At least ${min} characters'
}: {
  required?: boolean
  requiredMessage?: string
  min?: number
  minMessage?: string
}) => {
  let schema = yup.string().nullable()
  if (required) {
    schema = schema.required(requiredMessage)
  }
  if (min !== undefined) {
    schema = schema.min(min, minMessage.replace('${min}', String(min)))
  }
  return schema
}

export const validateTextarea = ({
  required = false,
  requiredMessage = 'Field Textarea is required',
  min,
  minMessage = 'At least ${min} characters'
}: {
  required?: boolean
  requiredMessage?: string
  min?: number
  minMessage?: string
}) => {
  let schema = yup.string().nullable()
  if (required) {
    schema = schema.required(requiredMessage)
  }
  if (min !== undefined) {
    schema = schema.min(min, minMessage.replace('${min}', String(min)))
  }
  return schema
}

export const validateSelect = ({
  required = false,
  requiredMessage = 'Field Select is required'
}: {
  required?: boolean
  requiredMessage?: string
}) => {
  let schema = yup.string().nullable()
  if (required) {
    schema = schema.required(requiredMessage)
  }
  return schema
}

export const validateRadio = ({
  required = false,
  requiredMessage = 'Field Radio is required'
}: {
  required?: boolean
  requiredMessage?: string
}) => {
  let schema = yup.string().nullable()
  if (required) {
    schema = schema.required(requiredMessage)
  }
  return schema
}

export const validateCheckbox = ({
  required = false,
  requiredMessage = 'Field Checkbox is required at least 1',
  count,
  countMessage = 'Exactly ${count} option(s) must be selected',
  min,
  minMessage = 'Select at least ${min} options',
  max,
  maxMessage = 'Select no more than ${max} options'
}: {
  required?: boolean
  requiredMessage?: string
  count?: number
  countMessage?: string
  min?: number
  minMessage?: string
  max?: number
  maxMessage?: string
} = {}) => {
  let schema = yup.array().of(yup.string().required())
  if (typeof count === 'number' && count > 0) {
    const message = countMessage.replace('${count}', `${count}`)
    schema = schema.test('exact-length', message, (value) => {
      if (!required && (!value || value.length === 0)) return true
      return Array.isArray(value) && value.length === count
    })
    if (required) {
      schema = schema.required(message)
    }
  } else {
    if (typeof min === 'number' && min > 0) {
      const message = minMessage.replace('${min}', `${min}`)
      schema = schema.test('min-length', message, (value) => {
        if (!required && (!value || value.length === 0)) return true
        return Array.isArray(value) && value.length >= min
      })
    }
    if (typeof max === 'number' && max > 0) {
      const message = maxMessage.replace('${max}', `${max}`)
      schema = schema.test('max-length', message, (value) => {
        if (!required && (!value || value.length === 0)) return true
        return Array.isArray(value) && value.length <= max
      })
    }
    if (required) {
      schema = schema.min(1, requiredMessage).required()
    }
  }
  return schema
}

export const validateDate = ({
  required = false,
  requiredMessage = 'Field Date is required',
  formatMessage = 'Date must be in DD-MM-YYYY format'
}: {
  required?: boolean
  requiredMessage?: string
  formatMessage?: string
}) => {
  let schema = yup.string().nullable()
  if (required) {
    schema = schema
      .required(requiredMessage)
      .matches(/^\d{2}-\d{2}-\d{4}$/, formatMessage)
      .test('isValidDate', 'Invalid date', (value) => {
        const [day, month, year] = value.split('-').map(Number)
        const date = new Date(year, month - 1, day)
        return (
          date &&
          date.getDate() === day &&
          date.getMonth() + 1 === month &&
          date.getFullYear() === year
        )
      })
  }
  return schema
}

export const validateTime = ({
  required = false,
  requiredMessage = 'Field Time is required'
}: {
  required?: boolean
  requiredMessage?: string
}) => {
  let schema = yup.string().nullable()
  if (required) {
    schema = schema.required(requiredMessage)
  }
  return schema
}

export const validateDateTimes = ({
  startMessage = 'Start Time is required',
  endMessage = 'End Time must be greater than Start Time'
}: {
  startMessage?: string
  endMessage?: string
} = {}) => {
  const schema = yup.array().of(
    yup.object({
      waktu_mulai: yup.string().required(startMessage),
      waktu_berakhir: yup
        .string()
        .nullable()
        .test('is-greater', endMessage, function (value) {
          const { waktu_mulai } = this.parent
          if (!value) return true
          return value >= waktu_mulai
        })
    })
  )
  return schema
}

export const validateFileSingle = ({
  required = false,
  requiredMessage = 'Field File Single is required',
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
}) => {
  let schema = yup.mixed().nullable()
  if (required) {
    schema = schema
      .required(requiredMessage)
      .test(
        'fileSize',
        fileSizeMessage,
        (value) => !value || (value as File).size <= fileSize * 1024 * 1024
      )
      .test(
        'fileType',
        fileTypesMessage,
        (value) => !value || fileTypes.includes((value as File).type)
      )
  }
  return schema
}

export const validateFileMultiple = ({
  required = false,
  requiredMessage = 'Field File Multiple is required at least 1',
  count,
  countMessage = 'Exactly ${count} file(s) must be selected',
  min,
  minMessage = 'Select at least ${min} files',
  max,
  maxMessage = 'Select no more than ${max} files',
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
}) => {
  let schema = yup.array().of(
    yup
      .mixed()
      .required()
      .test(
        'fileSize',
        fileSizeMessage,
        (value) => !value || (value as File).size <= fileSize * 1024 * 1024
      )
      .test(
        'fileType',
        fileTypesMessage,
        (value) => !value || fileTypes.includes((value as File).type)
      )
  )
  if (typeof count === 'number' && count > 0) {
    const message = countMessage.replace('${count}', `${count}`)
    schema = schema.test('exact-length', message, (value) => {
      if (!required && (!value || value.length === 0)) return true
      return Array.isArray(value) && value.length === count
    })
    if (required) {
      schema = schema.required(message)
    }
  } else {
    if (typeof min === 'number' && min > 0) {
      const message = minMessage.replace('${min}', `${min}`)
      schema = schema.test('min-length', message, (value) => {
        if (!required && (!value || value.length === 0)) return true
        return Array.isArray(value) && value.length >= min
      })
    }
    if (typeof max === 'number' && max > 0) {
      const message = maxMessage.replace('${max}', `${max}`)
      schema = schema.test('max-length', message, (value) => {
        if (!required && (!value || value.length === 0)) return true
        return Array.isArray(value) && value.length <= max
      })
    }
    if (required) {
      schema = schema.min(1, requiredMessage).required()
    }
  }
  return schema
}

// ================================================================================================
// Generate Date Times
// ================================================================================================
const parseDate = (dateStr: string): Date => {
  const [day, month, year] = dateStr.split('-').map(Number)
  return new Date(year, month - 1, day)
}
const formatDate = (date: Date): string => date.toLocaleDateString('en-GB').split('/').join('-')
export const generateDateTimes = (
  startValue: string,
  endValue: string,
  values: DateTimeData[]
): DateTimeData[] => {
  const start = parseDate(startValue)
  const end = parseDate(endValue)
  const existingMap = new Map(values.map((item) => [item.tanggal, item]))
  const result: DateTimeData[] = []
  const current = new Date(start)
  while (current <= end) {
    const dateStr = formatDate(current)
    result.push(
      existingMap.get(dateStr) || {
        tanggal: dateStr,
        waktu_mulai: '',
        waktu_berakhir: null
      }
    )
    current.setDate(current.getDate() + 1)
  }
  return result
}

// ================================================================================================
// Map Form Data
// ================================================================================================
export const mapFormData = (formData: FormData, values: Record<string, any>): FormData => {
  Object.keys(values).forEach((key) => {
    const value = values[key]

    if (value === null || value === undefined) {
      return
    }

    if (value instanceof File || value instanceof Blob) {
      formData.append(key, value)
    } else if (Array.isArray(value)) {
      if (value.length > 0 && value[0] instanceof File) {
        value.forEach((file: File) => {
          formData.append(`${key}[]`, file)
        })
      } else {
        formData.append(key, JSON.stringify(value))
      }
    } else if (value instanceof Date) {
      formData.append(key, formatDate(value))
    } else if (typeof value === 'object') {
      formData.append(key, JSON.stringify(value))
    } else if (typeof value === 'boolean') {
      formData.append(key, value ? 'true' : 'false')
    } else {
      formData.append(key, value.toString())
    }
  })

  return formData
}

// ================================================================================================
// Search Options
// ================================================================================================
export const searchOptions = (options: OptionData[], value: string) => {
  return options.filter((option) =>
    value
      .trim()
      .toLowerCase()
      .split(/\s+/)
      .every((word) => option.label.toLowerCase().includes(word))
  )
}

// ================================================================================================
// Request API
// ================================================================================================
export const mapRequestOptions = (options: Record<string, any> = {}) => {
  const { signal, headers = {}, ...rest } = options

  return {
    ...rest,
    ...(signal ? { signal } : {}),
    headers: {
      ...headers
    }
  }
}

export const mapMultipartRequestOptions = (options: Record<string, any> = {}) => {
  const { signal, headers = {}, ...rest } = options

  return {
    ...rest,
    ...(signal ? { signal } : {}),
    headers: {
      ...headers,
      'Content-Type': 'multipart/form-data'
    }
  }
}

// ================================================================================================
// Open New Tab
// ================================================================================================
export const openNewTab = (url: string) => {
  window.open(url, '_blank')
}

// ================================================================================================
// Get Filename From URL
// ================================================================================================
export const getFilenameFromUrl = (url: string): string => {
  try {
    const { pathname } = new URL(url)
    return pathname.substring(pathname.lastIndexOf('/') + 1) || ''
  } catch {
    return url.split('/').pop()?.split('?')[0].split('#')[0] || ''
  }
}

// ================================================================================================
// Format Status
// ================================================================================================
export function formatStatus(status: string) {
  return status
    .split('_')
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ')
}
