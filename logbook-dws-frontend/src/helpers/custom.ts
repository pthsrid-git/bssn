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
// Format Date
// ================================================================================================
export const formatDate = (dateStr: string): string => {
  const date = new Date(dateStr)
  const day = String(date.getDate()).padStart(2, '0')
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const year = date.getFullYear()
  return `${day}-${month}-${year}`
}

// ================================================================================================
// Format Date Long (Indonesian)
// ================================================================================================
export const formatDateLong = (dateString: string): string => {
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// ================================================================================================
// Readable Role
// ================================================================================================
export const readableRole = (role: string) => {
  if (role === 'admin') {
    return 'Admin Keuangan'
  } else if (role === 'pengolah') {
    return 'Pengolah Logbook'
  } else {
    return 'Pegawai'
  }
}

// ================================================================================================
// Logbook Status Class
// ================================================================================================
export const getLogbookStatusClass = (status: string): string => {
  const classes: Record<string, string> = {
    Disetujui: 'bg-blue-100 text-blue-700',
    Disubmit: 'bg-warning-100 text-warning-700',
    Ditolak: 'bg-red-100 text-red-700'
  }
  return classes[status] || 'bg-gray-100 text-gray-700'
}

// ================================================================================================
// Logbook Status Label
// ================================================================================================
export const getLogbookStatusLabel = (status: string): string => {
  return status || 'Draft'
}

// ================================================================================================
// Format Date Indonesian (DD Month YYYY)
// ================================================================================================
export const formatDateIndonesian = (dateString: string): string => {
  if (!dateString) return '-'

  const date = new Date(dateString)
  const months = [
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

  const day = String(date.getDate()).padStart(2, '0')
  const month = months[date.getMonth()]
  const year = date.getFullYear()

  return `${day} ${month} ${year}`
}

// ================================================================================================
// Format Current Month (Indonesian)
// ================================================================================================
export const formatCurrentMonth = (): string => {
  const now = new Date()
  const monthNames = [
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
  const monthName = monthNames[now.getMonth()]
  const day = now.getDate()
  const year = now.getFullYear()
  return `${monthName} (${day} ${monthName} ${year})`
}

// ================================================================================================
// Spellout (Indonesian Number to Words)
// ================================================================================================
export const spellout = (n: number): string => {
  const words = [
    '',
    'Satu',
    'Dua',
    'Tiga',
    'Empat',
    'Lima',
    'Enam',
    'Tujuh',
    'Delapan',
    'Sembilan',
    'Sepuluh',
    'Sebelas'
  ]

  const units = [
    { value: 1e15, label: ' Kuadriliun ' },
    { value: 1e12, label: ' Triliun ' },
    { value: 1e9, label: ' Milyar ' },
    { value: 1e6, label: ' Juta ' },
    { value: 1e3, label: ' Ribu ' }
  ]

  if (n < 12) return words[n]
  if (n < 20) return words[n - 10] + ' Belas'
  if (n < 100) return (words[Math.floor(n / 10)] + ' Puluh ' + words[n % 10]).trim()
  if (n < 200) return ('Seratus ' + spellout(n - 100)).trim()
  if (n < 1000) return (words[Math.floor(n / 100)] + ' Ratus ' + spellout(n % 100)).trim()
  if (n < 2000) return ('Seribu ' + spellout(n - 1000)).trim()

  for (const { value, label } of units) {
    if (n >= value) {
      return (spellout(Math.floor(n / value)) + label + spellout(n % value)).trim()
    }
  }

  return 'Angka terlalu besar'
}

// ================================================================================================
// File Helpers
// ================================================================================================

/**
 * Get clean file name from file path or file name
 * Removes timestamp prefix if exists
 */
export const getFileName = (filePath?: string | null, fileName?: string | null): string => {
  if (fileName) {
    return fileName
  }

  if (filePath) {
    const parts = filePath.split('/')
    const fullFileName = parts[parts.length - 1]
    const cleanFileName = fullFileName.replace(/^\d+_/, '')
    return cleanFileName
  }

  return 'File.pdf'
}

/**
 * Format file size from bytes to human readable format
 */
export const formatFileSize = (bytes: number | string | null | undefined): string => {
  if (!bytes || bytes === 0) return '0 KB'

  const size = typeof bytes === 'string' ? parseInt(bytes) : bytes

  if (size < 1024) {
    return `${size} B`
  } else if (size < 1024 * 1024) {
    return `${(size / 1024).toFixed(2)} KB`
  } else {
    return `${(size / (1024 * 1024)).toFixed(2)} MB`
  }
}

/**
 * Download file from storage path
 */
export const downloadFile = (filePath: string, fileName?: string): void => {
  if (!filePath) {
    console.warn('File path tidak ditemukan')
    alert('File tidak tersedia')
    return
  }

  // Gunakan API URL dari environment variable
  const baseUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000'

  // Hilangkan /api jika ada di akhir URL
  const cleanBaseUrl = baseUrl.replace(/\/api$/, '')

  // Buat URL lengkap ke Laravel storage
  const fileUrl = `${cleanBaseUrl}/storage/${filePath}`

  console.log('Downloading file from:', fileUrl)

  // Buat link element untuk download
  const link = document.createElement('a')
  link.href = fileUrl
  link.download = fileName || getFileName(filePath)
  link.target = '_blank'
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
}

// ================================================================================================
// Date Grouping Helpers
// ================================================================================================

/**
 * Indonesian day names
 */
export const INDONESIAN_DAY_NAMES = [
  'Minggu',
  'Senin',
  'Selasa',
  'Rabu',
  'Kamis',
  'Jumat',
  'Sabtu'
] as const

/**
 * Group logs by date for calendar view
 * Creates a date map for all days in a month and groups activities by date
 */
export const groupLogsByDate = <T extends { tanggal: string }>(
  logs: T[],
  selectedMonth: string
): Array<{
  date: string
  day: string
  dayName: string
  activities: T[]
}> => {
  const groups: Array<{
    date: string
    day: string
    dayName: string
    activities: T[]
  }> = []
  const dateMap = new Map()

  if (selectedMonth) {
    const [year, month] = selectedMonth.split('-')
    const daysInMonth = new Date(parseInt(year), parseInt(month), 0).getDate()

    for (let day = 1; day <= daysInMonth; day++) {
      const date = `${year}-${month}-${String(day).padStart(2, '0')}`
      const dateObj = new Date(date)

      dateMap.set(date, {
        date,
        day: String(day).padStart(2, '0'),
        dayName: INDONESIAN_DAY_NAMES[dateObj.getDay()],
        activities: []
      })
    }
  }

  logs.forEach((log) => {
    const tanggalOnly = log.tanggal.split('T')[0]
    if (dateMap.has(tanggalOnly)) {
      dateMap.get(tanggalOnly).activities.push(log)
    }
  })

  dateMap.forEach((value) => groups.push(value))
  return groups
}

/**
 * Check if a date string is weekend (Saturday or Sunday)
 */
export const isWeekend = (dateString: string): boolean => {
  const dateObj = new Date(dateString)
  const day = dateObj.getDay()
  return day === 0 || day === 6
}
