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
          <div v-for="notif in paginatedNotifications" :key="notif.id" class="p-4 border rounded-md flex justify-between items-center">
            <div>
              <p v-if="notif.type === 'App\\Notifications\\ReportCreated'">
                <strong>{{ notif.data.reporter.name }}</strong> reported
                <span v-if="notif.data.reportable_type === 'User'">user</span><span v-else>item</span> #{{ notif.data.reportable_id }}: "{{ notif.data.reason }}"
              </p>
              <p v-else-if="notif.type === 'App\\Notifications\\NewMatchFound'">Match found for your item.</p>
              <p class="text-xs text-muted-foreground">{{ formatDate(notif.created_at) }}</p>
            </div>
            <button class="text-primary text-sm underline" @click="markAsRead(notif)">Mark as read</button>
          </div>
        </div>
        <div v-if="hasMore" class="text-center mt-4">
          <button class="bg-primary text-white px-4 py-2 rounded" @click="nextPage">Load more</button>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { toast } from 'vue3-toastify';
import NotificationService from '@/services/api/notification';
import { useNotificationStore } from '@/stores/notification.store';

const notificationStore = useNotificationStore();
const loading = ref(false);
const notifications = ref([]);
const searchQuery = ref('');
const typeFilter = ref('all');
const statusFilter = ref('all');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const prefs = ref({
  email_notifications: true,
  push_notifications: true,
  item_updates: true
});

// Computed values for pagination
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage.value);
const endIndex = computed(() => Math.min(startIndex.value + itemsPerPage.value, notifications.value.length));
const paginatedNotifications = computed(() => notifications.value.slice(startIndex.value, endIndex.value));

// Computed values for filters
const unreadCount = computed(() => 
  notifications.value.filter(n => n.read_at === null).length
);

const hasMore = computed(() => 
  notifications.value.length > paginatedNotifications.value.length
);

// Methods
async function fetchNotifications() {
  loading.value = true;
  try {
    const response = await NotificationService.getNotifications({
      search: searchQuery.value,
      type: typeFilter.value === 'all' ? '' : typeFilter.value,
      status: statusFilter.value === 'all' ? '' : statusFilter.value,
      page: currentPage.value,
      per_page: itemsPerPage.value
    });
    notifications.value = response.data.data;
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to fetch notifications');
    console.error('Failed to fetch notifications:', err);
  } finally {
    loading.value = false;
  }
}

function handleSearch() {
  currentPage.value = 1;
  fetchNotifications();
}

function filterNotifications() {
  currentPage.value = 1;
  fetchNotifications();
}

function prevPage() {
  if (currentPage.value > 1) {
    currentPage.value--;
    fetchNotifications();
  }
}

function nextPage() {
  currentPage.value++;
  fetchNotifications();
}

async function markAsRead(notification) {
  try {
    await NotificationService.markAsRead(notification.id);
    notification.read_at = new Date().toISOString();
    notificationStore.updateUnreadCount();
    toast.success('Notification marked as read');
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to mark notification as read');
    console.error('Failed to mark notification as read:', err);
  }
}

async function markAllAsRead() {
  try {
    await NotificationService.markAllAsRead();
    notifications.value.forEach(n => n.read_at = new Date().toISOString());
    notificationStore.updateUnreadCount();
    toast.success('All notifications marked as read');
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to mark all notifications as read');
    console.error('Failed to mark all notifications as read:', err);
  }
}

function getNotificationTypeClass(type) {
  const classes = {
    'info': 'bg-blue-100 text-blue-800',
    'success': 'bg-green-100 text-green-800',
    'warning': 'bg-yellow-100 text-yellow-800',
    'error': 'bg-red-100 text-red-800'
  };
  return classes[type] || 'bg-gray-100 text-gray-800';
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleString();
}

async function loadPrefs() {
  try {
    const res = await NotificationService.getPreferences();
    if (res.data.user.notification_preferences) {
      Object.assign(prefs.value, res.data.user.notification_preferences);
    }
  } catch (e) {
    console.error('Error loading preferences', e);
  }
};

async function savePrefs() {
  try {
    await NotificationService.savePreferences(prefs.value);
  } catch (e) {
    console.error('Error saving preferences', e);
  }
};

// Load notifications on component mount
onMounted(async () => {
  await loadPrefs();
  await fetchNotifications();
});
</script>

<style scoped>
/* custom styles */
</style>
