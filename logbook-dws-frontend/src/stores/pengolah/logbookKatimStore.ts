import {
  composeErrorMessage,
  mapFormData,
  requestState,
  responseDataArrayFromJson
} from '@bssn/ui-kit-frontend'
import { defineStore } from 'pinia'

import { pengolahLogbookKatimStore } from '@/const'
import { recordsLogbookKatimDummy, teamMembersDummy } from '@/dummies/pengolah/logbookKatim'
import { log } from '@/helpers/custom'
import { getRequest, postMultipartRequest, putRequest } from '@/helpers/request'
import {
  logbookKatimDataFromJson,
  teamMemberDataFromJson,
  type LogbookKatimData,
  type TeamMemberData
} from '@/models/pengolah/logbookKatim'
import {
  addLogbookUrl,
  recordsLogbookKatimUrl,
  teamMembersUrl,
  memberLogsUrl,
  updateCatatanKatimUrl
} from '@/urls/pengolah/logbookKatim'

const dummy = import.meta.env.VITE_DUMMY === 'true'

export const useLogbookKatimStore = defineStore(pengolahLogbookKatimStore, {
  state: () => {
    return {
      records: requestState<LogbookKatimData[]>([]),
      teamMembers: requestState<TeamMemberData[]>([]),
      memberLogs: requestState<LogbookKatimData[]>([]),
      add: requestState(),
      updateCatatan: requestState(),
      currentMemberId: null as number | null,
      currentFilters: null as { start_date?: string; end_date?: string } | null
    }
  },
  actions: {
    async callRecords(keyword: string, page: number) {
      const userId = import.meta.env.VITE_TEST_USER_ID
        ? Number(import.meta.env.VITE_TEST_USER_ID)
        : undefined
      const url = recordsLogbookKatimUrl(keyword, page, userId)
      log(`url: ${url}`)
      this.records.status = 'loading'
      this.records.errorMessage = null
      try {
        let response: any
        if (dummy) {
          await new Promise((resolve) => setTimeout(resolve, 500))
          response = recordsLogbookKatimDummy()
        } else {
          response = await getRequest(url)
        }
        const responseData = responseDataArrayFromJson<LogbookKatimData>(
          response.data,
          logbookKatimDataFromJson
        )
        this.records.data = responseData.items
        this.records.status = 'success'
      } catch (error) {
        this.records.status = 'error'
        this.records.errorMessage = composeErrorMessage(error)
      }
    },
    clearRecords() {
      this.records = requestState<LogbookKatimData[]>([])
    },
    async callTeamMembers() {
      const userId = import.meta.env.VITE_TEST_USER_ID
        ? Number(import.meta.env.VITE_TEST_USER_ID)
        : undefined
      const url = `${teamMembersUrl()}${userId ? `?user_id=${userId}` : ''}`
      this.teamMembers.status = 'loading'
      this.teamMembers.errorMessage = null
      try {
        let response: any
        if (dummy) {
          await new Promise((resolve) => setTimeout(resolve, 500))
          response = teamMembersDummy()
        } else {
          response = await getRequest(url)
        }
        const members = Array.isArray(response.data)
          ? response.data.map(teamMemberDataFromJson)
          : []

        this.teamMembers.data = members
        this.teamMembers.status = 'success'
      } catch (error) {
        this.teamMembers.status = 'error'
        this.teamMembers.errorMessage = composeErrorMessage(error)
      }
    },
    clearTeamMembers() {
      this.teamMembers = requestState<TeamMemberData[]>([])
    },
    async callMemberLogs(memberId: number, filters?: { start_date?: string; end_date?: string }) {
      // Store current member and filters for reload
      this.currentMemberId = memberId
      this.currentFilters = filters || null

      const userId = import.meta.env.VITE_TEST_USER_ID
        ? Number(import.meta.env.VITE_TEST_USER_ID)
        : undefined

      const url = memberLogsUrl(memberId, {
        user_id: userId,
        start_date: filters?.start_date,
        end_date: filters?.end_date
      })

      this.memberLogs.status = 'loading'
      this.memberLogs.errorMessage = null
      try {
        const response = await getRequest(url)

        // Backend returns { success: true, data: [...], member: {...} }
        const dataArray = response.data?.data || response.data || []
        const members = Array.isArray(dataArray) ? dataArray.map(logbookKatimDataFromJson) : []

        this.memberLogs.data = members
        this.memberLogs.status = 'success'
      } catch (error) {
        this.memberLogs.status = 'error'
        this.memberLogs.errorMessage = composeErrorMessage(error)
      }
    },
    clearMemberLogs() {
      this.memberLogs = requestState<LogbookKatimData[]>([])
    },
    async callAdd(values: Record<string, any>) {
      const userId = import.meta.env.VITE_TEST_USER_ID
        ? Number(import.meta.env.VITE_TEST_USER_ID)
        : undefined
      const url = addLogbookUrl()
      const data = { ...values, user_id: userId }
      log(`url: ${url} | values: ${JSON.stringify(data)}`)
      this.add.status = 'loading'
      this.add.errorMessage = null
      const formData = mapFormData(new FormData(), data) as FormData
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
    },
    async callUpdateCatatanKatim(logId: number, catatan: string) {
      const userId = import.meta.env.VITE_TEST_USER_ID
        ? Number(import.meta.env.VITE_TEST_USER_ID)
        : undefined

      let url = updateCatatanKatimUrl(logId)
      if (userId) {
        url += `?user_id=${userId}`
      }

      const data = { catatan_katim: catatan }

      log(`url: ${url} | data: ${JSON.stringify(data)}`)
      this.updateCatatan.status = 'loading'
      this.updateCatatan.errorMessage = null

      try {
        await putRequest(url, data)
        this.updateCatatan.status = 'success'
      } catch (error) {
        log(error)
        this.updateCatatan.status = 'error'
        this.updateCatatan.errorMessage = composeErrorMessage(error)
        throw error
      }
    },
    clearUpdateCatatan() {
      this.updateCatatan = requestState()
    },
    async saveCatatanKatimAndReload(logId: number, catatan: string) {
      try {
        // Update catatan katim to backend
        await this.callUpdateCatatanKatim(logId, catatan)

        // Reload member logs using stored member ID and filters
        if (this.currentMemberId) {
          await this.callMemberLogs(this.currentMemberId, this.currentFilters || undefined)
        }

        return { success: true }
      } catch (error) {
        log(error)
        return { success: false, error }
      }
    }
  }
})
