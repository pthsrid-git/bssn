import api from './api'

export const authService = {
  async login(credentials) {
    try {
      const response = await api.post('/auth/login', credentials)
      
      if (response.data.success) {
        const { access_token, user } = response.data
        localStorage.setItem('token', access_token)
        localStorage.setItem('user', JSON.stringify(user))
        return response.data
      }
      
      throw new Error(response.data.message || 'Login failed')
    } catch (error) {
      throw error.response?.data || error
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
    }
  },

  async me() {
    try {
      const response = await api.get('/auth/me')
      return response.data
    } catch (error) {
      throw error.response?.data || error
    }
  },

  getUser() {
    try {
      const userStr = localStorage.getItem('user')
      return userStr ? JSON.parse(userStr) : null
    } catch (error) {
      return null
    }
  },

  getToken() {
    return localStorage.getItem('token')
  },

  isAuthenticated() {
    return !!this.getToken()
  }
}