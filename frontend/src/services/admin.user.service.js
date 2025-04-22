import axios from 'axios';
import authHeader from './auth-header';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';
const ADMIN_API_URL = `${API_URL}/admin`;

class AdminUserService {
  // Get all users with filtering
  async getUsers(params = {}) {
    try {
      // Clean up params for the API
      const cleanParams = {};
      if (params.search && params.search.trim() !== '') cleanParams.search = params.search.trim();
      if (params.role && params.role !== 'all') cleanParams.role = params.role;
      if (params.status && params.status !== 'all') cleanParams.status = params.status;
      if (params.page) cleanParams.page = params.page;
      if (params.per_page) cleanParams.per_page = params.per_page;
      
      const response = await axios.get(`${ADMIN_API_URL}/users`, { 
        headers: authHeader(),
        params: cleanParams
      });
      return response;
    } catch (error) {
      console.error('Error in getUsers:', error);
      return { data: { data: [] } };
    }
  }

  // Get a specific user
  async getUser(id) {
    try {
      const response = await axios.get(`${ADMIN_API_URL}/users/${id}`, { 
        headers: authHeader() 
      });
      return response;
    } catch (error) {
      console.error(`Error fetching user details for ID ${id}:`, error);
      throw error;
    }
  }

  // Update a user's status
  async updateStatus(id, status) {
    try {
      const response = await axios.patch(
        `${ADMIN_API_URL}/users/${id}/status`, 
        { status }, 
        { headers: authHeader() }
      );
      return response;
    } catch (error) {
      console.error(`Error updating user status for ID ${id}:`, error);
      throw error;
    }
  }

  // Update a user's role
  async updateRole(id, role) {
    try {
      const response = await axios.patch(
        `${ADMIN_API_URL}/users/${id}/role`, 
        { role }, 
        { headers: authHeader() }
      );
      return response;
    } catch (error) {
      console.error(`Error updating user role for ID ${id}:`, error);
      throw error;
    }
  }

  // Ban a user
  async banUser(id, reason = null) {
    try {
      const response = await axios.patch(
        `${ADMIN_API_URL}/users/${id}/ban`, 
        { reason }, 
        { headers: authHeader() }
      );
      return response;
    } catch (error) {
      console.error(`Error banning user ID ${id}:`, error);
      throw error;
    }
  }
  
  // Unban a user
  async unbanUser(id) {
    try {
      const response = await axios.patch(
        `${ADMIN_API_URL}/users/${id}/unban`, 
        {}, 
        { headers: authHeader() }
      );
      return response;
    } catch (error) {
      console.error(`Error unbanning user ID ${id}:`, error);
      throw error;
    }
  }

  // Suspend a user
  async suspendUser(id, days, reason = null) {
    try {
      const response = await axios.patch(
        `${ADMIN_API_URL}/users/${id}/suspend`, 
        { days, reason }, 
        { headers: authHeader() }
      );
      return response;
    } catch (error) {
      console.error(`Error suspending user ID ${id}:`, error);
      throw error;
    }
  }

  // Activate a user (update status to active)
  async activateUser(id) {
    return this.updateStatus(id, 'active');
  }

  // Get user statistics
  async getUserStats() {
    try {
      const response = await axios.get(`${ADMIN_API_URL}/users/stats`, { 
        headers: authHeader() 
      });
      return response;
    } catch (error) {
      console.error('Error fetching user statistics:', error);
      throw error;
    }
  }
}

export default new AdminUserService();
