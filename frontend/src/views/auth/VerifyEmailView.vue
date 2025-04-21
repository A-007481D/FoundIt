<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <main class="flex-1 flex items-center justify-center p-4 md:p-8">
      <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
          <div class="p-6">
            <div class="space-y-1 mb-6 text-center">
              <h2 class="text-2xl font-bold text-gray-900">Vérification de l'email</h2>
              <p class="text-gray-600">Vérifiez votre email pour continuer.</p>
            </div>

            <div v-if="loading" class="text-center py-8">
              <div class="animate-spin mx-auto h-8 w-8 border-4 border-purple-600 border-t-transparent rounded-full"></div>
              <p class="mt-4 text-gray-600">Vérification en cours...</p>
            </div>

            <div v-else-if="error" class="text-center py-8">
              <div class="text-red-600 mb-2">
                <p>{{ error }}</p>
              </div>
              <button 
                @click="verifyEmail"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
              >
                Réessayer
              </button>
            </div>

            <div v-else class="text-center py-8">
              <p class="text-green-600 mb-2">
                Email vérifié avec succès !
              </p>
              <router-link 
                to="/login" 
                class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
              >
                Se connecter
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="border-t py-4 bg-gray-50">
      <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
          <p class="text-sm text-gray-500">© {{ new Date().getFullYear() }} FoundIt! Tous droits réservés.</p>
          <div class="flex items-center gap-4 text-sm text-gray-500">
            <router-link to="/terms" class="hover:text-gray-700 transition-colors">
              Conditions d'utilisation
            </router-link>
            <router-link to="/privacy" class="hover:text-gray-700 transition-colors">
              Confidentialité
            </router-link>
            <router-link to="/contact" class="hover:text-gray-700 transition-colors">
              Contact
            </router-link>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'
import { MapPin } from 'lucide-vue-next'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const loading = ref(true)
const error = ref(null)

const verifyEmail = async () => {
  loading.value = true
  error.value = null
  
  try {
    const id = route.params.id
    const hash = route.params.hash
    
    if (!id || !hash) {
      throw new Error('Paramètres manquants')
    }
    
    await authStore.verifyEmail(id, hash)
    loading.value = false
  } catch (err) {
    loading.value = false
    error.value = err.message || 'Erreur lors de la vérification de l\'email'
    console.error('Email verification error:', err)
  }
}

onMounted(() => {
  verifyEmail()
})
</script>
