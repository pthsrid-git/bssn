import {
  composeErrorMessage,
  requestState,
  responseDataObjectFromJson
} from '@bssn/ui-kit-frontend'
import { defineStore } from 'pinia'

import { pegawaiPendapatanStore } from '@/const'
import { recordPendapatanDummy } from '@/dummies/pegawai/pendapatan'
import { log } from '@/helpers/custom'
import { getRequest } from '@/helpers/request'
import { pendapatanDataFromJson, type PendapatanData } from '@/models/pegawai/pendapatan'
import { recordPendapatanUrl } from '@/urls/pegawai/pendapatan'

const dummy = import.meta.env.VITE_DUMMY === 'true'

export const usePendapatanStore = defineStore(pegawaiPendapatanStore, {
  state: () => {
    return {
      record: requestState<PendapatanData>()
    }
  },
  actions: {
    async callRecord(periode: string) {
      const url = recordPendapatanUrl(periode)
      log(`url: ${url}`)
      this.record.status = 'loading'
      this.record.errorMessage = null
      try {
        let response: any
        if (dummy) {
          await new Promise((resolve) => setTimeout(resolve, 500))
          response = recordPendapatanDummy()
        } else {
          response = await getRequest(url)
        }
        const responseData = responseDataObjectFromJson<PendapatanData>(
          response,
          pendapatanDataFromJson
        )
        this.record.data = responseData.item
        this.record.status = 'success'
      } catch (error) {
        log(error)
        this.record.status = 'error'
        this.record.errorMessage = composeErrorMessage(error)
      }
    },
    clearRecord() {
      this.record = requestState<PendapatanData>()
    }
  }
})
