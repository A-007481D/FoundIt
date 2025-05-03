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
          v-model="roleFilter" 
          class="border rounded-md px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary"
          @change="filterUsers"
        >
          <option value="all">All Roles</option>
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
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
      </div>
    </div>

    <!-- Loading indicator -->
    <div v-if="loading" class="flex justify-center items-center py-8">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
    </div>

    <!-- Users Table -->
    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
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
          <tr v-for="user in paginatedUsers" :key="user.id" class="hover:bg-gray-50">
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
                <button @click="viewUser(user)" class="text-indigo-600 hover:text-indigo-900">
                  View
                </button>
                <button @click="confirmAction(user, 'ban')" class="text-red-600 hover:text-red-900">
                  Ban
                </button>
                <button @click="confirmAction(user, 'suspend')" class="text-yellow-600 hover:text-yellow-900">
                  Suspend
                </button>
                <button @click="confirmAction(user, 'delete')" class="text-red-600 hover:text-red-900">
                  Delete
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

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
      <div class="text-sm text-gray-700">
        Showing <span class="font-medium">{{ startIndex + 1 }}</span> to <span class="font-medium">{{ endIndex }}</span> of <span class="font-medium">{{ users.length }}</span> users
      </div>
      <div class="flex space-x-2">
        <button 
          @click="prevPage" 
          :disabled="currentPage === 1" 
          :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''"
          class="px-3 py-1 border rounded-md hover:bg-gray-50"
        >
          Previous
        </button>
        <button 
          @click="nextPage" 
          :disabled="endIndex >= users.length" 
          :class="endIndex >= users.length ? 'opacity-50 cursor-not-allowed' : ''"
          class="px-3 py-1 border rounded-md hover:bg-gray-50"
        >
          Next
        </button>
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
          <button @click="confirmActionCallback" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
            {{ confirmTitle }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AdminUserService from '@/services/api/admin/user';
import { toast } from 'vue3-toastify';

const loading = ref(false);
const users = ref([]);
const searchQuery = ref('');
const roleFilter = ref('all');
const statusFilter = ref('all');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const showConfirmModal = ref(false);
const selectedUser = ref(null);
const confirmTitle = ref('');
const confirmMessage = ref('');
const confirmActionCallback = ref(null);

// Computed values for pagination
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage.value);
const endIndex = computed(() => Math.min(startIndex.value + itemsPerPage.value, users.value.length));
const paginatedUsers = computed(() => users.value.slice(startIndex.value, endIndex.value));

// Methods
async function fetchUsers() {
  loading.value = true;
  try {
    const response = await AdminUserService.getUsers({
      search: searchQuery.value,
      role: roleFilter.value === 'all' ? '' : roleFilter.value,
      status: statusFilter.value === 'all' ? '' : statusFilter.value,
      page: currentPage.value,
      per_page: itemsPerPage.value
    });
    users.value = response.data.data;
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to fetch users');
    console.error('Failed to fetch users:', err);
  } finally {
    loading.value = false;
  }
}

function handleSearch() {
  currentPage.value = 1;
  fetchUsers();
}

function filterUsers() {
  currentPage.value = 1;
  fetchUsers();
}

function prevPage() {
  if (currentPage.value > 1) {
    currentPage.value--;
    fetchUsers();
  }
}

function nextPage() {
  currentPage.value++;
  fetchUsers();
}

function viewUser(user) {
  // Implement view user functionality
}

async function banUser(user) {
  try {
    await AdminUserService.banUser(user.id);
    toast.success('User banned successfully');
    fetchUsers();
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to ban user');
    console.error('Failed to ban user:', err);
  }
}

async function suspendUser(user) {
  try {
    await AdminUserService.suspendUser(user.id, 7);
    toast.success('User suspended successfully');
    fetchUsers();
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to suspend user');
    console.error('Failed to suspend user:', err);
  }
}

async function deleteUser(user) {
  try {
    await AdminUserService.deleteUser(user.id);
    toast.success('User deleted successfully');
    fetchUsers();
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to delete user');
    console.error('Failed to delete user:', err);
  }
}

function confirmAction(user, action) {
  selectedUser.value = user;
  confirmTitle.value = action === 'ban' ? 'Ban User' : action === 'suspend' ? 'Suspend User' : 'Delete User';
  confirmMessage.value = action === 'ban' 
    ? 'Are you sure you want to ban this user? This action cannot be undone.'
    : action === 'suspend'
    ? 'Are you sure you want to suspend this user for 7 days?'
    : 'Are you sure you want to delete this user? This action cannot be undone.';
  confirmActionCallback.value = action === 'ban' ? banUser : action === 'suspend' ? suspendUser : deleteUser;
  showConfirmModal.value = true;
}

function getStatusClass(status) {
  switch (status) {
    case 'active':
      return 'bg-green-100 text-green-800';
    case 'banned':
      return 'bg-red-100 text-red-800';
    case 'suspended':
      return 'bg-yellow-100 text-yellow-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString();
}

// Load users on component mount
onMounted(() => {
  fetchUsers();
});
</script>
