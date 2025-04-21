import axios from 'axios';
import authHeader from './auth-header';
import { API_BASE_URL } from '@/config';

export default {
  async getCategories() {
    try {
      const response = await axios.get(`${API_BASE_URL}/categories`, { headers: authHeader() });
      return response.data || [];
    } catch (error) {
      console.error('Error fetching categories:', error);
      // Return a fallback of dummy categories if API fails
      return [
        { id: 1, name: 'Electronics' },
        { id: 2, name: 'Accessories' },
        { id: 3, name: 'Clothing' },
        { id: 4, name: 'Documents' },
        { id: 5, name: 'Keys' },
        { id: 6, name: 'Pets' },
        { id: 7, name: 'Other' }
      ];
    }
  }
}
