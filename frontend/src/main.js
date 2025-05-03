import './assets/main.css'
import './assets/transition.css'

import { createApp }  from 'vue'
import { createPinia } from 'pinia'
import { createI18n } from 'vue-i18n'
import App            from './App.vue'
import router         from './router'
import Vue3Toastify, { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import authHeader from '@/services/auth-header'
// Import our custom axios interceptor instead of axios directly
import axios from './interceptors/axios'

// Axios is now configured in interceptors/axios.js with session termination handling

// Apply auth token to all Axios requests
axios.defaults.headers.common = {
  ...axios.defaults.headers.common,
  ...authHeader(),
};

// Import language files
import en from './lang/en.json'
import fr from './lang/fr.json'

// Create i18n instance
const i18n = createI18n({
  legacy: false, // Use Composition API
  locale: 'en', // Default language
  fallbackLocale: 'en',
  messages: {
    en,
    fr
  }
})

const app = createApp(App)

const pinia = createPinia()
app.use(pinia)

app.use(router)

app.use(Vue3Toastify, {
  autoClose: 3000,
  position: toast.POSITION.TOP_RIGHT
})

app.use(i18n)

// Initialize Echo for real-time notifications
window.Pusher = Pusher
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
  forceTLS: true,
  authEndpoint: `${import.meta.env.VITE_API_URL}/broadcasting/auth`,
  auth: {
    headers: {
      ...authHeader(),
      'Accept': 'application/json'
    }
  },
  authorizer: (channel, options) => ({
    authorize: (socketId, callback) => {
      axios.post(options.authEndpoint, {
        socket_id: socketId,
        channel_name: channel.name
      }, {
        headers: {
          ...authHeader(),
          'Accept': 'application/json'
        }
      })
      .then(response => callback(null, response.data))
      .catch(error => {
        console.error('Pusher auth error:', error)
        callback(error)
      })
    }
  })
})

import { useNotificationStore } from '@/stores/notification.store'
const notificationStore = useNotificationStore()
notificationStore.fetchNotifications()
if (notificationStore.notifications) {
  const user = JSON.parse(localStorage.getItem('user'))
  if (user && user.id) {
    window.Echo.private(`App.Models.User.${user.id}`)
      .notification(notification => {
        notificationStore.addNotification(notification)
      })
  }
}

app.mount('#app')
