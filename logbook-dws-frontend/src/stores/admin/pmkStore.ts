import {
  composeErrorMessage,
  paginatedRequestState,
  requestState
} from '@bssn/ui-kit-frontend'
import { defineStore } from 'pinia'

import { adminPmkStore } from '@/const'
import { log } from '@/helpers/custom'
import { deleteRequest, getRequest, postRequest } from '@/helpers/request'
import {
  pmkDataFromJson,
  type PmkData
} from '@/models/admin/petugasLogbook'
import {
  recordsPmkUrl,
  addPmkUrl,
  deletePmkUrl
} from '@/urls/admin/petugasLogbook'

export const usePmkStore = defineStore(adminPmkStore, {
  state: () => {
    return {
      records: paginatedRequestState<PmkData[]>([]),
      add: requestState<PmkData | null>(null),
      delete: requestState<null>(null)
    }
  },
  actions: {
    async callRecords(keyword: string, page: number) {
      const url = recordsPmkUrl(keyword, page)
      log(`url: ${url}`)
      this.records.status = 'loading'
      this.records.errorMessage = null
      try {
        const response = await getRequest(url)
        const dataArray = Array.isArray(response.data?.data) ? response.data.data : []
        const recordsData = dataArray.map(pmkDataFromJson)

        this.records.data = recordsData
        this.records.meta = {
          currentPage: response.data?.meta?.current_page || 1,
          lastPage: response.data?.meta?.last_page || 1,
          perPage: response.data?.meta?.per_page || 10,
          total: response.data?.meta?.total || 0
        }
        this.records.status = 'success'
      } catch (error) {
        log(error)
        this.records.status = 'error'
        this.records.errorMessage = composeErrorMessage(error)
      }
    },
    clearRecords() {
      this.records = paginatedRequestState<PmkData[]>([])
    },

    async callAdd(payload: { guid: string; unitKerjaPko: string; nama?: string }) {
      const url = addPmkUrl()
      log(`url: ${url}`)
      this.add.status = 'loading'
      this.add.errorMessage = null
      try {
        const response = await postRequest(url, {
          guid: payload.guid,
          unit_kerja_pko: payload.unitKerjaPko,
          nama: payload.nama
        })
        this.add.data = pmkDataFromJson(response.data?.data || response.data)
        this.add.status = 'success'
      } catch (error) {
        log(error)
        this.add.status = 'error'
        this.add.errorMessage = composeErrorMessage(error)
      }
    },
    clearAdd() {
      this.add = requestState<PmkData | null>(null)
    },

    async callDelete(guid: string) {
      const url = deletePmkUrl(guid)
      log(`url: ${url}`)
      this.delete.status = 'loading'
      this.delete.errorMessage = null
      try {
        await deleteRequest(url)
        this.delete.status = 'success'
      } catch (error) {
        log(error)
        this.delete.status = 'error'
        this.delete.errorMessage = composeErrorMessage(error)
      }
    },
    clearDelete() {
      this.delete = requestState<null>(null)
    }
  }
})
