import axios from 'axios';

const API_URL = 'http://localhost:8000/api';

const axiosInstance = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

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

axiosInstance.interceptors.response.use(
    response => response,
    async error => {
      const originalRequest = error.config;

      if (error.response && error.response.data && error.response.data.errors && error.response.data.errors.account) {
        const accountError = error.response.data.errors.account[0];
        if (accountError.includes('banned') || accountError.includes('suspended')) {
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          return Promise.reject(error);
        }
      }
      // Prevent infinite refresh loop for refresh or logout endpoints
      if (originalRequest.url.includes('/auth/refresh') || originalRequest.url.includes('/auth/logout')) {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '/login';
        return Promise.reject(error);
      }
      // Skip token refresh for login errors to allow front-end error handling
      if (originalRequest.url.includes('/auth/login')) {
        return Promise.reject(error);
      }
      if (error.response && error.response.status === 401 && !originalRequest._retry) {
        originalRequest._retry = true;

        try {
          const refreshResponse = await axios.post(`${API_URL}/auth/refresh`);
          const newToken = refreshResponse.data.token;

          if (newToken) {
            // Update stored token and retry original request with new token
            localStorage.setItem('token', newToken);
            originalRequest.headers['Authorization'] = `Bearer ${newToken}`;
            return axiosInstance(originalRequest);
          }
        } catch (refreshError) {
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          window.location.href = '/login';
          return Promise.reject(refreshError);
        }
      }

      return Promise.reject(error);
    }
);

const authService = {
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
    // sending reset link
    const response = await axiosInstance.post('/auth/password/email', { email });
    return response.data;
  },

  async resetPassword(resetData) {
    // resetting password
    const response = await axiosInstance.post('/auth/password/reset', resetData);
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
  },

  updateStoredUser(userData) {
    // Keep the existing user and only update provided fields
    const currentUser = this.getAuthUser() || {};
    const updatedUser = { ...currentUser, ...userData };
    localStorage.setItem('user', JSON.stringify(updatedUser));
    return updatedUser;
  }
};

export { authService };
export default authService;