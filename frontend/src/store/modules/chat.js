import chatService from '@/services/chat.service';

export default {
    namespaced: true,

    state: {
        conversations: [],
        currentConversation: null,
        messages: [],
        notifications: [],
        loading: false,
        error: null
    },

    mutations: {
        SET_CONVERSATIONS(state, conversations) {
            state.conversations = conversations;
        },
        SET_CURRENT_CONVERSATION(state, conversation) {
            state.currentConversation = conversation;
        },
        SET_MESSAGES(state, messages) {
            state.messages = messages;
        },
        ADD_MESSAGE(state, message) {
            state.messages.push(message);
        },
        SET_NOTIFICATIONS(state, notifications) {
            state.notifications = notifications;
        },
        REMOVE_NOTIFICATION(state, notificationId) {
            state.notifications = state.notifications.filter(n => n.id !== notificationId);
        },
        SET_LOADING(state, loading) {
            state.loading = loading;
        },
        SET_ERROR(state, error) {
            state.error = error;
        }
    },

    actions: {
        async fetchConversations({ commit }) {
            try {
                commit('SET_LOADING', true);
                const response = await chatService.getConversations();
                commit('SET_CONVERSATIONS', response.data);
            } catch (error) {
                commit('SET_ERROR', error.response?.data?.message || 'Error fetching conversations');
            } finally {
                commit('SET_LOADING', false);
            }
        },

        async fetchMessages({ commit }, conversationId) {
            try {
                commit('SET_LOADING', true);
                const response = await chatService.getMessages(conversationId);
                commit('SET_MESSAGES', response.data);
            } catch (error) {
                commit('SET_ERROR', error.response?.data?.message || 'Error fetching messages');
            } finally {
                commit('SET_LOADING', false);
            }
        },

        async sendMessage({ commit }, { conversationId, content }) {
            try {
                const response = await chatService.sendMessage(conversationId, content);
                commit('ADD_MESSAGE', response.data);
                return response.data;
            } catch (error) {
                commit('SET_ERROR', error.response?.data?.message || 'Error sending message');
                throw error;
            }
        },

        async createConversation({ commit }, userId) {
            try {
                const response = await chatService.createConversation(userId);
                return response.data.conversation_id;
            } catch (error) {
                commit('SET_ERROR', error.response?.data?.message || 'Error creating conversation');
                throw error;
            }
        },

        async reportMessage({ commit }, { messageId, reason }) {
            try {
                await chatService.reportMessage(messageId, reason);
            } catch (error) {
                commit('SET_ERROR', error.response?.data?.message || 'Error reporting message');
                throw error;
            }
        },

        async fetchNotifications({ commit }) {
            try {
                const response = await chatService.getNotifications();
                commit('SET_NOTIFICATIONS', response.data);
            } catch (error) {
                commit('SET_ERROR', error.response?.data?.message || 'Error fetching notifications');
            }
        },

        async markNotificationAsRead({ commit }, notificationId) {
            try {
                await chatService.markNotificationAsRead(notificationId);
                commit('REMOVE_NOTIFICATION', notificationId);
            } catch (error) {
                commit('SET_ERROR', error.response?.data?.message || 'Error marking notification as read');
            }
        },

        async markAllNotificationsAsRead({ commit }) {
            try {
                await chatService.markAllNotificationsAsRead();
                commit('SET_NOTIFICATIONS', []);
            } catch (error) {
                commit('SET_ERROR', error.response?.data?.message || 'Error marking all notifications as read');
            }
        }
    },

    getters: {
        unreadNotificationsCount: state => state.notifications.length,
        sortedConversations: state => [...state.conversations].sort((a, b) => {
            const dateA = a.last_message?.created_at || 0;
            const dateB = b.last_message?.created_at || 0;
            return new Date(dateB) - new Date(dateA);
        })
    }
};
