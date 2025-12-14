import './assets/css/index.css'
import './font-awesome.ts'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { createPinia } from 'pinia'
import { createApp } from 'vue'

import App from './App.vue'
import { getTestAuthData, getTestUserDefaultData, getTestUserDwsData } from './helpers/test.ts'
import {
  callBadgeDropdownUiKitFrontend,
  initMenuBadge,
  setAuthUiKitFrontend,
  setUserDefaultUiKitFrontend,
  setUserDwsUiKitFrontend
} from './index.ts'
import router from './router'
import { useMenuBadgeStore } from './stores/menuBadgeStore.ts'

const app = createApp(App)

app.use(createPinia())

app.use(router)

app.component('font-awesome-icon', FontAwesomeIcon)

setAuthUiKitFrontend(getTestAuthData()) // setAuth AuthData
setUserDefaultUiKitFrontend(getTestUserDefaultData()) // setUser UserDefaultData
setUserDwsUiKitFrontend(getTestUserDwsData()) // setUser UserDwsData

const menuBadgeStore = useMenuBadgeStore()
initMenuBadge(menuBadgeStore)
callBadgeDropdownUiKitFrontend()

app.mount('#app')
