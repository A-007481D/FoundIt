<template>
  <div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->  
        <div class="w-full lg:w-1/4">
          <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Profile Photo -->  
            <div class="p-6 border-b border-gray-100 text-center">
              <div class="relative mx-auto h-32 w-32 mb-4">
                <img 
                  :src="profilePhotoUrl" 
                  alt="Profile Photo" 
                  class="h-full w-full object-cover rounded-full ring-4 ring-purple-100"
                />
                <button 
                  @click="openPhotoUpload" 
                  :disabled="!!photoChangeRestriction"
                  class="absolute bottom-0 right-0 h-8 w-8 rounded-full bg-purple-600 text-white flex items-center justify-center shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition"
                  :class="{ 'opacity-50 cursor-not-allowed': !!photoChangeRestriction }"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </button>
                <input 
                  type="file" 
                  ref="photoInput" 
                  accept="image/jpeg,image/png,image/jpg" 
                  class="hidden" 
                  @change="updateProfilePhoto"
                />
              </div>
              <h2 class="text-lg font-bold text-gray-900">{{ profile.name }}</h2>
              <p class="text-sm text-gray-500 mt-1">{{ profile.email }}</p>
              <div v-if="photoChangeRestriction" class="text-xs text-orange-600 mt-2 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ photoChangeRestriction }}
              </div>
            </div>
            
            <!-- Navigation -->  
            <nav class="p-2">
              <div 
                v-for="(tab, index) in tabs" 
                :key="index"
                @click="activeTab = tab.id"
                class="px-4 py-3 rounded-lg flex items-center gap-3 cursor-pointer mb-1 transition-colors"
                :class="activeTab === tab.id ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-gray-50'"
              >
                <i :class="[tab.icon, activeTab === tab.id ? 'text-purple-600' : 'text-gray-500']" class="w-5"></i>
                <span>{{ tab.name }}</span>
              </div>
            </nav>
          </div>
        </div>

        <!-- Main content -->  
        <div class="w-full lg:w-3/4">
          <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Header -->  
            <div class="border-b border-gray-100 px-6 py-4">
              <h3 class="text-xl font-bold text-gray-900">
                <span v-if="activeTab === 'basic'">Profile Information</span>
                <span v-else-if="activeTab === 'notifications'">Notification Settings</span>
                <span v-else-if="activeTab === 'privacy'">Privacy Settings</span>
              </h3>
            </div>
                
            <!-- Content -->  
            <div class="p-6">
              <!-- Profile Info Tab -->  
              <div v-if="activeTab === 'basic'">
                <form @submit.prevent="updateProfileData" class="space-y-6">
                  <!-- Name Fields -->  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                      <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                      <input 
                        type="text" 
                        id="firstname"
                        v-model="profile.name" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                        required
                      />
                    </div>
                    <div class="space-y-2">
                      <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                      <input 
                        type="text" 
                        id="lastname"
                        v-model="profile.name" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                        required
                      />
                    </div>
                  </div>
                    
                  <!-- Email -->  
                  <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input 
                      type="email" 
                      id="email"
                      v-model="profile.email"
                      :disabled="!canChangeEmail"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 disabled:bg-gray-100 disabled:text-gray-500"
                      required
                    />
                    <p v-if="!canChangeEmail" class="mt-1 text-xs text-gray-500">
                      <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                      </svg>
                      Email can be changed after {{ emailChangeRestriction }}
                    </p>
                  </div>
                    
                  <!-- Phone -->  
                  <div class="space-y-2">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number (optional)</label>
                    <input 
                      type="tel" 
                      id="phone"
                      v-model="profile.phone"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                    />
                  </div>
                    
                  <!-- Address -->  
                  <div class="space-y-2">
                    <label for="address" class="block text-sm font-medium text-gray-700">Address (optional)</label>
                    <input 
                      type="text" 
                      id="address"
                      v-model="profile.location"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                    />
                  </div>
                    
                  <!-- Bio -->  
                  <div class="space-y-2">
                    <label for="bio" class="block text-sm font-medium text-gray-700">Bio (optional)</label>
                    <textarea 
                      id="bio"
                      v-model="profile.bio"
                      rows="4"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                    ></textarea>
                  </div>
                    
                  <!-- Submit -->  
                  <div class="flex items-center justify-between pt-2">
                    <button 
                      type="submit" 
                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors" 
                      :disabled="isSaving"
                    >
                      <svg v-if="isSaving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      <span v-if="isSaving">Saving...</span>
                      <span v-else>Save Changes</span>
                    </button>
                      
                    <p v-if="lastUpdateText" class="text-xs text-gray-500">
                      Last updated: {{ lastUpdateText }}
                    </p>
                  </div>
                </form>
              </div>
                
              <!-- Notifications Tab -->  
              <div v-if="activeTab === 'notifications'">
                <form @submit.prevent="updateNotifications" class="space-y-6">
                  <!-- Email Notifications -->  
                  <div class="bg-gray-50 rounded-lg p-4 flex items-start space-x-3">
                    <div class="relative inline-block h-6 w-11 flex-shrink-0 cursor-pointer">
                      <input 
                        type="checkbox" 
                        id="emailNotifications"
                        v-model="notificationForm.email_notifications"
                        class="sr-only peer"
                      />
                      <span class="absolute inset-0 rounded-full bg-purple-100 transition peer-checked:bg-purple-600"></span>
                      <span class="absolute inset-y-0 left-0 ml-0.5 mt-0.5 h-5 w-5 rounded-full bg-white transition-all peer-checked:translate-x-5 shadow-md"></span>
                    </div>
                    <div class="flex-1">
                      <label for="emailNotifications" class="block text-sm font-medium text-gray-900 cursor-pointer">Email Notifications</label>
                      <p class="text-xs text-gray-600 mt-1">Receive notifications via email</p>
                    </div>
                  </div>
                    
                  <!-- Push Notifications -->  
                  <div class="bg-gray-50 rounded-lg p-4 flex items-start space-x-3">
                    <div class="relative inline-block h-6 w-11 flex-shrink-0 cursor-pointer">
                      <input 
                        type="checkbox" 
                        id="pushNotifications"
                        v-model="notificationForm.push_notifications"
                        class="sr-only peer"
                      />
                      <span class="absolute inset-0 rounded-full bg-purple-100 transition peer-checked:bg-purple-600"></span>
                      <span class="absolute inset-y-0 left-0 ml-0.5 mt-0.5 h-5 w-5 rounded-full bg-white transition-all peer-checked:translate-x-5 shadow-md"></span>
                    </div>
                    <div class="flex-1">
                      <label for="pushNotifications" class="block text-sm font-medium text-gray-900 cursor-pointer">Push Notifications</label>
                      <p class="text-xs text-gray-600 mt-1">Receive notifications in browser</p>
                    </div>
                  </div>
                    
                  <!-- Item Updates -->  
                  <div class="bg-gray-50 rounded-lg p-4 flex items-start space-x-3">
                    <div class="relative inline-block h-6 w-11 flex-shrink-0 cursor-pointer">
                      <input 
                        type="checkbox" 
                        id="itemUpdates"
                        v-model="notificationForm.item_updates"
                        class="sr-only peer"
                      />
                      <span class="absolute inset-0 rounded-full bg-purple-100 transition peer-checked:bg-purple-600"></span>
                      <span class="absolute inset-y-0 left-0 ml-0.5 mt-0.5 h-5 w-5 rounded-full bg-white transition-all peer-checked:translate-x-5 shadow-md"></span>
                    </div>
                    <div class="flex-1">
                      <label for="itemUpdates" class="block text-sm font-medium text-gray-900 cursor-pointer">Item Updates</label>
                      <p class="text-xs text-gray-600 mt-1">Receive notifications when your items are updated or matched</p>
                    </div>
                  </div>
                    
                  <!-- Submit -->  
                  <button 
                    type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors" 
                    :disabled="isSaving"
                  >
                    <svg v-if="isSaving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span v-if="isSaving">Saving...</span>
                    <span v-else>Save Changes</span>
                  </button>
                </form>
              </div>
                
              <!-- Privacy Tab -->  
              <div v-if="activeTab === 'privacy'">
                <form @submit.prevent="updatePrivacy" class="space-y-6">
                  <!-- Show Email -->  
                  <div class="bg-gray-50 rounded-lg p-4 flex items-start space-x-3">
                    <div class="relative inline-block h-6 w-11 flex-shrink-0 cursor-pointer">
                      <input 
                        type="checkbox" 
                        id="showEmail"
                        v-model="privacyForm.show_email"
                        class="sr-only peer"
                      />
                      <span class="absolute inset-0 rounded-full bg-purple-100 transition peer-checked:bg-purple-600"></span>
                      <span class="absolute inset-y-0 left-0 ml-0.5 mt-0.5 h-5 w-5 rounded-full bg-white transition-all peer-checked:translate-x-5 shadow-md"></span>
                    </div>
                    <div class="flex-1">
                      <label for="showEmail" class="block text-sm font-medium text-gray-900 cursor-pointer">Show Email to Others</label>
                      <p class="text-xs text-gray-600 mt-1">Allow others to see your email in your profile</p>
                    </div>
                  </div>
                    
                  <!-- Show Phone -->  
                  <div class="bg-gray-50 rounded-lg p-4 flex items-start space-x-3">
                    <div class="relative inline-block h-6 w-11 flex-shrink-0 cursor-pointer">
                      <input 
                        type="checkbox" 
                        id="showPhone"
                        v-model="privacyForm.show_phone"
                        class="sr-only peer"
                      />
                      <span class="absolute inset-0 rounded-full bg-purple-100 transition peer-checked:bg-purple-600"></span>
                      <span class="absolute inset-y-0 left-0 ml-0.5 mt-0.5 h-5 w-5 rounded-full bg-white transition-all peer-checked:translate-x-5 shadow-md"></span>
                    </div>
                    <div class="flex-1">
                      <label for="showPhone" class="block text-sm font-medium text-gray-900 cursor-pointer">Show Phone Number</label>
                      <p class="text-xs text-gray-600 mt-1">Allow others to see your phone number</p>
                    </div>
                  </div>
                    
                  <!-- Show Address -->  
                  <div class="bg-gray-50 rounded-lg p-4 flex items-start space-x-3">
                    <div class="relative inline-block h-6 w-11 flex-shrink-0 cursor-pointer">
                      <input 
                        type="checkbox" 
                        id="showAddress"
                        v-model="privacyForm.show_address"
                        class="sr-only peer"
                      />
                      <span class="absolute inset-0 rounded-full bg-purple-100 transition peer-checked:bg-purple-600"></span>
                      <span class="absolute inset-y-0 left-0 ml-0.5 mt-0.5 h-5 w-5 rounded-full bg-white transition-all peer-checked:translate-x-5 shadow-md"></span>
                    </div>
                    <div class="flex-1">
                      <label for="showAddress" class="block text-sm font-medium text-gray-900 cursor-pointer">Show Address</label>
                      <p class="text-xs text-gray-600 mt-1">Allow others to see your address</p>
                    </div>
                  </div>
                    
                  <!-- Submit -->  
                  <button 
                    type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors" 
                    :disabled="isSaving"
                  >
                    <svg v-if="isSaving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span v-if="isSaving">Saving...</span>
                    <span v-else>Save Changes</span>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
    <!-- Alerts with Tailwind CSS -->
    <div class="fixed bottom-5 right-5 z-50">
      <div 
        v-if="showAlert"
        :class="{
          'bg-white': true,
          'border-l-4': true,
          'border-green-500': alertType === 'success',
          'border-red-500': alertType === 'error'
        }"
        class="rounded-lg shadow-lg overflow-hidden max-w-sm transform transition-all duration-300 ease-in-out"
        role="alert"
      >
        <div class="p-4 flex items-start">
          <div class="flex-shrink-0 mr-3">
            <svg v-if="alertType === 'success'" class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <svg v-else class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="flex-1 pr-8">
            <h4 class="text-sm font-medium text-gray-900">{{ alertTitle }}</h4>
            <p class="mt-1 text-sm text-gray-600">{{ alertMessage }}</p>
          </div>
          <button 
            @click="showAlert = false" 
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none"
          >
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import { toast } from 'vue3-toastify';
import { getProfile, updateProfile, updatePhoto, getStats, getActivity, getNotifications, markNotificationRead, markAllNotificationsRead } from '@services/api/profile';
import { auth } from '@services/api/auth';
import { API_BASE_URL, STORAGE_BASE_URL, DEFAULT_AVATAR } from '@config/index';

