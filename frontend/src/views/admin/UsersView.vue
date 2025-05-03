<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">User Management</h1>
      <div class="flex gap-2">
        <div class="relative">
          <input 
            type="text" 
            v-model="searchQuery" 
            placeholder="Search users..." 
            class="pl-10 pr-4 py-2 border rounded-md focus:ring-2 focus:ring-primary focus:border-primary"
            @input="handleSearch"
          />
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-2.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <select 
          v-model="statusFilter" 
          class="border rounded-md px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary"
          @change="filterUsers"
        >
          <option value="all">All Statuses</option>
          <option value="active">Active</option>
          <option value="suspended">Suspended</option>
          <option value="banned">Banned</option>
        </select>
        <select 
          v-model="roleFilter" 
          class="border rounded-md px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary"
          @change="filterUsers"
        >
          <option value="all">All Roles</option>
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
      </div>
    </div>

    <!-- Loading indicator -->
    <div v-if="loading" class="flex justify-center items-center py-8">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
    </div>

    <!-- Desktop Users Table - hidden on small screens -->
    <div v-if="!loading" class="bg-white rounded-lg shadow overflow-hidden hidden md:block">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              User
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Email
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Role
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Status
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Joined
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 mr-3">
                  {{ getUserInitials(user) }}
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-900">{{ user.firstname }} {{ user.lastname }}</div>
                  <div class="text-sm text-gray-500">ID: {{ user.id }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ user.email }}</div>
              <div class="text-xs text-gray-500">
                {{ user.email_verified_at ? 'Verified' : 'Not Verified' }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                :class="user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'">
                {{ user.role }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                :class="getStatusClass(user.status)">
                {{ user.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ formatDate(user.created_at) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <div class="flex space-x-2">
                <button @click="openUserModal(user)" class="text-indigo-600 hover:text-indigo-900">
                  Edit
                </button>
                <button v-if="user.status === 'active'" @click="confirmSuspendUser(user)" class="text-yellow-600 hover:text-yellow-900">
                  Suspend
                </button>
                <button v-if="user.status === 'suspended'" @click="confirmReactivateUser(user)" class="text-green-600 hover:text-green-900">
                  Reactivate
                </button>
                <button v-if="user.status !== 'banned'" @click="confirmBanUser(user)" class="text-red-600 hover:text-red-900">
                  Ban
                </button>
                <button v-if="user.status === 'banned'" @click="confirmUnbanUser(user)" class="text-green-600 hover:text-green-900">
                  Unban
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="users.length === 0">
            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
              No users found matching your criteria.
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Mobile User Cards - visible only on small screens -->
    <div v-if="!loading" class="space-y-4 md:hidden">
      <div v-if="users.length === 0" class="bg-white rounded-lg shadow p-4 text-center text-gray-500">
        No users found matching your criteria.
      </div>
      
      <div v-for="user in users" :key="user.id" class="bg-white rounded-lg shadow p-4">
        <div class="flex items-start">
          <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 mr-3 flex-shrink-0">
            {{ getUserInitials(user) }}
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start">
              <div>
                <h3 class="text-sm font-medium text-gray-900">{{ user.firstname }} {{ user.lastname }}</h3>
                <p class="text-xs text-gray-500 mt-1">ID: {{ user.id }}</p>
              </div>
              <div class="flex flex-wrap gap-1 mt-2 sm:mt-0">
                <span class="px-2 py-1 text-xs font-semibold rounded-full" 
                  :class="user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'">
                  {{ user.role }}
                </span>
                <span class="px-2 py-1 text-xs font-semibold rounded-full" 
                  :class="getStatusClass(user.status)">
                  {{ user.status }}
                </span>
              </div>
            </div>
            
            <div class="mt-3">
              <p class="text-xs text-gray-900">{{ user.email }}</p>
              <p class="text-xs text-gray-500">
                {{ user.email_verified_at ? 'Verified' : 'Not Verified' }} • Joined {{ formatDate(user.created_at) }}
              </p>
            </div>
            
            <div class="mt-4 flex flex-wrap gap-2 border-t pt-3">
              <button @click="openUserModal(user)" class="px-2 py-1 text-xs bg-indigo-100 text-indigo-800 rounded hover:bg-indigo-200">
                Edit
              </button>
              <button v-if="user.status === 'active'" @click="confirmSuspendUser(user)" class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200">
                Suspend
              </button>
              <button v-if="user.status === 'suspended'" @click="confirmReactivateUser(user)" class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200">
                Reactivate
              </button>
              <button v-if="user.status !== 'banned'" @click="confirmBanUser(user)" class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200">
                Ban
              </button>
              <button v-if="user.status === 'banned'" @click="confirmUnbanUser(user)" class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200">
                Unban
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-3">
      <div class="text-xs md:text-sm text-gray-700 order-2 sm:order-1">
        Showing <span class="font-medium">{{ startIndex + 1 }}</span> to <span class="font-medium">{{ endIndex }}</span> of <span class="font-medium">{{ totalUsers }}</span> users
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
          :disabled="endIndex >= totalUsers" 
          :class="endIndex >= totalUsers ? 'opacity-50 cursor-not-allowed' : ''"
          class="px-3 py-1 border rounded-md hover:bg-gray-50 text-sm"
        >
          Next
        </button>
      </div>
    </div>

    <!-- User Edit Modal -->
    <div v-if="showUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium">Edit User</h3>
          <button @click="showUserModal = false" class="text-gray-400 hover:text-gray-500">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">First Name</label>
            <input type="text" v-model="editingUser.firstname" class="mt-1 block w-full border rounded-md px-3 py-2 focus:ring-primary focus:border-primary" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Last Name</label>
            <input type="text" v-model="editingUser.lastname" class="mt-1 block w-full border rounded-md px-3 py-2 focus:ring-primary focus:border-primary" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" v-model="editingUser.email" class="mt-1 block w-full border rounded-md px-3 py-2 focus:ring-primary focus:border-primary" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Role</label>
            <select v-model="editingUser.role" class="mt-1 block w-full border rounded-md px-3 py-2 focus:ring-primary focus:border-primary">
              <option value="user">User</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select v-model="editingUser.status" class="mt-1 block w-full border rounded-md px-3 py-2 focus:ring-primary focus:border-primary">
              <option value="active">Active</option>
              <option value="suspended">Suspended</option>
              <option value="banned">Banned</option>
            </select>
          </div>
        </div>
        <div class="mt-6 flex justify-end space-x-3">
          <button @click="showUserModal = false" class="px-4 py-2 border rounded-md hover:bg-gray-50">
            Cancel
          </button>
          <button @click="saveUser" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">
            Save Changes
          </button>
        </div>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div v-if="showConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="mb-4">
          <h3 class="text-lg font-medium">{{ confirmTitle }}</h3>
          <p class="mt-2 text-sm text-gray-500">{{ confirmMessage }}</p>
        </div>
        <div class="flex justify-end space-x-3">
          <button @click="showConfirmModal = false" class="px-4 py-2 border rounded-md hover:bg-gray-50">
            Cancel
          </button>
          <button @click="confirmAction" :class="confirmButtonClass">
            {{ confirmButtonText }}
          </button>
        </div>
      </div>
    </div>

    <!-- Suspension Dialog -->
    <div v-if="showSuspensionDialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium">Suspend User</h3>
          <button @click="showSuspensionDialog = false" class="text-gray-500 hover:text-gray-700">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div v-if="userToSuspend" class="space-y-4">
          <p>You are about to suspend <strong>{{ userToSuspend.firstname }} {{ userToSuspend.lastname }}</strong>.</p>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Suspension Length (Days)</label>
            <input 
              type="number" 
              v-model="suspensionDays" 
              min="1" 
              max="365" 
              class="mt-1 block w-full border rounded-md px-3 py-2 focus:ring-primary focus:border-primary"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Reason (Optional)</label>
            <textarea 
              v-model="suspensionReason" 
              rows="3"
              class="mt-1 block w-full border rounded-md px-3 py-2 focus:ring-primary focus:border-primary"
              placeholder="Explain why this user is being suspended..."
            ></textarea>
          </div>
          
          <div class="flex justify-end space-x-3 mt-6">
            <button @click="showSuspensionDialog = false" class="px-4 py-2 border rounded-md hover:bg-gray-50">
              Cancel
            </button>
            <button @click="submitSuspension" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
              Suspend User
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Ban Dialog -->
    <div v-if="showBanDialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium">Ban User</h3>
          <button @click="showBanDialog = false" class="text-gray-500 hover:text-gray-700">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div v-if="userToBan" class="space-y-4">
          <p class="text-red-600 font-medium">Warning: This action is permanent!</p>
          <p>You are about to ban <strong>{{ userToBan.firstname }} {{ userToBan.lastname }}</strong> from the platform.</p>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Reason (Optional)</label>
            <textarea 
              v-model="banReason" 
              rows="3"
              class="mt-1 block w-full border rounded-md px-3 py-2 focus:ring-primary focus:border-primary"
              placeholder="Explain why this user is being banned..."
            ></textarea>
          </div>
          
          <div class="flex justify-end space-x-3 mt-6">
            <button @click="showBanDialog = false" class="px-4 py-2 border rounded-md hover:bg-gray-50">
              Cancel
            </button>
            <button @click="submitBan" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
              Ban User
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import adminUserService from '@/services/admin.user.service';
import { toast } from 'vue3-toastify';

// Users data
const users = ref([]);
const loading = ref(false);
const totalUsers = ref(0);

// Pagination
const itemsPerPage = 10;
const currentPage = ref(1);
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage);
const endIndex = computed(() => Math.min(startIndex.value + itemsPerPage, totalUsers.value));

// Filtering
const searchQuery = ref('');
const statusFilter = ref('all');
const roleFilter = ref('all');
const filteredUsers = computed(() => {
  return users.value.filter(user => {
    // Apply search filter
    const searchLower = searchQuery.value.toLowerCase();
    const matchesSearch = searchQuery.value === '' || 
      user.firstname.toLowerCase().includes(searchLower) ||
      user.lastname.toLowerCase().includes(searchLower) ||
      user.email.toLowerCase().includes(searchLower);
    
    // Apply status filter
    const matchesStatus = statusFilter.value === 'all' || user.status === statusFilter.value;
    
    // Apply role filter
    const matchesRole = roleFilter.value === 'all' || user.role === roleFilter.value;
    
    return matchesSearch && matchesStatus && matchesRole;
  });
});

// Fetch users from API
const fetchUsers = async () => {
  try {
    loading.value = true;
    const response = await adminUserService.getUsers({
      search: searchQuery.value,
      role: roleFilter.value,
      status: statusFilter.value,
      page: currentPage.value,
      per_page: itemsPerPage
    });
    
    console.log('API Response:', response.data);
    
    // Handle the data based on the API response structure
    if (response.data && response.data.data) {
      // Laravel pagination response structure
      users.value = response.data.data || [];
      totalUsers.value = response.data.total || 0;
      currentPage.value = response.data.current_page || 1;
    } else if (response.data) {
      // Simple array response
      users.value = response.data || [];
      totalUsers.value = response.data.length || 0;
      currentPage.value = 1;
    } else {
      // Fallback to mock data if no valid response
      loadMockUsers();
    }
    
    loading.value = false;
  } catch (error) {
    console.error('Error fetching users:', error);
    toast.error('Failed to load users');
    loading.value = false;
    // Load mock data for development
    loadMockUsers();
  }
};

// Mock data for development purposes
const loadMockUsers = () => {
  users.value = [
    {
      id: 1,
      firstname: 'John',
      lastname: 'Doe',
      email: 'john@example.com',
      email_verified_at: '2023-01-15T14:30:00Z',
      status: 'active',
      role: 'admin',
      created_at: '2023-01-10T10:00:00Z'
    },
    {
      id: 2,
      firstname: 'Jane',
      lastname: 'Smith',
      email: 'jane@example.com',
      email_verified_at: '2023-01-20T09:15:00Z',
      status: 'active',
      role: 'user',
      created_at: '2023-01-15T11:30:00Z'
    },
    {
      id: 3,
      firstname: 'Mark',
      lastname: 'Johnson',
      email: 'mark@example.com',
      email_verified_at: null,
      status: 'suspended',
      role: 'user',
      created_at: '2023-01-18T14:45:00Z'
    },
    {
      id: 4,
      firstname: 'Sarah',
      lastname: 'Wilson',
      email: 'sarah@example.com',
      email_verified_at: '2023-01-25T16:20:00Z',
      status: 'banned',
      role: 'user',
      created_at: '2023-01-20T08:15:00Z'
    }
  ];
  totalUsers.value = users.value.length;
};

// Modal state
const showUserModal = ref(false);
const editingUser = ref({});
const currentUser = ref({});
const showConfirmModal = ref(false);
const confirmTitle = ref('');
const confirmMessage = ref('');
const confirmButtonText = ref('');
const confirmButtonClass = ref('');
const confirmCallback = ref(null);

// Suspension dialog
const showSuspensionDialog = ref(false);
const userToSuspend = ref(null);
const suspensionDays = ref(7);
const suspensionReason = ref('');

// Ban dialog
const showBanDialog = ref(false);
const userToBan = ref(null);
const banReason = ref('');

// Methods
const handleSearch = () => {
  currentPage.value = 1; // Reset to first page on search
  fetchUsers();
};

const filterUsers = () => {
  currentPage.value = 1; // Reset to first page on filter change
  fetchUsers();
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    fetchUsers();
  }
};

const nextPage = () => {
  if (endIndex.value < totalUsers.value) {
    currentPage.value++;
    fetchUsers();
  }
};

const openUserModal = (user) => {
  currentUser.value = { ...user }; // Store original for comparison
  editingUser.value = { ...user };
  showUserModal.value = true;
};

const saveUser = async () => {
  try {
    // Save changes to role and status
    if (editingUser.value.role !== currentUser.value.role) {
      await adminUserService.updateRole(editingUser.value.id, editingUser.value.role);
    }
    
    if (editingUser.value.status !== currentUser.value.status) {
      await adminUserService.updateStatus(editingUser.value.id, editingUser.value.status);
    }
    
    toast.success('User updated successfully');
    showUserModal.value = false;
    fetchUsers(); // Refresh the list
  } catch (error) {
    toast.error('Failed to update user: ' + (error.response?.data?.message || error.message));
  }
};

const confirmSuspendUser = (user) => {
  confirmTitle.value = 'Suspend User';
  confirmMessage.value = `Are you sure you want to suspend ${user.firstname} ${user.lastname}? They will not be able to log in until reactivated.`;
  confirmButtonText.value = 'Suspend';
  confirmButtonClass.value = 'px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700';
  confirmCallback.value = () => suspendUser(user);
  showConfirmModal.value = true;
};

const confirmReactivateUser = (user) => {
  confirmTitle.value = 'Reactivate User';
  confirmMessage.value = `Are you sure you want to reactivate ${user.firstname} ${user.lastname}?`;
  confirmButtonText.value = 'Reactivate';
  confirmButtonClass.value = 'px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700';
  confirmCallback.value = () => reactivateUser(user);
  showConfirmModal.value = true;
};

const confirmBanUser = (user) => {
  confirmTitle.value = 'Ban User';
  confirmMessage.value = `Are you sure you want to ban ${user.firstname} ${user.lastname}? This will prevent them from using the platform permanently.`;
  confirmButtonText.value = 'Ban';
  confirmButtonClass.value = 'px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700';
  confirmCallback.value = () => banUser(user);
  showConfirmModal.value = true;
};

const confirmUnbanUser = (user) => {
  confirmTitle.value = 'Unban User';
  confirmMessage.value = `Are you sure you want to unban ${user.firstname} ${user.lastname}? This will allow them to access the platform again.`;
  confirmButtonText.value = 'Unban';
  confirmButtonClass.value = 'px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700';
  confirmCallback.value = () => unbanUser(user);
  showConfirmModal.value = true;
};

const confirmAction = () => {
  if (confirmCallback.value) {
    confirmCallback.value();
  }
  showConfirmModal.value = false;
};

const suspendUser = async (user) => {
  try {
    // Show suspension dialog
    showSuspensionDialog.value = true;
    userToSuspend.value = user;
  } catch (error) {
    toast.error('Failed to prepare suspension: ' + (error.response?.data?.message || error.message));
  }
};

const reactivateUser = async (user) => {
  try {
    await adminUserService.activateUser(user.id);
    toast.success(`${user.firstname} ${user.lastname} has been reactivated`);
    fetchUsers();
  } catch (error) {
    toast.error('Failed to reactivate user: ' + (error.response?.data?.message || error.message));
  }
};

const banUser = async (user) => {
  try {
    // Show ban dialog
    showBanDialog.value = true;
    userToBan.value = user;
  } catch (error) {
    toast.error('Failed to prepare ban: ' + (error.response?.data?.message || error.message));
  }
};

const unbanUser = async (user) => {
  try {
    await adminUserService.unbanUser(user.id);
    toast.success(`${user.firstname} ${user.lastname} has been unbanned`);
    fetchUsers();
  } catch (error) {
    toast.error('Failed to unban user: ' + (error.response?.data?.message || error.message));
  }
};

const getUserInitials = (user) => {
  if (user.firstname && user.lastname) {
    return (user.firstname[0] + user.lastname[0]).toUpperCase();
  }
  return user.email ? user.email[0].toUpperCase() : '?';
};

const getStatusClass = (status) => {
  const classes = {
    'active': 'bg-green-100 text-green-800',
    'suspended': 'bg-yellow-100 text-yellow-800',
    'banned': 'bg-red-100 text-red-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

// Submit suspension
const submitSuspension = async () => {
  if (!userToSuspend.value) return;
  
  try {
    await adminUserService.suspendUser(
      userToSuspend.value.id, 
      suspensionDays.value, 
      suspensionReason.value
    );
    
    toast.success(`${userToSuspend.value.firstname} ${userToSuspend.value.lastname} has been suspended for ${suspensionDays.value} days`);
    showSuspensionDialog.value = false;
    suspensionDays.value = 7;
    suspensionReason.value = '';
    fetchUsers();
  } catch (error) {
    toast.error('Failed to suspend user: ' + (error.response?.data?.message || error.message));
  }
};

// Submit ban
const submitBan = async () => {
  if (!userToBan.value) return;
  
  try {
    await adminUserService.banUser(
      userToBan.value.id, 
      banReason.value
    );
    
    toast.success(`${userToBan.value.firstname} ${userToBan.value.lastname} has been banned`);
    showBanDialog.value = false;
    banReason.value = '';
    fetchUsers();
  } catch (error) {
    toast.error('Failed to ban user: ' + (error.response?.data?.message || error.message));
  }
};

// Load users on component mount
onMounted(() => {
  fetchUsers();
});
</script>
