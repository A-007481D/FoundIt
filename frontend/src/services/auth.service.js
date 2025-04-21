import axios from 'axios';

const API_URL = 'http://localhost:8000/api';

const axiosInstance = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

// Add interceptor to add the JWT token to requests
axiosInstance.interceptors.request.use(
    config => {
      const token = localStorage.getItem('token');
      if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
      }
      return config;
    },
    error => Promise.reject(error)
);

// Add interceptor to handle token expiration
axiosInstance.interceptors.response.use(
    response => response,
    async error => {
      const originalRequest = error.config;

      // If the error is due to an expired token (401) and we haven't already tried to refresh
      if (error.response && error.response.status === 401 && !originalRequest._retry) {
        originalRequest._retry = true;

        try {
          // Try to refresh the token
          const refreshResponse = await axiosInstance.post('/auth/refresh');
          const newToken = refreshResponse.data.token;

          if (newToken) {
            // Store the new token
            localStorage.setItem('token', newToken);

            // Update the Authorization header
            originalRequest.headers['Authorization'] = `Bearer ${newToken}`;

            // Retry the original request
            return axiosInstance(originalRequest);
          }
        } catch (refreshError) {
          // If refresh fails, clear auth data and redirect to login
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          window.location.href = '/login';
          return Promise.reject(refreshError);
        }
      }

      return Promise.reject(error);
    }
);

export const authService = {
  async register(userData) {
    const response = await axiosInstance.post('/auth/register', userData);
    return response.data;
  },

  async login(credentials) {
    const response = await axiosInstance.post('/auth/login', credentials);
    return response.data;
  },

  async logout() {
    const response = await axiosInstance.post('/auth/logout');
    return response.data;
  },

  async verifyEmail(id, hash, signature, expires) {
    const response = await axiosInstance.get(`/auth/email/verify/${id}/${hash}`, {
      params: { signature, expires }
    });
    return response.data;
  },

  async resendVerificationEmail(email) {
    const response = await axiosInstance.post('/auth/email/resend', { email });
    return response.data;
  },

  async forgotPassword(email) {
    const response = await axiosInstance.post('/auth/forgot-password', { email });
    return response.data;
  },

  async resetPassword(resetData) {
    const response = await axiosInstance.post('/auth/reset-password', resetData);
    return response.data;
  },

  async refreshToken() {
    const response = await axiosInstance.post('/auth/refresh');
    if (response.data.token) {
      localStorage.setItem('token', response.data.token);
    }
    return response.data;
  },

  getAuthUser() {
    return JSON.parse(localStorage.getItem('user'));
  },

  getToken() {
    return localStorage.getItem('token');
  },

  isAuthenticated() {
    return !!this.getToken();
  },

  hasVerifiedEmail() {
    const user = this.getAuthUser();
    return user && user.email_verified_at;
  }
};