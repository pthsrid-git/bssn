// services/Logbook/logbookService.js
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

export const logbookService = {
  async login(credentials) {
    try {
      const response = await api.post('/auth/login', credentials)
      
      if (response.data.access_token) {
        localStorage.setItem('token', response.data.access_token)
        localStorage.setItem('user', JSON.stringify(response.data.user))
        api.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`
      }
      
      return response.data
    } catch (error) {
      console.error('Login error:', error)
      throw error
    }
  },

  async logout() {
    try {
      await api.post('/auth/logout')
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      delete api.defaults.headers.common['Authorization']
    }
  },

  async getProfile() {
    const response = await api.get('/auth/profile')
    return response.data
  },

  async getLogbooks(params = {}) {
    const response = await api.get('/logbooks', { params })
    return response.data
  },

  async createLogbook(formData) {
    try {
      console.log('📡 Sending to API...')
      console.log('📡 Is FormData?', formData instanceof FormData)
      
      const response = await api.post('/logbooks', formData)
      
      console.log('✅ Response:', response.status, response.data)
      return response.data
    } catch (error) {
      console.error('❌ API Error:', error.response?.data)
      throw error
    }
  },

  async updateLogbook(id, formData) {
    try {
      if (formData instanceof FormData) {
        formData.append('_method', 'PUT')
        const response = await api.post(`/logbooks/${id}`, formData)
        return response.data
      } else {
        const response = await api.put(`/logbooks/${id}`, formData)
        return response.data
      }
    } catch (error) {
      console.error('Update error:', error)
      throw error
    }
  },

  async deleteLogbook(id) {
    const response = await api.delete(`/logbooks/${id}`)
    return response.data
  },

  async getLogbook(id) {
    const response = await api.get(`/logbooks/${id}`)
    return response.data
  }
}

export default api