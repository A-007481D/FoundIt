import { BaseService } from '../BaseService';

export class ProfileService extends BaseService {
  constructor() {
    super('/profile');
  }

  /**
   * Get user profile
   * @returns {Promise} - Profile response
   */
  async getProfile() {
    return this.get('');
  }

  /**
   * Update user profile
   * @param {Object} data - Profile data
   * @returns {Promise} - Update response
   */
  async updateProfile(data) {
    return this.put('', data);
  }

  /**
   * Update user photo
   * @param {File} file - Photo file
   * @returns {Promise} - Update response
   */
  async updatePhoto(file) {
    const formData = new FormData();
    formData.append('photo', file);
    return this.post('/photo', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  }

  /**
   * Get user statistics
   * @returns {Promise} - Stats response
   */
  async getStats() {
    return this.get('/stats');
  }

  /**
   * Get user activity
   * @param {Object} params - Query parameters
   * @returns {Promise} - Activity response
   */
  async getActivity(params = {}) {
    return this.get('/activity', { params });
  }

  /**
   * Get user notifications
   * @param {Object} params - Query parameters
   * @returns {Promise} - Notifications response
   */
  async getNotifications(params = {}) {
    return this.get('/notifications', { params });
  }

  /**
   * Mark notification as read
   * @param {string} notificationId - Notification ID
   * @returns {Promise} - Update response
   */
  async markNotificationRead(notificationId) {
    return this.put(`/notifications/${notificationId}/read`);
  }

  /**
   * Mark all notifications as read
   * @returns {Promise} - Update response
   */
  async markAllNotificationsRead() {
    return this.put('/notifications/read');
  }
}

// Export specific functions
export const getProfile = () => new ProfileService().getProfile();
export const updateProfile = (data) => new ProfileService().updateProfile(data);
export const updatePhoto = (file) => new ProfileService().updatePhoto(file);
export const getStats = () => new ProfileService().getStats();
export const getActivity = (params) => new ProfileService().getActivity(params);
export const getNotifications = (params) => new ProfileService().getNotifications(params);
export const markNotificationRead = (notificationId) => new ProfileService().markNotificationRead(notificationId);
export const markAllNotificationsRead = () => new ProfileService().markAllNotificationsRead();
