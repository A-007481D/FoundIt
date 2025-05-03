<template>
  <div class="user-sessions-container">
    <h1 class="text-2xl font-bold mb-4">User Sessions</h1>
    
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
          <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
          <input type="date" v-model="filters.from_date" class="w-full rounded-md border border-gray-300 p-2">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
          <input type="date" v-model="filters.to_date" class="w-full rounded-md border border-gray-300 p-2">
        </div>
        <div class="flex items-end">
          <button 
            @click="fetchSessions" 
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
          <button 
            @click="cleanupExpiredSessions" 
            class="bg-orange-500 text-white px-4 py-2 rounded-md ml-4 hover:bg-orange-600"
          >
            Cleanup Expired
          </button>
        </div>
      </div>
    </div>
    
    <!-- User Details (when filtering by user) -->
    <div v-if="filters.user_id !== 'all' && selectedUser" class="bg-white shadow rounded-lg p-4 mb-6">
      <div class="flex items-center justify-between">
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
        <button 
          @click="confirmTerminateAllUserSessions"
          class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700"
        >
          Terminate All Sessions
        </button>
      </div>
    </div>
    
    <!-- Sessions List -->
    <div class="bg-white shadow rounded-lg p-4 mb-6">
      <h2 class="text-lg font-semibold mb-3">
        Active Sessions
        <span class="text-sm font-normal text-gray-600 ml-2">
          ({{ totalSessions }} total)
        </span>
      </h2>
      
      <div v-if="loading" class="py-10 text-center">
        <div class="spinner mb-2"></div>
        <p class="text-gray-600">Loading active sessions...</p>
      </div>
      
      <div v-else-if="sessions.length === 0" class="py-10 text-center">
        <p class="text-gray-600">No active sessions found matching your filters.</p>
      </div>
      
      <div v-else>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="session in sessions" :key="session.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="text-sm font-medium text-gray-900">
                      {{ session.user.firstname }} {{ session.user.lastname }}
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ session.device_info || 'Unknown device' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ session.ip_address || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDateTime(session.last_activity_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDateTime(session.expires_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <button 
                    @click="confirmTerminateSession(session)"
                    class="text-red-600 hover:text-red-800"
                  >
                    Terminate
                  </button>
                  <button 
                    @click="showSessionDetails(session)"
                    class="text-blue-600 hover:text-blue-800 ml-4"
                  >
                    Details
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
                <span class="font-medium">{{ Math.min(currentPage * perPage, totalSessions) }}</span>
                of
                <span class="font-medium">{{ totalSessions }}</span>
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
    
    <!-- Session Details Modal -->
    <div v-if="selectedSession" class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="selectedSession = null"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                  Session Details
                </h3>
                <div class="mt-4">
                  <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                      <dt class="text-sm font-medium text-gray-500">User</dt>
                      <dd class="mt-1 text-sm text-gray-900">
                        {{ selectedSession.user.firstname }} {{ selectedSession.user.lastname }} ({{ selectedSession.user.email }})
                      </dd>
                    </div>
                    <div class="sm:col-span-1">
                      <dt class="text-sm font-medium text-gray-500">Device</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ selectedSession.device_info || 'Unknown device' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                      <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ selectedSession.ip_address || '-' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                      <dt class="text-sm font-medium text-gray-500">Created</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ formatDateTime(selectedSession.created_at) }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                      <dt class="text-sm font-medium text-gray-500">Last Activity</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ formatDateTime(selectedSession.last_activity_at) }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                      <dt class="text-sm font-medium text-gray-500">Expires</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ formatDateTime(selectedSession.expires_at) }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                      <dt class="text-sm font-medium text-gray-500">User Agent</dt>
                      <dd class="mt-1 text-sm text-gray-900 break-words">{{ selectedSession.user_agent || '-' }}</dd>
                    </div>
                  </dl>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button 
              type="button" 
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              @click="terminateSession(selectedSession.id)"
            >
              Terminate Session
            </button>
            <button 
              type="button" 
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              @click="selectedSession = null"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Confirmation Modal -->
    <div v-if="showConfirmation" class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="cancelConfirmation"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                  {{ confirmationTitle }}
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    {{ confirmationMessage }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button 
              type="button" 
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              @click="confirmAction"
            >
              Confirm
            </button>
            <button 
              type="button" 
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              @click="cancelConfirmation"
            >
              Cancel
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
  name: 'UserSessionsView',
  setup() {
    const usersStore = useUsersStore();
    const loading = ref(false);
    const sessions = ref([]);
    const selectedSession = ref(null);
    const totalSessions = ref(0);
    const currentPage = ref(1);
    const lastPage = ref(1);
    const perPage = ref(15);
    
    // For confirmation modal
    const showConfirmation = ref(false);
    const confirmationTitle = ref('');
    const confirmationMessage = ref('');
    const confirmAction = ref(() => {});
    
    const filters = ref({
      user_id: 'all',
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
    
    const fetchSessions = async () => {
      loading.value = true;
      
      try {
        const params = { ...filters.value, page: currentPage.value };
        
        // Remove the '/api' prefix to avoid duplication
        const response = await axios.get('/admin/sessions', { params });
        
        sessions.value = response.data.data;
        totalSessions.value = response.data.total;
        lastPage.value = response.data.last_page;
        currentPage.value = response.data.current_page;
        perPage.value = response.data.per_page;
        
      } catch (error) {
        console.error('Error fetching sessions:', error);
      } finally {
        loading.value = false;
      }
    };
    
    const changePage = (page) => {
      if (page >= 1 && page <= lastPage.value && page !== currentPage.value) {
        currentPage.value = page;
        fetchSessions();
      }
    };
    
    const resetFilters = () => {
      filters.value = {
        user_id: 'all',
        from_date: '',
        to_date: '',
        page: 1,
        per_page: 15
      };
      currentPage.value = 1;
      fetchSessions();
    };
    
    const showSessionDetails = (session) => {
      selectedSession.value = session;
    };
    
    const confirmTerminateSession = (session) => {
      confirmationTitle.value = 'Terminate Session';
      confirmationMessage.value = `Are you sure you want to terminate this session for user ${session.user.firstname} ${session.user.lastname}? This will log the user out immediately.`;
      confirmAction.value = () => terminateSession(session.id);
      showConfirmation.value = true;
    };
    
    const confirmTerminateAllUserSessions = () => {
      if (!selectedUser.value) return;
      
      confirmationTitle.value = 'Terminate All Sessions';
      confirmationMessage.value = `Are you sure you want to terminate ALL sessions for user ${selectedUser.value.firstname} ${selectedUser.value.lastname}? This will log the user out from all devices.`;
      confirmAction.value = () => terminateAllUserSessions(selectedUser.value.id);
      showConfirmation.value = true;
    };
    
    const confirmCleanupExpiredSessions = () => {
      confirmationTitle.value = 'Cleanup Expired Sessions';
      confirmationMessage.value = 'Are you sure you want to clean up all expired sessions? This will mark all expired sessions as inactive.';
      confirmAction.value = () => cleanupExpiredSessions();
      showConfirmation.value = true;
    };
    
    const cancelConfirmation = () => {
      showConfirmation.value = false;
      confirmationTitle.value = '';
      confirmationMessage.value = '';
      confirmAction.value = () => {};
    };
    
    const terminateSession = async (sessionId) => {
      try {
        await axios.post(`/admin/sessions/${sessionId}/terminate`);
        
        // Close modals
        selectedSession.value = null;
        showConfirmation.value = false;
        
        // Refresh sessions
        fetchSessions();
      } catch (error) {
        console.error('Error terminating session:', error);
      }
    };
    
    const terminateAllUserSessions = async (userId) => {
      try {
        await axios.post(`/admin/sessions/users/${userId}/terminate-all`);
        
        // Close confirmation
        showConfirmation.value = false;
        
        // Refresh sessions
        fetchSessions();
      } catch (error) {
        console.error('Error terminating all user sessions:', error);
      }
    };
    
    const cleanupExpiredSessions = async () => {
      try {
        await axios.post('/admin/sessions/cleanup');
        
        // Close confirmation if it was shown
        showConfirmation.value = false;
        
        // Refresh sessions
        fetchSessions();
      } catch (error) {
        console.error('Error cleaning up expired sessions:', error);
      }
    };
    
    const formatDateTime = (datetime) => {
      if (!datetime) return '';
      const date = new Date(datetime);
      return date.toLocaleString();
    };
    
    onMounted(async () => {
      await Promise.all([
        fetchUsers(),
        fetchSessions()
      ]);
    });
    
    return {
      loading,
      users: computed(() => usersStore.users),
      sessions,
      selectedSession,
      filters,
      selectedUser,
      totalSessions,
      currentPage,
      lastPage,
      perPage,
      pageNumbers,
      showConfirmation,
      confirmationTitle,
      confirmationMessage,
      fetchSessions,
      changePage,
      resetFilters,
      showSessionDetails,
      confirmTerminateSession,
      confirmTerminateAllUserSessions,
      cleanupExpiredSessions,
      confirmAction,
      cancelConfirmation,
      terminateSession,
      terminateAllUserSessions,
      formatDateTime
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
