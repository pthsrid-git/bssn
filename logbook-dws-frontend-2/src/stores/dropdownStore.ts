import { defineStore } from 'pinia'

import { dropdownStore } from '@/const'
import { composeErrorMessage, log } from '@/helpers'
import { requestState, responseDataObjectFromJson } from '@/models'

const dropdownDummy1 = (): any => {
  return {
    data: {
      result: {
        data: {
          component: 1,
          helper: 3,
          model: 2
        }
      }
    }
  }
}

const dropdownDummy2 = (): any => {
  return {
    data: {
      result: {
        data: {
          component: 0,
          helper: 3,
          model: 2
        }
      }
    }
  }
}

const dropdownDummy3 = (): any => {
  return {
    data: {
      result: {
        data: {
          component: 0,
          helper: 0,
          model: 0
        }
      }
    }
  }
}

interface DropdownData {
  component: number
  helper: number
  model: number
}
const dropdownDataFromJson = (json: any): DropdownData => {
  return {
    component: json.component,
    helper: json.helper,
    model: json.model
  }
}

const defaultState = {
  badge: requestState<DropdownData>({
    component: 0,
    helper: 0,
    model: 0
  })
}

export const useDropdownStore = defineStore(dropdownStore, {
  state: () => {
    return defaultState
  },
  actions: {
    async callBadge(type: number = 1) {
      this.badge.status = 'loading'
      try {
        await new Promise((resolve) => setTimeout(resolve, 500))
        let response
        if (type === 1) {
          response = dropdownDummy1()
        } else if (type === 2) {
          response = dropdownDummy2()
        } else {
          response = dropdownDummy3()
        }

        const responseData = responseDataObjectFromJson<DropdownData>(
          response.data.result,
          dropdownDataFromJson
        )
        this.badge.data = responseData.item
        this.badge.status = 'success'
      } catch (error) {
        log(error)
        this.badge.status = 'error'
        this.badge.errorMessage = composeErrorMessage(error)
      }
    },
    clearBadge() {
      this.badge = defaultState.badge
    }
  }
})
