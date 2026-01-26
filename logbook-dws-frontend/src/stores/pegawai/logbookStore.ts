import {
  composeErrorMessage,
  mapFormData,
  requestState,
  responseDataArrayFromJson
} from '@bssn/ui-kit-frontend'
import { defineStore } from 'pinia'

import { pegawaiLogbookStore } from '@/const'
import { recordsLogbookDummy } from '@/dummies/pegawai/logbook'
import { log } from '@/helpers/custom'
import { getRequest, postMultipartRequest } from '@/helpers/request'
import { logbookDataFromJson, type LogbookData } from '@/models/pegawai/logbook'
import { addLogbookUrl, recordsLogbookUrl } from '@/urls/pegawai/logbook'

const dummy = import.meta.env.VITE_DUMMY === 'true'

export const useLogbookStore = defineStore(pegawaiLogbookStore, {
  state: () => {
    return {
      records: requestState<LogbookData[]>([]),
      add: requestState()
    }
  },
  actions: {
    async callRecords(keyword: string, page: number) {
      const url = recordsLogbookUrl(keyword, page)
      log(`url: ${url}`)
      this.records.status = 'loading'
      this.records.errorMessage = null
      try {
        let response: any
        if (dummy) {
          await new Promise((resolve) => setTimeout(resolve, 500))
          response = recordsLogbookDummy()
        } else {
          response = await getRequest(url)
        }
        const responseData = responseDataArrayFromJson<LogbookData>(
          response.data,
          logbookDataFromJson
        )
        this.records.data = responseData.items
        this.records.status = 'success'
      } catch (error) {
        this.records.status = 'error'
        this.records.errorMessage = composeErrorMessage(error)
      }
    },
    clearRecords() {
      this.records = requestState<LogbookData[]>([])
    },
    async callAdd(values: Record<string, any>) {
      const url = addLogbookUrl()
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
