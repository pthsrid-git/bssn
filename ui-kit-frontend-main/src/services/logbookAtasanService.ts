// services/Logbook/logbookAtasanService.ts
import { getRequest, postRequest, putRequest } from '@/helpers/request'

// Types & Interfaces
export interface Pegawai {
  id: number
  nama: string
  nip: string
  pangkat: string
  golongan: string
  jabatan: string
  email?: string
  unit_kerja?: string
}

export interface PegawaiLog {
  id: number
  tanggal: string
  judul_kegiatan?: string
  deskripsi?: string
  durasi?: number
  status: 'Disubmit' | 'Disetujui' | 'Ditolak'
  catatan_katim?: string
  catatan_atasan?: string
  tanggal_approve?: string
  user_id: number
  jam_mulai?: string
  jam_selesai?: string
  rencana_hasil_kinerja_skp?: string
  indikator_hasil_rencana_kerja?: string
  aktivitas_kegiatan_harian?: string
  keterangan?: string
  file_path?: string
  file_name?: string
  file_size?: number
  created_at?: string
  updated_at?: string
}

export interface LogDetail extends PegawaiLog {
  file_url?: string
  user?: {
    id: number
    nama: string
    nip: string
  }
}

export interface PegawaiLogsFilter {
  start_date?: string
  end_date?: string
  status?: 'Disubmit' | 'Disetujui' | 'Ditolak'
  page?: number
  limit?: number
  month?: number
  year?: number
}

export interface UnitStatistics {
  total_logs: number
  pending: number
  approved: number
  rejected: number
  by_pegawai: Array<{
    pegawai_id: number
    pegawai_name: string
    total: number
    pending: number
    approved: number
    rejected: number
  }>
}

// Logbook Atasan Service
export const LogbookAtasanService = {
  /**
   * Get daftar pegawai dalam unit kerja atasan
   */
  async getPegawaiList() {
    try {
      console.log('📡 [LogbookAtasanService] Getting pegawai list...')
      const response = await getRequest('/logbook-atasan/pegawai')
      console.log('✅ [LogbookAtasanService] Pegawai list loaded:', response)
      return response
    } catch (error: any) {
      console.error('❌ [LogbookAtasanService] Failed to get pegawai list:', {
        status: error.response?.status,
        statusText: error.response?.statusText,
        data: error.response?.data,
        message: error.message
      })
      throw error
    }
  },

  /**
   * Get logbook pegawai berdasarkan pegawai ID
   */
  async getPegawaiLogs(pegawaiId: number, filters?: PegawaiLogsFilter) {
    try {
      const params: Record<string, any> = {}
      if (filters) {
        Object.entries(filters).forEach(([key, value]) => {
          if (value !== undefined && value !== null) {
            params[key] = value
          }
        })
      }

      console.log(
        '📡 [LogbookAtasanService] Getting pegawai logs for:',
        pegawaiId,
        'with filters:',
        params
      )
      const response = await getRequest(`/logbook-atasan/pegawai/${pegawaiId}/logs`, { params })
      console.log('✅ [LogbookAtasanService] Pegawai logs loaded:', response)
      return response
    } catch (error: any) {
      console.error('❌ [LogbookAtasanService] Failed to get pegawai logs:', error)
      throw error
    }
  },

  /**
   * Get detail logbook
   */
  async getLogDetail(logId: number) {
    try {
      console.log('📡 [LogbookAtasanService] Getting log detail for:', logId)
      const response = await getRequest(`/logbook-atasan/logs/${logId}`)
      console.log('✅ [LogbookAtasanService] Log detail loaded:', response)
      return response
    } catch (error: any) {
      console.error('❌ [LogbookAtasanService] Failed to get log detail:', error)
      throw error
    }
  },

  /**
   * Approve logbook dengan catatan atasan (opsional)
   */
  async approveLog(logId: number, catatanAtasan?: string) {
    try {
      console.log('📡 [LogbookAtasanService] Approving log:', logId)
      const response = await postRequest(`/logbook-atasan/logs/${logId}/approve`, {
        catatan_atasan: catatanAtasan || ''
      })
      console.log('✅ [LogbookAtasanService] Log approved:', response)
      return response
    } catch (error: any) {
      console.error('❌ [LogbookAtasanService] Failed to approve log:', error)
      throw error
    }
  },

  /**
   * Reject logbook dengan catatan atasan (wajib)
   */
  async rejectLog(logId: number, catatanAtasan: string) {
    try {
      console.log('📡 [LogbookAtasanService] Rejecting log:', logId)
      const response = await postRequest(`/logbook-atasan/logs/${logId}/reject`, {
        catatan_atasan: catatanAtasan
      })
      console.log('✅ [LogbookAtasanService] Log rejected:', response)
      return response
    } catch (error: any) {
      console.error('❌ [LogbookAtasanService] Failed to reject log:', error)
      throw error
    }
  },

  /**
   * Update catatan atasan saja (tanpa approve/reject)
   */
  async updateCatatanAtasan(logId: number, catatanAtasan: string) {
    try {
      const response = await putRequest(`/logbook-atasan/logs/${logId}/catatan`, {
        catatan_atasan: catatanAtasan
      })
      return response
    } catch (error: any) {
      console.error('❌ [LogbookAtasanService] Failed to update catatan:', error)
      throw error
    }
  },

  /**
   * Get summary/statistik logbook unit
   */
  async getUnitSummary(filters?: {
    start_date?: string
    end_date?: string
    month?: number
    year?: number
  }) {
    try {
      const params: Record<string, any> = {}
      if (filters) {
        Object.entries(filters).forEach(([key, value]) => {
          if (value !== undefined && value !== null) {
            params[key] = value
          }
        })
      }
      const response = await getRequest('/logbook-atasan/summary', { params })
      return response
    } catch (error: any) {
      console.error('❌ [LogbookAtasanService] Failed to get unit summary:', error)
      throw error
    }
  },

  /**
   * Get pending logs count
   */
  async getPendingLogsCount() {
    try {
      const response = await getRequest('/logbook-atasan/pending-count')
      return response
    } catch (error: any) {
      console.error('❌ [LogbookAtasanService] Failed to get pending count:', error)
      throw error
    }
  },

  /**
   * Export unit logs to Excel
   */
  async exportUnitLogs(filters?: PegawaiLogsFilter) {
    try {
      const params: Record<string, any> = {}
      if (filters) {
        Object.entries(filters).forEach(([key, value]) => {
          if (value !== undefined && value !== null) {
            params[key] = value
          }
        })
      }
      const response = await getRequest('/logbook-atasan/export', { params, responseType: 'blob' })
      return response
    } catch (error: any) {
      console.error('❌ [LogbookAtasanService] Failed to export unit logs:', error)
      throw error
    }
  },

  /**
   * Export specific pegawai logs to Excel
   */
  async exportPegawaiLogs(pegawaiId: number, filters?: PegawaiLogsFilter) {
    try {
      const params: Record<string, any> = {}
      if (filters) {
        Object.entries(filters).forEach(([key, value]) => {
          if (value !== undefined && value !== null) {
            params[key] = value
          }
        })
      }
      const response = await getRequest(`/logbook-atasan/pegawai/${pegawaiId}/export`, {
        params,
        responseType: 'blob'
      })
      return response
    } catch (error: any) {
      console.error('❌ [LogbookAtasanService] Failed to export pegawai logs:', error)
      throw error
    }
  }
}

export default LogbookAtasanService