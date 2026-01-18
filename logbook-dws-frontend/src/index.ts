import './assets/css/style.css'

import { type AuthData, type UserDwsData } from '@bssn/ui-kit-frontend'

import ProjectConst from './const.ts'
import { log } from './helpers/custom.ts'
import { logbookRuangKerjaRoutes } from './router/ruang-kerja'
import { logbookRuangPribadiKepagawaianSubRoute } from './router/ruang-pribadi/kepegawaianSub.ts'
import { useApiStore } from './stores/apiStore.ts'
import { useUserDwsStore } from './stores/userDwsStore.ts'

// ================================================================================================
// export set and remove auth data functions
// ================================================================================================
export const setAuthLogbookDwsFrontend = (authData: AuthData) => {
  log(`${ProjectConst.name} AuthData`, authData)
  const apiStore = useApiStore()
  apiStore.setToken(authData.accessToken)
}
export const removeAuthLogbookDwsFrontend = () => {
  log(`${ProjectConst.name} AuthData null`)
  const apiStore = useApiStore()
  apiStore.removeToken()
}

// ================================================================================================
// export set and remove user dws data functions
// ================================================================================================
export const setUserDwsLogbookDwsFrontend = (userData: UserDwsData) => {
  log(`${ProjectConst.name} UserDwsData:`, userData)
  const userStore = useUserDwsStore()
  userStore.setUser(userData)
}
export const removeUserDwsLogbookDwsFrontend = () => {
  log(`${ProjectConst.name} UserDwsData null`)
  const userStore = useUserDwsStore()
  userStore.removeUser()
}

// ================================================================================================
// export router
// ================================================================================================
export { logbookRuangPribadiKepagawaianSubRoute, logbookRuangKerjaRoutes }
