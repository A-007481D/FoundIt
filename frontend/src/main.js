
import './assets/main.css'

import { createApp }  from 'vue'
import { createPinia } from 'pinia'
import App            from './App.vue'
import router         from './router'   // your router/index.js

// 1️⃣ Create your Vue application instance
const app = createApp(App)

// 2️⃣ Create a Pinia instance and install it BEFORE any store is used
const pinia = createPinia()
app.use(pinia)            // ← without this, calls to useUserStore() will fail :contentReference[oaicite:0]{index=0}

// 3️⃣ Install the router plugin
app.use(router)

// 4️⃣ Mount to the DOM
app.mount('#app')