// storage URL helper
const storageBase = API_BASE_URL.replace(/\/api$/, '');
function getAvatarUrl(photo) {
  if (!photo) return DEFAULT_AVATAR;
  if (photo.startsWith('http')) return photo;
  if (photo.startsWith('/storage')) return `${storageBase}${photo}`;
  return `${storageBase}/storage/${photo}`;
}

// State
const profile = reactive({
  name: '',
  email: '',
  bio: '',
  location: '',
  phone: '',
  photo: null
});

const stats = reactive({
  itemsFound: 0,
  itemsLost: 0,
  matchesCreated: 0,
  matchesResolved: 0
});

const notifications = ref([]);
const unreadCount = ref(0);
const loading = ref(true);
const photoChangeRestriction = ref(null);

const isSaving = ref(false);
const activeTab = ref('basic');
const photoInput = ref(null);
const canChangeEmail = ref(true);
const emailChangeRestriction = ref('');
const lastUpdateText = ref('');
const showAlert = ref(false);
const alertMessage = ref('');
const alertTitle = ref('');
const alertType = ref('success');
let alertTimeout;

// Computed properties
const profilePhotoUrl = computed(() => {
  if (profile.photo) {
    return `${STORAGE_BASE_URL}/storage/${profile.photo}`;
  }
  return DEFAULT_AVATAR;
});

