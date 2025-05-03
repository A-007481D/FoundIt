import { BaseService } from '../BaseService';

class NotificationService extends BaseService {
  constructor() {
    super('/notifications');
  }

  /**
   * Get notifications
   * @param {Object} params - Filter parameters
   * @returns {Promise} - Notifications response
   */
  async getNotifications(params = {}) {
    return this.get('', params);
  }

  /**
   * Mark notification as read
   * @param {string} id - Notification ID
   * @returns {Promise} - Read response
   */
  async markAsRead(id) {
    return this.post(`/${id}/read`);
  }

  /**
   * Mark all notifications as read
   * @returns {Promise} - Read response
   */
  async markAllAsRead() {
    return this.post('/read-all');
  }

  /**
   * Get notification statistics
   * @returns {Promise} - Stats response
   */
  async getStats() {
    return this.get('/stats');
  }
}

export default new NotificationService();
