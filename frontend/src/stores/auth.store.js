// src/stores/auth.store.js
import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { authService } from '@/services/auth.service';
import router from '@/router';

export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref(JSON.parse(localStorage.getItem('user')) || null);
  const token = ref(localStorage.getItem('token') || null);
  const loading = ref(false);
  const error = ref(null);

  // Getters (computed properties)
  const isAuthenticated = computed(() => !!token.value);
  const isAdmin = computed(() => user.value?.role === 'admin');
  const userFullName = computed(() => 
    user.value ? `${user.value.firstname} ${user.value.lastname}` : ''
  );

  // Actions
  async function login(credentials) {
    loading.value = true;
    error.value = null;
    
    try {
      const response = await authService.login(credentials);
      token.value = response.token;
      user.value = response.user;
      
      // Navigate to the discover page after successful login
      router.push({ name: 'Discover' });
      return true;
    } catch (err) {
      error.value = err.message || 'Authentication failed';
      return false;
    } finally {
      loading.value = false;
    }
  }

  async function register(userData) {
    loading.value = true;
    error.value = null;
    
    try {
      await authService.register(userData);
      // Don't automatically login after registration since email verification may be required
      router.push({ 
        name: 'Login', 
        query: { registered: 'true' } 
      });
      return true;
    } catch (err) {
      error.value = err.message || 'Registration failed';
      return false;
    } finally {
      loading.value = false;
    }
  }

  async function logout() {
    try {
      await authService.logout();
    } finally {
      // Clear state regardless of API response
      token.value = null;
      user.value = null;
      router.push({ name: 'Login' });
    }
  }

  async function verifyEmail(id, hash) {
    loading.value = true;
    error.value = null;
    
    try {
      await authService.verifyEmail(id, hash);
      return true;
    } catch (err) {
      error.value = err.message || 'Email verification failed';
      return false;
    } finally {
      loading.value = false;
    }
  }

  async function forgotPassword(email) {
    loading.value = true;
    error.value = null;
    
    try {
      await authService.forgotPassword(email);
      return true;
    } catch (err) {
      error.value = err.message || 'Password reset request failed';
      return false;
    } finally {
      loading.value = false;
    }
  }

  async function resetPassword(resetData) {
    loading.value = true;
    error.value = null;
    
    try {
      await authService.resetPassword(resetData);
      router.push({ name: 'Login', query: { reset: 'success' } });
      return true;
    } catch (err) {
      error.value = err.message || 'Password reset failed';
      return false;
    } finally {
      loading.value = false;
    }
  }

  function clearError() {
    error.value = null;
  }

  return {
    // State
    user,
    token,
    loading,
    error,
    
    // Getters
    isAuthenticated,
    isAdmin,
    userFullName,
    
    // Actions
    login,
    register,
    logout,
    verifyEmail,
    forgotPassword,
    resetPassword,
    clearError
  };
});
