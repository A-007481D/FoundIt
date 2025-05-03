import { defineStore } from 'pinia';
import { reactive, computed } from 'vue';
import NotificationService from '@/services/api/notification';

export const useNotificationStore = defineStore('notification', () => {
  // State
  const state = reactive({
    notifications: [],
    page: 1,
    perPage: 100,
    hasMore: true,
    loading: false,
    error: null,
    meta: null
  });

  // Getters
  const unreadCount = computed(() => 
    state.notifications && state.notifications.filter(n => !n.read_at).length || 0
  );

  // Actions
  async function fetchNotifications(pageNum = 1) {
    try {
      state.loading = true;
      state.error = null;
      
      const response = await NotificationService.getNotifications({
        page: pageNum,
        per_page: state.perPage
      });

      // Handle different API response structures
      const notificationData = response.data || response;
      const notifications = Array.isArray(notificationData) 
        ? notificationData 
        : (notificationData.data || []);
      
      const meta = notificationData.meta || { 
        total: notifications.length,
        current_page: pageNum,
        per_page: state.perPage
      };

      if (pageNum === 1) {
        state.notifications = notifications;
      } else {
        state.notifications = [...state.notifications, ...notifications];
      }

      state.meta = meta;
      state.hasMore = meta.total > (pageNum * state.perPage);
      state.page = pageNum;
    } catch (err) {
      console.error('Failed to fetch notifications:', err);
      state.error = err.response?.data?.message || 'Failed to fetch notifications';
      // Initialize empty state on error
      if (pageNum === 1) {
        state.notifications = [];
        state.meta = { total: 0, current_page: 1, per_page: state.perPage };
        state.hasMore = false;
      }
    } finally {
      state.loading = false;
    }
  }

  async function markAsRead(notificationId) {
    try {
      state.loading = true;
      state.error = null;
      
      await NotificationService.markAsRead(notificationId);
      
      // Update local state
      const notificationIndex = state.notifications.findIndex(n => n.id === notificationId);
      if (notificationIndex !== -1) {
        state.notifications[notificationIndex].read_at = new Date().toISOString();
      }
    } catch (err) {
      state.error = err.response?.data?.message || 'Failed to mark notification as read';
      console.error('Failed to mark notification as read:', err);
    } finally {
      state.loading = false;
    }
  }

  async function markAllAsRead() {
    try {
      state.loading = true;
      state.error = null;
      
      await NotificationService.markAllAsRead();
      
      // Update local state
      if (state.notifications && state.notifications.length) {
        state.notifications.forEach(notification => {
          notification.read_at = new Date().toISOString();
        });
      }
    } catch (err) {
      state.error = err.response?.data?.message || 'Failed to mark all notifications as read';
      console.error('Failed to mark all notifications as read:', err);
    } finally {
      state.loading = false;
    }
  }

  return {
    notifications: computed(() => state.notifications || []),
    page: computed(() => state.page),
    perPage: computed(() => state.perPage),
    hasMore: computed(() => state.hasMore),
    loading: computed(() => state.loading),
    error: computed(() => state.error),
    meta: computed(() => state.meta),
    unreadCount,
    fetchNotifications,
    markAsRead,
    markAllAsRead
  };
});
