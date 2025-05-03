// src/stores/users.store.js
import { defineStore } from 'pinia';
import axios from 'axios';

export const useUsersStore = defineStore('users', {
  state: () => ({
    users: [],
    loading: false,
    error: null,
    currentPage: 1,
    totalUsers: 0,
    totalPages: 0,
    perPage: 10,
    filters: {
      search: '',
      role: 'all',
      status: 'all'
    }
  }),
  
  getters: {
    // Converting to computed properties that accept parameters
    activeUsers() {
      return this.users.filter(user => user.status === 'active');
    },
    
    adminUsers() {
      return this.users.filter(user => user.role === 'admin');
    }
  },
  
  actions: {
    // Find a user by ID (moved from getter to action)
    findUserById(id) {
      if (!id) return null;
      const userId = typeof id === 'string' ? parseInt(id, 10) : id;
      return this.users.find(user => user.id === userId);
    },
    
    async fetchUsers(page = 1, filters = null) {
      this.loading = true;
      this.error = null;
      
      try {
        const params = {
          page: page,
          per_page: this.perPage,
          ...this.filters,
          ...filters
        };
        
        const response = await axios.get('/admin/users', { params });
        
        this.users = response.data.data;
        this.currentPage = response.data.current_page;
        this.totalUsers = response.data.total;
        this.totalPages = response.data.last_page;
        
        return response.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch users';
        console.error('Error fetching users:', error);
        throw error;
      } finally {
        this.loading = false;
      }
    },
    
    async fetchUserById(id) {
      try {
        // Remove the '/api' prefix to avoid duplication since it's already in the baseURL
        const response = await axios.get(`/admin/users/${id}`);
        
        // Update the user in the users array if it exists
        const index = this.users.findIndex(user => user.id === id);
        if (index !== -1) {
          this.users[index] = response.data;
        } else {
          this.users.push(response.data);
        }
        
        return response.data;
      } catch (error) {
        console.error(`Error fetching user with ID ${id}:`, error);
        throw error;
      }
    },
    
    setFilters(filters) {
      this.filters = { ...this.filters, ...filters };
    },
    
    resetFilters() {
      this.filters = {
        search: '',
        role: 'all',
        status: 'all'
      };
    }
  }
});
