import { BaseService } from '../../BaseService';

class AdminItemService extends BaseService {
  constructor() {
    super('/admin/items');
  }

  /**
   * Get all items with filtering
   * @param {Object} params - Filter parameters
   * @returns {Promise} - Items response
   */
  async getItems(params = {}) {
    return this.get('', params);
  }

  /**
   * Get a single item
   * @param {string} id - Item ID
   * @returns {Promise} - Item response
   */
  async getItem(id) {
    return this.get(`/${id}`);
  }

  /**
   * Update item status
   * @param {string} id - Item ID
   * @param {string} status - New status
   * @returns {Promise} - Update response
   */
  async updateStatus(id, status) {
    return this.patch(`/${id}/status`, { status });
  }

  /**
   * Update item visibility
   * @param {string} id - Item ID
   * @param {boolean} visible - New visibility state
   * @returns {Promise} - Update response
   */
  async updateVisibility(id, visible) {
    return this.patch(`/${id}/visibility`, { visible });
  }

  /**
   * Delete an item
   * @param {string} id - Item ID
   * @param {string} reason - Deletion reason
   * @returns {Promise} - Delete response
   */
  async deleteItem(id, reason = null) {
    return this.delete(`/${id}`, { data: { reason } });
  }

  /**
   * Get item statistics
   * @returns {Promise} - Stats response
   */
  async getItemStats() {
    return this.get('/stats');
  }
}

export default new AdminItemService();
