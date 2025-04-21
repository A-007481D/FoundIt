<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <!-- Main content -->
        <main class="flex-1 flex items-center justify-center p-4 md:p-8">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="mb-4">
                            <router-link to="/login" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                                <ArrowLeft class="h-4 w-4 mr-1" />
                            </router-link>
                        </div>
                        <div class="space-y-1 mb-6">
                            <h2 class="text-2xl font-bold text-center text-gray-900">Mot de passe oublié</h2>
                            <p class="text-center text-gray-600">Entrez votre adresse email pour réinitialiser votre mot de passe</p>
                        </div>

                        <div v-if="isSubmitted" class="text-center space-y-4">
                            <div class="mx-auto h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 mb-4">
                                <Mail class="h-6 w-6" />
                            </div>
                            <h3 class="text-lg font-medium">Email envoyé!</h3>
                            <p class="text-gray-600">
                                Si un compte existe avec l'adresse <strong>{{ email }}</strong>, vous recevrez un email avec les
                                instructions pour réinitialiser votre mot de passe.
                            </p>
                            <p class="text-sm text-gray-500 mt-2">
                                N'oubliez pas de vérifier votre dossier de spam si vous ne trouvez pas l'email.
                            </p>
                            <router-link to="/login" class="mt-4 inline-block text-purple-600 hover:underline">
                                Retour à la page de connexion
                            </router-link>
                        </div>

                        <form v-else @submit.prevent="handleSubmit" class="space-y-4">
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
                                    Envoi en cours...
                                </div>
                                <div v-else class="flex items-center">
                                    <Send class="mr-2 h-4 w-4" />
                                    Envoyer les instructions
                                </div>
                            </button>
                        </form>
                    </div>
                    <div v-if="!isSubmitted" class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <p class="text-center text-sm text-gray-600">
                            Vous vous souvenez de votre mot de passe?
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

<script>
import { ref } from 'vue'
import { ArrowLeft, Mail, MapPin, Send } from 'lucide-vue-next'

export default {
    name: 'ForgotPassword',
    components: {
        ArrowLeft,
        Mail,
        MapPin,
        Send
    },
    setup() {
        const email = ref('')
        const isLoading = ref(false)
        const isSubmitted = ref(false)

        const handleSubmit = () => {
            isLoading.value = true

            // Simulate API call
            setTimeout(() => {
                isLoading.value = false
                isSubmitted.value = true
            }, 1500)
        }

        return {
            email,
            isLoading,
            isSubmitted,
            handleSubmit
        }
    }
}
</script>
