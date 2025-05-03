import { BaseService } from '../BaseService';

class ChatService extends BaseService {
  constructor() {
    super('/chat');
    this.listeners = {
      newMessage: [],
      typing: []
    };
  }

  /**
   * Get all conversations
   * @returns {Promise} - Conversations response
   */
  async getConversations() {
    return this.get('/conversations');
  }

  /**
   * Get a specific conversation
   * @param {string} conversationId - Conversation ID
   * @returns {Promise} - Conversation response
   */
  async getConversation(conversationId) {
    return this.get(`/conversations/${conversationId}`);
  }

  /**
   * Create a new conversation
   * @param {string} userId - User ID to start conversation with
   * @returns {Promise} - Create conversation response
   */
  async createConversation(userId) {
    return this.post('/conversations', { user_id: userId });
  }

  /**
   * Get conversation messages
   * @param {string} conversationId - Conversation ID
   * @returns {Promise} - Messages response
   */
  async getMessages(conversationId) {
    return this.get(`/conversations/${conversationId}/messages`);
  }

  /**
   * Send a message
   * @param {string} conversationId - Conversation ID
   * @param {string} content - Message content
   * @returns {Promise} - Send response
   */
  async sendMessage(conversationId, content) {
    return this.post(`/conversations/${conversationId}/messages`, { content });
  }

  /**
   * Mark conversation as read
   * @param {string} conversationId - Conversation ID
   * @returns {Promise} - Read response
   */
  async markConversationAsRead(conversationId) {
    return this.post(`/conversations/${conversationId}/read`);
  }

  /**
   * Get notifications
   * @returns {Promise} - Notifications response
   */
  async getNotifications() {
    return this.get('/notifications');
  }

  /**
   * Mark all notifications as read
   * @returns {Promise} - Read response
   */
  async markAllNotificationsAsRead() {
    return this.post('/notifications/read-all');
  }

  /**
   * Mark notification as read
   * @param {string} notificationId - Notification ID
   * @returns {Promise} - Read response
   */
  async markNotificationAsRead(notificationId) {
    return this.post(`/notifications/${notificationId}/read`);
  }

  /**
   * Report a message
   * @param {string} messageId - Message ID
   * @param {string} reason - Report reason
   * @returns {Promise} - Report response
   */
  async reportMessage(messageId, reason) {
    return this.post('/reports', {
      reportable_type: 'message',
      reportable_id: messageId,
      reason
    });
  }

  /**
   * Register a callback for new messages
   * @param {Function} callback - Function to call when a new message is received
   */
  onNewMessage(callback) {
    if (typeof callback === 'function') {
      this.listeners.newMessage.push(callback);
    }
  }

  /**
   * Remove all new message callbacks
   */
  offNewMessage() {
    this.listeners.newMessage = [];
  }

  /**
   * Register a callback for typing notifications
   * @param {Function} callback - Function to call when typing status changes
   */
  onTyping(callback) {
    if (typeof callback === 'function') {
      this.listeners.typing.push(callback);
    }
  }

  /**
   * Remove all typing callbacks
   */
  offTyping() {
    this.listeners.typing = [];
  }

  /**
   * Signal that the user has started typing
   * @param {string} conversationId - Conversation ID
   */
  startTyping(conversationId) {
    // In a real app, this would send a websocket/socket.io message
    console.log(`User started typing in conversation ${conversationId}`);
    // For demo purposes: just do nothing since we don't have a real-time backend yet
  }

  /**
   * Signal that the user has stopped typing
   * @param {string} conversationId - Conversation ID
   */
  stopTyping(conversationId) {
    // In a real app, this would send a websocket/socket.io message
    console.log(`User stopped typing in conversation ${conversationId}`);
    // For demo purposes: just do nothing since we don't have a real-time backend yet
  }

  /**
   * Simulate receiving a new message (for testing/demo purposes)
   * @param {Object} message - The message object
   */
  receiveMessage(message) {
    this.listeners.newMessage.forEach(callback => callback(message));
  }

  /**
   * Simulate receiving typing status (for testing/demo purposes)
   * @param {Array} users - Array of users who are typing
   */
  receiveTypingStatus(users) {
    this.listeners.typing.forEach(callback => callback(users));
  }
}

export default new ChatService();
