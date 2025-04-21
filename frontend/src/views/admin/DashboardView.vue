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
          <button class="text-sm text-primary hover:underline">View All Activity</button>
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
              <button class="text-sm text-primary hover:underline">View</button>
            </div>
          </div>
        </div>
        <div class="mt-4 text-center">
          <button class="text-sm text-primary hover:underline">View All Reports</button>
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
import { ref, computed } from 'vue';

// Mock data for dashboard stats
const stats = ref({
  totalUsers: 1248,
  newUsers: 42,
  totalItems: 3567,
  newItems: 128,
  activeReports: 23,
  newReports: 5,
  successRate: 87,
  successRateChange: 2.4
});

// Mock data for recent activity
const recentActivity = ref([
  { 
    type: 'user', 
    message: 'New user registered: Ahmed Mahmoud', 
    time: '10 minutes ago' 
  },
  { 
    type: 'item', 
    message: 'New item reported as found: MacBook Pro', 
    time: '25 minutes ago' 
  },
  { 
    type: 'report', 
    message: 'New report submitted against item #4532', 
    time: '1 hour ago' 
  },
  { 
    type: 'user', 
    message: 'User Karim Hassan was suspended for policy violation', 
    time: '2 hours ago' 
  },
  { 
    type: 'item', 
    message: 'Item #3211 (iPhone 13) was matched with its owner', 
    time: '3 hours ago' 
  }
]);

// Mock data for recent reports
const recentReports = ref([
  {
    title: 'Inappropriate item description',
    description: 'User reported item #4532 for inappropriate content',
    status: 'New',
    time: '1 hour ago'
  },
  {
    title: 'Fake lost item report',
    description: 'Item #3987 reported as potentially fraudulent',
    status: 'Under Review',
    time: '3 hours ago'
  },
  {
    title: 'Harassment in item comments',
    description: 'User reported harassment in comments on item #2156',
    status: 'Resolved',
    time: '1 day ago'
  },
  {
    title: 'Suspicious user activity',
    description: 'Multiple reports against user ID #567',
    status: 'Under Review',
    time: '2 days ago'
  }
]);

// Mock data for user stats
const userStats = ref({
  active: 1184,
  suspended: 42,
  banned: 22,
  admins: 5
});

// Mock data for item stats
const itemStats = ref({
  lost: 1845,
  found: 1722,
  matched: 1124,
  reported: 87
});

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
</script>
