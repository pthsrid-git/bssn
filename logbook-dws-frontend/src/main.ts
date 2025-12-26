import './assets/css/index.css'

import { FontAwesomeIcon } from '@bssn/ui-kit-frontend'
import { createPinia } from 'pinia'
import { createApp } from 'vue'

import { setAuthLogbookDwsFrontend, setUserDwsLogbookDwsFrontend } from '.'
import App from './App.vue'
import { getTestAuthData, getTestUserDwsData } from './helpers/test'
import router from './router'

const app = createApp(App)

app.use(createPinia())

app.use(router)

app.component('font-awesome-icon', FontAwesomeIcon)

setAuthLogbookDwsFrontend(getTestAuthData())
setUserDwsLogbookDwsFrontend(getTestUserDwsData())

app.mount('#app')
