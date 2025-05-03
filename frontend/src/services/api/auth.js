import { BaseService } from '../BaseService';

const authService = new (class extends BaseService {
  constructor() {
    super('/auth');
  }

  /**
   * Login user
   * @param {Object} credentials - Login credentials
   * @returns {Promise} - Login response
   */
  async login(credentials) {
    try {
      const response = await this.post('/login', credentials);
      return response;
    } catch (error) {
      console.error('Login request failed:', error);
      throw error;
    }
  }

  /**
   * Register user
   * @param {Object} userData - User registration data
   * @returns {Promise} - Registration response
   */
  async register(userData) {
    return this.post('/register', userData);
  }

  /**
   * Update user profile
   * @param {Object} userData - User data to update
   * @returns {Promise} - Update response
   */
  async updateProfile(userData) {
    return this.put('/profile', userData);
  }

  /**
   * Logout user
   * @returns {Promise} - Logout response
   */
  async logout() {
    return this.post('/logout');
  }

  /**
   * Send password reset link
   * @param {Object} data - Email data
   * @returns {Promise} - Reset response
   */
  async sendResetLink(data) {
    return this.post('/password/email', data);
  }

  /**
   * Reset password
   * @param {Object} data - Reset data
   * @returns {Promise} - Reset response
   */
  async resetPassword(data) {
    return this.post('/password/reset', data);
  }

  /**
   * Verify email
   * @param {string} id - User ID
   * @param {string} hash - Verification hash
   * @returns {Promise} - Verification response
   */
  async verifyEmail(id, hash) {
    return this.get(`/email/verify/${id}/${hash}`);
  }

  /**
   * Resend verification email
   * @returns {Promise} - Resend response
   */
  async resendVerification() {
    return this.post('/email/verification-notification');
  }

  /**
   * Forgot password
   * @param {string} email - User email
   * @returns {Promise} - Forgot password response
   */
  async forgotPassword(email) {
    return this.post('/password/email', { email });
  }

  /**
   * Confirm password reset
   * @param {string} token - Reset token
   * @param {string} password - New password
   * @returns {Promise} - Confirmation response
   */
  async confirmPasswordReset(token, password) {
    return this.post(`/password/reset/${token}`, { password });
  }

  /**
   * Get user profile
   * @returns {Promise} - Profile response
   */
  async getProfile() {
    return this.get('/user');
  }

  /**
   * Update user settings
   * @param {Object} settings - Settings data
   * @returns {Promise} - Update response
   */
  async updateSettings(settings) {
    return this.put('/settings', settings);
  }

  /**
   * Get user notifications
   * @returns {Promise} - Notifications response
   */
  async getNotifications() {
    return this.get('/notifications');
  }

  /**
   * Mark notification as read
   * @param {string} id - Notification ID
   * @returns {Promise} - Update response
   */
  async markNotificationRead(id) {
    return this.post(`/notifications/${id}/read`);
  }

  /**
   * Mark all notifications as read
   * @returns {Promise} - Update response
   */
  async markAllNotificationsRead() {
    return this.post('/notifications/read');
  }

  /**
   * Get user stats
   * @returns {Promise} - Stats response
   */
  async getStats() {
    return this.get('/stats');
  }

  /**
   * Get user activity
   * @returns {Promise} - Activity response
   */
  async getActivity() {
    return this.get('/activity');
  }

  /**
   * Update user photo
   * @param {Object} formData - Photo form data
   * @returns {Promise} - Update response
   */
  async updatePhoto(formData) {
    return this.post('/photo', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  }

  /**
   * Get user settings
   * @returns {Promise} - Settings response
   */
  async getSettings() {
    return this.get('/settings');
  }
})();

// Export methods directly
export { authService as auth };
