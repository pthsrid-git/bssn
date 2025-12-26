import {
  composeErrorMessage,
  paginatedRequestState,
  requestState,
  responseDataPaginationFromJson
} from '@bssn/ui-kit-frontend'
import { defineStore } from 'pinia'

import { pegawaiPengajuanStore } from '@/const'
import { recordsPengajuanDummy } from '@/dummies/pegawai/pengajuan'
import { log } from '@/helpers/custom'
import { getRequest, postRequest } from '@/helpers/request'
import { pengajuanDataFromJson, type PengajuanData } from '@/models/pegawai/pengajuan'
import { addPengajuanUrl, recordsPengajuanUrl } from '@/urls/pegawai/pengajuan'

const dummy = import.meta.env.VITE_DUMMY === 'true'

export const usePengajuanStore = defineStore(pegawaiPengajuanStore, {
  state: () => {
    return {
      records: paginatedRequestState<PengajuanData[]>([]),
      add: requestState()
    }
  },
  actions: {
    async callRecords(keyword: string, page: number) {
      const url = recordsPengajuanUrl(keyword, page)
      log(`url: ${url}`)
      this.records.status = 'loading'
      this.records.errorMessage = null
      try {
        let response: any
        if (dummy) {
          await new Promise((resolve) => setTimeout(resolve, 500))
          response = recordsPengajuanDummy()
        } else {
          response = await getRequest(url)
        }
        const responseData = responseDataPaginationFromJson<PengajuanData>(
          response,
          pengajuanDataFromJson
        )
        this.records.data = responseData.items
        this.records.meta = responseData.meta
        this.records.status = 'success'
      } catch (error) {
        log(error)
        this.records.status = 'error'
        this.records.errorMessage = composeErrorMessage(error)
      }
    },
    clearRecords() {
      this.records = paginatedRequestState<PengajuanData[]>([])
    },
    async callAdd(values: Record<string, any>) {
      const url = addPengajuanUrl()
      log(`url: ${url} | values: ${JSON.stringify(values)}`)
      this.add.status = 'loading'
      this.add.errorMessage = null
      try {
        if (dummy) {
          await new Promise((resolve) => setTimeout(resolve, 500))
        } else {
          await postRequest(url, values)
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
