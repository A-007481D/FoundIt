<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Admin Settings</h1>
    <div class="mb-4">
      <label class="block font-medium mb-2" for="siteName">Site Name:</label>
      <input v-model="settings.siteName" id="siteName" class="border rounded px-3 py-2 w-full" />
    </div>
    <div class="mb-4">
      <label class="block font-medium mb-2" for="siteDescription">Site Description:</label>
      <textarea v-model="settings.siteDescription" id="siteDescription" class="border rounded px-3 py-2 w-full" />
    </div>
    <div class="mb-4">
      <label class="block font-medium mb-2" for="email">Email:</label>
      <input v-model="settings.email" id="email" class="border rounded px-3 py-2 w-full" />
    </div>
    <div class="mb-4">
      <label class="block font-medium mb-2" for="phone">Phone:</label>
      <input v-model="settings.phone" id="phone" class="border rounded px-3 py-2 w-full" />
    </div>
    <div class="mb-4">
      <label class="block font-medium mb-2" for="address">Address:</label>
      <input v-model="settings.address" id="address" class="border rounded px-3 py-2 w-full" />
    </div>
    <div class="mb-4">
      <label class="block font-medium mb-2" for="mapEmbed">Map Embed:</label>
      <textarea v-model="settings.mapEmbed" id="mapEmbed" class="border rounded px-3 py-2 w-full" />
    </div>
    <div class="mb-4">
      <label class="block font-medium mb-2" for="socialLinks">Social Links:</label>
      <div class="mb-2">
        <label class="block font-medium mb-1" for="facebook">Facebook:</label>
        <input v-model="settings.socialLinks.facebook" id="facebook" class="border rounded px-3 py-2 w-full" />
      </div>
      <div class="mb-2">
        <label class="block font-medium mb-1" for="twitter">Twitter:</label>
        <input v-model="settings.socialLinks.twitter" id="twitter" class="border rounded px-3 py-2 w-full" />
      </div>
      <div class="mb-2">
        <label class="block font-medium mb-1" for="instagram">Instagram:</label>
        <input v-model="settings.socialLinks.instagram" id="instagram" class="border rounded px-3 py-2 w-full" />
      </div>
      <div class="mb-2">
        <label class="block font-medium mb-1" for="linkedin">LinkedIn:</label>
        <input v-model="settings.socialLinks.linkedin" id="linkedin" class="border rounded px-3 py-2 w-full" />
      </div>
    </div>
    <div class="mb-4">
      <label class="block font-medium mb-2" for="notificationSettings">Notification Settings:</label>
      <div class="mb-2">
        <label class="block font-medium mb-1" for="emailNotifications">Email Notifications:</label>
        <input v-model="settings.notificationSettings.emailNotifications" id="emailNotifications" type="checkbox" />
      </div>
      <div class="mb-2">
        <label class="block font-medium mb-1" for="pushNotifications">Push Notifications:</label>
        <input v-model="settings.notificationSettings.pushNotifications" id="pushNotifications" type="checkbox" />
      </div>
      <div class="mb-2">
        <label class="block font-medium mb-1" for="smsNotifications">SMS Notifications:</label>
        <input v-model="settings.notificationSettings.smsNotifications" id="smsNotifications" type="checkbox" />
      </div>
    </div>
    <div class="mb-4">
      <label class="block font-medium mb-2" for="securitySettings">Security Settings:</label>
      <div class="mb-2">
        <label class="block font-medium mb-1" for="passwordRequirements">Password Requirements:</label>
        <div class="mb-1">
          <label class="block font-medium mb-1" for="minLength">Min Length:</label>
          <input v-model="settings.securitySettings.passwordRequirements.minLength" id="minLength" class="border rounded px-3 py-2 w-full" />
        </div>
        <div class="mb-1">
          <label class="block font-medium mb-1" for="requireUppercase">Require Uppercase:</label>
          <input v-model="settings.securitySettings.passwordRequirements.requireUppercase" id="requireUppercase" type="checkbox" />
        </div>
        <div class="mb-1">
          <label class="block font-medium mb-1" for="requireLowercase">Require Lowercase:</label>
          <input v-model="settings.securitySettings.passwordRequirements.requireLowercase" id="requireLowercase" type="checkbox" />
        </div>
        <div class="mb-1">
          <label class="block font-medium mb-1" for="requireNumbers">Require Numbers:</label>
          <input v-model="settings.securitySettings.passwordRequirements.requireNumbers" id="requireNumbers" type="checkbox" />
        </div>
        <div class="mb-1">
          <label class="block font-medium mb-1" for="requireSpecialChars">Require Special Chars:</label>
          <input v-model="settings.securitySettings.passwordRequirements.requireSpecialChars" id="requireSpecialChars" type="checkbox" />
        </div>
      </div>
      <div class="mb-2">
        <label class="block font-medium mb-1" for="sessionTimeout">Session Timeout:</label>
        <input v-model="settings.securitySettings.sessionTimeout" id="sessionTimeout" class="border rounded px-3 py-2 w-full" />
      </div>
      <div class="mb-2">
        <label class="block font-medium mb-1" for="failedLoginAttempts">Failed Login Attempts:</label>
        <input v-model="settings.securitySettings.failedLoginAttempts" id="failedLoginAttempts" class="border rounded px-3 py-2 w-full" />
      </div>
      <div class="mb-2">
        <label class="block font-medium mb-1" for="lockoutDuration">Lockout Duration:</label>
        <input v-model="settings.securitySettings.lockoutDuration" id="lockoutDuration" class="border rounded px-3 py-2 w-full" />
      </div>
    </div>
    <button @click="saveSettings" :disabled="loading" class="bg-primary text-white px-4 py-2 rounded">
      {{ loading ? 'Saving...' : 'Save Settings' }}
    </button>
    <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>
    <p v-if="success" class="text-green-500 mt-2">{{ success }}</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { toast } from 'vue3-toastify';
import AdminUserService from '@/services/api/admin/user';

const loading = ref(false);
const settings = ref({
  siteName: '',
  siteDescription: '',
  email: '',
  phone: '',
  address: '',
  mapEmbed: '',
  socialLinks: {
    facebook: '',
    twitter: '',
    instagram: '',
    linkedin: ''
  },
  notificationSettings: {
    emailNotifications: true,
    pushNotifications: true,
    smsNotifications: false
  },
  securitySettings: {
    passwordRequirements: {
      minLength: 8,
      requireUppercase: true,
      requireLowercase: true,
      requireNumbers: true,
      requireSpecialChars: true
    },
    sessionTimeout: 30,
    failedLoginAttempts: 5,
    lockoutDuration: 30
  }
});

async function fetchSettings() {
  loading.value = true;
  try {
    const response = await AdminUserService.getSettings();
    settings.value = response.data;
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to fetch settings');
    console.error('Failed to fetch settings:', err);
  } finally {
    loading.value = false;
  }
}

async function saveSettings() {
  loading.value = true;
  try {
    await AdminUserService.updateSettings(settings.value);
    toast.success('Settings saved successfully');
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to save settings');
    console.error('Failed to save settings:', err);
  } finally {
    loading.value = false;
  }
}

// Load settings on component mount
onMounted(() => {
  fetchSettings();
});
</script>

<style scoped>
.bg-primary { background-color: #4f46e5; }
</style>
