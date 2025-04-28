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
          <li v-for="notif in notifications" :key="notif.id" class="flex items-start px-4 py-2 hover:bg-gray-100 cursor-pointer" @click="navigate(notif)">
            <component :is="iconFor(notif.data.reportable_type)" class="h-5 w-5 text-gray-600 mr-3"/>
            <div class="flex-1">
              <p class="text-sm text-gray-800">{{ notif.data.reason }}</p>
              <p class="text-xs text-gray-500">{{ formatTime(notif.created_at) }}</p>
            </div>
          </li>
          <li v-if="!notifications.length" class="text-center text-gray-500 py-4">No notifications</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useNotificationStore } from '@/stores/notification.store';
import { Bell, User, FileText } from 'lucide-vue-next';
import { useRouter } from 'vue-router';
import { formatDistanceToNow } from 'date-fns';

const router = useRouter();
const store = useNotificationStore();
const notifications = computed(() => store.notifications);
const unreadCount = computed(() => store.unreadCount);
const open = ref(false);

function toggle() {
  open.value = !open.value;
}

function formatTime(time) {
  return formatDistanceToNow(new Date(time), { addSuffix: true });
}

function iconFor(type) {
  const t = String(type).toLowerCase();
  if (t === 'user') return User;
  if (t === 'item') return FileText;
  return Bell;
}

function navigate(notif) {
  router.push({ name: 'AdminReports' });
  open.value = false;
}
</script>
