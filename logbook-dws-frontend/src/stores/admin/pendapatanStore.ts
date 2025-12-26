import { composeErrorMessage, mapFormData, requestState } from '@bssn/ui-kit-frontend'
import { defineStore } from 'pinia'

import { adminPendapatanStore } from '@/const'
import { log } from '@/helpers/custom'
import { postMultipartRequest } from '@/helpers/request'
import { addPendapatanUrl } from '@/urls/admin/pendapatan'

const dummy = import.meta.env.VITE_DUMMY === 'true'

export const usePendapatanStore = defineStore(adminPendapatanStore, {
  state: () => {
    return {
      add: requestState()
    }
  },
  actions: {
    async callAdd(values: Record<string, any>) {
      const url = addPendapatanUrl(values.jenis)
      log(`url: ${url} | values: ${JSON.stringify(values)}`)
      this.add.status = 'loading'
      this.add.errorMessage = null
      const formData = mapFormData(new FormData(), values) as FormData
      try {
        if (dummy) {
          await new Promise((resolve) => setTimeout(resolve, 500))
        } else {
          await postMultipartRequest(url, formData)
        }
        this.add.status = 'success'
      } catch (error) {
        log(error)
        this.add.status = 'error'
        this.add.errorMessage = composeErrorMessage(error)
      }
    },
    clearAdd() {
      this.add = requestState()
    }
  }
})