// Methods
async function loadProfile() {
  try {
    const response = await getProfile();
    Object.assign(profile, response.data);
    
    // Check email change restrictions
    canChangeEmail.value = response.data.can_change_email;
    if (!canChangeEmail.value && response.data.can_change_email_after) {
      emailChangeRestriction.value = formatDate(response.data.can_change_email_after);
    }
    
    // Check photo change restrictions
    if (profile.last_profile_pic_change) {
      const changeDate = new Date(profile.last_profile_pic_change);
      const weekLater = new Date(changeDate);
      weekLater.setDate(weekLater.getDate() + 7);
      
      if (weekLater > new Date()) {
        photoChangeRestriction.value = formatDate(weekLater);
      }
    }
    
    // Set last update text
    if (profile.profile_updated_at) {
      lastUpdateText.value = formatDate(profile.profile_updated_at);
    }
  } catch (error) {
    console.error('Error loading profile:', error);
    toast.error('Failed to load profile');
  }
}

async function loadStats() {
  try {
    const response = await getStats();
    Object.assign(stats, response.data);
  } catch (error) {
    console.error('Error loading statistics:', error);
    toast.error('Failed to load statistics');
  }
}

async function loadNotifications() {
  try {
    const response = await getNotifications({
      page: 1,
      per_page: 10
    });
    notifications.value = response.data.data;
    unreadCount.value = response.data.meta.unread_count;
  } catch (error) {
    console.error('Error loading notifications:', error);
    toast.error('Failed to load notifications');
  }
}

