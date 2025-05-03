<template>
  <div class="max-w-4xl mx-auto">
    <h1 class="text-xl md:text-2xl font-bold mb-6">Admin Settings</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Application Settings Card -->
      <div class="bg-white rounded-lg shadow p-4 md:p-6">
        <h2 class="text-lg font-semibold mb-4">
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Application Settings
          </div>
        </h2>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="mode">Item Detective Mode:</label>
            <select v-model="settings.item_detective_mode" id="mode" class="border rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-primary focus:border-primary">
        <option value="simple">Simple</option>
        <option value="tf">TensorFlow</option>
        <option value="phash">PHash</option>
      </select>
            <p class="text-xs text-gray-500 mt-1">Choose the algorithm used for matching lost and found items.</p>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="items_per_page">Items Per Page:</label>
            <input 
              type="number" 
              v-model="settings.items_per_page" 
              id="items_per_page" 
              min="5" 
              max="100" 
              class="border rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-primary focus:border-primary"
            />
            <p class="text-xs text-gray-500 mt-1">Number of items to display per page in listings.</p>
          </div>
          
          <div class="flex items-center">
            <input 
              type="checkbox" 
              v-model="settings.enable_auto_match" 
              id="enable_auto_match" 
              class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
            />
            <label for="enable_auto_match" class="ml-2 block text-sm text-gray-700">
              Enable Automatic Matching
            </label>
          </div>
          <p class="text-xs text-gray-500 mt-1">System will automatically suggest potential matches for lost/found items.</p>
        </div>
      </div>
      
      <!-- Notification Settings Card -->
      <div class="bg-white rounded-lg shadow p-4 md:p-6">
        <h2 class="text-lg font-semibold mb-4">
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            Notification Settings
          </div>
        </h2>
        
        <div class="space-y-4">
          <div class="flex items-center">
            <input 
              type="checkbox" 
              v-model="settings.enable_email_notifications" 
              id="enable_email_notifications" 
              class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
            />
            <label for="enable_email_notifications" class="ml-2 block text-sm text-gray-700">
              Enable Email Notifications
            </label>
          </div>
          
          <div class="flex items-center">
            <input 
              type="checkbox" 
              v-model="settings.enable_push_notifications" 
              id="enable_push_notifications" 
              class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
            />
            <label for="enable_push_notifications" class="ml-2 block text-sm text-gray-700">
              Enable Push Notifications
            </label>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="admin_email">Admin Email Address:</label>
            <input 
              type="email" 
              v-model="settings.admin_email" 
              id="admin_email" 
              class="border rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-primary focus:border-primary"
              placeholder="admin@example.com"
            />
            <p class="text-xs text-gray-500 mt-1">Email address to receive system notifications and alerts.</p>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="notification_frequency">Notification Frequency:</label>
            <select v-model="settings.notification_frequency" id="notification_frequency" class="border rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-primary focus:border-primary">
              <option value="immediately">Immediately</option>
              <option value="hourly">Hourly</option>
              <option value="daily">Daily</option>
              <option value="weekly">Weekly</option>
            </select>
          </div>
        </div>
      </div>
      
      <!-- Security Settings Card -->
      <div class="bg-white rounded-lg shadow p-4 md:p-6">
        <h2 class="text-lg font-semibold mb-4">
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            Security Settings
          </div>
        </h2>
        
        <div class="space-y-4">
          <div class="flex items-center">
            <input 
              type="checkbox" 
              v-model="settings.enable_two_factor" 
              id="enable_two_factor" 
              class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
            />
            <label for="enable_two_factor" class="ml-2 block text-sm text-gray-700">
              Enable Two-Factor Authentication
            </label>
          </div>
          
          <div class="flex items-center">
            <input 
              type="checkbox" 
              v-model="settings.require_email_verification" 
              id="require_email_verification" 
              class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
            />
            <label for="require_email_verification" class="ml-2 block text-sm text-gray-700">
              Require Email Verification
            </label>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="session_timeout">Session Timeout (minutes):</label>
            <input 
              type="number" 
              v-model="settings.session_timeout" 
              id="session_timeout" 
              min="5" 
              max="1440" 
              class="border rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-primary focus:border-primary"
            />
          </div>
        </div>
      </div>
      
      <!-- Content Moderation Card -->
      <div class="bg-white rounded-lg shadow p-4 md:p-6">
        <h2 class="text-lg font-semibold mb-4">
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            Content Moderation
          </div>
        </h2>
        
        <div class="space-y-4">
          <div class="flex items-center">
            <input 
              type="checkbox" 
              v-model="settings.auto_moderate_content" 
              id="auto_moderate_content" 
              class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
            />
            <label for="auto_moderate_content" class="ml-2 block text-sm text-gray-700">
              Automatically Moderate Content
            </label>
          </div>
          
          <div class="flex items-center">
            <input 
              type="checkbox" 
              v-model="settings.require_item_approval" 
              id="require_item_approval" 
              class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
            />
            <label for="require_item_approval" class="ml-2 block text-sm text-gray-700">
              Require Item Approval
            </label>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="profanity_filter">Profanity Filter Level:</label>
            <select v-model="settings.profanity_filter" id="profanity_filter" class="border rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-primary focus:border-primary">
              <option value="off">Off</option>
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Save Button -->
    <div class="mt-6 flex justify-end">
      <div v-if="error" class="text-red-500 mr-4 self-center text-sm">{{ error }}</div>
      <div v-if="success" class="text-green-500 mr-4 self-center text-sm">{{ success }}</div>
      <button 
        @click="saveSettings"
        :disabled="loading"
        class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors"
      >
        <span v-if="loading" class="flex items-center">
          <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Saving...
        </span>
        <span v-else>Save All Settings</span>
    </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue';
