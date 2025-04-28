import { defineStore } from 'pinia';
import axiosInstance from '@/services/apiClient';

export const useNotificationStore = defineStore('notification', {
  state: () => ({
    notifications: [],
    page: 1,
    perPage: 20,
    hasMore: true
  }),
  getters: {
    unreadCount: (state) => state.notifications.filter(n => !n.read_at).length
  },
  actions: {
    async fetchNotifications(page = 1) {
      try {
        const res = await axiosInstance.get('/notifications', { params: { per_page: this.perPage, page } });
        const { data, current_page, last_page } = res.data;
        if (page === 1) {
          this.notifications = data;
        } else {
          this.notifications.push(...data);
        }
        this.page = current_page;
        this.hasMore = current_page < last_page;
      } catch (e) {
        console.error('Failed to fetch notifications', e);
      }
    },
    addNotification(notification) {
      this.notifications.unshift(notification);
    }
  }
});
