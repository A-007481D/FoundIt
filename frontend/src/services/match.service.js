import axios from 'axios';
import authHeader from './auth-header';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

class MatchService {
  async getMatches() {
    try {
      const response = await axios.get(`${API_URL}/matches`, { headers: authHeader() });
      return response;
    } catch (error) {
      console.error('Error fetching matches:', error);
      return { data: [] };
    }
  }
}

export default new MatchService();
