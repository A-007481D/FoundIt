<template>
  <div class="activity-logs-container">
    <h1 class="text-2xl font-bold mb-4">Activity Logs</h1>
    
    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-4 mb-6">
      <h2 class="text-lg font-semibold mb-3">Filters</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
          <select v-model="filters.user_id" class="w-full rounded-md border border-gray-300 p-2">
            <option value="all">All Users</option>
            <option v-for="user in users" :key="user.id" :value="user.id">
              {{ user.firstname }} {{ user.lastname }} ({{ user.email }})
            </option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
          <select v-model="filters.action" class="w-full rounded-md border border-gray-300 p-2">
            <option value="all">All Actions</option>
            <option v-for="action in actions" :key="action" :value="action">
              {{ formatAction(action) }}
            </option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Entity Type</label>
          <select v-model="filters.entity_type" class="w-full rounded-md border border-gray-300 p-2">
            <option value="all">All Entities</option>
            <option v-for="entity in entityTypes" :key="entity" :value="entity">
              {{ entity }}
            </option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
          <select v-model="filters.category" class="w-full rounded-md border border-gray-300 p-2">
            <option value="all">All Categories</option>
            <option v-for="(label, value) in categories" :key="value" :value="value">
              {{ label }}
            </option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
          <input type="date" v-model="filters.from_date" class="w-full rounded-md border border-gray-300 p-2">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
          <input type="date" v-model="filters.to_date" class="w-full rounded-md border border-gray-300 p-2">
        </div>
        <div class="flex items-end">
          <button 
            @click="fetchActivityLogs" 
            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
          >
            Apply Filters
          </button>
          <button 
            @click="resetFilters" 
            class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md ml-2 hover:bg-gray-300"
          >
            Reset
          </button>
        </div>
      </div>
    </div>
    
    <!-- User Details (when filtering by user) -->
    <div v-if="filters.user_id !== 'all' && selectedUser" class="bg-white shadow rounded-lg p-4 mb-6">
      <div class="flex items-center">
        <div class="rounded-full bg-gray-200 w-16 h-16 flex items-center justify-center text-xl font-bold text-gray-600">
          {{ selectedUser.firstname[0] }}{{ selectedUser.lastname[0] }}
        </div>
        <div class="ml-4">
          <h2 class="text-xl font-semibold">{{ selectedUser.firstname }} {{ selectedUser.lastname }}</h2>
          <p class="text-gray-600">{{ selectedUser.email }}</p>
          <div class="flex mt-1">
            <span 
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
              :class="{
                'bg-green-100 text-green-800': selectedUser.status === 'active',
                'bg-yellow-100 text-yellow-800': selectedUser.status === 'suspended',
                'bg-red-100 text-red-800': selectedUser.status === 'banned'
              }"
            >
              {{ selectedUser.status }}
            </span>
            <span 
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2"
              :class="{
                'bg-blue-100 text-blue-800': selectedUser.role === 'admin',
                'bg-gray-100 text-gray-800': selectedUser.role === 'user'
              }"
            >
              {{ selectedUser.role }}
            </span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Activity Log List -->
    <div class="bg-white shadow rounded-lg p-4 mb-6">
      <h2 class="text-lg font-semibold mb-3">
        Activity Logs
        <span class="text-sm font-normal text-gray-600 ml-2">
          ({{ totalLogs }} total)
        </span>
      </h2>
      
      <div v-if="loading" class="py-10 text-center">
        <div class="spinner mb-2"></div>
        <p class="text-gray-600">Loading activity logs...</p>
      </div>
      
      <div v-else-if="activityLogs.length === 0" class="py-10 text-center">
        <p class="text-gray-600">No activity logs found matching your filters.</p>
      </div>
      
      <div v-else>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="log in activityLogs" :key="log.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDateTime(log.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="text-sm font-medium text-gray-900">
                      {{ log.user.firstname }} {{ log.user.lastname }}
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="getActionClass(log.action)"
                  >
                    {{ formatAction(log.action) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ log.entity_type || '-' }}
                  {{ log.entity_id ? `#${log.entity_id}` : '' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ log.ip_address || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <button 
                    @click="showLogDetails(log)"
                    class="text-blue-600 hover:text-blue-800 underline"
                  >
                    View Details
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
          <div class="flex-1 flex justify-between sm:hidden">
            <button
              @click="changePage(currentPage - 1)"
              :disabled="currentPage === 1"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
            >
              Previous
            </button>
            <button
              @click="changePage(currentPage + 1)"
              :disabled="currentPage === lastPage"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              :class="{ 'opacity-50 cursor-not-allowed': currentPage === lastPage }"
            >
              Next
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Showing
                <span class="font-medium">{{ (currentPage - 1) * perPage + 1 }}</span>
                to
                <span class="font-medium">{{ Math.min(currentPage * perPage, totalLogs) }}</span>
                of
                <span class="font-medium">{{ totalLogs }}</span>
                results
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button
                  @click="changePage(currentPage - 1)"
                  :disabled="currentPage === 1"
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                  :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
                >
                  <span class="sr-only">Previous</span>
                  &laquo;
                </button>
                <button
                  v-for="page in pageNumbers"
                  :key="page"
                  @click="changePage(page)"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium"
                  :class="page === currentPage ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'text-gray-500 hover:bg-gray-50'"
                >
                  {{ page }}
                </button>
                <button
                  @click="changePage(currentPage + 1)"
                  :disabled="currentPage === lastPage"
                  class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                  :class="{ 'opacity-50 cursor-not-allowed': currentPage === lastPage }"
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
    
    <!-- Log Details Modal -->
    <div v-if="selectedLog" class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="selectedLog = null"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                  Activity Log Details
                </h3>
                <div class="mt-4">
                  <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                      <dt class="text-sm font-medium text-gray-500">Date/Time</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ formatDateTime(selectedLog.created_at) }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                      <dt class="text-sm font-medium text-gray-500">User</dt>
                      <dd class="mt-1 text-sm text-gray-900">
                        {{ selectedLog.user.firstname }} {{ selectedLog.user.lastname }} ({{ selectedLog.user.email }})
                      </dd>
                    </div>
                    <div class="sm:col-span-1">
                      <dt class="text-sm font-medium text-gray-500">Action</dt>
                      <dd class="mt-1 text-sm text-gray-900">
                        <span 
                          class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="getActionClass(selectedLog.action)"
                        >
                          {{ formatAction(selectedLog.action) }}
                        </span>
                      </dd>
                    </div>
                    <div class="sm:col-span-1">
                      <dt class="text-sm font-medium text-gray-500">Entity</dt>
                      <dd class="mt-1 text-sm text-gray-900">
                        {{ selectedLog.entity_type || '-' }}
                        {{ selectedLog.entity_id ? `#${selectedLog.entity_id}` : '' }}
                      </dd>
                    </div>
                    <div class="sm:col-span-1">
                      <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ selectedLog.ip_address || '-' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                      <dt class="text-sm font-medium text-gray-500">User Agent</dt>
                      <dd class="mt-1 text-sm text-gray-900 break-words">{{ selectedLog.user_agent || '-' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                      <dt class="text-sm font-medium text-gray-500">Metadata</dt>
                      <dd class="mt-1 text-sm text-gray-900 break-words">
                        <pre v-if="selectedLog.metadata" class="bg-gray-100 p-2 rounded-md overflow-auto max-h-40">{{ JSON.stringify(selectedLog.metadata, null, 2) }}</pre>
                        <span v-else>-</span>
                      </dd>
                    </div>
                  </dl>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button 
              type="button" 
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              @click="selectedLog = null"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue';
import { useUsersStore } from '@/stores/users.store';
import axios from 'axios';

export default {
  name: 'ActivityLogsView',
  setup() {
    const usersStore = useUsersStore();
    const loading = ref(false);
    const activityLogs = ref([]);
    const selectedLog = ref(null);
    const actions = ref([]);
    const entityTypes = ref([]);
    const categories = ref({});
    const totalLogs = ref(0);
    const currentPage = ref(1);
    const lastPage = ref(1);
    const perPage = ref(15);
    
    const filters = ref({
      user_id: 'all',
      action: 'all',
      entity_type: 'all',
      category: 'all',
      from_date: '',
      to_date: '',
      page: 1,
      per_page: 15
    });
    
    const selectedUser = computed(() => {
      if (filters.value.user_id === 'all') return null;
      return usersStore.findUserById(filters.value.user_id);
    });
    
    watch(() => filters.value.user_id, () => {
      if (filters.value.user_id !== 'all') {
        const user = usersStore.findUserById(filters.value.user_id);
        if (!user) {
          filters.value.user_id = 'all';
        }
      }
    });
    
    const pageNumbers = computed(() => {
      const pages = [];
      const totalPages = lastPage.value;
      const currentPageNum = currentPage.value;
      
      // Always show first page
      if (totalPages > 0) {
        pages.push(1);
      }
      
      // Show pages around current page
      for (let i = Math.max(2, currentPageNum - 1); i <= Math.min(totalPages - 1, currentPageNum + 1); i++) {
        if (pages[pages.length - 1] !== i - 1) {
          pages.push('...');
        }
        pages.push(i);
      }
      
      // Always show last page if there is more than one page
      if (totalPages > 1 && pages[pages.length - 1] !== totalPages - 1) {
        if (pages[pages.length - 1] !== totalPages - 2) {
          pages.push('...');
        }
        pages.push(totalPages);
      }
      
      return pages;
    });
    
    const fetchUsers = async () => {
      if (usersStore.users.length === 0) {
        await usersStore.fetchUsers();
      }
    };
    
    const fetchActionTypes = async () => {
      try {
        const response = await axios.get('/admin/activity-logs/actions');
        actions.value = response.data.action_types;
      } catch (error) {
        console.error('Error fetching action types:', error);
      }
    };
    
    const fetchEntityTypes = async () => {
      try {
        const response = await axios.get('/admin/activity-logs/entities');
        entityTypes.value = response.data.entity_types;
      } catch (error) {
        console.error('Error fetching entity types:', error);
      }
    };
    
    const fetchCategories = async () => {
      try {
        const response = await axios.get('/admin/activity-logs/categories');
        categories.value = response.data.categories;
      } catch (error) {
        console.error('Error fetching log categories:', error);
      }
    };
    
    const fetchActivityLogs = async () => {
      loading.value = true;
      
      try {
        const params = { ...filters.value, page: currentPage.value };
        
        const response = await axios.get('/admin/activity-logs', { params });
        
        activityLogs.value = response.data.data;
        totalLogs.value = response.data.total;
        lastPage.value = response.data.last_page;
        currentPage.value = response.data.current_page;
        perPage.value = response.data.per_page;
        
      } catch (error) {
        console.error('Error fetching activity logs:', error);
      } finally {
        loading.value = false;
      }
    };
    
    const changePage = (page) => {
      if (page >= 1 && page <= lastPage.value && page !== currentPage.value) {
        currentPage.value = page;
        fetchActivityLogs();
      }
    };
    
    const resetFilters = () => {
      filters.value = {
        user_id: 'all',
        action: 'all',
        entity_type: 'all',
        category: 'all',
        from_date: '',
        to_date: '',
        page: 1,
        per_page: 15
      };
      currentPage.value = 1;
      fetchActivityLogs();
    };
    
    const showLogDetails = (log) => {
      selectedLog.value = log;
    };
    
    const formatDateTime = (datetime) => {
      if (!datetime) return '';
      const date = new Date(datetime);
      return date.toLocaleString();
    };
    
    const formatAction = (action) => {
      if (!action) return '';
      
      // Convert snake_case to Title Case With Spaces
      return action
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
    };
    
    const getActionClass = (action) => {
      if (!action) return 'bg-gray-100 text-gray-800';
      
      const actionMap = {
        'login': 'bg-green-100 text-green-800',
        'login_failed': 'bg-red-100 text-red-800',
        'logout': 'bg-blue-100 text-blue-800',
        'register': 'bg-purple-100 text-purple-800',
        'create': 'bg-indigo-100 text-indigo-800',
        'update': 'bg-yellow-100 text-yellow-800',
        'delete': 'bg-red-100 text-red-800',
        'session_terminated': 'bg-orange-100 text-orange-800',
        'all_sessions_terminated': 'bg-orange-100 text-orange-800'
      };
      
      // Use the specific class if defined, otherwise use the first word of the action
      const firstWord = action.split('_')[0];
      return actionMap[action] || actionMap[firstWord] || 'bg-gray-100 text-gray-800';
    };
    
    onMounted(async () => {
      await Promise.all([
        fetchUsers(),
        fetchActionTypes(),
        fetchEntityTypes(),
        fetchCategories(),
        fetchActivityLogs()
      ]);
    });
    
    return {
      loading,
      users: computed(() => usersStore.users),
      actions,
      entityTypes,
      categories,
      activityLogs,
      selectedLog,
      filters,
      selectedUser,
      totalLogs,
      currentPage,
      lastPage,
      perPage,
      pageNumbers,
      fetchActivityLogs,
      changePage,
      resetFilters,
      showLogDetails,
      formatDateTime,
      formatAction,
      getActionClass
    };
  }
};
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
