import { defineStore } from 'pinia'

import { testLogicStore } from '@/const'
import { requestState } from '@/models'

export const useLogicStore = defineStore(testLogicStore, {
  state: () => {
    return {
      all: requestState<[]>([])
    }
  },
  actions: {
    async callAll() {
      this.all.status = 'loading'
      try {
        await new Promise((resolve) => setTimeout(resolve, 1000))

        this.all.status = 'success'
      } catch (error) {
        this.all.status = 'error'
      }
    },
    clearAll() {
      this.all = requestState<[]>([])
    }
  }
})
