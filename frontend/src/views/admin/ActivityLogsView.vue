<template>
  <div class="activity-logs-container">
    <h1 class="text-2xl font-bold mb-4">Activity Logs</h1>
    
    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-4 mb-6">
      <h2 class="text-lg font-semibold mb-3">Filters</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
          <input 
            v-model="searchQuery" 
            type="text" 
            class="w-full rounded-md border border-gray-300 p-2"
            @input="handleSearch"
          >
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
          <select 
            v-model="typeFilter" 
            class="w-full rounded-md border border-gray-300 p-2"
            @change="filterLogs"
          >
            <option value="all">All Types</option>
            <option value="create">Create</option>
            <option value="update">Update</option>
            <option value="delete">Delete</option>
            <option value="login">Login</option>
          </select>
        </div>
      </div>
    </div>
    
    <!-- Activity Log List -->
    <div class="bg-white shadow rounded-lg p-4 mb-6">
      <h2 class="text-lg font-semibold mb-3">
        Activity Logs
        <span class="text-sm font-normal text-gray-600 ml-2">
          ({{ logs.length }} total)
        </span>
      </h2>
      
      <div v-if="loading" class="py-10 text-center">
        <div class="spinner mb-2"></div>
        <p class="text-gray-600">Loading activity logs...</p>
      </div>
      
      <div v-else-if="logs.length === 0" class="py-10 text-center">
        <p class="text-gray-600">No activity logs found matching your filters.</p>
      </div>
      
      <div v-else>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="log in paginatedLogs" :key="log.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(log.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="getLogTypeClass(log.type)"
                  >
                    {{ log.type }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ log.details }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
          <div class="flex-1 flex justify-between sm:hidden">
            <button
              @click="prevPage"
              :disabled="currentPage === 1"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
            >
              Previous
            </button>
            <button
              @click="nextPage"
              :disabled="currentPage === Math.ceil(logs.length / itemsPerPage)"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              :class="{ 'opacity-50 cursor-not-allowed': currentPage === Math.ceil(logs.length / itemsPerPage) }"
            >
              Next
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Showing
                <span class="font-medium">{{ startIndex + 1 }}</span>
                to
                <span class="font-medium">{{ endIndex }}</span>
                of
                <span class="font-medium">{{ logs.length }}</span>
                results
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button
                  @click="prevPage"
                  :disabled="currentPage === 1"
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                  :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
                >
                  <span class="sr-only">Previous</span>
                  &laquo;
                </button>
                <button
                  v-for="page in Math.ceil(logs.length / itemsPerPage)"
                  :key="page"
                  @click="currentPage = page"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium"
                  :class="page === currentPage ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'text-gray-500 hover:bg-gray-50'"
                >
                  {{ page }}
                </button>
                <button
                  @click="nextPage"
                  :disabled="currentPage === Math.ceil(logs.length / itemsPerPage)"
                  class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                  :class="{ 'opacity-50 cursor-not-allowed': currentPage === Math.ceil(logs.length / itemsPerPage) }"
                >
                  <span class="sr-only">Next</span>
                  &raquo;
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { toast } from 'vue3-toastify';
import AdminUserService from '@/services/api/admin/user';

const loading = ref(false);
const logs = ref([]);
const searchQuery = ref('');
const typeFilter = ref('all');
const currentPage = ref(1);
const itemsPerPage = ref(10);

// Computed values for pagination
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage.value);
const endIndex = computed(() => Math.min(startIndex.value + itemsPerPage.value, logs.value.length));
const paginatedLogs = computed(() => logs.value.slice(startIndex.value, endIndex.value));

// Methods
async function fetchLogs() {
  loading.value = true;
  try {
    const response = await AdminUserService.getActivityLogs({
      search: searchQuery.value,
      type: typeFilter.value === 'all' ? '' : typeFilter.value,
      page: currentPage.value,
      per_page: itemsPerPage.value
    });
    logs.value = response.data.data;
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to fetch activity logs');
    console.error('Failed to fetch activity logs:', err);
  } finally {
    loading.value = false;
  }
}

function handleSearch() {
  currentPage.value = 1;
  fetchLogs();
}

function filterLogs() {
  currentPage.value = 1;
  fetchLogs();
}

function prevPage() {
  if (currentPage.value > 1) {
    currentPage.value--;
    fetchLogs();
  }
}

function nextPage() {
  currentPage.value++;
  fetchLogs();
}

function getLogTypeClass(type) {
  switch (type) {
    case 'create':
      return 'bg-green-100 text-green-800';
    case 'update':
      return 'bg-blue-100 text-blue-800';
    case 'delete':
      return 'bg-red-100 text-red-800';
    case 'login':
      return 'bg-yellow-100 text-yellow-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleString();
}

// Load logs on component mount
onMounted(() => {
  fetchLogs();
});
</script>

<style scoped>
.spinner {
  border: 3px solid rgba(0, 0, 0, 0.1);
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border-left-color: #3b82f6;
  animation: spin 1s linear infinite;
  margin: 0 auto;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
