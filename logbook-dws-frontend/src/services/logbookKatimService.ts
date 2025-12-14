// services/Logbook/logbookKatimService.ts
import { getRequest, postRequest, putRequest, deleteRequest } from '@/helpers/request'

// Types & Interfaces
export interface TeamMember {
  id: number
  nama: string
  nip: string
  pangkat: string
  golongan: string
  jabatan: string
  parent_id?: number
  email?: string
  unit_kerja?: string
}

export interface MemberLog {
  id: number
  tanggal: string
  judul_kegiatan?: string
  deskripsi?: string
  durasi?: number
  status: 'pending' | 'approved' | 'rejected' | 'Disubmit' | 'Disetujui' | 'Ditolak'
  catatan_katim?: string
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
  created_at?: string
  updated_at?: string
}

export interface LogDetail extends MemberLog {
  catatan_atasan?: string
  file_url?: string
  file_size?: number
  user?: {
    id: number
    nama: string
    nip: string
  }
}

export interface MemberLogsFilter {
  start_date?: string
  end_date?: string
  status?: 'pending' | 'approved' | 'rejected' | 'Disubmit' | 'Disetujui' | 'Ditolak'
  page?: number
  limit?: number
  month?: number
  year?: number
}

export interface TeamStatistics {
  total_logs: number
  pending: number
  approved: number
  rejected: number
  by_member: Array<{
    member_id: number
    member_name: string
    total: number
    pending: number
    approved: number
    rejected: number
  }>
}

export interface BulkApproveResult {
  approved: number[]
  failed: number[]
  total_approved: number
  total_failed: number
}

