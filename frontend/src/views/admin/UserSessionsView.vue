<template>
  <div class="user-sessions-container">
    <h1 class="text-xl md:text-2xl font-bold mb-4 md:mb-6">User Sessions</h1>
    
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
      <div class="flex flex-col md:flex-row justify-between mb-4">
        <h2 class="text-base md:text-lg font-semibold mb-3 md:mb-0">Filters</h2>
        <div>
          <button 
            @click="confirmCleanupExpiredSessions" 
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
          >
            Clean Up Expired Sessions
          </button>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
          <select v-model="filters.user_id" @change="fetchSessions" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
            <option value="all">All Users</option>
            <option v-for="user in users" :key="user.id" :value="user.id">
              {{ user.firstname }} {{ user.lastname }}
            </option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Device</label>
          <select v-model="filters.device" @change="fetchSessions" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
            <option value="all">All Devices</option>
            <option value="desktop">Desktop</option>
            <option value="mobile">Mobile</option>
            <option value="tablet">Tablet</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
          <select v-model="filters.status" @change="fetchSessions" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
            <option value="all">All Statuses</option>
            <option value="active">Active</option>
            <option value="expired">Expired</option>
          </select>
        </div>
      </div>
    </div>
    
    <!-- Loading state -->
    <div v-if="loading" class="flex justify-center items-center py-8">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
    </div>
    
    <!-- Desktop sessions table - hidden on small screens -->
    <div v-if="!loading" class="hidden md:block">
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device / Browser</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Active</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="session in sessions" :key="session.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 mr-3">
                    {{ getUserInitials(session.user) }}
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ session.user.firstname }} {{ session.user.lastname }}</div>
                    <div class="text-xs text-gray-500">{{ session.user.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ session.ip_address }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ session.device }}</div>
                <div class="text-xs text-gray-500">{{ session.browser }}</div>
                <button 
                  v-if="session.user_agent" 
                  @click="showUserAgentDetails(session)" 
                  class="text-xs text-blue-500 hover:text-blue-700 mt-1"
                >
                  Show Details
                </button>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(session.last_active) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                  :class="session.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                >
                  {{ session.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button 
                  v-if="session.status === 'active'"
                  @click="confirmTerminateSession(session)" 
                  class="text-red-600 hover:text-red-900"
                >
                  Terminate
                </button>
              </td>
            </tr>
            <tr v-if="sessions.length === 0">
              <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                No sessions found matching your criteria.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Mobile cards view - visible only on small screens -->
    <div v-if="!loading" class="md:hidden space-y-4">
      <div v-if="sessions.length === 0" class="bg-white rounded-lg shadow p-4 text-center text-gray-500">
        No sessions found matching your criteria.
      </div>
      
      <div v-for="session in sessions" :key="session.id" class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4">
          <div class="flex items-start">
            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 mr-3 flex-shrink-0">
              {{ getUserInitials(session.user) }}
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start">
                <div>
                  <h3 class="text-sm font-medium text-gray-900">{{ session.user.firstname }} {{ session.user.lastname }}</h3>
                  <p class="text-xs text-gray-500 mt-0.5">{{ session.user.email }}</p>
                </div>
                <span 
                  class="px-2 py-1 text-xs font-semibold rounded-full mt-1 sm:mt-0 inline-flex"
                  :class="session.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                >
                  {{ session.status }}
                </span>
              </div>
              
              <div class="mt-3 grid grid-cols-2 gap-2 text-xs text-gray-700">
                <div>
                  <span class="font-medium">IP Address:</span> 
                  <span>{{ session.ip_address }}</span>
                </div>
                <div>
                  <span class="font-medium">Device:</span> 
                  <span>{{ session.device }}</span>
                </div>
                <div>
                  <span class="font-medium">Browser:</span> 
                  <span>{{ session.browser }}</span>
                </div>
                <div>
                  <span class="font-medium">Last Active:</span> 
                  <span>{{ formatDate(session.last_active) }}</span>
                </div>
                <div v-if="session.user_agent" class="col-span-2">
                  <button 
                    @click="showUserAgentDetails(session)" 
                    class="text-xs text-blue-500 hover:text-blue-700"
                  >
                    Show User Agent Details
                  </button>
                </div>
              </div>
              
              <div class="mt-3 pt-3 border-t flex justify-end" v-if="session.status === 'active'">
                <button 
                  @click="confirmTerminateSession(session)" 
                  class="px-3 py-1 bg-red-100 text-red-800 text-xs rounded hover:bg-red-200"
                >
                  Terminate Session
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-3">
      <div class="text-xs md:text-sm text-gray-700 order-2 sm:order-1">
        Showing <span class="font-medium">{{ startIndex + 1 }}</span> to <span class="font-medium">{{ endIndex }}</span> of <span class="font-medium">{{ totalSessions }}</span> sessions
      </div>
      <div class="flex space-x-2 order-1 sm:order-2">
        <button 
          @click="prevPage" 
          :disabled="currentPage === 1" 
          :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''"
          class="px-3 py-1 border rounded-md hover:bg-gray-50 text-sm"
        >
          Previous
        </button>
        <button 
          @click="nextPage" 
          :disabled="endIndex >= totalSessions" 
          :class="endIndex >= totalSessions ? 'opacity-50 cursor-not-allowed' : ''"
          class="px-3 py-1 border rounded-md hover:bg-gray-50 text-sm"
        >
          Next
        </button>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div v-if="showConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 m-4 md:m-0">
        <div class="mb-4">
          <h3 class="text-lg font-medium">{{ confirmTitle }}</h3>
          <p class="mt-2 text-sm text-gray-500">{{ confirmMessage }}</p>
        </div>
        <div class="flex justify-end space-x-3">
          <button @click="showConfirmModal = false" class="px-4 py-2 border rounded-md hover:bg-gray-50">
            Cancel
          </button>
          <button @click="confirmAction" :class="confirmButtonClass || 'px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700'">
            {{ confirmButtonText }}
          </button>
        </div>
      </div>
    </div>

    <!-- User Agent Modal -->
    <div v-if="showUserAgentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 m-4 md:m-0">
        <div class="mb-4 flex justify-between items-start">
          <h3 class="text-lg font-medium">User Agent Details</h3>
          <button @click="showUserAgentModal = false" class="text-gray-500 hover:text-gray-700">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
          </button>
        </div>
        
        <div class="mb-4">
          <div class="text-sm mb-2">
            <span class="font-medium">User:</span> 
            {{ selectedSession?.user?.firstname }} {{ selectedSession?.user?.lastname }}
          </div>
          <div class="text-sm mb-2">
            <span class="font-medium">IP Address:</span> 
            {{ selectedSession?.ip_address }}
          </div>
          <div class="text-sm mb-2">
            <span class="font-medium">Device:</span> 
            {{ selectedSession?.device }}
          </div>
          <div class="text-sm mb-2">
            <span class="font-medium">Browser:</span> 
            {{ selectedSession?.browser }}
          </div>
          <div class="text-sm mb-4">
            <span class="font-medium">Last Active:</span> 
            {{ selectedSession ? formatDate(selectedSession.last_active) : '' }}
          </div>
          
          <div class="mt-4 pt-4 border-t">
            <h4 class="font-medium mb-2">Raw User Agent</h4>
            <div class="bg-gray-50 p-3 rounded text-xs font-mono break-all">
              {{ selectedSession?.user_agent }}
            </div>
          </div>
        </div>
        
        <div class="flex justify-end">
          <button @click="showUserAgentModal = false" class="px-4 py-2 border rounded-md hover:bg-gray-50">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue';
import { format, formatDistance } from 'date-fns';
import axios from 'axios';
import { useUsersStore } from '@/stores/users.store';
import { toast } from 'vue3-toastify';

export default {
  name: 'UserSessionsView',
  setup() {
    const usersStore = useUsersStore();
    const loading = ref(false);
    const sessions = ref([]);
    const totalSessions = ref(0);
    const currentPage = ref(1);
    const lastPage = ref(1);
    const perPage = ref(15);
    
    // For confirmation modal
    const showConfirmModal = ref(false);
    const confirmTitle = ref('');
    const confirmMessage = ref('');
    const confirmButtonText = ref('');
    const confirmButtonClass = ref('');
    const confirmCallback = ref(null);
    
    // For user agent modal
    const showUserAgentModal = ref(false);
    const selectedSession = ref(null);
    
    const filters = ref({
      user_id: 'all',
      device: 'all',
      status: 'all',
      page: 1,
      per_page: 15
    });
    
    const selectedUser = computed(() => {
      if (filters.value.user_id === 'all') return null;
      return usersStore.users.find(user => user.id.toString() === filters.value.user_id.toString());
    });
    
    const pageNumbers = computed(() => {
      const numbers = [];
      let start = Math.max(1, currentPage.value - 2);
      let end = Math.min(lastPage.value, currentPage.value + 2);
      
      // Ensure we always show 5 page numbers if possible
      if (end - start + 1 < 5 && lastPage.value >= 5) {
        if (start === 1) {
          end = Math.min(5, lastPage.value);
        } else if (end === lastPage.value) {
          start = Math.max(1, lastPage.value - 4);
        }
      }
      
      for (let i = start; i <= end; i++) {
        numbers.push(i);
      }
      
      return numbers;
    });
    
    const fetchUsers = async () => {
      try {
        if (usersStore.users.length === 0) {
          await usersStore.fetchUsers();
        }
      } catch (error) {
        console.error('Error fetching users:', error);
        toast.error('Failed to load users: ' + (error.response?.data?.message || error.message));
      }
    };
    
    const fetchSessions = async () => {
      loading.value = true;
      
      try {
        const params = {
          user_id: filters.value.user_id,
          device: filters.value.device !== 'all' ? filters.value.device : null,
          status: filters.value.status !== 'all' ? (filters.value.status === 'active' ? 1 : 0) : null,
          page: currentPage.value,
          per_page: perPage.value
        };
        
        // Make sure we're using the correct API endpoint (without duplicate /api prefix)
        const response = await axios.get('/admin/sessions', { params });
        
        // Process session data to ensure it has all required fields
        if (response.data && response.data.data && response.data.data.length > 0) {
          // Debug: Log the first session to see its structure
          console.log('Sample session data:', response.data.data[0]);
          
          // Backend returns session data; map it to ensure consistent format
          sessions.value = response.data.data.map(session => {
            // Extract device and browser from device_info if available
            let deviceInfo = {};
            let userAgent = '';
            
            // Extract user_agent directly if available
            if (session.user_agent) {
              userAgent = session.user_agent;
            }
            
            // Try to get device_info
            if (session.device_info) {
              try {
                // Handle if it's a string that needs parsing
                if (typeof session.device_info === 'string') {
                  deviceInfo = JSON.parse(session.device_info);
                } 
                // Handle if it's already an object
                else if (typeof session.device_info === 'object') {
                  deviceInfo = session.device_info;
                }
              } catch (error) {
                console.error('Error parsing device_info:', error);
              }
            }
            
            // Parse OS, Browser and Device information
            let deviceType = 'Unknown';
            let browserInfo = 'Unknown';
            
            // If we have parsed device info, use it
            if (deviceInfo) {
              deviceType = deviceInfo.device_type || 
                           deviceInfo.device || 
                           deviceInfo.os || 
                           'Unknown';
                           
              browserInfo = deviceInfo.browser ? 
                            `${deviceInfo.browser} ${deviceInfo.version || ''}` : 
                            'Unknown';
            }
            
            // Use the user agent string as a fallback if we don't have structured info
            if (userAgent) {
              // For device type
              if (deviceType === 'Unknown') {
                if (userAgent.includes('Mobile') || 
                    userAgent.includes('Android') && !userAgent.includes('Android TV') || 
                    userAgent.includes('iPhone')) {
                  deviceType = 'Mobile';
                } else if (userAgent.includes('iPad') || userAgent.includes('Tablet')) {
                  deviceType = 'Tablet';
                } else if (userAgent.includes('Linux')) {
                  deviceType = 'Linux Desktop';
                } else if (userAgent.includes('Windows')) {
                  deviceType = 'Windows Desktop';
                } else if (userAgent.includes('Mac')) {
                  deviceType = 'Mac Desktop';
                } else {
                  deviceType = 'Desktop';
                }
              }
              
              // For browser info
              if (browserInfo === 'Unknown') {
                if (userAgent.includes('Chrome') && !userAgent.includes('Chromium')) {
                  browserInfo = userAgent.match(/Chrome\/([0-9.]+)/)?.[1] 
                    ? `Chrome ${userAgent.match(/Chrome\/([0-9.]+)/)[1]}` 
                    : 'Chrome';
                } else if (userAgent.includes('Firefox')) {
                  browserInfo = userAgent.match(/Firefox\/([0-9.]+)/)?.[1] 
                    ? `Firefox ${userAgent.match(/Firefox\/([0-9.]+)/)[1]}` 
                    : 'Firefox';
                } else if (userAgent.includes('Safari') && !userAgent.includes('Chrome')) {
                  browserInfo = userAgent.match(/Safari\/([0-9.]+)/)?.[1] 
                    ? `Safari ${userAgent.match(/Safari\/([0-9.]+)/)[1]}` 
                    : 'Safari';
                } else if (userAgent.includes('Edge')) {
                  browserInfo = userAgent.match(/Edge\/([0-9.]+)/)?.[1] 
                    ? `Edge ${userAgent.match(/Edge\/([0-9.]+)/)[1]}` 
                    : 'Edge';
                }
              }
            }
            
            return {
              id: session.id,
              user: session.user || {},
              ip_address: session.ip_address || 'Unknown',
              device: deviceType,
              browser: browserInfo,
              user_agent: userAgent, // Store the raw user agent for detail view
              last_active: session.last_activity_at || session.created_at,
              status: session.is_active ? 'active' : 'expired'
            };
          });
          
          totalSessions.value = response.data.total || sessions.value.length;
          lastPage.value = response.data.last_page || 1;
          currentPage.value = response.data.current_page || 1;
          perPage.value = response.data.per_page || 15;
        } else {
          toast.error('No session data returned from server');
          sessions.value = [];
          totalSessions.value = 0;
        }
      } catch (error) {
        console.error('Error fetching sessions:', error);
        toast.error('Failed to load sessions: ' + (error.response?.data?.message || error.message));
        sessions.value = [];
        totalSessions.value = 0;
      } finally {
        loading.value = false;
      }
    };
    
    // Mock data for development and fallback
    const loadMockSessions = () => {
      sessions.value = [
        {
          id: 1,
          user: {
            id: 1,
            firstname: 'John',
            lastname: 'Doe',
            email: 'john@example.com'
          },
          ip_address: '192.168.1.101',
          device: 'Desktop',
          browser: 'Chrome 98.0.4758',
          last_active: new Date(Date.now() - 15 * 60 * 1000).toISOString(), // 15 minutes ago
          status: 'active'
        },
        {
          id: 2,
          user: {
            id: 2,
            firstname: 'Jane',
            lastname: 'Smith',
            email: 'jane@example.com'
          },
          ip_address: '192.168.1.102',
          device: 'Mobile',
          browser: 'Safari Mobile 15.4',
          last_active: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString(), // 2 hours ago
          status: 'active'
        },
        {
          id: 3,
          user: {
            id: 3,
            firstname: 'Mark',
            lastname: 'Johnson',
            email: 'mark@example.com'
          },
          ip_address: '192.168.1.103',
          device: 'Tablet',
          browser: 'Firefox 97.0.1',
          last_active: new Date(Date.now() - 48 * 60 * 60 * 1000).toISOString(), // 2 days ago
          status: 'expired'
        }
      ];
      totalSessions.value = sessions.value.length;
      lastPage.value = 1;
      currentPage.value = 1;
    };
    
    const changePage = (page) => {
      // Only change page if it's a number and different from current page
      if (typeof page === 'number' && page !== currentPage.value) {
        currentPage.value = page;
        fetchSessions();
      }
    };
    
    const resetFilters = () => {
      filters.value = {
        user_id: 'all',
        device: 'all',
        status: 'all',
        page: 1,
        per_page: 15
      };
      currentPage.value = 1;
      fetchSessions();
    };
    
    const showSessionDetails = (session) => {
      // We could implement a modal to show more session details here
      // For now, we'll just log the session details to the console
      console.log('Session details:', session);
      
      // In the future, you might implement something like:
      // sessionDetailsModalData.value = session;
      // showSessionDetailsModal.value = true;
    };
    
    const confirmTerminateSession = (session) => {
      confirmTitle.value = 'Terminate Session';
      confirmMessage.value = `Are you sure you want to terminate the session for ${session.user.firstname} ${session.user.lastname}? This will log them out immediately.`;
      confirmButtonText.value = 'Terminate';
      confirmButtonClass.value = 'px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700';
      confirmCallback.value = () => terminateSession(session);
      showConfirmModal.value = true;
    };

    const terminateSession = async (session) => {
      try {
        await axios.post(`/admin/sessions/${session.id}/terminate`);
        
        // Close modals
        showConfirmModal.value = false;
        
        // Refresh sessions
        fetchSessions();
        
        toast.success(`Session for ${session.user.firstname} ${session.user.lastname} has been terminated`);
      } catch (error) {
        console.error('Error terminating session:', error);
        toast.error('Failed to terminate session: ' + (error.response?.data?.message || error.message));
      }
    };

    const confirmCleanupExpiredSessions = () => {
      confirmTitle.value = 'Clean Up Expired Sessions';
      confirmMessage.value = 'Are you sure you want to clean up all expired sessions? This will remove them from the database.';
      confirmButtonText.value = 'Clean Up';
      confirmButtonClass.value = 'px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700';
      confirmCallback.value = () => cleanupExpiredSessions();
      showConfirmModal.value = true;
    };

    const cleanupExpiredSessions = async () => {
      try {
        const response = await axios.post('/admin/sessions/cleanup');
        
        // Close modal
        showConfirmModal.value = false;
        
        // Refresh sessions
        fetchSessions();
        
        toast.success(`${response.data.count} expired sessions have been cleaned up`);
      } catch (error) {
        console.error('Error cleaning up expired sessions:', error);
        toast.error('Failed to clean up expired sessions: ' + (error.response?.data?.message || error.message));
      }
    };

    const confirmAction = () => {
      if (confirmCallback.value) {
        confirmCallback.value();
      }
      showConfirmModal.value = false;
    };

    const nextPage = () => {
      if (currentPage.value < lastPage.value) {
        currentPage.value++;
        fetchSessions();
      }
    };
    
    const prevPage = () => {
      if (currentPage.value > 1) {
        currentPage.value--;
        fetchSessions();
      }
    };

    const startIndex = computed(() => (currentPage.value - 1) * perPage.value);
    const endIndex = computed(() => Math.min(startIndex.value + perPage.value, totalSessions.value));

    const formatDate = (dateString) => {
      try {
        const date = new Date(dateString);
        const now = new Date();
        
        // If it's within the last 24 hours, show relative time
        if (now - date < 24 * 60 * 60 * 1000) {
          return formatDistance(date, now, { addSuffix: true });
        }
        
        // Otherwise show formatted date
        return format(date, 'MMM d, yyyy h:mm a');
      } catch (e) {
        return 'Invalid date';
      }
    };

    const getUserInitials = (user) => {
      if (!user || !user.firstname || !user.lastname) return '??';
      return `${user.firstname.charAt(0)}${user.lastname.charAt(0)}`;
    };

    const showUserAgentDetails = (session) => {
      selectedSession.value = session;
      showUserAgentModal.value = true;
    };

    onMounted(async () => {
      await fetchUsers();
      fetchSessions();
    });

    // Return data and methods that should be accessible in the template
    return {
      usersStore,
      users: computed(() => usersStore.users),
      loading,
      sessions,
      totalSessions,
      currentPage,
      lastPage,
      perPage,
      showConfirmModal,
      confirmTitle,
      confirmMessage,
      confirmButtonText,
      confirmButtonClass,
      filters,
      selectedUser,
      pageNumbers,
      startIndex,
      endIndex,
      fetchSessions,
      changePage,
      resetFilters,
      showSessionDetails,
      confirmTerminateSession,
      confirmCleanupExpiredSessions,
      terminateSession,
      confirmAction,
      nextPage,
      prevPage,
      formatDate,
      getUserInitials,
      showUserAgentDetails,
      showUserAgentModal,
      selectedSession,
    };
  }
};
</script>

<style scoped>
/* Add your styles here */
</style>
