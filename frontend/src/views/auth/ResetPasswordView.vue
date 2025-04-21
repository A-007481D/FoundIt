<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <header class="w-full border-b bg-white shadow-sm">
      <div class="container mx-auto px-4 flex h-16 items-center">
        <router-link to="/" class="flex items-center gap-2 font-bold">
          <div class="h-8 w-8 rounded-full bg-purple-600 flex items-center justify-center text-white">
            <MapPin class="h-4 w-4" />
          </div>
          <span class="text-xl">FoundIt!</span>
        </router-link>
      </div>
    </header>

    <!-- Main content -->
    <main class="flex-1 flex items-center justify-center p-4 md:p-8">
      <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
          <div class="p-6">
            <div class="space-y-1 mb-6 text-center">
              <h2 class="text-2xl font-bold text-gray-900">Réinitialiser le mot de passe</h2>
              <p class="text-gray-600">Entrez votre nouveau mot de passe</p>
            </div>

            <div v-if="errorMessage" class="mb-4 text-sm text-red-600 text-center">
              {{ errorMessage }}
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-4">
              <!-- Password -->
              <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                <div class="relative">
                  <Lock class="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                  <input
                    id="password"
                    v-model="password"
                    :type="showPassword ? 'text' : 'password'"
                    placeholder="••••••••"
                    class="pl-10 pr-10 w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                    required
                  />
                  <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                    <EyeOff v-if="showPassword" class="h-4 w-4" />
                    <Eye v-else class="h-4 w-4" />
                  </button>
                </div>
              </div>

              <!-- Confirm Password -->
              <div class="space-y-2">
                <label for="passwordConfirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                <div class="relative">
                  <Lock class="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                  <input
                    id="passwordConfirmation"
                    v-model="passwordConfirmation"
                    :type="showPasswordConfirmation ? 'text' : 'password'"
                    placeholder="••••••••"
                    class="pl-10 pr-10 w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                    required
                  />
                  <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                    <EyeOff v-if="showPasswordConfirmation" class="h-4 w-4" />
                    <Eye v-else class="h-4 w-4" />
                  </button>
                </div>
              </div>

              <!-- Submit -->
              <button
                type="submit"
                :disabled="isLoading"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
              >
                <span v-if="isLoading" class="flex items-center">
                  <svg class="animate-spin mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4h-4z" />
                  </svg>
                  Réinitialisation...
                </span>
                <span v-else class="flex items-center">
                  <RefreshCw class="mr-2 h-4 w-4" /> Réinitialiser le mot de passe
                </span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="border-t py-4 bg-gray-50">
      <div class="container mx-auto px-4 text-sm text-gray-500 text-center">
        © {{ new Date().getFullYear() }} FoundIt! Tous droits réservés.
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { Eye, EyeOff, Lock, RefreshCw, MapPin } from 'lucide-vue-next'
import { useAuthStore } from '@/stores/auth.store'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

// Form state
const email = ref('')
const token = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const showPassword = ref(false)
const showPasswordConfirmation = ref(false)
const isLoading = ref(false)
const errorMessage = ref('')

onMounted(() => {
  // Get email and token from query params
  email.value = route.query.email || ''
  token.value = route.query.token || ''
  
  if (!email.value || !token.value) {
    errorMessage.value = 'Lien de réinitialisation invalide. Veuillez demander un nouveau lien.'
  }
})

const handleSubmit = async () => {
  if (password.value !== passwordConfirmation.value) {
    errorMessage.value = 'Les mots de passe ne correspondent pas.'
    return
  }
  
  isLoading.value = true
  errorMessage.value = ''
  
  try {
    const success = await authStore.resetPassword({
      email: email.value,
      token: token.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value
    })
    
    if (!success && authStore.error) {
      errorMessage.value = authStore.error
    }
  } catch (error) {
    errorMessage.value = 'Erreur lors de la réinitialisation du mot de passe. Veuillez réessayer.'
    console.error('Password reset error:', error)
  } finally {
    isLoading.value = false
  }
}
</script>