// Logbook Katim Service
export const LogbookKatimService = {
  /**
   * Get daftar anggota tim (bawahan dengan parent_id = user login id)
   */
  async getTeamMembers() {
    try {
      console.log('📡 [LogbookKatimService] Getting team members...')
      const response = await getRequest('/logbook-katim/team-members')
      console.log('✅ [LogbookKatimService] Team members loaded:', response)
      return response
    } catch (error: any) {
      console.error('❌ [LogbookKatimService] Failed to get team members:', {
        status: error.response?.status,
        statusText: error.response?.statusText,
        data: error.response?.data,
        message: error.message
      })
      throw error
    }
  },

  /**
   * Get logbook anggota berdasarkan member ID
   */
  async getMemberLogs(memberId: number, filters?: MemberLogsFilter) {
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
        '📡 [LogbookKatimService] Getting member logs for:',
        memberId,
        'with filters:',
        params
      )
      const response = await getRequest(`/logbook-katim/member/${memberId}/logs`, { params })
      console.log('✅ [LogbookKatimService] Member logs loaded:', response)
      return response
    } catch (error: any) {
      console.error('❌ [LogbookKatimService] Failed to get member logs:', error)
      throw error
    }
  },

  /**
   * Get detail logbook
   */
  async getLogDetail(logId: number) {
    try {
      console.log('📡 [LogbookKatimService] Getting log detail for:', logId)
      const response = await getRequest(`/logbook-katim/logs/${logId}`)
      console.log('✅ [LogbookKatimService] Log detail loaded:', response)
      return response
    } catch (error: any) {
      console.error('❌ [LogbookKatimService] Failed to get log detail:', error)
      throw error
    }
  },

  /**
   * Approve logbook
   */
  async approveLog(logId: number, catatanKatim?: string) {
    try {
      console.log('📡 [LogbookKatimService] Approving log:', logId)
      const response = await postRequest(`/logbook-katim/logs/${logId}/approve`, {
        catatan_katim: catatanKatim || ''
      })
      console.log('✅ [LogbookKatimService] Log approved:', response)
      return response
    } catch (error: any) {
      console.error('❌ [LogbookKatimService] Failed to approve log:', error)
      throw error
    }
  },

  /**
   * Reject logbook dengan catatan
   */
  async rejectLog(logId: number, catatanKatim: string) {
    try {
      console.log('📡 [LogbookKatimService] Rejecting log:', logId)
      const response = await postRequest(`/logbook-katim/logs/${logId}/reject`, {
        catatan_katim: catatanKatim
      })
      console.log('✅ [LogbookKatimService] Log rejected:', response)
      return response
    } catch (error: any) {
      console.error('❌ [LogbookKatimService] Failed to reject log:', error)
      throw error
    }
  },

  /**
   * Update catatan katim pada logbook
   */
  async updateCatatanKatim(logId: number, catatanKatim: string) {
    try {
      const response = await putRequest(`/logbook-katim/logs/${logId}/catatan`, {
        catatan_katim: catatanKatim
      })
      return response
    } catch (error: any) {
      console.error('❌ [LogbookKatimService] Failed to update catatan:', error)
      throw error
    }
  },

  /**
   * Bulk approve multiple logs
   */
  async bulkApproveLogs(logIds: number[], catatanKatim?: string) {
    try {
      const response = await postRequest('/logbook-katim/logs/bulk-approve', {
        log_ids: logIds,
        catatan_katim: catatanKatim || ''
      })
      return response
    } catch (error: any) {
      console.error('❌ [LogbookKatimService] Failed to bulk approve:', error)
      throw error
    }
  },

  /**
   * Bulk reject multiple logs
   */
  async bulkRejectLogs(logIds: number[], catatanKatim: string) {
    try {
      const response = await postRequest('/logbook-katim/logs/bulk-reject', {
        log_ids: logIds,
        catatan_katim: catatanKatim
      })
      return response
    } catch (error: any) {
      console.error('❌ [LogbookKatimService] Failed to bulk reject:', error)
      throw error
    }
  },

  /**
   * Get statistics of team member logs
   */
  async getTeamStatistics(filters?: {
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
      const response = await getRequest('/logbook-katim/statistics', { params })
      return response
    } catch (error: any) {
      console.error('❌ [LogbookKatimService] Failed to get statistics:', error)
      throw error
    }
  },

  /**
   * Get team summary (ringkasan tim)
   */
  async getTeamSummary(month?: number, year?: number) {
    try {
      const params: Record<string, any> = {}
      if (month) params.month = month
      if (year) params.year = year
      const response = await getRequest('/logbook-katim/summary', { params })
      return response
    } catch (error: any) {
      console.error('❌ [LogbookKatimService] Failed to get team summary:', error)
      throw error
    }
  },

  /**
   * Export team logs to Excel
   */
  async exportTeamLogs(filters?: MemberLogsFilter) {
    try {
      const params: Record<string, any> = {}
      if (filters) {
        Object.entries(filters).forEach(([key, value]) => {
          if (value !== undefined && value !== null) {
            params[key] = value
          }
        })
      }
      const response = await getRequest('/logbook-katim/export', { params, responseType: 'blob' })
      return response
    } catch (error: any) {
      console.error('❌ [LogbookKatimService] Failed to export team logs:', error)
      throw error
    }
  },

  /**
   * Export specific member logs to Excel
   */
  async exportMemberLogs(memberId: number, filters?: MemberLogsFilter) {
    try {
      const params: Record<string, any> = {}
      if (filters) {
        Object.entries(filters).forEach(([key, value]) => {
          if (value !== undefined && value !== null) {
            params[key] = value
          }
        })
      }
      const response = await getRequest(`/logbook-katim/member/${memberId}/export`, {
        params,
        responseType: 'blob'
      })
      return response
    } catch (error: any) {
      console.error('❌ [LogbookKatimService] Failed to export member logs:', error)
      throw error
    }
  },

  /**
   * Get pending logs count
   */
  async getPendingLogsCount() {
    try {
      const response = await getRequest('/logbook-katim/pending-count')
      return response
    } catch (error: any) {
      console.error('❌ [LogbookKatimService] Failed to get pending count:', error)
      throw error
    }
  },

  /**
   * Get activity timeline for a member
   */
  async getMemberActivityTimeline(memberId: number, month?: number, year?: number) {
    try {
      const params: Record<string, any> = {}
      if (month) params.month = month
      if (year) params.year = year
      const response = await getRequest(`/logbook-katim/member/${memberId}/timeline`, { params })
      return response
    } catch (error: any) {
      console.error('❌ [LogbookKatimService] Failed to get member timeline:', error)
      throw error
    }
  }
}

export default LogbookKatimService
