import axios from 'axios'
import { defineStore } from 'pinia'

import { apiStore } from '@/const'

const getApiConfig = () => {
  const apiBaseUrl = import.meta.env.VITE_API_BASE_URL
  const apiKey = import.meta.env.VITE_API_KEY

  if (import.meta.env.VITE_DEBUG === 'true') {
    console.log(`API Base URL: ${apiBaseUrl} | API Key: ${apiKey}`)
  }

  return { apiBaseUrl, apiKey }
}

const { apiBaseUrl, apiKey } = getApiConfig()

export const useApiStore = defineStore(apiStore, {
  state: () => {
    return {
      token: '' as string
    }
  },
  getters: {
    request: (state) => {
      return axios.create({
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          Authorization: state.token !== '' ? `Bearer ${state.token}` : '',
          apikey: apiKey
        },
        baseURL: apiBaseUrl
      })
    }
  },
  actions: {
    setToken(token: string) {
      this.token = token
    },
    removeToken() {
      this.token = ''
    }
  }
})
