import axios from 'axios';
import { useAuthStore } from '@/stores/auth.store';
import router from '@/router';

// Fix the duplicate API prefix issue
// The environment variable already has /api, so we need to ensure we don't add it again
axios.defaults.baseURL = import.meta.env.VITE_API_URL.endsWith('/api') ? 
  import.meta.env.VITE_API_URL : 
  `${import.meta.env.VITE_API_URL}/api`;

axios.interceptors.request.use(
  config => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  error => Promise.reject(error)
);

axios.interceptors.response.use(
  response => response,
  error => {
    // Handle session termination responses
    if (error.response && error.response.status === 401) {
      const authStore = useAuthStore();
      
      // Check if this is a session termination
      if (error.response.data && error.response.data.session_terminated) {
        // Clear auth data
        authStore.logout();
        
        // Redirect to login with message
        router.push({ 
          name: 'login', 
          query: { 
            message: 'Your session has been terminated by an administrator.', 
            type: 'warning' 
          } 
        });
        
        return Promise.reject(error);
      }
      
      // Handle token expiration
      if (error.response.data && error.response.data.session_expired) {
        authStore.logout();
        router.push({ 
          name: 'login', 
          query: { 
            message: 'Your session has expired. Please log in again.', 
            type: 'info' 
          } 
        });
        return Promise.reject(error);
      }
    }
    
    return Promise.reject(error);
  }
);

export default axios;
