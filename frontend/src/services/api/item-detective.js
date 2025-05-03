import { BaseService } from '../BaseService';

class ItemDetectiveService extends BaseService {
  constructor() {
    super('/item-detective');
  }

  /**
   * Check service status
   * @returns {Promise} - Ping response
   */
  async ping() {
    return this.get('/ping');
  }

  /**
   * Search for similar items
   * @param {Object} formData - Search data
   * @returns {Promise} - Search response
   */
  async search(formData) {
    return this.post('/search', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  }

  /**
   * Save search query
   * @param {Object} formData - Query data
   * @returns {Promise} - Save response
   */
  async saveQuery(formData) {
    return this.post('/save-query', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  }

  /**
   * Get service debug information
   * @returns {Promise} - Debug response
   */
  async debug() {
    return this.get('/debug');
  }
}

export default new ItemDetectiveService();
