import {
  composeErrorMessage,
  paginatedRequestState,
  requestState,
  responseDataPaginationFromJson
} from '@bssn/ui-kit-frontend'
import { defineStore } from 'pinia'

import { adminPetugasStore } from '@/const'
import { recordsPetugasDummy } from '@/dummies/admin/petugas'
import { log } from '@/helpers/custom'
import { deleteRequest, getRequest, postRequest, putRequest } from '@/helpers/request'
import { petugasDataFromJson, type PetugasData } from '@/models/admin/petugas'
import {
  addPetugasUrl,
  deletePetugasUrl,
  editPetugasUrl,
  recordsPetugasUrl
} from '@/urls/admin/petugas'

const dummy = import.meta.env.VITE_DUMMY === 'true'

export const usePetugasStore = defineStore(adminPetugasStore, {
  state: () => {
    return {
      records: paginatedRequestState<PetugasData[]>([]),
      add: requestState(),
      edit: requestState(),
      delete: requestState()
    }
  },
  actions: {
    async callRecords(keyword: string, page: number) {
      const url = recordsPetugasUrl(keyword, page)
      log(`url: ${url}`)
      this.records.status = 'loading'
      this.records.errorMessage = null
      try {
        let response: any
        if (dummy) {
          await new Promise((resolve) => setTimeout(resolve, 500))
          response = recordsPetugasDummy()
        } else {
          response = await getRequest(url)
        }
        const responseData = responseDataPaginationFromJson<PetugasData>(
          response,
          petugasDataFromJson
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
      this.records = paginatedRequestState<PetugasData[]>([])
    },
    async callAdd(values: Record<string, any>) {
      const url = addPetugasUrl()
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
    },
    async callEdit(values: Record<string, any>) {
      const url = editPetugasUrl()
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
    async callDelete(values: Record<string, any>) {
      const url = deletePetugasUrl()
      log(`url: ${url} | values: ${JSON.stringify(values)}`)
      this.delete.status = 'loading'
      this.delete.errorMessage = null
      try {
        if (dummy) {
          await new Promise((resolve) => setTimeout(resolve, 500))
        } else {
          await deleteRequest(url, values)
        }
        this.delete.status = 'success'
      } catch (error) {
        log(error)
        this.delete.status = 'error'
        this.delete.errorMessage = composeErrorMessage(error)
      }
    },
    clearDelete() {
      this.delete = requestState()
    }
  }
})
