<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <!-- Main content -->
        <main class="flex-1 flex items-center justify-center p-4 md:p-8">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="space-y-1 mb-6">
                            <h2 class="text-2xl font-bold text-center text-gray-900">Créer un compte</h2>
                            <p class="text-center text-gray-600">Inscrivez-vous pour commencer à utiliser FoundIt!</p>
                        </div>

                        <div class="space-y-4">
                            <!-- Social Login Buttons -->
                            <div class="grid grid-cols-2 gap-4">
                                <button   @click="redirectToSocial('google')"

                                          class="w-full flex items-center justify-center gap-2 py-2 px-4 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                                          type="button"
                                >
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M20.283 10.356h-8.327v3.451h4.792c-.446 2.193-2.313 3.453-4.792 3.453a5.27 5.27 0 0 1-5.279-5.28 5.27 5.27 0 0 1 5.279-5.279c1.259 0 2.397.447 3.29 1.178l2.6-2.599c-1.584-1.381-3.615-2.233-5.89-2.233a8.908 8.908 0 0 0-8.934 8.934 8.907 8.907 0 0 0 8.934 8.934c4.467 0 8.529-3.249 8.529-8.934 0-.528-.081-1.097-.202-1.625z"
                                            fill="#4285F4"
                                        />
                                        <path d="M12.956 16.455h-3.956v-3.956h3.956v3.956z" fill="#34A853" />
                                        <path d="M12.956 8.544h-3.956v3.956h3.956v-3.956z" fill="#FBBC05" />
                                        <path d="M8.999 12.5h3.957v3.955h-3.957z" fill="#EA4335" />
                                    </svg>
                                    Google
                                </button>
                                <button   @click="redirectToSocial('facebook')"

                                          class="w-full flex items-center justify-center gap-2 py-2 px-4 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                                          type="button"
                                >
                                    <Facebook class="h-4 w-4 text-blue-600" />
                                    Facebook
                                </button>
                            </div>

                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <span class="w-full border-t border-gray-300" />
                                </div>
                                <div class="relative flex justify-center text-xs uppercase">
                                    <span class="bg-white px-2 text-gray-500">Ou inscrivez-vous avec</span>
                                </div>
                            </div>

                            <form @submit.prevent="handleSubmit" class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label for="firstName" class="block text-sm font-medium text-gray-700">
                                            Prénom
                                        </label>
                                        <div class="relative">
                                            <User class="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                                            <input
                                                id="firstName"
                                                v-model="firstName"
                                                placeholder="John"
                                                class="pl-10 w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label for="lastName" class="block text-sm font-medium text-gray-700">
                                            Nom
                                        </label>
                                        <div class="relative">
                                            <User class="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                                            <input
                                                id="lastName"
                                                v-model="lastName"
                                                placeholder="Doe"
                                                class="pl-10 w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                                required
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700">
                                        Email
                                    </label>
                                    <div class="relative">
                                        <Mail class="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                                        <input
                                            id="email"
                                            v-model="email"
                                            placeholder="nom@exemple.com"
                                            type="email"
                                            class="pl-10 w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label for="password" class="block text-sm font-medium text-gray-700">
                                        Mot de passe
                                    </label>
                                    <div class="relative">
                                        <Lock class="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                                        <input
                                            id="password"
                                            v-model="password"
                                            :type="showPassword ? 'text' : 'password'"
                                            class="pl-10 pr-10 w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                            placeholder="••••••••"
                                            required
                                        />
                                        <button
                                            type="button"
                                            @click="showPassword = !showPassword"
                                            class="absolute right-3 top-3 text-gray-400 hover:text-gray-600"
                                        >
                                            <EyeOff v-if="showPassword" class="h-4 w-4" />
                                            <Eye v-else class="h-4 w-4" />
                                        </button>
                                    </div>
                                    <div v-if="password" class="mt-2">
                                        <div class="flex items-center space-x-1 mb-1">
                                            <div
                                                v-for="i in 5"
                                                :key="i"
                                                class="h-1 flex-1 rounded-full"
                                                :class="[
                          passwordStrength >= i * 20 ? getPasswordStrengthColorClass() : 'bg-gray-200'
                        ]"
                                            ></div>
                                            <span class="text-xs ml-2" :class="getPasswordStrengthTextClass()">
                        {{ getPasswordStrengthText() }}
                      </span>
                                        </div>
                                        <ul class="text-xs space-y-1 text-gray-500">
                                            <li :class="{ 'text-green-600': password.length >= 8 }">
                                                • Au moins 8 caractères
                                            </li>
                                            <li :class="{ 'text-green-600': /[A-Z]/.test(password) }">
                                                • Au moins une majuscule
                                            </li>
                                            <li :class="{ 'text-green-600': /[0-9]/.test(password) }">
                                                • Au moins un chiffre
                                            </li>
                                            <li :class="{ 'text-green-600': /[^A-Za-z0-9]/.test(password) }">
                                                • Au moins un caractère spécial
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input
                                        type="checkbox"
                                        id="terms"
                                        v-model="termsAccepted"
                                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                                        required
                                    />
                                    <label for="terms" class="text-sm text-gray-700">
                                        J'accepte les
                                        <router-link to="/terms" class="text-purple-600 hover:underline">
                                            conditions d'utilisation
                                        </router-link>
                                        et la
                                        <router-link to="/privacy" class="text-purple-600 hover:underline">
                                            politique de confidentialité
                                        </router-link>
                                    </label>
                                </div>
                                <button
                                    type="submit"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                                    :disabled="isLoading"
                                >
                                    <div v-if="isLoading" class="flex items-center">
                                        <svg
                                            class="animate-spin -ml-1 mr-3 h-4 w-4 text-white"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            ></path>
                                        </svg>
                                        Inscription en cours...
                                    </div>
                                    <div v-else class="flex items-center">
                                        <UserPlus class="mr-2 h-4 w-4" />
                                        S'inscrire
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <p class="text-center text-sm text-gray-600">
                            Déjà inscrit?
                            <router-link to="/login" class="text-purple-600 hover:underline font-medium">
                                Se connecter
                            </router-link>
                        </p>
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
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { Eye, EyeOff, Facebook, Lock, LogIn, Mail, MapPin } from 'lucide-vue-next'
import { useAuthStore } from '@/stores/auth.store'

