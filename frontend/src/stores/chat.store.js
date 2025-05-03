import { defineStore } from 'pinia';
import { reactive, computed } from 'vue';
import ChatService from '@/services/api/chat';

export const useChatStore = defineStore('chat', () => {
  // State
  const state = reactive({
    conversations: [],
    currentConversation: null,
    messages: [],
    notifications: [],
    loading: false,
    error: null
  });

  // Getters
  const sortedConversations = computed(() => {
    return [...state.conversations].sort((a, b) => {
      const dateA = a.last_message?.created_at || 0;
      const dateB = b.last_message?.created_at || 0;
      return new Date(dateB) - new Date(dateA);
    });
  });

  const unreadNotificationsCount = computed(() => state.notifications.length);

  // Actions
  async function setCurrentConversation(conversation) {
    state.currentConversation = conversation;
    // Mark all messages as read for this conversation if there are unread
    if (conversation.unread_count > 0) {
      await markConversationAsRead(conversation.id);
    }
  }

  async function fetchConversations() {
    try {
      state.loading = true;
      const response = await ChatService.getConversations();
      state.conversations = response.data;
    } catch (err) {
      state.error = err.response?.data?.message || 'Failed to fetch conversations';
      console.error('Failed to fetch conversations:', err);
    } finally {
      state.loading = false;
    }
  }

  async function fetchMessages(conversationId) {
    try {
      state.loading = true;
      const response = await ChatService.getMessages(conversationId);
      state.messages = response.data;
    } catch (err) {
      state.error = err.response?.data?.message || 'Failed to fetch messages';
      console.error('Failed to fetch messages:', err);
    } finally {
      state.loading = false;
    }
  }

  async function sendMessage(conversationId, content) {
    try {
      state.loading = true;
      const response = await ChatService.sendMessage(conversationId, content);
      state.messages.push(response.data);
      return response.data;
    } catch (err) {
      state.error = err.response?.data?.message || 'Failed to send message';
      console.error('Failed to send message:', err);
    } finally {
      state.loading = false;
    }
  }

  async function createConversation(userId) {
    try {
      state.loading = true;
      const response = await ChatService.createConversation(userId);
      state.conversations.push(response.data);
      state.currentConversation = response.data;
      return response.data.conversation_id;
    } catch (err) {
      state.error = err.response?.data?.message || 'Failed to create conversation';
      console.error('Failed to create conversation:', err);
    } finally {
      state.loading = false;
    }
  }

  async function reportMessage(messageId, reason) {
    try {
      state.loading = true;
      await ChatService.reportMessage(messageId, reason);
    } catch (err) {
      state.error = err.response?.data?.message || 'Failed to report message';
      console.error('Failed to report message:', err);
    } finally {
      state.loading = false;
    }
  }

  async function fetchNotifications() {
    try {
      state.loading = true;
      const response = await ChatService.getNotifications();
      state.notifications = response.data;
    } catch (err) {
      state.error = err.response?.data?.message || 'Failed to fetch notifications';
      console.error('Failed to fetch notifications:', err);
    } finally {
      state.loading = false;
    }
  }

  async function markNotificationAsRead(notificationId) {
    try {
      state.loading = true;
      await ChatService.markNotificationAsRead(notificationId);
    } catch (err) {
      state.error = err.response?.data?.message || 'Failed to mark notification as read';
      console.error('Failed to mark notification as read:', err);
    } finally {
      state.loading = false;
    }
  }

  async function markAllNotificationsAsRead() {
    try {
      state.loading = true;
      await ChatService.markAllNotificationsAsRead();
    } catch (err) {
      state.error = err.response?.data?.message || 'Failed to mark all notifications as read';
      console.error('Failed to mark all notifications as read:', err);
    } finally {
      state.loading = false;
    }
  }

  async function markConversationAsRead(conversationId) {
    try {
      state.loading = true;
      await ChatService.markConversationAsRead(conversationId);
    } catch (err) {
      state.error = err.response?.data?.message || 'Failed to mark conversation as read';
      console.error('Failed to mark conversation as read:', err);
    } finally {
      state.loading = false;
    }
  }

  return {
    conversations: computed(() => state.conversations),
    currentConversation: computed(() => state.currentConversation),
    messages: computed(() => state.messages),
    notifications: computed(() => state.notifications),
    loading: computed(() => state.loading),
    error: computed(() => state.error),
    sortedConversations,
    unreadNotificationsCount,
    setCurrentConversation,
    fetchConversations,
    fetchMessages,
    sendMessage,
    createConversation,
    reportMessage,
    fetchNotifications,
    markNotificationAsRead,
    markAllNotificationsAsRead,
    markConversationAsRead
  };
});
