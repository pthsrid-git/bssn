import { defineStore } from 'pinia'

import { optionStore } from '@/const'
import { allOptionDummy } from '@/dummies/option'
import { composeErrorMessage, log } from '@/helpers'
import {
  optionRawDataFromJson,
  requestState,
  responseDataArrayFromJson,
  type OptionRawData
} from '@/models'

export const useOptionStore = defineStore(optionStore, {
  state: () => {
    return {
      all: requestState<OptionRawData[]>([])
    }
  },
  actions: {
    async callAll() {
      this.all.status = 'loading'
      try {
        await new Promise((resolve) => setTimeout(resolve, 500))
        const response = allOptionDummy()
        const responseData = responseDataArrayFromJson<OptionRawData>(
          response.data.result,
          optionRawDataFromJson
        )
        this.all.data = responseData.items
        this.all.status = 'success'
      } catch (error) {
        log(error)
        this.all.status = 'error'
        this.all.errorMessage = composeErrorMessage(error)
      }
    },
    clearAll() {
      this.all = requestState<OptionRawData[]>([])
    }
  }
})
