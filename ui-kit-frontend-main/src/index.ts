import './assets/css/style.css'
import './font-awesome.ts'

import { FontAwesomeIcon as RawFontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import type { Store } from 'pinia'

import ProjectConst from './const.ts'
import { log } from './helpers/index.ts'
import type { AuthData, UserDefaultData, UserDwsData } from './models/index.ts'
import { useApiStore } from './stores/apiStore.ts'
import { useDropdownStore } from './stores/dropdownStore.ts'
import { useUserDefaultStore } from './stores/userDefaultStore.ts'
import { useUserDwsStore } from './stores/userDwsStore.ts'

// ================================================================================================
// export set and remove auth data functions
// ================================================================================================
export const setAuthUiKitFrontend = (authData: AuthData) => {
  log(`${ProjectConst.name} AuthData`, authData)
  const apiStore = useApiStore()
  apiStore.setToken(authData.accessToken)
}
export const removeAuthUiKitFrontend = () => {
  log(`${ProjectConst.name} AuthData null`)
  const apiStore = useApiStore()
  apiStore.removeToken()
}

// ================================================================================================
// export set and remove user default data functions
// ================================================================================================
export const setUserDefaultUiKitFrontend = (userData: UserDefaultData) => {
  log(`${ProjectConst.name} UserDefaultData:`, userData)
  const userDefaultStore = useUserDefaultStore()
  userDefaultStore.setUser(userData)
}
export const removeUserDefaultUiKitFrontend = () => {
  log(`${ProjectConst.name} UserDefaultData null`)
  const userStore = useUserDefaultStore()
  userStore.removeUser()
}

// ================================================================================================
// export set and remove user dws data functions
// ================================================================================================
export const setUserDwsUiKitFrontend = (userData: UserDwsData) => {
  log(`${ProjectConst.name} UserDwsData:`, userData)
  const userStore = useUserDwsStore()
  userStore.setUser(userData)
}
export const removeUserDwsUiKitFrontend = () => {
  log(`${ProjectConst.name} UserDwsData null`)
  const userStore = useUserDwsStore()
  userStore.removeUser()
}

// ================================================================================================
// export initMenuBadge
// ================================================================================================
let parentMenuBadgeStore: Store | null = null
export function initMenuBadge(storeInstance: Store) {
  parentMenuBadgeStore = storeInstance
}

// ================================================================================================
// export callBadgeUiKitFrontend
// ================================================================================================
export const callBadgeDropdownUiKitFrontend = async () => {
  log(`${ProjectConst.name} callBadgeDropdownUiKitFrontend`)

  const dropdownStore = useDropdownStore()
  await dropdownStore.callBadge(1)

  if (dropdownStore.badge.status === 'success' && dropdownStore.badge.data) {
    updateBadgeDropdownUiKitFrontend(dropdownStore.badge.data)
  }
}

// ================================================================================================
// export updateBadgeDropdownUiKitFrontend
// ================================================================================================
export function updateBadgeDropdownUiKitFrontend(badgeData: {
  component: number
  helper: number
  model: number
}) {
  if (!parentMenuBadgeStore) {
    throw new Error('initBadgeUiKitFrontend() must be called before callBadgeUiKitFrontend()')
  }

  const parentBadgeCount =
    (badgeData.component ?? 0) + (badgeData.helper ?? 0) + (badgeData.model ?? 0)

  const newBadges = [
    { name: 'dashboard.dropdown', badge: parentBadgeCount },
    { name: 'dashboard.dropdown.component', badge: badgeData.component ?? 0 },
    { name: 'dashboard.dropdown.helper', badge: badgeData.helper ?? 0 },
    { name: 'dashboard.dropdown.model', badge: badgeData.model ?? 0 }
  ]

  const store = parentMenuBadgeStore as Store as any

  newBadges.forEach((nb) => {
    const existing = store.badge.find((b: { name: string; badge: number }) => b.name === nb.name)
    if (existing) {
      existing.badge = nb.badge
    } else {
      store.badge.push(nb)
    }
  })
}

// ================================================================================================
// export FontAwesomeIcon | models | helpers | components
// ================================================================================================
export const FontAwesomeIcon: any = RawFontAwesomeIcon
export * from './models'
export * from './helpers'
export * from './components'
