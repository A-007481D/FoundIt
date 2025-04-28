<template>
  <div class="container mx-auto px-6 md:px-20 py-6">
    <h1 class="text-2xl font-semibold mb-4">Notification Center</h1>
    <section class="mb-6">
      <h2 class="font-semibold mb-2">Settings</h2>
      <div class="flex flex-col md:flex-row gap-4">
        <label class="flex items-center gap-2">
          <input type="checkbox" v-model="prefs.email_notifications" @change="savePrefs" />
          Email Notifications
        </label>
        <label class="flex items-center gap-2">
          <input type="checkbox" v-model="prefs.push_notifications" @change="savePrefs" />
          Push Notifications
        </label>
        <label class="flex items-center gap-2">
          <input type="checkbox" v-model="prefs.item_updates" @change="savePrefs" />
          Item Updates
        </label>
      </div>
    </section>
    <section>
      <h2 class="font-semibold mb-2">Notifications</h2>
      <div v-if="loading" class="text-center py-4">Loading...</div>
      <div v-else>
        <div v-if="!notifications.length" class="text-muted">No notifications in the last 30 days.</div>
        <div v-else class="space-y-4">
          <div v-for="notif in notifications" :key="notif.id" class="p-4 border rounded-md flex justify-between items-center">
            <div>
              <p v-if="notif.type === 'App\\Notifications\\ReportCreated'">
                <strong>{{ notif.data.reporter.name }}</strong> reported
                <span v-if="notif.data.reportable_type === 'User'">user</span><span v-else>item</span> #{{ notif.data.reportable_id }}: "{{ notif.data.reason }}"
              </p>
              <p v-else-if="notif.type === 'App\\Notifications\\NewMatchFound'">Match found for your item.</p>
              <p class="text-xs text-muted-foreground">{{ new Date(notif.created_at).toLocaleString() }}</p>
            </div>
            <button class="text-primary text-sm underline" @click="markRead(notif.id)">Mark as read</button>
          </div>
        </div>
        <div v-if="hasMore" class="text-center mt-4">
          <button class="bg-primary text-white px-4 py-2 rounded" @click="loadMore">Load more</button>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axiosInstance from '@/services/apiClient';
import { useNotificationStore } from '@/stores/notification.store';

const notificationStore = useNotificationStore();
const loading = ref(false);
const prefs = ref({
  email_notifications: true,
  push_notifications: true,
  item_updates: true
});

const notifications = computed(() => notificationStore.notifications);
const hasMore = computed(() => notificationStore.hasMore);

const loadNotifications = async () => {
  loading.value = true;
  await notificationStore.fetchNotifications(1);
  loading.value = false;
};

const loadMore = async () => {
  const nextPage = notificationStore.page + 1;
  await notificationStore.fetchNotifications(nextPage);
};

const markRead = async (id) => {
  try {
    await axiosInstance.post(`/notifications/${id}/read`);
    notificationStore.notifications = notificationStore.notifications.filter(n => n.id !== id);
  } catch (e) {
    console.error('Error marking notification read', e);
  }
};

const loadPrefs = async () => {
  try {
    const res = await axiosInstance.get('/profile');
    if (res.data.user.notification_preferences) {
      Object.assign(prefs.value, res.data.user.notification_preferences);
    }
  } catch (e) {
    console.error('Error loading preferences', e);
  }
};

const savePrefs = async () => {
  try {
    await axiosInstance.put('/profile/notifications', prefs.value);
  } catch (e) {
    console.error('Error saving preferences', e);
  }
};

onMounted(async () => {
  await loadPrefs();
  await loadNotifications();
});
</script>

<style scoped>
/* custom styles */
</style>
