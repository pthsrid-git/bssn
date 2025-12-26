// services/logbookService.ts
import {
  getRequest,
  postRequest,
  postMultipartRequest,
  putRequest,
  deleteRequest
} from '@/helpers/request'

// Type Definitions
export type LogbookStatus = 'Draft' | 'Disubmit' | 'Disetujui' | 'Ditolak'

export interface LogbookEntry {
  id?: number
  user_id?: number
  tanggal: string
  jam_mulai: string
  jam_selesai: string
  rencana_hasil_kinerja_skp: string
  indikator_hasil_rencana_kerja: string
  aktivitas_kegiatan_harian: string
  keterangan?: string
  status: LogbookStatus
  catatan_katim?: string
  catatan_atasan?: string
  file_path?: string
  file_name?: string
  file_size?: number
  file_url?: string
  created_at?: string
  updated_at?: string
}

export interface LogbookFilter {
  start_date?: string
  end_date?: string
  status?: LogbookStatus
  bulan?: number
  tahun?: number
}

// Helper to format data for Laravel backend
function formatEntryForBackend(data: any): FormData | Record<string, any> {
  const hasFile = data.files && data.files.length > 0 && data.files[0] instanceof File

  if (hasFile) {
    const formData = new FormData()

    if (data.date) formData.append('tanggal', data.date)
    if (data.startTime) formData.append('jam_mulai', data.startTime)
    if (data.endTime) formData.append('jam_selesai', data.endTime)
    if (data.skpPlanId) formData.append('rencana_hasil_kinerja_skp', data.skpPlanId)
    if (data.workIndicator) formData.append('indikator_hasil_rencana_kerja', data.workIndicator)
    if (data.dailyActivity) formData.append('aktivitas_kegiatan_harian', data.dailyActivity)
    if (data.description) formData.append('keterangan', data.description)
    if (data.status) formData.append('status', data.status)

    formData.append('file', data.files[0])

    return formData
  }

  // Regular JSON data
  const formatted: Record<string, any> = {}

  if (data.date) formatted.tanggal = data.date
  if (data.startTime) formatted.jam_mulai = data.startTime
  if (data.endTime) formatted.jam_selesai = data.endTime
  if (data.skpPlanId) formatted.rencana_hasil_kinerja_skp = data.skpPlanId
  if (data.workIndicator) formatted.indikator_hasil_rencana_kerja = data.workIndicator
  if (data.dailyActivity) formatted.aktivitas_kegiatan_harian = data.dailyActivity
  if (data.description !== undefined) formatted.keterangan = data.description
  if (data.status) formatted.status = data.status

  return formatted
}

// Main Service
export const LogbookService = {
  async getEntries(filters?: LogbookFilter) {
    const params: Record<string, any> = {}
    if (filters) {
      Object.entries(filters).forEach(([key, value]) => {
        if (value !== undefined && value !== null) {
          params[key] = value
        }
      })
    }
    return await getRequest('/logbooks', { params })
  },

  async getEntryById(id: number) {
    return await getRequest(`/logbooks/${id}`)
  },

  async createEntry(data: any) {
    const formatted = formatEntryForBackend(data)

    if (formatted instanceof FormData) {
      return await postMultipartRequest('/logbooks', formatted)
    }
    return await postRequest('/logbooks', formatted)
  },

  async updateEntry(id: number, data: any) {
    const formatted = formatEntryForBackend(data)

    if (formatted instanceof FormData) {
      return await postMultipartRequest(`/logbooks/${id}`, formatted)
    }
    return await putRequest(`/logbooks/${id}`, formatted)
  },

  async deleteEntry(id: number) {
    return await deleteRequest(`/logbooks/${id}`)
  },

  async submitEntry(id: number) {
    return await postRequest(`/logbooks/${id}/submit`, {})
  },

  async downloadFile(fileUrl: string) {
    window.open(fileUrl, '_blank')
  }
}

// Katim Service
export const LogbookKatimService = {
  async getTeamMembers() {
    return await getRequest('/logbook-katim/team-members')
  },

  async getMemberLogs(memberId: number, filters?: LogbookFilter) {
    const params: Record<string, any> = {}
    if (filters) {
      Object.entries(filters).forEach(([key, value]) => {
        if (value !== undefined && value !== null) {
          params[key] = value
        }
      })
    }
    return await getRequest(`/logbook-katim/member/${memberId}/logs`, { params })
  },

  async getLogDetail(logId: number) {
    return await getRequest(`/logbook-katim/logs/${logId}`)
  },

  async approveLog(logId: number, catatan?: string) {
    return await postRequest(`/logbook-katim/logs/${logId}/approve`, {
      catatan_katim: catatan || ''
    })
  },

  async rejectLog(logId: number, catatan: string) {
    return await postRequest(`/logbook-katim/logs/${logId}/reject`, {
      catatan_katim: catatan
    })
  },

  async getTeamSummary(month?: number, year?: number) {
    const params: Record<string, any> = {}
    if (month) params.month = month
    if (year) params.year = year
    return await getRequest('/logbook-katim/summary', { params })
  }
}

// Atasan Service
export const LogbookAtasanService = {
  async getPegawaiList() {
    return await getRequest('/logbook-atasan/pegawai')
  },

  async getPegawaiLogs(pegawaiId: number, filters?: LogbookFilter) {
    const params: Record<string, any> = {}
    if (filters) {
      Object.entries(filters).forEach(([key, value]) => {
        if (value !== undefined && value !== null) {
          params[key] = value
        }
      })
    }
    return await getRequest(`/logbook-atasan/pegawai/${pegawaiId}/logs`, { params })
  },

  async getLogDetail(logId: number) {
    return await getRequest(`/logbook-atasan/logs/${logId}`)
  },

  async approveLog(logId: number, catatan?: string) {
    return await postRequest(`/logbook-atasan/logs/${logId}/approve`, {
      catatan_atasan: catatan || ''
    })
  },

  async rejectLog(logId: number, catatan: string) {
    return await postRequest(`/logbook-atasan/logs/${logId}/reject`, {
      catatan_atasan: catatan
    })
  },

  async updateCatatanAtasan(logId: number, catatan: string) {
    return await putRequest(`/logbook-atasan/logs/${logId}/catatan`, {
      catatan_atasan: catatan
    })
  },

  async getUnitSummary(month?: number, year?: number) {
    const params: Record<string, any> = {}
    if (month) params.month = month
    if (year) params.year = year
    return await getRequest('/logbook-atasan/summary', { params })
  }
}

export default LogbookService
