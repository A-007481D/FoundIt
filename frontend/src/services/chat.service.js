import axios from 'axios';

const API_URL = 'http://localhost:8000/api';

const axiosInstance = axios.create({
    baseURL: API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

// Add request interceptor to add auth token
axiosInstance.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

export default {
    getConversations() {
        return axiosInstance.get('/chat/conversations');
    },

    getMessages(conversationId) {
        return axiosInstance.get(`/chat/conversations/${conversationId}/messages`);
    },

    sendMessage(conversationId, content) {
        return axiosInstance.post(`/chat/conversations/${conversationId}/messages`, { content });
    },

    createConversation(userId) {
        return axiosInstance.post('/chat/conversations', { user_id: userId });
    },

    reportMessage(messageId, reason) {
        return axiosInstance.post(`/messages/${messageId}/report`, { reason });
    },

    getNotifications() {
        return axiosInstance.get('/notifications');
    },

    markNotificationAsRead(notificationId) {
        return axiosInstance.post(`/notifications/${notificationId}/read`);
    },

    markAllNotificationsAsRead() {
        return axiosInstance.post('/notifications/read-all');
    },

    markConversationAsRead(conversationId) {
        return axiosInstance.post(`/chat/conversations/${conversationId}/read`);
    }
};
