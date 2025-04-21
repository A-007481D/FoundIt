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
  const verificationStatus = ref(localStorage.getItem('verification_status') || null);

  // Getters (computed properties)
  const isAuthenticated = computed(() => !!token.value);
  const isAdmin = computed(() => user.value?.role === 'admin');
  const userFullName = computed(() => 
    user.value ? `${user.value.firstname} ${user.value.lastname}` : ''
  );
  const needsVerification = computed(() => verificationStatus.value === 'pending');

  // Actions
  async function login(credentials) {
    loading.value = true;
    error.value = null;
    
    try {
      const response = await authService.login(credentials);
      
      if (response.token) {
        token.value = response.token;
        user.value = response.user;
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
      if (err.response && err.response.data) {
        // Handle specific error messages from the backend
        if (err.response.data.errors && err.response.data.errors.email) {
          error.value = err.response.data.errors.email[0];
          
          // Check if the error is about email verification
          if (error.value.includes('not verified')) {
            verificationStatus.value = 'pending';
            localStorage.setItem('verification_status', 'pending');
          }
        } else if (err.response.data.message) {
          error.value = err.response.data.message;
        } else {
          error.value = 'Authentication failed';
        }
      } else {
        error.value = err.message || 'Authentication failed';
      }
      return false;
    } finally {
      loading.value = false;
    }
  }

  async function register(userData) {
    loading.value = true;
    error.value = null;
    
    try {
      const response = await authService.register(userData);
      
      // Store verification status
      verificationStatus.value = 'pending';
      localStorage.setItem('verification_status', 'pending');
      
      // Store the email for later use
      localStorage.setItem('pending_verification_email', userData.email);
      
      // Don't automatically login after registration since email verification is required
      router.push({ 
        name: 'Login', 
        query: { registered: 'true' } 
      });
      return true;
    } catch (err) {
      if (err.response && err.response.data) {
        if (err.response.data.errors) {
          // Format validation errors
          const validationErrors = Object.values(err.response.data.errors).flat();
          error.value = validationErrors.join(', ');
        } else if (err.response.data.message) {
          error.value = err.response.data.message;
        } else {
          error.value = 'Registration failed';
        }
      } else {
        error.value = err.message || 'Registration failed';
      }
      return false;
    } finally {
      loading.value = false;
    }
  }

  async function logout() {
    try {
      if (token.value) {
        await authService.logout();
      }
    } catch (err) {
      console.error('Logout error:', err);
    } finally {
      // Clear state regardless of API response
      token.value = null;
      user.value = null;
      verificationStatus.value = null;
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      localStorage.removeItem('verification_status');
      localStorage.removeItem('pending_verification_email');
      
      // Use router.replace instead of push to avoid navigation issues
      router.replace({ name: 'Login' });
    }
  }

  async function verifyEmail(id, hash, signature, expires) {
    loading.value = true;
    error.value = null;
    
    try {
      const response = await authService.verifyEmail(id, hash, signature, expires);
      
      // Clear verification status
      verificationStatus.value = 'verified';
      localStorage.setItem('verification_status', 'verified');
      localStorage.removeItem('pending_verification_email');
      
      return true;
    } catch (err) {
      if (err.response && err.response.data && err.response.data.message) {
        error.value = err.response.data.message;
      } else {
        error.value = err.message || 'Email verification failed';
      }
      return false;
    } finally {
      loading.value = false;
    }
  }

  async function resendVerificationEmail(email) {
    loading.value = true;
    error.value = null;
    
    try {
      await authService.resendVerificationEmail(email || localStorage.getItem('pending_verification_email'));
      return true;
    } catch (err) {
      if (err.response && err.response.data && err.response.data.message) {
        error.value = err.response.data.message;
      } else {
        error.value = err.message || 'Failed to resend verification email';
      }
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
      if (err.response && err.response.data && err.response.data.message) {
        error.value = err.response.data.message;
      } else {
        error.value = err.message || 'Password reset request failed';
      }
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
      if (err.response && err.response.data && err.response.data.message) {
        error.value = err.response.data.message;
      } else {
        error.value = err.message || 'Password reset failed';
      }
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
    verificationStatus,
    
    // Getters
    isAuthenticated,
    isAdmin,
    userFullName,
    needsVerification,
    
    // Actions
    login,
    register,
    logout,
    verifyEmail,
    resendVerificationEmail,
    forgotPassword,
    resetPassword,
    clearError
  };
});
