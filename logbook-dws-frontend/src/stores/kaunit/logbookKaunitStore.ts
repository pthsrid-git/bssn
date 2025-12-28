import {
  composeErrorMessage,
  paginatedRequestState,
  requestState,
  responseDataPaginationFromJson
} from '@bssn/ui-kit-frontend'
import { defineStore } from 'pinia'

import { kaunitLogbookKaunitStore } from '@/const'
import { log } from '@/helpers/custom'
import { getRequest, postRequest } from '@/helpers/request'
import {
  logbookKaunitDataFromJson,
  pegawaiDataFromJson,
  type LogbookKaunitData,
  type PegawaiData
} from '@/models/kaunit/logbookKaunit'
import {
  pegawaiListUrl,
  pegawaiLogsUrl,
  recordsLogbookKaunitUrl,
  approveLogKaunitUrl,
  rejectLogKaunitUrl
} from '@/urls/kaunit/logbookKaunit'

export const useLogbookKaunitStore = defineStore(kaunitLogbookKaunitStore, {
  state: () => {
    return {
      records: paginatedRequestState<LogbookKaunitData[]>([]),
      pegawaiList: paginatedRequestState<PegawaiData[]>([]),
      pegawaiLogs: requestState<LogbookKaunitData[]>([]),
      approve: requestState(),
      reject: requestState(),
      currentPegawaiId: null as number | null,
      currentFilters: null as { start_date?: string; end_date?: string } | null
    }
  },
  actions: {
    async callRecords(keyword: string, page: number) {
      const userId = import.meta.env.VITE_TEST_USER_ID
        ? Number(import.meta.env.VITE_TEST_USER_ID)
        : undefined
      const url = recordsLogbookKaunitUrl(keyword, page, userId)
      log(`url: ${url}`)
      this.records.status = 'loading'
      this.records.errorMessage = null
      try {
        const response = await getRequest(url)
        const responseData = responseDataPaginationFromJson<LogbookKaunitData>(
          response,
          logbookKaunitDataFromJson
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
      this.records = paginatedRequestState<LogbookKaunitData[]>([])
    },
    async callPegawaiList(keyword: string, page: number) {
      const userId = import.meta.env.VITE_TEST_USER_ID
        ? Number(import.meta.env.VITE_TEST_USER_ID)
        : undefined

      let url = pegawaiListUrl()
      const params = new URLSearchParams()

      if (userId) params.append('user_id', userId.toString())
      if (keyword) params.append('keyword', keyword)
      params.append('page', page.toString())
      params.append('per_page', '10')

      const queryString = params.toString()
      if (queryString) {
        url += `?${queryString}`
      }

      this.pegawaiList.status = 'loading'
      this.pegawaiList.errorMessage = null
      try {
        const response = await getRequest(url)
        const dataArray = Array.isArray(response.data?.data) ? response.data.data : []
        const pegawaiData = dataArray.map(pegawaiDataFromJson)

        this.pegawaiList.data = pegawaiData
        this.pegawaiList.meta = {
          currentPage: response.data?.meta?.current_page || 1,
          lastPage: response.data?.meta?.last_page || 1,
          perPage: response.data?.meta?.per_page || 10,
          total: response.data?.meta?.total || 0
        }
        this.pegawaiList.status = 'success'
      } catch (error) {
        this.pegawaiList.status = 'error'
        this.pegawaiList.errorMessage = composeErrorMessage(error)
      }
    },
    clearPegawaiList() {
      this.pegawaiList = paginatedRequestState<PegawaiData[]>([])
    },
    async callPegawaiLogs(pegawaiId: number, filters?: { start_date?: string; end_date?: string }) {
      this.currentPegawaiId = pegawaiId
      this.currentFilters = filters || null

      const userId = import.meta.env.VITE_TEST_USER_ID
        ? Number(import.meta.env.VITE_TEST_USER_ID)
        : undefined

      const url = pegawaiLogsUrl(pegawaiId, {
        user_id: userId,
        start_date: filters?.start_date,
        end_date: filters?.end_date
      })

      this.pegawaiLogs.status = 'loading'
      this.pegawaiLogs.errorMessage = null
      try {
        const response = await getRequest(url)
        const dataArray = Array.isArray(response.data?.data)
          ? response.data.data
          : Array.isArray(response.data)
            ? response.data
            : []
        const pegawai = dataArray.map(logbookKaunitDataFromJson)

        this.pegawaiLogs.data = pegawai
        this.pegawaiLogs.status = 'success'
      } catch (error) {
        this.pegawaiLogs.status = 'error'
        this.pegawaiLogs.errorMessage = composeErrorMessage(error)
      }
    },
    clearPegawaiLogs() {
      this.pegawaiLogs = requestState<LogbookKaunitData[]>([])
    },
    async callApproveLog(logId: number, catatan?: string) {
      const userId = import.meta.env.VITE_TEST_USER_ID
        ? Number(import.meta.env.VITE_TEST_USER_ID)
        : undefined

      let url = approveLogKaunitUrl(logId)
      if (userId) {
        url += `?user_id=${userId}`
      }

      const data = catatan ? { catatan_atasan: catatan } : {}

      log(`url: ${url} | data: ${JSON.stringify(data)}`)
      this.approve.status = 'loading'
      this.approve.errorMessage = null

      try {
        await postRequest(url, data)
        this.approve.status = 'success'

        if (this.currentPegawaiId) {
          await this.callPegawaiLogs(this.currentPegawaiId, this.currentFilters || undefined)
        }
      } catch (error) {
        log(error)
        this.approve.status = 'error'
        this.approve.errorMessage = composeErrorMessage(error)
        throw error
      }
    },
    clearApprove() {
      this.approve = requestState()
    },
    async callRejectLog(logId: number, catatan: string) {
      const userId = import.meta.env.VITE_TEST_USER_ID
        ? Number(import.meta.env.VITE_TEST_USER_ID)
        : undefined

      let url = rejectLogKaunitUrl(logId)
      if (userId) {
        url += `?user_id=${userId}`
      }

      const data = { catatan_atasan: catatan }

      log(`url: ${url} | data: ${JSON.stringify(data)}`)
      this.reject.status = 'loading'
      this.reject.errorMessage = null

      try {
        await postRequest(url, data)
        this.reject.status = 'success'

        if (this.currentPegawaiId) {
          await this.callPegawaiLogs(this.currentPegawaiId, this.currentFilters || undefined)
        }
      } catch (error) {
        log(error)
        this.reject.status = 'error'
        this.reject.errorMessage = composeErrorMessage(error)
        throw error
      }
    },
    clearReject() {
      this.reject = requestState()
    }
  }
})
