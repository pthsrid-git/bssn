import {
  composeErrorMessage,
  paginatedRequestState,
  requestState,
  responseDataPaginationFromJson
} from '@bssn/ui-kit-frontend'
import { defineStore } from 'pinia'

import { pengolahPengajuanStore } from '@/const'
import { recordsPengajuanDummy } from '@/dummies/pengolah/pengajuan'
import { log } from '@/helpers/custom'
import { getRequest, putRequest } from '@/helpers/request'
import { pengajuanDataFromJson, type PengajuanData } from '@/models/pengolah/pengajuan'
import {
  diprosesAllPengajuanUrl,
  diselesaikanAllPengajuanUrl,
  downloadPengajuanUrl,
  editPengajuanUrl,
  recordsPengajuanUrl
} from '@/urls/pengolah/pengajuan'

const dummy = import.meta.env.VITE_DUMMY === 'true'

export const usePengajuanStore = defineStore(pengolahPengajuanStore, {
  state: () => {
    return {
      records: paginatedRequestState<PengajuanData[]>([]),
      edit: requestState(),
      diprosesAll: requestState(),
      diselesaikanAll: requestState()
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
    async callEdit(id: string, values: Record<string, any>) {
      const url = editPengajuanUrl(id)
      log(`url: ${url} | values: ${JSON.stringify(values)}`)
      this.edit.status = 'loading'
      this.edit.errorMessage = null
      try {
        if (dummy) {
          await new Promise((resolve) => setTimeout(resolve, 500))
        } else {
          await putRequest(url, values)
        }
        this.edit.status = 'success'
      } catch (error) {
        log(error)
        this.edit.status = 'error'
        this.edit.errorMessage = composeErrorMessage(error)
      }
    },
    clearEdit() {
      this.edit = requestState()
    },
    async callDiprosesAll(values: Record<string, any>) {
      const url = diprosesAllPengajuanUrl()
      log(`url: ${url} | values: ${JSON.stringify(values)}`)
      this.diprosesAll.status = 'loading'
      this.diprosesAll.errorMessage = null
      try {
        if (dummy) {
          await new Promise((resolve) => setTimeout(resolve, 500))
        } else {
          await putRequest(url, values)
        }
        this.diprosesAll.status = 'success'
      } catch (error) {
        log(error)
        this.diprosesAll.status = 'error'
        this.diprosesAll.errorMessage = composeErrorMessage(error)
      }
    },
    clearDiprosesAll() {
      this.diprosesAll = requestState()
    },
    async callDiselesaikanAll(values: Record<string, any>) {
      const url = diselesaikanAllPengajuanUrl()
      log(`url: ${url} | values: ${JSON.stringify(values)}`)
      this.diselesaikanAll.status = 'loading'
      this.diselesaikanAll.errorMessage = null
      try {
        if (dummy) {
          await new Promise((resolve) => setTimeout(resolve, 500))
        } else {
          await putRequest(url, values)
        }
        this.diselesaikanAll.status = 'success'
      } catch (error) {
        log(error)
        this.diselesaikanAll.status = 'error'
        this.diselesaikanAll.errorMessage = composeErrorMessage(error)
      }
    },
    clearDiselesaikanAll() {
      this.diselesaikanAll = requestState()
    },
    async callDownload(id: string) {
      const url = downloadPengajuanUrl(id)
      log(`url: ${url}`)
      try {
        if (dummy) {
          await new Promise((resolve) => setTimeout(resolve, 500))
        } else {
          const response = await getRequest(url, {
            responseType: 'blob'
          })

          const disposition = response.headers['content-disposition']
          let filename = `file-pengajuan-logbook-${id}`
          if (disposition) {
            const match = disposition.match(/filename="?([^"]+)"?/)
            if (match && match[1]) {
              filename = match[1]
            }
          }

          const blob = new Blob([response.data], { type: response.headers['content-type'] })
          const windowUrl = window.URL.createObjectURL(blob)
          const link = document.createElement('a')

          link.href = windowUrl
          link.setAttribute('download', filename)
          document.body.appendChild(link)
          link.click()

          window.URL.revokeObjectURL(windowUrl)
          document.body.removeChild(link)
        }
      } catch (error) {
        log(error)
      }
    }
  }
})
