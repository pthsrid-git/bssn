import { defineStore } from 'pinia'

import { menuBadgeStore } from '@/const'

interface MenuBadgeData {
  name: string
  badge: number
}

export const useMenuBadgeStore = defineStore(menuBadgeStore, {
  state: () => {
    return {
      badge: [] as MenuBadgeData[]
    }
  }
})
