<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <!-- Main content -->
        <main class="flex-1 flex items-center justify-center p-4 md:p-8">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <!-- Title -->
                        <div class="space-y-1 mb-6 text-center">
                            <h2 class="text-2xl font-bold text-gray-900">Login</h2>
                            <p class="text-gray-600">Enter your credentials to log in</p>
                        </div>

                        <!-- Error Message -->
                        <div v-if="errorMessage" class="mb-4 text-sm text-red-600 text-center">
                            {{ errorMessage }}
                        </div>

                        <form @submit.prevent="handleSubmit" class="space-y-4">
                            <!-- Email -->
                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <div class="relative">
                                    <Mail class="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                                    <input
                                        id="email"
                                        v-model="email"
                                        placeholder="name@example.com"
                                        type="email"
                                        class="pl-10 w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                        required
                                    />
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                    <router-link to="/forgot-password" class="text-xs text-purple-600 hover:underline">Forgot password?</router-link>
                                </div>
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

                            <!-- Remember Me -->
                            <div class="flex items-center space-x-2">
                                <input
                                    type="checkbox"
                                    id="remember"
                                    v-model="rememberMe"
                                    class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                                />
                                <label for="remember" class="text-sm font-medium text-gray-700">Remember me</label>
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
                  Logging in...
                </span>
                                <span v-else class="flex items-center">
                  <LogIn class="mr-2 h-4 w-4" /> Log in
                </span>
                            </button>
                        </form>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-center text-sm text-gray-600">
                        Don't have an account?
                        <router-link to="/register" class="text-purple-600 hover:underline font-medium">Sign up</router-link>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer ... same as before -->
        <footer class="border-t py-4 bg-gray-50">
            <div class="container mx-auto px-4 text-sm text-gray-500 text-center">
                &copy; {{ new Date().getFullYear() }} FoundIt! All rights reserved.
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { Eye, EyeOff, Facebook, Lock, LogIn, Mail, MapPin } from 'lucide-vue-next'
import { useAuthStore } from '@/stores/auth.store'

// Setup the auth store
const authStore = useAuthStore()
const route = useRoute()

// Form state
const showPassword = ref(false)
const email = ref('')
const password = ref('')
const rememberMe = ref(false)
const isLoading = ref(false)
const errorMessage = ref('')

// Check for query parameters on mount
onMounted(() => {
    // Handle registration success message
    if (route.query.registered) {
        errorMessage.value = 'Registration successful! Please check your email for verification instructions.'
    }
    
    // Handle password reset success
    if (route.query.reset === 'success') {
        errorMessage.value = 'Password reset successfully! You can now login with your new password.'
    }

    // Clear any existing errors in the store
    authStore.clearError()
})

const handleSubmit = async () => {
    isLoading.value = true
    errorMessage.value = ''
    
    try {
        // Pass credentials to the auth store
        const success = await authStore.login({
            email: email.value,
            password: password.value,
            remember: rememberMe.value
        })
        
        if (!success && authStore.error) {
            errorMessage.value = authStore.error
        }
    } catch (error) {
        errorMessage.value = 'Login failed. Please try again.'
        console.error('Login error:', error)
    } finally {
        isLoading.value = false
    }
}
</script>
