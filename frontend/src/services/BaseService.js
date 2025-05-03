import axios from 'axios';
import authHeader from './auth-header';

export class BaseService {
  constructor(endpoint) {
    this.axios = axios.create({
      baseURL: '/api',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      timeout: 30000, // 30 seconds
      withCredentials: true
    });

    // Add auth token interceptor
    this.axios.interceptors.request.use(
      (config) => {
        const token = localStorage.getItem('token');
        if (token) {
          config.headers.Authorization = `Bearer ${token}`;
        }
        console.log(`Request to: ${config.baseURL}${config.url}`, config);
        return config;
      },
      (error) => {
        console.error('Request error:', error);
        return Promise.reject(error);
      }
    );

    // Add response interceptor for error handling
    this.axios.interceptors.response.use(
      (response) => {
        console.log(`Response from: ${response.config.url}`, response.data);
        return response;
      },
      (error) => {
        if (error.response) {
          // The request was made and the server responded with a status code
          // that falls out of the range of 2xx
          console.error('Response error:', error.response);
          
          // Extract error message from response
          const errorMessage = error.response.data?.message || 'An error occurred';
          
          // Show toast notification
          if (typeof window !== 'undefined' && window.toast) {
            window.toast.error(errorMessage);
          }
          
          return Promise.reject({
            status: error.response.status,
            message: errorMessage,
            data: error.response.data
          });
        } else if (error.request) {
          // The request was made but no response was received
          console.error('Request failed:', error.request);
          
          const errorMessage = 'Failed to connect to server. Please check if the backend server is running.';
          
          if (typeof window !== 'undefined' && window.toast) {
            window.toast.error(errorMessage);
          }
          
          return Promise.reject({
            status: 500,
            message: errorMessage
          });
        } else {
          // Something happened in setting up the request that triggered an Error
          console.error('Error:', error.message);
          
          const errorMessage = 'An error occurred while processing your request.';
          
          if (typeof window !== 'undefined' && window.toast) {
            window.toast.error(errorMessage);
          }
          
          return Promise.reject({
            status: 500,
            message: errorMessage
          });
        }
      }
    );

    this.endpoint = endpoint;
  }

  // HTTP Methods
  async get(path = '', params = {}) {
    try {
      console.log(`Making GET request to: ${this.endpoint}${path}`, params);
      const response = await this.axios.get(`${this.endpoint}${path}`, { params });
      return response.data;
    } catch (error) {
      console.error(`GET request to ${this.endpoint}${path} failed:`, error);
      throw error;
    }
  }

  async post(path = '', data = {}, config = {}) {
    try {
      console.log(`Making POST request to: ${this.endpoint}${path}`, data);
      const response = await this.axios.post(`${this.endpoint}${path}`, data, config);
      return response.data;
    } catch (error) {
      console.error(`POST request to ${this.endpoint}${path} failed:`, error);
      throw error;
    }
  }

  async put(path = '', data = {}) {
    try {
      console.log(`Making PUT request to: ${this.endpoint}${path}`, data);
      const response = await this.axios.put(`${this.endpoint}${path}`, data);
      return response.data;
    } catch (error) {
      console.error(`PUT request to ${this.endpoint}${path} failed:`, error);
      throw error;
    }
  }

  async delete(path = '') {
    try {
      console.log(`Making DELETE request to: ${this.endpoint}${path}`);
      const response = await this.axios.delete(`${this.endpoint}${path}`);
      return response.data;
    } catch (error) {
      console.error(`DELETE request to ${this.endpoint}${path} failed:`, error);
      throw error;
    }
  }

  async patch(path = '', data = {}) {
    try {
      console.log(`Making PATCH request to: ${this.endpoint}${path}`, data);
      const response = await this.axios.patch(`${this.endpoint}${path}`, data);
      return response.data;
    } catch (error) {
      console.error(`PATCH request to ${this.endpoint}${path} failed:`, error);
      throw error;
    }
  }
}

export default BaseService;
