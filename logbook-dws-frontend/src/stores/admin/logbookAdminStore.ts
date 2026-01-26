import {
  composeErrorMessage,
  paginatedRequestState,
  requestState
} from '@bssn/ui-kit-frontend'
import { defineStore } from 'pinia'

import { adminLogbookAdminStore } from '@/const'
import { log } from '@/helpers/custom'
import { getRequest } from '@/helpers/request'
import {
  unitDataFromJson,
  pegawaiAdminDataFromJson,
  logbookAdminDataFromJson,
  summaryAdminDataFromJson,
  type UnitData,
  type PegawaiAdminData,
  type LogbookAdminData,
  type SummaryAdminData
} from '@/models/admin/logbookAdmin'
import {
  unitsListUrl,
  allPegawaiUrl,
  unitPegawaiUrl,
  pegawaiLogsAdminUrl,
  detailLogAdminUrl,
  summaryAdminUrl,
  downloadAbkReportUrl
} from '@/urls/admin/logbookAdmin'

export const useLogbookAdminStore = defineStore(adminLogbookAdminStore, {
  state: () => {
    return {
      units: paginatedRequestState<UnitData[]>([]),
      pegawaiList: paginatedRequestState<PegawaiAdminData[]>([]),
      pegawaiLogs: requestState<LogbookAdminData[]>([]),
      logDetail: requestState<LogbookAdminData | null>(null),
      summary: requestState<SummaryAdminData | null>(null),
      currentPegawaiId: null as number | null,
      currentFilters: null as { start_date?: string; end_date?: string } | null
    }
  },
  actions: {
    async callUnits(keyword: string, page: number) {
      let url = unitsListUrl()
      const params = new URLSearchParams()

      if (keyword) params.append('keyword', keyword)
      params.append('page', page.toString())
      params.append('per_page', '10')

      const queryString = params.toString()
      if (queryString) {
        url += `?${queryString}`
      }

      this.units.status = 'loading'
      this.units.errorMessage = null
      try {
        const response = await getRequest(url)
        const dataArray = Array.isArray(response.data?.data) ? response.data.data : []
        const unitsData = dataArray.map(unitDataFromJson)

        this.units.data = unitsData
        this.units.meta = {
          currentPage: response.data?.meta?.current_page || 1,
          lastPage: response.data?.meta?.last_page || 1,
          perPage: response.data?.meta?.per_page || 10,
          total: response.data?.meta?.total || 0
        }
        this.units.status = 'success'
      } catch (error) {
        this.units.status = 'error'
        this.units.errorMessage = composeErrorMessage(error)
      }
    },
    clearUnits() {
      this.units = paginatedRequestState<UnitData[]>([])
    },
    async callAllPegawai(keyword: string, page: number) {
      let url = allPegawaiUrl()
      const params = new URLSearchParams()

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
        const pegawaiData = dataArray.map(pegawaiAdminDataFromJson)

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
    async callUnitPegawai(unitCode: string, keyword: string, page: number) {
      let url = unitPegawaiUrl(unitCode)
      const params = new URLSearchParams()

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
        const pegawaiData = dataArray.map(pegawaiAdminDataFromJson)

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
      this.pegawaiList = paginatedRequestState<PegawaiAdminData[]>([])
    },
    async callPegawaiLogs(
      pegawaiId: number,
      filters?: { start_date?: string; end_date?: string }
    ) {
      this.currentPegawaiId = pegawaiId
      this.currentFilters = filters || null

      const url = pegawaiLogsAdminUrl(pegawaiId, {
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
        const logs = dataArray.map(logbookAdminDataFromJson)

        this.pegawaiLogs.data = logs
        this.pegawaiLogs.status = 'success'
      } catch (error) {
        this.pegawaiLogs.status = 'error'
        this.pegawaiLogs.errorMessage = composeErrorMessage(error)
      }
    },
    clearPegawaiLogs() {
      this.pegawaiLogs = requestState<LogbookAdminData[]>([])
    },
    async callLogDetail(logId: number) {
      const url = detailLogAdminUrl(logId)
      this.logDetail.status = 'loading'
      this.logDetail.errorMessage = null
      try {
        const response = await getRequest(url)
        const logData = logbookAdminDataFromJson(response.data?.data || response.data)

        this.logDetail.data = logData
        this.logDetail.status = 'success'
      } catch (error) {
        this.logDetail.status = 'error'
        this.logDetail.errorMessage = composeErrorMessage(error)
      }
    },
    clearLogDetail() {
      this.logDetail = requestState<LogbookAdminData | null>(null)
    },
    async callSummary() {
      const url = summaryAdminUrl()
      this.summary.status = 'loading'
      this.summary.errorMessage = null
      try {
        const response = await getRequest(url)
        const summaryData = summaryAdminDataFromJson(response.data?.data || response.data)

        this.summary.data = summaryData
        this.summary.status = 'success'
      } catch (error) {
        this.summary.status = 'error'
        this.summary.errorMessage = composeErrorMessage(error)
      }
    },
    clearSummary() {
      this.summary = requestState<SummaryAdminData | null>(null)
    },
    async downloadAbkReport(year: number) {
      const url = downloadAbkReportUrl(year)

      try {
        const response = await getRequest(url, {
          responseType: 'blob'
        })

        // Create blob from response
        const blob = new Blob([response.data], {
          type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        })

        // Create download link
        const downloadUrl = window.URL.createObjectURL(blob)
        const link = document.createElement('a')
        link.href = downloadUrl
        link.download = `Laporan_ABK_${year}.xlsx`
        document.body.appendChild(link)
        link.click()
        document.body.removeChild(link)
        window.URL.revokeObjectURL(downloadUrl)
      } catch (error) {
        console.error('Error downloading ABK report:', error)
        throw error
      }
    }
  }
})
