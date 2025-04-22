import axios from 'axios';
import authHeader from './auth-header';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';
const ADMIN_API_URL = `${API_URL}/admin`;

class AdminItemService {

  async getItems(params = {}) {
    try {
      const cleanParams = {};
      if (params.search && params.search.trim() !== '') cleanParams.search = params.search.trim();
      if (params.type && params.type !== 'all') cleanParams.type = params.type;
      if (params.status && params.status !== 'all') cleanParams.status = params.status;
      if (params.visibility && params.visibility !== 'all') cleanParams.visibility = params.visibility;
      if (params.page) cleanParams.page = params.page;
      if (params.per_page) cleanParams.per_page = params.per_page;
      
      const response = await axios.get(`${ADMIN_API_URL}/items`, { 
        headers: authHeader(),
        params: cleanParams
      });
      return response;
    } catch (error) {
      console.error('Error in getItems:', error);
      return { data: { data: [] } };
    }
  }

  async getItem(id) {
    try {
      const response = await axios.get(`${ADMIN_API_URL}/items/${id}`, { 
        headers: authHeader() 
      });
      return response;
    } catch (error) {
      console.error(`Error fetching item details for ID ${id}:`, error);
      throw error;
    }
  }


  async updateStatus(id, status) {
    try {
      const response = await axios.patch(
        `${ADMIN_API_URL}/items/${id}/status`, 
        { status }, 
        { headers: authHeader() }
      );
      return response;
    } catch (error) {
      console.error(`Error updating item status for ID ${id}:`, error);
      throw error;
    }
  }


  async updateVisibility(id, visible) {
    try {
      const response = await axios.patch(
        `${ADMIN_API_URL}/items/${id}/visibility`, 
        { visible }, 
        { headers: authHeader() }
      );
      return response;
    } catch (error) {
      console.error(`Error updating item visibility for ID ${id}:`, error);
      throw error;
    }
  }


  async archiveItem(id) {
    try {
      const response = await axios.patch(
        `${ADMIN_API_URL}/items/${id}/archive`, 
        {}, 
        { headers: authHeader() }
      );
      return response;
    } catch (error) {
      console.error(`Error archiving item ID ${id}:`, error);
      throw error;
    }
  }


  async deleteItem(id, reason = null) {
    try {
      const response = await axios.delete(
        `${ADMIN_API_URL}/items/${id}`, 
        { 
          headers: authHeader(),
          data: { reason } 
        }
      );
      return response;
    } catch (error) {
      console.error(`Error deleting item ID ${id}:`, error);
      throw error;
    }
  }


  async getItemStats() {
    try {
      const response = await axios.get(`${ADMIN_API_URL}/items/stats`, { 
        headers: authHeader() 
      });
      return response;
    } catch (error) {
      console.error('Error fetching item statistics:', error);
      throw error;
    }
  }
}

export default new AdminItemService();
