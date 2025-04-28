import './assets/main.css'
import './assets/transition.css'

import { createApp }  from 'vue'
import { createPinia } from 'pinia'
import App            from './App.vue'
import router         from './router'
import Vue3Toastify, { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import authHeader from '@/services/auth-header'
import axios from 'axios'

const app = createApp(App)

const pinia = createPinia()
app.use(pinia)

app.use(router)

app.use(Vue3Toastify, {
  autoClose: 3000,
  position: toast.POSITION.TOP_RIGHT
})

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
