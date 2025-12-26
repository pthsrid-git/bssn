import type { UserDwsData } from '@bssn/ui-kit-frontend'
import { defineStore } from 'pinia'

import { userDwsStore } from '@/const'

export const useUserDwsStore = defineStore(userDwsStore, {
  state: () => {
    return {
      user: null as UserDwsData | null
    }
  },
  getters: {
    isRuangPribadiAuthorized: (state) => (permission: string) => {
      if (state.user) {
        return state.user.permissions.ruangPribadi.includes(permission)
      }
    },
    isRuangKerjaAuthorized: (state) => (permission: string) => {
      if (state.user) {
        return state.user.permissions.ruangKerja.includes(permission)
      }
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
