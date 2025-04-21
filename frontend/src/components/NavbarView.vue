<template>
  <nav class="bg-white shadow-sm border-b">
    <div class="container mx-auto px-4">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <router-link to="/" class="flex items-center gap-2 font-bold">
            <div class="h-8 w-8 rounded-full bg-purple-600 flex items-center justify-center text-white">
              <MapPin class="h-4 w-4" />
            </div>
            <span class="text-xl">FoundIt!</span>
          </router-link>
        </div>
        
        <div class="flex items-center space-x-4">
          <template v-if="isAuthenticated">
            <router-link 
              to="/discover" 
              class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium"
              active-class="text-purple-600 font-semibold"
            >
              Découvrir
            </router-link>
            <router-link 
              to="/matches" 
              class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium"
              active-class="text-purple-600 font-semibold"
            >
              Matches
            </router-link>
            <div class="relative ml-3" ref="dropdownRef">
              <button 
                @click="showDropdown = !showDropdown" 
                class="flex items-center text-sm rounded-full focus:outline-none"
              >
                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                  <User class="h-4 w-4 text-gray-600" />
                </div>
              </button>
              
              <div 
                v-if="showDropdown" 
                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
              >
                <router-link 
                  to="/profile" 
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  Profil
                </router-link>
                <button 
                  @click="logout" 
                  class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  Déconnexion
                </button>
              </div>
            </div>
          </template>
          
          <template v-else>
            <router-link 
              to="/login" 
              class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium"
            >
              Se connecter
            </router-link>
            <router-link 
              to="/register" 
              class="bg-purple-600 text-white hover:bg-purple-700 px-3 py-2 rounded-md text-sm font-medium"
            >
              S'inscrire
            </router-link>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '@/stores/auth.store'
import { MapPin, User } from 'lucide-vue-next'

const authStore = useAuthStore()
const showDropdown = ref(false)
const dropdownRef = ref(null)

const isAuthenticated = computed(() => authStore.isAuthenticated)

const logout = () => {
  authStore.logout()
  showDropdown.value = false
}

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    showDropdown.value = false
  }
}

// Add event listener when component is mounted
onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

// Remove event listener when component is unmounted
onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>
