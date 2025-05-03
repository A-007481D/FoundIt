/**
 * Authentication Store
 * Manages user authentication state and operations
 * 
 * @module stores/auth
 */

import { defineStore } from 'pinia';
import { reactive, computed } from 'vue';
import { auth } from '@/services/api/auth';
import router from '@/router';

export const useAuthStore = defineStore('auth', () => {
  // State
  const state = reactive({
    user: JSON.parse(localStorage.getItem('user')) || null,
    /**
     * Current authenticated user
     * @type {Object|null}
     */
    token: localStorage.getItem('token') || null,
    /**
     * Authentication token
     * @type {String|null}
     */
    loading: false,
    error: null,
    verificationStatus: localStorage.getItem('verification_status') || null,
    /**
     * Email verification status
     * @type {string|null}
     */
  });

  // Getters
  const isAuthenticated = computed(() => !!state.token);
  /**
   * Check if user is authenticated
   * @returns {boolean}
   */
  const isAdmin = computed(() => state.user?.role === 'admin');
  /**
   * Check if user is admin
   * @returns {boolean}
   */
  const userFullName = computed(() => 
    state.user ? `${state.user.firstname} ${state.user.lastname}` : ''
  );
  /**
   * Get user's full name
   * @returns {string}
   */
  const needsVerification = computed(() => state.verificationStatus === 'pending');
  /**
   * Check if email verification is needed
   * @returns {boolean}
   */

  // Actions
  /**
   * Authenticate user with credentials
   * @param {Object} credentials - User credentials
   * @param {string} credentials.email - User's email
   * @param {string} credentials.password - User's password
   * @returns {Promise<boolean>} - Success status
   */
  async function login(credentials) {
    try {
      state.loading = true;
      state.error = null;

      const response = await auth.login(credentials);
      
      if (response && response.token) {
        state.token = response.token;
        state.user = response.user;
        localStorage.setItem('token', response.token);
        localStorage.setItem('user', JSON.stringify(response.user));
        
        // Get the redirect path from the router query or default to Discover
        const redirectPath = router.currentRoute.value.query.redirect || { name: 'Discover' };
        
        // Use router.replace instead of push to avoid back button issues
        router.replace(redirectPath);
        return true;
      } else {
        throw new Error('No token received from server');
      }
    } catch (err) {
      console.error('Login error details:', err);
      if (err.response && err.response.data) {
        if (err.response.data.errors) {
          if (err.response.data.errors.account) {
            state.error = err.response.data.errors.account[0];
          } else if (err.response.data.errors.email) {
            state.error = err.response.data.errors.email[0];
          } else if (err.response.data.errors.password) {
            state.error = err.response.data.errors.password[0];
          } else {
            state.error = 'Invalid credentials';
          }
        } else {
          state.error = err.response.data.message || 'Authentication failed';
        }
      } else {
        state.error = 'Failed to connect to server. Please check your network connection.';
      }
      state.loading = false;
      return false;
    } finally {
      state.loading = false;
    }
  }

  /**
   * Logout the current user
   * @returns {Promise<void>}
   */
  async function logout() {
    try {
      state.loading = true;
      state.error = null;

      await auth.logout();
      
      // Clear local storage
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      localStorage.removeItem('verification_status');
      
      // Reset store state
      state.token = null;
      state.user = null;
      state.verificationStatus = null;
      
      // Redirect to login
      router.push('/login');
      return true;
    } catch (err) {
      state.error = err.response?.data?.message || 'Failed to logout';
      state.loading = false;
      return false;
    }
  }

  /**
   * Clear any error message
   */
  function clearError() {
    state.error = null;
  }

  return {
    // State
    user: computed(() => state.user),
    token: computed(() => state.token),
    loading: computed(() => state.loading),
    error: computed(() => state.error),
    verificationStatus: computed(() => state.verificationStatus),
    
    // Getters
    isAuthenticated,
    isAdmin,
    userFullName,
    needsVerification,
    
    // Actions
    login,
    logout,
    clearError
  };
});