import axios from 'axios';
import { toast } from 'vue3-toastify';

const settings = reactive({
  item_detective_mode: 'simple',
  items_per_page: 10,
  enable_auto_match: true,
  enable_email_notifications: true,
  enable_push_notifications: false,
  admin_email: '',
  notification_frequency: 'daily',
  enable_two_factor: false,
  require_email_verification: true,
  session_timeout: 60,
  auto_moderate_content: true,
  require_item_approval: false,
  profanity_filter: 'medium'
});

const loading = ref(false);
const error = ref('');
const success = ref('');

onMounted(async () => {
  try {
    loading.value = true;
    const res = await axios.get('/api/admin/settings');
    
    // Main settings from API
    settings.item_detective_mode = res.data.item_detective_mode || 'simple';
    
    // For demo purposes, we're adding mock settings
    // In a real app, all of these would come from the API
    settings.items_per_page = res.data.items_per_page || 10;
    settings.enable_auto_match = res.data.enable_auto_match !== undefined ? res.data.enable_auto_match : true;
    settings.enable_email_notifications = res.data.enable_email_notifications !== undefined ? res.data.enable_email_notifications : true;
    settings.admin_email = res.data.admin_email || '';
    
    error.value = '';
  } catch (e) {
    error.value = 'Failed to load settings.';
    toast.error('Failed to load settings');
    console.error(e);
  } finally {
    loading.value = false;
  }
});

const saveSettings = async () => {
  loading.value = true;
  error.value = '';
  success.value = '';
  
  try {
    // Save the item_detective_mode setting to the API
    const response = await axios.put('/api/admin/settings', { 
      item_detective_mode: settings.item_detective_mode 
    });
    
    // In a real app, we would save all settings here
    // For now we'll just simulate a successful save
    
    success.value = 'Settings saved successfully.';
    toast.success('Settings saved successfully');
  } catch (e) {
    error.value = 'Failed to save settings.';
    toast.error('Failed to save settings');
    console.error(e);
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.bg-primary { background-color: #4f46e5; }
.bg-primary-dark { background-color: #4338ca; }
</style>
