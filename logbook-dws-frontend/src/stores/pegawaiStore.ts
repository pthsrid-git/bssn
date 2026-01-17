import { composeErrorMessage, requestState } from '@bssn/ui-kit-frontend'
import { defineStore } from 'pinia'

import { pegawaiStore } from '@/const'
import { allPegawaiDummy } from '@/dummies/pegawai'
import { log } from '@/helpers/custom'
import { getRequest } from '@/helpers/request'
import { pegawaiDataFromJson, type PegawaiData } from '@/models/pegawai'
import { allPegawaiUrl } from '@/urls/pegawai'

const dummy = import.meta.env.VITE_DUMMY === 'true'

export const usePegawaiStore = defineStore(pegawaiStore, {
  state: () => {
    return {
      all: requestState<PegawaiData[]>([])
    }
  },
  getters: {
    options: (state) => {
      if (state.all.data) {
        return state.all.data.map((pegawai) => {
          return {
            value: pegawai.guid,
            label: pegawai.nama
          }
        })
      } else {
        return []
      }
    }
  },
  actions: {
    async callAll() {
      const url = allPegawaiUrl()
      log(`url: ${url}`)
      this.all.status = 'loading'
      this.all.errorMessage = null
      try {
        let response: any
        if (dummy) {
          await new Promise((resolve) => setTimeout(resolve, 500))
          response = allPegawaiDummy()
        } else {
          response = await getRequest(url)
        }
        // Handle response format: { success: true, data: [...] }
        const dataArray = Array.isArray(response.data?.data)
          ? response.data.data
          : Array.isArray(response.data)
            ? response.data
            : []
        this.all.data = dataArray.map(pegawaiDataFromJson)
        this.all.status = 'success'
      } catch (error) {
        log(error)
        this.all.status = 'error'
        this.all.errorMessage = composeErrorMessage(error)
      }
    },
    clearAll() {
      this.all = requestState<PegawaiData[]>([])
    }
  }
})
