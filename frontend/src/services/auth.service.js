// src/services/auth.service.js
import axios from 'axios';

const API_URL = 'http://localhost:8000/api';

export const authService = {
  login,
  register,
  logout,
  verifyEmail,
  forgotPassword,
  resetPassword,
  getAuthHeader,
  refreshToken
};

async function login(credentials) {
  try {
    const response = await axios.post(`${API_URL}/auth/login`, credentials);
    
    if (response.data.token) {
      localStorage.setItem('token', response.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.user));
      setAuthHeader(response.data.token);
    }
    
    return response.data;
  } catch (error) {
    throw handleAuthError(error);
  }
}

async function register(userData) {
  try {
    const response = await axios.post(`${API_URL}/auth/register`, userData);
    return response.data;
  } catch (error) {
    throw handleAuthError(error);
  }
}

async function verifyEmail(id, hash) {
  try {
    const response = await axios.get(`${API_URL}/auth/email/verify/${id}/${hash}`);
    return response.data;
  } catch (error) {
    throw handleAuthError(error);
  }
}

async function forgotPassword(email) {
  try {
    const response = await axios.post(`${API_URL}/auth/password/email`, { email });
    return response.data;
  } catch (error) {
    throw handleAuthError(error);
  }
}

async function resetPassword(resetData) {
  try {
    const response = await axios.post(`${API_URL}/auth/password/reset`, resetData);
    return response.data;
  } catch (error) {
    throw handleAuthError(error);
  }
}

async function logout() {
  try {
    const token = localStorage.getItem('token');
    if (token) {
      await axios.post(`${API_URL}/auth/logout`, {}, { 
        headers: { Authorization: `Bearer ${token}` }
      });
    }
  } catch (error) {
    console.error('Logout error:', error);
  } finally {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    removeAuthHeader();
  }
}

async function refreshToken() {
  try {
    const token = localStorage.getItem('token');
    if (!token) return false;
    
    const response = await axios.post(`${API_URL}/auth/refresh`, {}, {
      headers: { Authorization: `Bearer ${token}` }
    });
    
    if (response.data.token) {
      localStorage.setItem('token', response.data.token);
      setAuthHeader(response.data.token);
      return true;
    }
    
    return false;
  } catch (error) {
    console.error('Token refresh error:', error);
    logout();
    return false;
  }
}

function getAuthHeader() {
  const token = localStorage.getItem('token');
  return token ? { Authorization: `Bearer ${token}` } : {};
}

function setAuthHeader(token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

function removeAuthHeader() {
  delete axios.defaults.headers.common['Authorization'];
}

function handleAuthError(error) {
  if (error.response) {
    // Server responded with an error
    if (error.response.status === 401) {
      // Unauthorized - clear local storage
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    }
    
    const errorMessage = error.response.data.message || 'Authentication failed';
    return new Error(errorMessage);
  }
  return error;
}

// Set up axios interceptors for JWT auth
axios.interceptors.response.use(
  (response) => response,
  async (error) => {
    const originalRequest = error.config;
    
    // If the error is due to an expired JWT token (401) and we haven't already tried to refresh
    if (error.response.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true;
      
      // Try to refresh the token
      const refreshed = await refreshToken();
      
      if (refreshed) {
        // Retry the original request with the new token
        return axios(originalRequest);
      }
    }
    
    return Promise.reject(error);
  }
);

// Initialize auth header if token exists on app start
const token = localStorage.getItem('token');
if (token) {
  setAuthHeader(token);
}
