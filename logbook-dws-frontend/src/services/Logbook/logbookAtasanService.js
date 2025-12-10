// services/Logbook/logbookAtasanService.js
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

export const logbookAtasanService = {

  /**
   * Get daftar pegawai dalam unit kerja atasan
   */
  async getPegawaiList() {
    try {
      const response = await api.get('/logbook-atasan/pegawai')
      console.log('📡 Pegawai List Response:', response.data)
      return response.data
    } catch (error) {
      console.error('❌ Error fetching pegawai list:', error.response?.data)
      throw error
    }
  },

  /**
   * Get logbook pegawai berdasarkan pegawai ID
   */
  async getPegawaiLogs(pegawaiId, params = {}) {
    try {
      const response = await api.get(`/logbook-atasan/pegawai/${pegawaiId}/logs`, { params })
      console.log('📡 Pegawai Logs Response:', response.data)
      return response.data
    } catch (error) {
      console.error('❌ Error fetching pegawai logs:', error.response?.data)
      throw error
    }
  },

  /**
   * Get detail logbook
   */
  async getLogDetail(logId) {
    try {
      const response = await api.get(`/logbook-atasan/logs/${logId}`)
      return response.data
    } catch (error) {
      console.error('❌ Error fetching log detail:', error.response?.data)
      throw error
    }
  },

  /**
   * Approve logbook - kirim catatan_atasan
   */
  async approveLog(logId, catatanAtasan = null) {
    try {
      const payload = {}
      if (catatanAtasan) {
        payload.catatan_atasan = catatanAtasan
      }
      
      const response = await api.post(`/logbook-atasan/logs/${logId}/approve`, payload)
      console.log('✅ Log approved:', response.data)
      return response.data
    } catch (error) {
      console.error('❌ Error approving log:', error.response?.data)
      throw error
    }
  },

  /**
   * Reject logbook dengan catatan atasan (wajib)
   */
  async rejectLog(logId, data) {
    try {
      const response = await api.post(`/logbook-atasan/logs/${logId}/reject`, data)
      console.log('✅ Log rejected:', response.data)
      return response.data
    } catch (error) {
      console.error('❌ Error rejecting log:', error.response?.data)
      throw error
    }
  },

  /**
   * Update catatan atasan saja (tanpa approve/reject)
   */
  async updateCatatanAtasan(logId, data) {
    try {
      const response = await api.put(`/logbook-atasan/logs/${logId}/catatan`, data)
      console.log('✅ Catatan atasan updated:', response.data)
      return response.data
    } catch (error) {
      console.error('❌ Error updating catatan atasan:', error.response?.data)
      throw error
    }
  },

  /**
   * Get summary/statistik logbook unit
   */
  async getUnitSummary(params = {}) {
    try {
      const response = await api.get('/logbook-atasan/summary', { params })
      console.log('📡 Unit Summary Response:', response.data)
      return response.data
    } catch (error) {
      console.error('❌ Error fetching unit summary:', error.response?.data)
      throw error
    }
  }
}

export default api