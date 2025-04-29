<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Admin Settings</h1>
    <div class="mb-4">
      <label class="block font-medium mb-2" for="mode">Item Detective Mode:</label>
      <select v-model="mode" id="mode" class="border rounded px-3 py-2 w-full">
        <option value="simple">Simple</option>
        <option value="tf">TensorFlow</option>
        <option value="phash">PHash</option>
      </select>
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
import axios from 'axios';

const mode = ref('simple');
const loading = ref(false);
const error = ref('');
const success = ref('');

onMounted(async () => {
  try {
    const res = await axios.get('/api/admin/settings');
    mode.value = res.data.item_detective_mode;
  } catch (e) {
    error.value = 'Failed to load settings.';
  }
});

const saveSettings = async () => {
  loading.value = true;
  error.value = '';
  success.value = '';
  try {
    await axios.put('/api/admin/settings', { item_detective_mode: mode.value });
    success.value = 'Settings saved successfully.';
  } catch (e) {
    error.value = 'Failed to save settings.';
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.bg-primary { background-color: #4f46e5; }
</style>
