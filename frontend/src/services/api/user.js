import { BaseService } from '../BaseService';

class UserService extends BaseService {
  constructor() {
    super('/users');
  }

  /**
   * Get user profile
   * @returns {Promise} - Profile response
   */
  async getProfile() {
    return this.get('/profile');
  }

  /**
   * Update user profile
   * @param {Object} profileData - Profile data
   * @returns {Promise} - Update response
   */
  async updateProfile(profileData) {
    return this.put('/profile', profileData);
  }

  /**
   * Update profile photo
   * @param {File} photoFile - New photo file
   * @returns {Promise} - Update response
   */
  async updateProfilePhoto(photoFile) {
    const formData = new FormData();
    formData.append('photo', photoFile);
    return this.post('/profile/photo', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
  }

  /**
   * Update notification preferences
   * @param {Object} preferences - Notification preferences
   * @returns {Promise} - Update response
   */
  async updateNotificationPreferences(preferences) {
    return this.put('/profile/notifications', preferences);
  }

  /**
   * Update privacy settings
   * @param {Object} settings - Privacy settings
   * @returns {Promise} - Update response
   */
  async updatePrivacySettings(settings) {
    return this.put('/profile/privacy', settings);
  }
}

export default new UserService();
