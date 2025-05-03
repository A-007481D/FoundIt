import apiClient from './apiClient';
import authHeader from './auth-header';

class ItemDetectiveService {
  async ping() {
    // Use direct API to avoid /api prefix duplication
    return apiClient.get('/ping');
  }

  async search(formData) {
    // Use direct API to avoid /api prefix duplication 
    return apiClient.post('/item-detective/search', formData, {
      headers: {
        ...authHeader(),
        'Content-Type': 'multipart/form-data'
      }
    });
  }

  async saveQuery(formData) {
    // Use direct API to avoid /api prefix duplication
    return apiClient.post('/item-detective/save-query', formData, {
      headers: {
        ...authHeader(),
        'Content-Type': 'multipart/form-data'
      }
    });
  }

  async debug() {
    // Use direct API to avoid /api prefix duplication
    return apiClient.get('/item-detective/debug');
  }
}

export default new ItemDetectiveService();
