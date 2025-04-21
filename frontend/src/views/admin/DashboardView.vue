<template>
  <div>
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
    
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Total Users</p>
            <h2 class="text-3xl font-bold">{{ stats.totalUsers }}</h2>
          </div>
          <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <p class="text-sm text-green-600 flex items-center">
            <span class="mr-1">+{{ stats.newUsers }}</span> new this week
          </p>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Total Items</p>
            <h2 class="text-3xl font-bold">{{ stats.totalItems }}</h2>
          </div>
          <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <p class="text-sm text-green-600 flex items-center">
            <span class="mr-1">+{{ stats.newItems }}</span> new this week
          </p>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Active Reports</p>
            <h2 class="text-3xl font-bold">{{ stats.activeReports }}</h2>
          </div>
          <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center text-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <p class="text-sm text-red-600 flex items-center">
            <span class="mr-1">{{ stats.newReports }}</span> new unresolved
          </p>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Success Rate</p>
            <h2 class="text-3xl font-bold">{{ stats.successRate }}%</h2>
          </div>
          <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center text-green-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <p class="text-sm text-green-600 flex items-center">
            <span class="mr-1">+{{ stats.successRateChange }}%</span> from last month
          </p>
        </div>
      </div>
    </div>
    
    <!-- Recent Activity and Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Recent Activity -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
        <div class="space-y-4">
          <div v-for="(activity, index) in recentActivity" :key="index" class="flex items-start pb-4 border-b last:border-0 last:pb-0">
            <div class="h-10 w-10 rounded-full flex items-center justify-center mr-3" :class="activityIconClass(activity.type)">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path v-if="activity.type === 'user'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                <path v-else-if="activity.type === 'item'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                <path v-else-if="activity.type === 'report'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium">{{ activity.message }}</p>
              <p class="text-xs text-gray-500 mt-1">{{ activity.time }}</p>
            </div>
          </div>
        </div>
        <div class="mt-4 text-center">
          <button @click="navigateToUsers" class="text-sm text-primary hover:underline">View All Activity</button>
        </div>
      </div>
      
      <!-- Recent Reports -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Recent Reports</h3>
        <div class="space-y-4">
          <div v-for="(report, index) in recentReports" :key="index" class="flex items-start pb-4 border-b last:border-0 last:pb-0">
            <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 mr-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium">{{ report.title }}</p>
              <p class="text-xs text-gray-500 mt-1">{{ report.description }}</p>
              <div class="flex items-center mt-2">
                <span class="text-xs px-2 py-1 rounded-full" :class="reportStatusClass(report.status)">
                  {{ report.status }}
                </span>
                <span class="text-xs text-gray-500 ml-2">{{ report.time }}</span>
              </div>
            </div>
            <div>
              <button @click="viewReport(report)" class="text-sm text-primary hover:underline">View</button>
            </div>
          </div>
        </div>
        <div class="mt-4 text-center">
          <button @click="navigateToReports" class="text-sm text-primary hover:underline">View All Reports</button>
        </div>
      </div>
    </div>
    
    <!-- User Status -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
      <h3 class="text-lg font-semibold mb-4">User Status Overview</h3>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="text-3xl font-bold text-gray-800">{{ userStats.active }}</div>
          <div class="text-sm text-gray-500 mt-1">Active Users</div>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="text-3xl font-bold text-yellow-500">{{ userStats.suspended }}</div>
          <div class="text-sm text-gray-500 mt-1">Suspended Users</div>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="text-3xl font-bold text-red-500">{{ userStats.banned }}</div>
          <div class="text-sm text-gray-500 mt-1">Banned Users</div>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="text-3xl font-bold text-purple-500">{{ userStats.admins }}</div>
          <div class="text-sm text-gray-500 mt-1">Admin Users</div>
        </div>
      </div>
    </div>
    
    <!-- Item Stats -->
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold mb-4">Item Status Overview</h3>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="text-3xl font-bold text-blue-500">{{ itemStats.lost }}</div>
          <div class="text-sm text-gray-500 mt-1">Lost Items</div>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="text-3xl font-bold text-green-500">{{ itemStats.found }}</div>
          <div class="text-sm text-gray-500 mt-1">Found Items</div>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="text-3xl font-bold text-purple-500">{{ itemStats.matched }}</div>
          <div class="text-sm text-gray-500 mt-1">Matched Items</div>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
          <div class="text-3xl font-bold text-red-500">{{ itemStats.reported }}</div>
          <div class="text-sm text-gray-500 mt-1">Reported Items</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import adminService from '@/services/admin.service';
import { format, formatDistance } from 'date-fns';

const router = useRouter();
const loading = ref(true);
const error = ref(null);

// Dashboard stats
const stats = ref({
  totalUsers: 0,
  newUsers: 0,
  totalItems: 0,
  newItems: 0,
  activeReports: 0,
  newReports: 0,
  successRate: 0,
  successRateChange: 0
});