async function updateProfileData() {
  try {
    isSaving.value = true;
    const formData = new FormData();
    
    // Add form data
    Object.keys(profile).forEach(key => {
      if (key !== 'photo') {
        formData.append(key, profile[key]);
      }
    });
    
    await updateProfile(formData);
    
    // Update last update text
    lastUpdateText.value = formatDate(new Date());
    
    toast.success('Profile updated successfully');
  } catch (error) {
    console.error('Error updating profile:', error);
    toast.error('Failed to update profile');
  } finally {
    isSaving.value = false;
  }
}

async function updateProfilePhoto() {
  try {
    isSaving.value = true;
    const formData = new FormData();
    formData.append('photo', photoInput.value.files[0]);
    
    await updatePhoto(formData);
    
    // Update last update text
    lastUpdateText.value = formatDate(new Date());
    
    toast.success('Profile photo updated successfully');
  } catch (error) {
    console.error('Error updating profile photo:', error);
    toast.error('Failed to update profile photo');
  } finally {
    isSaving.value = false;
  }
}

async function updateNotifications() {
  try {
    isSaving.value = true;
    await updateProfile(notificationForm);
    toast.success('Notification settings updated successfully');
  } catch (error) {
    console.error('Error updating notifications:', error);
    toast.error('Failed to update notification settings');
  } finally {
    isSaving.value = false;
  }
}

async function handleLogout() {
  if (!confirm('Are you sure you want to logout?')) return;

  try {
    loading.value = true;
    await auth.logout();
    toast.success('Logged out successfully');
    router.push('/login');
  } catch (error) {
    console.error('Error logging out:', error);
    toast.error('Failed to logout');
  } finally {
    loading.value = false;
  }
}

// Helper functions
function formatDate(date) {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
}

// Lifecycle
onMounted(() => {
  loadProfile();
});
</script>