import api from './api'

export const periodeService = {
  // Get all periodes
  async getAll() {
    try {
      const response = await api.get('/periode')
      return response.data
    } catch (error) {
      console.error('Error fetching periodes:', error)
      throw error
    }
  },

  // Get active periode
  async getActive() {
    try {
      const response = await api.get('/periode/active')
      return response.data
    } catch (error) {
      console.error('Error fetching active periode:', error)
      throw error
    }
  },

  // Get periode by ID
  async getById(id) {
    try {
      const response = await api.get(`/periode/${id}`)
      return response.data
    } catch (error) {
      console.error('Error fetching periode:', error)
      throw error
    }
  }
}