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

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
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
          <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-50">
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
              </div>
            </td>
          </tr>
          <tr v-if="filteredUsers.length === 0">
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
        Showing <span class="font-medium">{{ startIndex + 1 }}</span> to <span class="font-medium">{{ endIndex }}</span> of <span class="font-medium">{{ totalUsers }}</span> users
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
          :disabled="endIndex >= totalUsers" 
          :class="endIndex >= totalUsers ? 'opacity-50 cursor-not-allowed' : ''"
          class="px-3 py-1 border rounded-md hover:bg-gray-50"
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
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

// Mock users data - replace with API calls in production
const users = ref([
  {
    id: 1,
    firstname: 'John',
    lastname: 'Doe',
    email: 'john@example.com',
    email_verified_at: '2025-01-15T10:00:00',
    role: 'admin',
    status: 'active',
    created_at: '2025-01-01T10:00:00'
  },
  {
    id: 2,
    firstname: 'Jane',
    lastname: 'Smith',
    email: 'jane@example.com',
    email_verified_at: '2025-01-16T10:00:00',
    role: 'user',
    status: 'active',
    created_at: '2025-01-02T10:00:00'
  },
  {
    id: 3,
    firstname: 'Robert',
    lastname: 'Johnson',
    email: 'robert@example.com',
    email_verified_at: null,
    role: 'user',
    status: 'suspended',
    created_at: '2025-01-03T10:00:00'
  },
  {
    id: 4,
    firstname: 'Emily',
    lastname: 'Williams',
    email: 'emily@example.com',
    email_verified_at: '2025-01-18T10:00:00',
    role: 'user',
    status: 'banned',
    created_at: '2025-01-04T10:00:00'
  }
]);

// Pagination
const itemsPerPage = 10;
const currentPage = ref(1);
const totalUsers = computed(() => filteredUsers.value.length);
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage);
const endIndex = computed(() => Math.min(startIndex.value + itemsPerPage, totalUsers.value));
const paginatedUsers = computed(() => {
  return filteredUsers.value.slice(startIndex.value, endIndex.value);
});

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

// Modal state
const showUserModal = ref(false);
const editingUser = ref({});
const showConfirmModal = ref(false);
const confirmTitle = ref('');
const confirmMessage = ref('');
const confirmButtonText = ref('');
const confirmButtonClass = ref('');
const confirmCallback = ref(null);

// Methods
const handleSearch = () => {
  currentPage.value = 1; // Reset to first page on search
};

const filterUsers = () => {
  currentPage.value = 1; // Reset to first page on filter change
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (endIndex.value < totalUsers.value) {
    currentPage.value++;
  }
};

const openUserModal = (user) => {
  editingUser.value = { ...user };
  showUserModal.value = true;
};

const saveUser = async () => {
  // In a real app, you would call an API to update the user
  const index = users.value.findIndex(u => u.id === editingUser.value.id);
  if (index !== -1) {
    users.value[index] = { ...editingUser.value };
  }
  showUserModal.value = false;
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

const confirmAction = () => {
  if (confirmCallback.value) {
    confirmCallback.value();
  }
  showConfirmModal.value = false;
};

const suspendUser = (user) => {
  // In a real app, you would call an API to suspend the user
  const index = users.value.findIndex(u => u.id === user.id);
  if (index !== -1) {
    users.value[index] = { ...user, status: 'suspended' };
  }
};

const reactivateUser = (user) => {
  // In a real app, you would call an API to reactivate the user
  const index = users.value.findIndex(u => u.id === user.id);
  if (index !== -1) {
    users.value[index] = { ...user, status: 'active' };
  }
};

const banUser = (user) => {
  // In a real app, you would call an API to ban the user
  const index = users.value.findIndex(u => u.id === user.id);
  if (index !== -1) {
    users.value[index] = { ...user, status: 'banned' };
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

// Load users on component mount
onMounted(() => {
  // In a real app, you would fetch users from an API
  // fetchUsers();
});
</script>
