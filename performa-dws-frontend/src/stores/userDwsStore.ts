import { defineStore } from 'pinia'

import { userDwsStore } from '@/const'
import type { UserDwsData } from '@/models'

export const useUserDwsStore = defineStore(userDwsStore, {
  state: () => {
    return {
      user: null as UserDwsData | null
    }
  },
  getters: {
    isRuangPribadiAuthorized: (state) => (permission: string) => {
      if (!state.user || !state.user.permissions) {
        return false
      }

      // Get permissions array
      const permissions = state.user.permissions.ruangPribadi || []
      return permissions.includes(permission)
    },

    isRuangKerjaAuthorized: (state) => (permission: string) => {
      if (!state.user || !state.user.permissions) {
        return false
      }

      // Get permissions array
      const permissions = state.user.permissions.ruangKerja || []
      return permissions.includes(permission)
    }
  },
  actions: {
    setUser(user: UserDwsData) {
      this.user = user
    },

    removeUser() {
      this.user = null
    }
  }
})
