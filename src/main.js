import App from '@/App.vue'
import en from '@/locales/en'
import fr from '@/locales/fr'
import { registerPlugins } from '@core/utils/plugins'
import { createApp } from 'vue'
import { createI18n } from 'vue-i18n'
import { createVuetify } from 'vuetify'

import '@/assets/styles/styles.scss'
import '@core/scss/template/index.scss'
import '@layouts/styles/index.scss'

import 'vuetify/styles'

const app = createApp(App)
const messages = {
  fr,
  en,
}

let user = {}

try {
  user = JSON.parse(localStorage.getItem('user') || '{}')
} catch (e) {
  localStorage.removeItem('user')
}

const userLang = user.langue || 'en'

const i18n = createI18n({
  legacy: false,
  locale: userLang,
  fallbackLocale: 'en',
  messages,
})

app.use(i18n)

const vuetify = createVuetify()
app.use(vuetify)

// Register plugins
registerPlugins(app)

// Mount vue app
app.mount('#app')