// Recent activity
const recentActivity = ref([]);

// Recent reports
const recentReports = ref([]);

// User stats
const userStats = ref({
  active: 0,
  suspended: 0,
  banned: 0,
  admins: 0
});

// Item stats
const itemStats = ref({
  lost: 0,
  found: 0,
  matched: 0,
  reported: 0
});

// Fetch dashboard data
const fetchDashboardData = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    const response = await adminService.getDashboardStats();
    const data = response.data;
    
    // Update stats
    stats.value = {
      totalUsers: data.counts.users,
      newUsers: data.users.trend[data.users.trend.length - 1].count,
      totalItems: data.counts.items,
      newItems: data.items.trend[data.items.trend.length - 1].count,
      activeReports: data.reports.pending,
      newReports: data.reports.pending,
      successRate: calculateSuccessRate(data),
      successRateChange: 2.4 // This would need to be calculated based on historical data
    };
    
    // Update user stats
    userStats.value = {
      active: data.users.active,
      suspended: data.users.suspended,
      banned: data.users.banned,
      admins: data.users.admins
    };
    
    // Update item stats
    itemStats.value = {
      lost: data.items.lost,
      found: data.items.found,
      matched: 0, // This would need to be added to the backend
      reported: data.items.reported
    };
    
    // Generate recent activity from the data
    generateRecentActivity(data);
    
    // Generate recent reports
    generateRecentReports(data);
    
  } catch (err) {
    console.error('Error fetching dashboard data:', err);
    error.value = 'Failed to load dashboard data. Please try again.';
  } finally {
    loading.value = false;
  }
};

// Calculate success rate based on found vs lost items
const calculateSuccessRate = (data) => {
  if (data.items.found === 0 || data.items.lost === 0) return 0;
  return Math.round((data.items.found / (data.items.lost + data.items.found)) * 100);
};

// Generate recent activity from the data
const generateRecentActivity = (data) => {
  const activity = [];
  
  // Add recent users
  data.users.recent.forEach(user => {
    activity.push({
      type: 'user',
      message: `New user registered: ${user.firstname} ${user.lastname}`,
      time: formatDistance(new Date(user.created_at), new Date(), { addSuffix: true })
    });
  });
  
  // Add recent items
  data.items.recent.forEach(item => {
    activity.push({
      type: 'item',
      message: `New item ${item.type === 'lost' ? 'reported as lost' : 'reported as found'}: ${item.title}`,
      time: formatDistance(new Date(item.created_at), new Date(), { addSuffix: true })
    });
  });
  
  // Add recent reports
  data.reports.recent.forEach(report => {
    activity.push({
      type: 'report',
      message: `New report submitted: ${report.reason.substring(0, 30)}${report.reason.length > 30 ? '...' : ''}`,
      time: formatDistance(new Date(report.created_at), new Date(), { addSuffix: true })
    });
  });
  
  // Sort by time (most recent first) and limit to 5
  activity.sort((a, b) => new Date(b.time) - new Date(a.time));
  recentActivity.value = activity.slice(0, 5);
};

// Generate recent reports from the data
const generateRecentReports = (data) => {
  const reports = data.reports.recent.map(report => {
    return {
      id: report.id,
      title: report.reason.substring(0, 30) + (report.reason.length > 30 ? '...' : ''),
      description: report.details ? (report.details.substring(0, 50) + (report.details.length > 50 ? '...' : '')) : 'No details provided',
      status: report.status === 'pending' ? 'New' : (report.status === 'resolved' ? 'Resolved' : 'Under Review'),
      time: formatDistance(new Date(report.created_at), new Date(), { addSuffix: true })
    };
  });
  
  recentReports.value = reports;
};

// Navigation functions
const navigateToReports = () => {
  router.push('/admin/reports');
};

const navigateToUsers = () => {
  router.push('/admin/users');
};

const navigateToItems = () => {
  router.push('/admin/items');
};

const viewReport = (report) => {
  router.push(`/admin/reports?id=${report.id}`);
};

// Helper functions for styling
const activityIconClass = (type) => {
  const classes = {
    user: 'bg-blue-100 text-blue-600',
    item: 'bg-purple-100 text-purple-600',
    report: 'bg-red-100 text-red-600',
    default: 'bg-gray-100 text-gray-600'
  };
  
  return classes[type] || classes.default;
};

const reportStatusClass = (status) => {
  const classes = {
    'New': 'bg-red-100 text-red-600',
    'Under Review': 'bg-yellow-100 text-yellow-600',
    'Resolved': 'bg-green-100 text-green-600',
    'default': 'bg-gray-100 text-gray-600'
  };
  
  return classes[status] || classes.default;
};

// Fetch data on component mount
onMounted(() => {
  fetchDashboardData();
});
</script>
