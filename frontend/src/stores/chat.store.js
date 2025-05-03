import { defineStore } from 'pinia';
import chatService from '@/services/chat.service';

export const useChatStore = defineStore('chat', {
    state: () => ({
        conversations: [],
        currentConversation: null,
        messages: [],
        notifications: [],
        loading: false,
        error: null
    }),

    getters: {
        sortedConversations: (state) => {
            return [...state.conversations].sort((a, b) => {
                const dateA = a.last_message?.created_at || 0;
                const dateB = b.last_message?.created_at || 0;
                return new Date(dateB) - new Date(dateA);
            });
        },
        unreadNotificationsCount: (state) => state.notifications.length
    },

    actions: {
        setCurrentConversation(conversation) {
            this.currentConversation = conversation;
            // Mark all messages as read for this conversation if there are unread
            if (conversation.unread_count > 0) {
                this.markConversationAsRead(conversation.id);
            }
        },

        async fetchConversations() {
            try {
                this.loading = true;
                const response = await chatService.getConversations();
                this.conversations = response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Error fetching conversations';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async fetchMessages(conversationId) {
            try {
                this.loading = true;
                const response = await chatService.getMessages(conversationId);
                this.messages = response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Error fetching messages';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async sendMessage({ conversationId, content }) {
            try {
                const response = await chatService.sendMessage(conversationId, content);
                this.messages.push(response.data);
                return response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Error sending message';
                throw error;
            }
        },

        async createConversation(userId) {
            try {
                const response = await chatService.createConversation(userId);
                return response.data.conversation_id;
            } catch (error) {
                this.error = error.response?.data?.message || 'Error creating conversation';
                throw error;
            }
        },

        async reportMessage({ messageId, reason }) {
            try {
                await chatService.reportMessage(messageId, reason);
            } catch (error) {
                this.error = error.response?.data?.message || 'Error reporting message';
                throw error;
            }
        },

        async fetchNotifications() {
            try {
                const response = await chatService.getNotifications();
                this.notifications = response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Error fetching notifications';
                throw error;
            }
        },

        async markNotificationAsRead(notificationId) {
            try {
                await chatService.markNotificationAsRead(notificationId);
                this.notifications = this.notifications.filter(n => n.id !== notificationId);
            } catch (error) {
                this.error = error.response?.data?.message || 'Error marking notification as read';
                throw error;
            }
        },

        async markAllNotificationsAsRead() {
            try {
                await chatService.markAllNotificationsAsRead();
                this.notifications = [];
            } catch (error) {
                this.error = error.response?.data?.message || 'Error marking all notifications as read';
                throw error;
            }
        },

        async markConversationAsRead(conversationId) {
            try {
                await chatService.markConversationAsRead(conversationId);
                // Update local unread_count for this conversation
                const conv = this.conversations.find(c => c.id === conversationId);
                if (conv) conv.unread_count = 0;
            } catch (error) {
                // Optionally handle error
                console.error('Failed to mark conversation as read:', error);
            }
        }
    }
});
