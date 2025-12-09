// services/Logbook/logbookKatimService.js
import axios from 'axios'

const API_URL = 'http://localhost:8000/api'

const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Accept': 'application/json'
  }
})

api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      window.location.href = '/login'
    }
    return Promise.reject(error)
  }
)

export const logbookKatimService = {

  /**
   * Get daftar anggota tim (bawahan dengan parent_id = user login id)
   */
  async getTeamMembers() {
    try {
      const response = await api.get('/logbook-katim/team-members')
      console.log('📡 Team Members Response:', response.data)
      return response.data
    } catch (error) {
      console.error('❌ Error fetching team members:', error.response?.data)
      throw error
    }
  },

  /**
   * Get logbook anggota berdasarkan member ID
   */
  async getMemberLogs(memberId, params = {}) {
    try {
      const response = await api.get(`/logbook-katim/member/${memberId}/logs`, { params })
      console.log('📡 Member Logs Response:', response.data)
      return response.data
    } catch (error) {
      console.error('❌ Error fetching member logs:', error.response?.data)
      throw error
    }
  },

  /**
   * Get detail logbook
   */
  async getLogDetail(logId) {
    try {
      const response = await api.get(`/logbook-katim/logs/${logId}`)
      return response.data
    } catch (error) {
      console.error('❌ Error fetching log detail:', error.response?.data)
      throw error
    }
  },

  /**
   * Approve logbook
   */
  async approveLog(logId) {
    try {
      const response = await api.post(`/logbook-katim/logs/${logId}/approve`)
      console.log('✅ Log approved:', response.data)
      return response.data
    } catch (error) {
      console.error('❌ Error approving log:', error.response?.data)
      throw error
    }
  },

  /**
   * Reject logbook dengan catatan
   */
  async rejectLog(logId, data) {
    try {
      const response = await api.post(`/logbook-katim/logs/${logId}/reject`, data)
      console.log('✅ Log rejected:', response.data)
      return response.data
    } catch (error) {
      console.error('❌ Error rejecting log:', error.response?.data)
      throw error
    }
  }
}

export default api