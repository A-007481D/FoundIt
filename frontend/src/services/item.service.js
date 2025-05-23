import axiosInstance from '@/services/apiClient';

class ItemService {
  // Get all items with filtering
  async getItems(params = {}) {
    try {
      const response = await axiosInstance.get('/items', { params });
      return response;
    } catch (error) {
      console.error('Error in getItems:', error);
      // Return a structured response even in error cases
      return { data: { items: [] } };
    }
  }

  // Get user's items
  async getUserItems(params = {}) {
    try {
      const response = await axiosInstance.get('/items/my-items', { params });
      return response;
    } catch (error) {
      console.error('Error in getUserItems:', error);
      // Return a structured response even in error cases
      return { data: { items: [] } };
    }
  }

  // Get a specific item
  async getItem(id) {
    try {
      console.log(`Fetching item details for ID: ${id}`);
      const response = await axiosInstance.get(`/items/${id}`);
      console.log('Item details response:', response);
      return response;
    } catch (error) {
      console.error(`Error fetching item details for ID ${id}:`, error);
      throw error;
    }
  }

  // Create a new item
  async createItem(itemData) {
    try {
      console.log('Creating item with data:', itemData);
      
      // Use FormData for image upload
      const formData = new FormData();
      
      // Ensure required fields are present based on item type
      if (itemData.type === 'lost' && !itemData.found_date) {
        itemData.found_date = new Date().toISOString();
      } else if (itemData.type === 'found' && !itemData.lost_date) {
        itemData.lost_date = new Date().toISOString();
      }
      
      // Ensure status is set
      if (!itemData.status) {
        itemData.status = 'active';
      }
      
      // Append all item data to FormData
      Object.keys(itemData).forEach(key => {
        if (key === 'image' && itemData[key] instanceof File) {
          formData.append(key, itemData[key]);
        } else if (itemData[key] !== null && itemData[key] !== undefined) {
          formData.append(key, itemData[key]);
        }
      });
      
      // Log the FormData entries for debugging
      for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
      }
      
      const response = await axiosInstance.post('/items', formData, { headers: { 'Content-Type': 'multipart/form-data' } });
      
      return response;
    } catch (error) {
      console.error('Error in createItem:', error);
      throw error; // Re-throw to allow component to handle the error
    }
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
    
    return axiosInstance.post(`/items/${id}?_method=PUT`, formData, { headers: { 'Content-Type': 'multipart/form-data' } });
  }

  // Archive an item
  archiveItem(id) {
    return axiosInstance.patch(`/items/${id}/archive`);
  }

  // Restore an archived item
  restoreItem(id) {
    return axiosInstance.patch(`/items/${id}/restore`);
  }

  // Get all categories
  getCategories() {
    return axiosInstance.get('/categories');
  }

  // Report an item
  reportItem(itemId, reason, details = null) {
    return axiosInstance.post('/reports', { reportable_type: 'item', reportable_id: itemId, reason, details });
  }
}

export default new ItemService();
