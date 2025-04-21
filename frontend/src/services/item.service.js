import axios from 'axios';
import authHeader from './auth-header';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

class ItemService {
  // Get all items with filtering
  getItems(params = {}) {
    return axios.get(`${API_URL}/items`, { 
      headers: authHeader(),
      params
    });
  }

  // Get user's items
  getUserItems(params = {}) {
    return axios.get(`${API_URL}/items/my-items`, { 
      headers: authHeader(),
      params
    });
  }

  // Get a specific item
  getItem(id) {
    return axios.get(`${API_URL}/items/${id}`, { headers: authHeader() });
  }

  // Create a new item
  createItem(itemData) {
    // Use FormData for image upload
    const formData = new FormData();
    
    // Append all item data to FormData
    Object.keys(itemData).forEach(key => {
      if (key === 'image' && itemData[key] instanceof File) {
        formData.append(key, itemData[key]);
      } else if (itemData[key] !== null && itemData[key] !== undefined) {
        formData.append(key, itemData[key]);
      }
    });
    
    return axios.post(`${API_URL}/items`, formData, { 
      headers: {
        ...authHeader(),
        'Content-Type': 'multipart/form-data'
      }
    });
  }

  // Update an item
  updateItem(id, itemData) {
    // Use FormData for image upload
    const formData = new FormData();
    
    // Append all item data to FormData
    Object.keys(itemData).forEach(key => {
      if (key === 'image' && itemData[key] instanceof File) {
        formData.append(key, itemData[key]);
      } else if (itemData[key] !== null && itemData[key] !== undefined) {
        formData.append(key, itemData[key]);
      }
    });
    
    return axios.post(`${API_URL}/items/${id}?_method=PUT`, formData, { 
      headers: {
        ...authHeader(),
        'Content-Type': 'multipart/form-data'
      }
    });
  }

  // Archive an item
  archiveItem(id) {
    return axios.patch(`${API_URL}/items/${id}/archive`, {}, { headers: authHeader() });
  }

  // Restore an archived item
  restoreItem(id) {
    return axios.patch(`${API_URL}/items/${id}/restore`, {}, { headers: authHeader() });
  }

  // Get all categories
  getCategories() {
    return axios.get(`${API_URL}/categories`, { headers: authHeader() });
  }

  // Report an item
  reportItem(itemId, reason, details = null) {
    return axios.post(
      `${API_URL}/reports`, 
      { 
        reportable_type: 'item', 
        reportable_id: itemId,
        reason,
        details
      }, 
      { headers: authHeader() }
    );
  }
}

export default new ItemService();
