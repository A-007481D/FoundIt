import { BaseService } from '../../BaseService';

class AdminUserService extends BaseService {
  constructor() {
    super('/admin/users');
  }

  /**
   * Get all users with filtering
   * @param {Object} params - Filter parameters
   * @returns {Promise} - Users response
   */
  async getUsers(params = {}) {
    return this.get('', params);
  }

  /**
   * Get a single user
   * @param {string} id - User ID
   * @returns {Promise} - User response
   */
  async getUser(id) {
    return this.get(`/${id}`);
  }

  /**
   * Update user status
   * @param {string} id - User ID
   * @param {string} status - New status
   * @returns {Promise} - Update response
   */
  async updateStatus(id, status) {
    return this.patch(`/${id}/status`, { status });
  }

  /**
   * Update user role
   * @param {string} id - User ID
   * @param {string} role - New role
   * @returns {Promise} - Update response
   */
  async updateRole(id, role) {
    return this.patch(`/${id}/role`, { role });
  }

  /**
   * Get user statistics
   * @returns {Promise} - Stats response
   */
  async getUserStats() {
    return this.get('/stats');
  }
}

export default new AdminUserService();