const authStore = useAuthStore()
const router = useRouter()

// Form state
const firstName = ref('')
const lastName = ref('')
const email = ref('')
const password = ref('')
const showPassword = ref(false)
const termsAccepted = ref(false)
const isLoading = ref(false)
const errorMessage = ref('')

// Social login redirection
const redirectToSocial = (provider) => {
    window.location.href = `http://localhost:8000/api/auth/${provider}/redirect`;
};

// Password strength logic
const passwordStrength = computed(() => {
    let score = 0
    if (password.value.length >= 8) score += 20
    if (/[A-Z]/.test(password.value)) score += 20
    if (/[0-9]/.test(password.value)) score += 20
    if (/[^A-Za-z0-9]/.test(password.value)) score += 20
    if (password.value.length >= 12) score += 20
    return score
})

function getPasswordStrengthText() {
    const strength = passwordStrength.value
    if (strength === 100) return 'Très fort'
    if (strength >= 80) return 'Fort'
    if (strength >= 60) return 'Moyen'
    if (strength >= 40) return 'Faible'
    return 'Très faible'
}

function getPasswordStrengthColorClass() {
    const strength = passwordStrength.value
    if (strength === 100) return 'bg-green-600'
    if (strength >= 80) return 'bg-green-500'
    if (strength >= 60) return 'bg-yellow-400'
    if (strength >= 40) return 'bg-orange-400'
    return 'bg-red-400'
}

function getPasswordStrengthTextClass() {
    const strength = passwordStrength.value
    if (strength >= 80) return 'text-green-600'
    if (strength >= 60) return 'text-yellow-600'
    if (strength >= 40) return 'text-orange-600'
    return 'text-red-600'
}

// Submit logic
const handleSubmit = async () => {
    if (!termsAccepted.value) return

    isLoading.value = true
    errorMessage.value = ''
    
    try {
        const success = await authStore.register({
            firstname: firstName.value,
            lastname: lastName.value,
            email: email.value,
            password: password.value
        })
        
        if (!success && authStore.error) {
            errorMessage.value = authStore.error
        }
    } catch (error) {
        errorMessage.value = 'Erreur lors de l\'inscription. Veuillez réessayer.'
        console.error('Registration error:', error)
    } finally {
        isLoading.value = false
    }
}
</script>
