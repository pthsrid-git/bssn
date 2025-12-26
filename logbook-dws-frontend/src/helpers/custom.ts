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
