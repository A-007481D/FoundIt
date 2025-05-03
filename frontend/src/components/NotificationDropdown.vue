<template>
  <div class="relative">
    <button @click="toggle" class="relative focus:outline-none">
      <Bell class="h-6 w-6 text-gray-700"/>
      <span v-if="unreadCount" class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
        {{ unreadCount }}
      </span>
    </button>
    <div v-if="open" class="absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-md z-50 overflow-hidden">
      <div class="py-2">
        <ul>
          <li v-for="notif in recentNotifications" :key="notif.id" class="flex items-start px-4 py-2 hover:bg-gray-100 cursor-pointer" @click="navigate(notif)">
            <component :is="iconFor(notif.type)" class="h-5 w-5 text-gray-600 mr-3"/>
            <div class="flex-1">
              <p class="text-sm text-gray-800">{{ formatMessage(notif) }}</p>
              <p class="text-xs text-gray-500">{{ formatTime(notif.created_at) }}</p>
            </div>
          </li>
          <li v-if="!recentNotifications.length" class="text-center text-gray-500 py-4">No recent notifications</li>
          <li v-else class="text-center py-2 border-t">
            <router-link :to="{ name: 'Notifications' }" class="text-blue-600 hover:text-blue-800">View all notifications</router-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useNotificationStore } from '@/stores/notification.store';
import { Bell, User, FileText, Search, Info, AlertCircle } from 'lucide-vue-next';
import { useRouter } from 'vue-router';
import { formatDistanceToNow } from 'date-fns';

const router = useRouter();
const store = useNotificationStore();
const notifications = computed(() => store.notifications);
const unreadCount = computed(() => store.unreadCount);
const open = ref(false);

// Debug logging
onMounted(() => {
  console.log('Notifications:', notifications.value);
  console.log('Unread count:', unreadCount.value);
});

// Show only the last 5 notifications
const recentNotifications = computed(() => {
  console.log('Recent notifications:', notifications.value.slice(0, 5));
  return notifications.value.slice(0, 5);
});

function toggle() {
  open.value = !open.value;
}

function formatTime(time) {
  return formatDistanceToNow(new Date(time), { addSuffix: true });
}

function iconFor(type) {
  const icons = {
    'App\\Notifications\\NewMatchFound': Search,
    'App\\Notifications\\ReportCreated': FileText,
    'App\\Notifications\\ItemFound': Info,
    'App\\Notifications\\ItemLost': Info,
    'App\\Notifications\\System': AlertCircle
  };
  return icons[type] || Bell;
}

function formatMessage(notif) {
  if (notif.type === 'App\\Notifications\\NewMatchFound') {
    return 'Match found for your item';
  } else if (notif.type === 'App\\Notifications\\ReportCreated') {
    return 'New report created';
  } else if (notif.type === 'App\\Notifications\\ItemFound') {
    return 'Your item has been found';
  } else if (notif.type === 'App\\Notifications\\ItemLost') {
    return 'Your item has been reported lost';
  } else {
    return notif.data?.message || 'System notification';
  }
}

function navigate(notif) {
  // Navigate based on notification type
  if (notif.type === 'App\\Notifications\\NewMatchFound') {
    router.push({ name: 'item', params: { id: notif.data.item_id } });
  } else if (notif.type === 'App\\Notifications\\ReportCreated') {
    router.push({ name: 'report', params: { id: notif.data.report_id } });
  } else {
    router.push({ name: 'notifications' });
  }
  open.value = false;
}
</script>
