import { BaseService } from '../BaseService';

const itemService = new (class extends BaseService {
  constructor() {
    super('/items');
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
   * Get user's items
   * @param {Object} params - Filter parameters
   * @returns {Promise} - User items response
   */
  async getMyItems(params = {}) {
    return this.get('/my-items', params);
  }

  /**
   * Get item by ID
   * @param {string} itemId - Item ID
   * @returns {Promise} - Item response
   */
  async getItem(itemId) {
    return this.get(`/${itemId}`);
  }

  /**
   * Create a new item
   * @param {Object} itemData - Item data
   * @returns {Promise} - Create response
   */
  async createItem(itemData) {
    const formData = new FormData();
    Object.keys(itemData).forEach(key => {
      if (itemData[key] !== null && itemData[key] !== undefined) {
        formData.append(key, itemData[key]);
      }
    });
    return this.post('', formData, { headers: { 'Content-Type': 'multipart/form-data' } });
  }

  /**
   * Update an item
   * @param {string} id - Item ID
   * @param {Object} itemData - Item data
   * @returns {Promise} - Update response
   */
  async updateItem(id, itemData) {
    const formData = new FormData();
    Object.keys(itemData).forEach(key => {
      if (itemData[key] !== null && itemData[key] !== undefined) {
        formData.append(key, itemData[key]);
      }
    });
    return this.post(`/${id}?_method=PUT`, formData, { headers: { 'Content-Type': 'multipart/form-data' } });
  }

  /**
   * Update item status
   * @param {string} itemId - Item ID
   * @param {string} status - New status
   * @returns {Promise} - Update response
   */
  async updateItemStatus(itemId, status) {
    return this.put(`/${itemId}/status`, { status });
  }

  /**
   * Delete an item
   * @param {string} itemId - Item ID
   * @returns {Promise} - Delete response
   */
  async deleteItem(itemId) {
    return this.delete(`/${itemId}`);
  }

  /**
   * Archive an item
   * @param {string} id - Item ID
   * @returns {Promise} - Archive response
   */
  async archiveItem(id) {
    return this.patch(`/${id}/archive`);
  }

  /**
   * Restore an archived item
   * @param {string} id - Item ID
   * @returns {Promise} - Restore response
   */
  async restoreItem(id) {
    return this.patch(`/${id}/restore`);
  }

  /**
   * Create a match for an item
   * @param {string} itemId - Item ID
   * @returns {Promise} - Match response
   */
  async createMatch(itemId) {
    return this.post(`/${itemId}/match`);
  }

  /**
   * Get matches for an item
   * @param {string} itemId - Item ID
   * @param {Object} params - Filter parameters
   * @returns {Promise} - Matches response
   */
  async getMatches(itemId, params = {}) {
    return this.get(`/${itemId}/matches`, params);
  }

  /**
   * Get all categories
   * @returns {Promise} - Categories response
   */
  async getCategories() {
    return this.get('/categories');
  }

  /**
   * Report an item
   * @param {string} itemId - Item ID
   * @param {string} reason - Report reason
   * @param {string} details - Report details
   * @returns {Promise} - Report response
   */
  async reportItem(itemId, reason, details = null) {
    return this.post('/reports', { 
      reportable_type: 'item', 
      reportable_id: itemId,
      reason,
      details
    });
  }
})();

// Export methods directly
export const getItems = (params) => itemService.getItems(params);
export const getMyItems = (params) => itemService.getMyItems(params);
export const getItem = (itemId) => itemService.getItem(itemId);
export const createItem = (itemData) => itemService.createItem(itemData);
export const updateItem = (id, itemData) => itemService.updateItem(id, itemData);
export const updateItemStatus = (itemId, status) => itemService.updateItemStatus(itemId, status);
export const deleteItem = (itemId) => itemService.deleteItem(itemId);
export const archiveItem = (id) => itemService.archiveItem(id);
export const restoreItem = (id) => itemService.restoreItem(id);
export const createMatch = (itemId) => itemService.createMatch(itemId);
export const getMatches = (itemId, params) => itemService.getMatches(itemId, params);
export const getCategories = () => itemService.getCategories();
export const reportItem = (itemId, reason, details = null) => itemService.reportItem(itemId, reason, details);
