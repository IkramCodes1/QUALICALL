import App from '@/App.vue'
import { registerPlugins } from '@core/utils/plugins'
import { createApp } from 'vue'
import { createVuetify } from 'vuetify'

import '@/assets/styles/styles.scss'
import '@core/scss/template/index.scss'
import '@layouts/styles/index.scss'

import 'vuetify/styles'

const app = createApp(App)

const vuetify = createVuetify()
app.use(vuetify)

// Register plugins
registerPlugins(app)

// Mount vue app
app.mount('#app')
