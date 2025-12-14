import { defineStore } from 'pinia'

import { userDefaultStore } from '@/const'
import type { UserDefaultData } from '@/models'

export const useUserDefaultStore = defineStore(userDefaultStore, {
  state: () => {
    return {
      user: null as UserDefaultData | null
    }
  },
  getters: {
    isAuthorized: (state) => (permission: string) => {
      if (state.user) {
        return state.user.permissions.includes(permission)
      }
    }
  },
  actions: {
    setUser(user: UserDefaultData) {
      this.user = user
    },
    removeUser() {
      this.user = null
    }
  }
})
