import { composeErrorMessage, requestState, responseDataArrayFromJson } from '@bssn/ui-kit-frontend'
import { defineStore } from 'pinia'

import { periodeStore } from '@/const'
import { allPeriodeDummy } from '@/dummies/periode'
import { log } from '@/helpers/custom'
import { getRequest } from '@/helpers/request'
import { periodeDataFromJson, type PeriodeData } from '@/models/periode'
import { allPeriodeUrl } from '@/urls/periode'

const dummy = import.meta.env.VITE_DUMMY === 'true'

export const usePeriodeStore = defineStore(periodeStore, {
  state: () => {
    return {
      all: requestState<PeriodeData[]>([]),
      selectedTahun: '' as string
    }
  },
  getters: {
    tahunOptions: (state) => {
      if (!state.all.data) return []
      const years = new Set<string>()
      state.all.data.forEach((periode) => {
        const year = periode.periode.split('-')[0]
        years.add(year)
      })
      return Array.from(years)
        .sort()
        .map((year) => ({
          value: year,
          label: year
        }))
    },
    bulanOptions: (state) => {
      if (!state.all.data || !state.selectedTahun) return []
      return state.all.data
        .filter((periode) => periode.periode.startsWith(state.selectedTahun))
        .sort((a, b) => a.periode.localeCompare(b.periode))
        .map((periode) => {
          const labelWithoutYear = periode.waktu.replace(/\s\d{4}$/, '')
          return {
            value: periode.periode,
            label: labelWithoutYear
          }
        })
    },
    records: (state) => {
      if (!state.all.data || !state.selectedTahun) return []
      return state.all.data
        .filter((periode) => periode.periode.startsWith(state.selectedTahun))
        .sort((a, b) => a.periode.localeCompare(b.periode))
    }
  },
  actions: {
    async callAll() {
      const url = allPeriodeUrl()
      log(`url: ${url}`)
      this.all.status = 'loading'
      this.all.errorMessage = null
      try {
        let response: any
        if (dummy) {
          await new Promise((resolve) => setTimeout(resolve, 500))
          response = allPeriodeDummy()
        } else {
          response = await getRequest(url)
        }
        const responseData = responseDataArrayFromJson<PeriodeData>(response, periodeDataFromJson)
        this.all.data = responseData.items
        this.all.status = 'success'
      } catch (error) {
        log(error)
        this.all.status = 'error'
        this.all.errorMessage = composeErrorMessage(error)
      }
    },
    clearAll() {
      this.all = requestState<PeriodeData[]>([])
      this.selectedTahun = ''
    }
  }
})
