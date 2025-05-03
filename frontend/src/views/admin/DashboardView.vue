<template>
  <div>
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
    
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Total Items</p>
            <h2 class="text-3xl font-bold">{{ stats.totalItems }}</h2>
          </div>
          <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <p class="text-sm text-green-600 flex items-center">
            <span class="mr-1">{{ stats.activeItems }}</span> active
          </p>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Total Users</p>
            <h2 class="text-3xl font-bold">{{ stats.totalUsers }}</h2>
          </div>
          <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <p class="text-sm text-green-600 flex items-center">
            <span class="mr-1">{{ stats.activeUsers }}</span> active
          </p>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Lost Items</p>
            <h2 class="text-3xl font-bold">{{ stats.lostItems }}</h2>
          </div>
          <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center text-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <p class="text-sm text-red-600 flex items-center">
            <span class="mr-1">{{ stats.lostItems }}</span> lost
          </p>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Found Items</p>
            <h2 class="text-3xl font-bold">{{ stats.foundItems }}</h2>
          </div>
          <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center text-green-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <p class="text-sm text-green-600 flex items-center">
            <span class="mr-1">{{ stats.foundItems }}</span> found
          </p>
        </div>
      </div>
    </div>
    
    <!-- Recent Activity and Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Recent Items -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Recent Items</h3>
        <div class="space-y-4">
          <div v-for="(item, index) in recentItems" :key="index" class="flex items-start pb-4 border-b last:border-0 last:pb-0">
            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium">{{ item.title }}</p>
              <p class="text-xs text-gray-500 mt-1">{{ item.description }}</p>
            </div>
          </div>
        </div>
        <div class="mt-4 text-center">
          <button @click="navigateToItems" class="text-sm text-primary hover:underline">View All Items</button>
        </div>
      </div>
      
      <!-- Recent Users -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Recent Users</h3>
        <div class="space-y-4">
          <div v-for="(user, index) in recentUsers" :key="index" class="flex items-start pb-4 border-b last:border-0 last:pb-0">
            <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 mr-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium">{{ user.firstname }} {{ user.lastname }}</p>
              <p class="text-xs text-gray-500 mt-1">{{ user.email }}</p>
            </div>
          </div>
        </div>
        <div class="mt-4 text-center">
          <button @click="navigateToUsers" class="text-sm text-primary hover:underline">View All Users</button>
        </div>
      </div>
    </div>
    
    <!-- User Status -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
      <h3 class="text-lg font-semibold mb-4">User Status Overview</h3>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="text-3xl font-bold text-gray-800">{{ stats.activeUsers }}</div>
          <div class="text-sm text-gray-500 mt-1">Active Users</div>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="text-3xl font-bold text-yellow-500">{{ stats.bannedUsers }}</div>
          <div class="text-sm text-gray-500 mt-1">Banned Users</div>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="text-3xl font-bold text-red-500">{{ stats.totalUsers - stats.activeUsers - stats.bannedUsers }}</div>
          <div class="text-sm text-gray-500 mt-1">Inactive Users</div>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="text-3xl font-bold text-purple-500">{{ stats.totalUsers }}</div>
          <div class="text-sm text-gray-500 mt-1">Total Users</div>
        </div>
      </div>
    </div>
    
    <!-- Item Stats -->
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold mb-4">Item Status Overview</h3>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="text-3xl font-bold text-blue-500">{{ stats.lostItems }}</div>
          <div class="text-sm text-gray-500 mt-1">Lost Items</div>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="text-3xl font-bold text-green-500">{{ stats.foundItems }}</div>
          <div class="text-sm text-gray-500 mt-1">Found Items</div>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="text-3xl font-bold text-purple-500">{{ stats.totalItems - stats.lostItems - stats.foundItems }}</div>
          <div class="text-sm text-gray-500 mt-1">Inactive Items</div>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="text-3xl font-bold text-red-500">{{ stats.totalItems }}</div>
          <div class="text-sm text-gray-500 mt-1">Total Items</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { toast } from 'vue3-toastify';
import AdminItemService from '@/services/api/admin/item';
import AdminUserService from '@/services/api/admin/user';

const loading = ref(false);
const stats = ref({
  totalItems: 0,
  activeItems: 0,
  lostItems: 0,
  foundItems: 0,
  totalUsers: 0,
  activeUsers: 0,
  bannedUsers: 0,
  totalReports: 0,
  pendingReports: 0
});

const recentItems = ref([]);
const recentUsers = ref([]);

async function fetchDashboardData() {
  loading.value = true;
  try {
    // Fetch item statistics
    const itemStats = await AdminItemService.getItemStats();
    stats.value.totalItems = itemStats.total;
    stats.value.activeItems = itemStats.active;
    stats.value.lostItems = itemStats.lost;
    stats.value.foundItems = itemStats.found;

    // Fetch user statistics
    const userStats = await AdminUserService.getUserStats();
    stats.value.totalUsers = userStats.total;
    stats.value.activeUsers = userStats.active;
    stats.value.bannedUsers = userStats.banned;

    // Fetch report statistics
    const reportStats = await AdminUserService.getReportStats();
    stats.value.totalReports = reportStats.total;
    stats.value.pendingReports = reportStats.pending;

    // Fetch recent items
    const itemsResponse = await AdminItemService.getRecentItems();
    recentItems.value = itemsResponse.data;

    // Fetch recent users
    const usersResponse = await AdminUserService.getRecentUsers();
    recentUsers.value = usersResponse.data;
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to fetch dashboard data');
    console.error('Failed to fetch dashboard data:', err);
  } finally {
    loading.value = false;
  }
}

// Load dashboard data on component mount
onMounted(() => {
  fetchDashboardData();
});
</script>
