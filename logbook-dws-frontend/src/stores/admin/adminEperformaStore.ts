import {
  composeErrorMessage,
  paginatedRequestState,
  requestState
} from '@bssn/ui-kit-frontend'
import { defineStore } from 'pinia'

import { adminAdminEperformaStore } from '@/const'
import { log } from '@/helpers/custom'
import { deleteRequest, getRequest, postRequest } from '@/helpers/request'
import {
  adminEperformaDataFromJson,
  type AdminEperformaData
} from '@/models/admin/petugasLogbook'
import {
  recordsAdminEperformaUrl,
  addAdminEperformaUrl,
  deleteAdminEperformaUrl
} from '@/urls/admin/petugasLogbook'

export const useAdminEperformaStore = defineStore(adminAdminEperformaStore, {
  state: () => {
    return {
      records: paginatedRequestState<AdminEperformaData[]>([]),
      add: requestState<AdminEperformaData | null>(null),
      delete: requestState<null>(null)
    }
  },
  actions: {
    async callRecords(keyword: string, page: number) {
      const url = recordsAdminEperformaUrl(keyword, page)
      log(`url: ${url}`)
      this.records.status = 'loading'
      this.records.errorMessage = null
      try {
        const response = await getRequest(url)
        const dataArray = Array.isArray(response.data?.data) ? response.data.data : []
        const recordsData = dataArray.map(adminEperformaDataFromJson)

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
      this.records = paginatedRequestState<AdminEperformaData[]>([])
    },

    async callAdd(payload: { guid: string; nama?: string }) {
      const url = addAdminEperformaUrl()
      log(`url: ${url}`)
      this.add.status = 'loading'
      this.add.errorMessage = null
      try {
        const response = await postRequest(url, payload)
        this.add.data = adminEperformaDataFromJson(response.data?.data || response.data)
        this.add.status = 'success'
      } catch (error) {
        log(error)
        this.add.status = 'error'
        this.add.errorMessage = composeErrorMessage(error)
      }
    },
    clearAdd() {
      this.add = requestState<AdminEperformaData | null>(null)
    },

    async callDelete(guid: string) {
      const url = deleteAdminEperformaUrl(guid)
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
