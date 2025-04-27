<template>
  <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
    <div class="container mx-auto px-6 md:px-20 flex h-16 items-center justify-between">
      <!-- Logo & Links -->
      <div class="flex items-center gap-6">
        <router-link to="/" class="flex items-center gap-2 font-bold">
          <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-white">
            <!-- pin icon -->
            <MapPin class="h-4 w-4" />
          </div>
          <span class="text-xl">FoundIt!</span>
        </router-link>

        <nav v-if="showNavLinks" class="hidden md:flex items-center gap-6">
          <router-link
              v-for="item in navItems"
              :key="item.name"
              :to="item.to"
              :class="[
              'text-sm font-medium hover:text-primary transition-colors',
              route.path === item.to ? 'text-primary' : 'text-foreground'
            ]"
          >
            {{ item.name }}
          </router-link>
        </nav>
      </div>

      <!-- Right-side controls -->
      <div class="flex items-center gap-4">
        <!-- Search (md+) - only shown when authenticated or on landing page -->
        <div v-if="showSearch" class="relative hidden md:block">
          <input
              v-model="search"
              @keyup.enter="onSearch"
              type="search"
              placeholder="Search..."
              class="w-[200px] lg:w-[300px] pl-8 py-2 rounded-full bg-muted border-none focus:ring-2 focus:ring-primary"
          />
          <svg class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" fill="none"
               stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <circle cx="11" cy="11" r="8"></circle>
            <path d="m21 21-4.3-4.3"></path>
          </svg>
        </div>

        <!-- Authenticated -->
        <template v-if="isAuthenticated">
          <!-- Chat -->
          <router-link 
            to="/chat" 
            class="relative h-9 w-9 rounded-full flex items-center justify-center hover:bg-muted/50 transition-colors text-primary"
            :class="{ 'text-primary': route.path.startsWith('/chat') }"
          >
            <MessageSquare class="h-5 w-5" />
            <span 
              v-if="unreadMessagesCount > 0" 
              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"
            >
              {{ unreadMessagesCount }}
            </span>
          </router-link>

          <!-- Notifications dropdown -->
          <div class="relative" ref="notifDropdownRef">
            <button @click="toggleNotif" class="relative h-9 w-9 rounded-full flex items-center justify-center hover:bg-muted/50 transition-colors">
              <BellIcon class="h-5 w-5"/>
              <span v-if="unreadCount" class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-primary text-[10px] text-primary-foreground">
                {{ unreadCount }}
              </span>
            </button>
            <transition name="fade">
              <div v-show="showNotif" class="absolute right-0 mt-2 w-80 rounded-md bg-background shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                <div class="p-4 text-sm">
                  <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold">Notifications</h3>
                    <button @click="markAllRead" class="text-xs text-primary hover:underline">Mark all as read</button>
                  </div>
                  <div class="py-2 text-center text-muted-foreground" v-if="!hasNotifications">
                    No new notifications
                  </div>
                  <div v-else>
                    <div v-for="notif in notifications" :key="notif.id" class="border-b py-3 cursor-pointer hover:bg-muted/10" @click="markNotificationRead(notif.id)">
                      <div class="flex gap-3">
                        <div class="h-8 w-8 rounded-full bg-muted flex items-center justify-center text-muted-foreground">
                          <MapPin class="h-4 w-4" />
                        </div>
                        <div>
                          <p v-if="notif.type === 'App\\Notifications\\NewMatchFound'">Match found for your item.</p>
                          <p class="text-xs text-muted-foreground mt-1">{{ new Date(notif.created_at).toLocaleString() }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </transition>
          </div>

          <!-- User menu -->
          <div class="relative" ref="dropdownRef">
            <button @click="toggleUserMenu" class="h-8 w-8 rounded-full hover:ring-2 hover:ring-muted">
              <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                {{ userInitials }}
              </div>
            </button>
            <transition name="fade">
              <div v-show="showDropdown" class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                <div class="py-1">
                  <router-link to="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Profile
                  </router-link>
                  <router-link to="/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Settings
                  </router-link>
                  <router-link v-if="isAdmin" to="/admin" class="block px-4 py-2 text-sm text-primary font-medium hover:bg-gray-100">
                    Admin Dashboard
                  </router-link>
                  <div class="border-t my-1"></div>
                  <button
                    @click="logout"
                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                  >
                    Log out
                  </button>
                </div>
              </div>
            </transition>
          </div>
        </template>

        <!-- Guest -->
        <template v-else>
          <router-link to="/login" class="rounded-md font-medium bg-white border-2 border-primary text-primary px-4 py-2 hover:bg-primary/10">
            Se connecter
          </router-link>
          <router-link to="/register" class="rounded-md text-white font-medium bg-primary hover:bg-primary/90 px-4 py-2">
            S'inscrire
          </router-link>
        </template>

        <!-- Mobile toggle -->
        <button class="border rounded-md p-2 md:hidden" @click="showMobile = !showMobile">
          <MenuIcon class="h-5 w-5"/>
        </button>
      </div>
    </div>

    <!-- Mobile menu -->
    <transition name="slide-fade">
      <div v-show="showMobile" class="container mx-auto px-4 py-4 md:hidden">
        <nav class="flex flex-col space-y-4">
          <router-link
              v-for="item in navItems"
              :key="item.name + '-mobile'"
              :to="item.to"
              class="flex items-center gap-2 text-sm font-medium hover:text-primary transition-colors"
          >
            {{ item.name }}
          </router-link>
          <div class="border-t my-2"></div>
          <template v-if="isAuthenticated">
            <router-link to="/profile" class="flex items-center gap-2 text-sm font-medium hover:text-primary">Profile</router-link>
            <button @click="logout" class="text-sm font-medium text-red-500">Log out</button>
          </template>
          <template v-else>
            <router-link to="/login" class="text-sm font-medium hover:text-primary">Se connecter</router-link>
            <router-link to="/register" class="text-sm font-medium hover:text-primary">S'inscrire</router-link>
          </template>
        </nav>
      </div>
    </transition>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'
import { useChatStore } from '@/stores/chat.store'
import axios from 'axios'
import authHeader from '@/services/auth-header'
import { MapPin, User, Bell as BellIcon, Menu as MenuIcon, MessageSquare } from 'lucide-vue-next'

// stores
const authStore = useAuthStore()
const chatStore = useChatStore()

// state
const showMobile = ref(false)
const showNotif = ref(false)
const showDropdown = ref(false)
const search = ref('')
const notifDropdownRef = ref(null)
const dropdownRef = ref(null)
const notifications = ref([])

// router hooks
const router = useRouter()
const route = useRoute()

// computed properties
const isAuthenticated = computed(() => authStore.isAuthenticated)
const user = computed(() => authStore.user)
const isAdmin = computed(() => authStore.isAdmin)
const showNavLinks = computed(() => isAuthenticated.value || route.path === '/')
const showSearch = computed(() => isAuthenticated.value || route.path === '/')
const navItems = computed(() => {
  if (isAuthenticated.value) {
    return [
      { name: 'Discover', to: '/discover' },
      { name: 'Recent Matches', to: '/matches' },
      { name: 'My Items', to: '/my-items' },
    ];
  } else {
    return [
      { name: 'Home', to: '/' },
      { name: 'How it works', to: '/#how-it-works' },
      { name: 'FAQ', to: '/#faq' },
    ];
  }
})

const unreadCount = computed(() => notifications.value.length)

const hasNotifications = computed(() => notifications.value.length > 0)

const userInitials = computed(() => {
  if (!authStore.user) return ''
  
  // Extract initials from user's name if available
  const user = authStore.user
  if (user.name) {
    const nameParts = user.name.split(' ')
    if (nameParts.length >= 2) {
      return (nameParts[0][0] + nameParts[1][0]).toUpperCase()
    } else if (nameParts.length === 1 && nameParts[0].length > 0) {
      return nameParts[0][0].toUpperCase()
    }
  }
  
  // Fallback to first letter of email
  if (user.email) {
    return user.email[0].toUpperCase()
  }
  
  return '?'
})

const unreadMessagesCount = computed(() => {
  return chatStore.conversations.reduce((total, conv) => total + conv.unread_count, 0)
})

// actions
const toggleNotif = () => {
  showNotif.value = !showNotif.value
  if (showNotif.value) showDropdown.value = false
}

const toggleUserMenu = () => {
  showDropdown.value = !showDropdown.value
  if (showDropdown.value) showNotif.value = false
}

const onSearch = () => {
  if (!search.value.trim()) return
  router.push({ path: '/search', query: { q: search.value } })
  search.value = ''
}

const logout = async () => {
  showDropdown.value = false
  await authStore.logout()

  // Force a navigation to login page after logout
  await nextTick()
  router.replace('/login')
}

// Mark a single notification as read
const markNotificationRead = async (id) => {
  try {
    await axios.post(
      `${import.meta.env.VITE_API_URL}/notifications/${id}/read`,
      {},
      { headers: { ...authHeader(), 'Accept': 'application/json' } }
    )
    notifications.value = notifications.value.filter(n => n.id !== id)
  } catch (err) {
    console.error('Error marking notification read:', err)
  }
}

// Mark all notifications as read
const markAllRead = async () => {
  try {
    await axios.post(
      `${import.meta.env.VITE_API_URL}/notifications/read-all`,
      {},
      { headers: { ...authHeader(), 'Accept': 'application/json' } }
    )
    notifications.value = []
  } catch (err) {
    console.error('Error marking all notifications read:', err)
  }
}

// Close dropdowns when clicking outside
const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    showDropdown.value = false
  }
  
  if (notifDropdownRef.value && !notifDropdownRef.value.contains(event.target)) {
    showNotif.value = false
  }
}

// lifecycle hooks
onMounted(async () => {
  if (isAuthenticated.value) {
    // fetch chat conversations
    await chatStore.fetchConversations()
    // fetch existing notifications
    try {
      const { data } = await axios.get(
        `${import.meta.env.VITE_API_URL}/notifications`,
        { headers: { ...authHeader(), 'Accept': 'application/json' } }
      )
      notifications.value = data
    } catch (err) {
      console.error('Notifications fetch error:', err)
    }
  }

  document.addEventListener('click', handleClickOutside)
  
  if (isAuthenticated.value) {
    window.Echo.private(`App.Models.User.${user.value.id}`)
      .notification((notif) => {
        notifications.value.unshift(notif)
      })
  }
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.3s ease;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateY(-10px);
  opacity: 0;
}
</style>
