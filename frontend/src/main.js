
import './assets/main.css'
import './assets/transition.css'

import { createApp }  from 'vue'
import { createPinia } from 'pinia'
import App            from './App.vue'
import router         from './router'
import Vue3Toastify, { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

const app = createApp(App)

const pinia = createPinia()
app.use(pinia)

app.use(router)

app.use(Vue3Toastify, {
  autoClose: 3000,
  position: toast.POSITION.TOP_RIGHT
})

app.mount('#app')
