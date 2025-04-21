import axios from 'axios';
import authHeader from './auth-header';
import { API_BASE_URL } from '@/config';

export default {
  async getCategories() {
    try {
      const response = await axios.get(`${API_BASE_URL}/categories`)
      return response.data
    } catch (error) {
      console.error('Error fetching categories:', error)
      throw error
    }
  }
}
