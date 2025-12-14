import api from './api'

export const pohonKinerjaService = {
  // Final Outcome
  async getFinalOutcomes(params = {}) {
    try {
      const response = await api.get('/pohon-kinerja/final-outcome', { params })
      return response.data
    } catch (error) {
      throw error.response?.data || error
    }
  },

  async getFinalOutcome(id) {
    try {
      const response = await api.get(`/pohon-kinerja/final-outcome/${id}`)
      return response.data
    } catch (error) {
      throw error.response?.data || error
    }
  },

  async createFinalOutcome(data) {
    try {
      const response = await api.post('/pohon-kinerja/final-outcome', data)
      return response.data
    } catch (error) {
      throw error.response?.data || error
    }
  },

  async uploadFinalOutcome(formData) {
    try {
      const response = await api.post('/pohon-kinerja/final-outcome/upload', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      return response.data
    } catch (error) {
      throw error.response?.data || error
    }
  },

  async downloadTemplate(type) {
    try {
      const response = await api.get(`/pohon-kinerja/download-template/${type}`, {
        responseType: 'blob'
      })
      
      const url = window.URL.createObjectURL(new Blob([response.data]))
      const link = document.createElement('a')
      link.href = url
      link.setAttribute('download', `${type}.xlsx`)
      document.body.appendChild(link)
      link.click()
      link.parentNode.removeChild(link)
      window.URL.revokeObjectURL(url)
      
      return response.data
    } catch (error) {
      throw error.response?.data || error
    }
  },

  // Intermediate Outcome Level 1
  async getIntermediateOutcomesLv1(params = {}) {
    try {
      const response = await api.get('/pohon-kinerja/intermediate-lv1', { params })
      return response.data
    } catch (error) {
      throw error.response?.data || error
    }
  },

  // Intermediate Outcome Level 2
  async getIntermediateOutcomesLv2(params = {}) {
    try {
      const response = await api.get('/pohon-kinerja/intermediate-lv2', { params })
      return response.data
    } catch (error) {
      throw error.response?.data || error
    }
  },

  // Immediate Outcome Level 3
  async getImmediateOutcomesLv3(params = {}) {
    try {
      const response = await api.get('/pohon-kinerja/immediate-lv3', { params })
      return response.data
    } catch (error) {
      throw error.response?.data || error
    }
  },

  // Output
  async getOutputs(params = {}) {
    try {
      const response = await api.get('/pohon-kinerja/output', { params })
      return response.data
    } catch (error) {
      throw error.response?.data || error
    }
  }
}