<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Item Management</h1>
      <div class="flex gap-2">
        <div class="relative">
          <input 
            type="text" 
            v-model="searchQuery" 
            placeholder="Search items..." 
            class="pl-10 pr-4 py-2 border rounded-md focus:ring-2 focus:ring-primary focus:border-primary"
            @input="handleSearch"
          />
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-2.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <select 
          v-model="typeFilter" 
          class="border rounded-md px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary"
          @change="filterItems"
        >
          <option value="all">All Types</option>
          <option value="lost">Lost</option>
          <option value="found">Found</option>
        </select>
        <select 
          v-model="statusFilter" 
          class="border rounded-md px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary"
          @change="filterItems"
        >
          <option value="all">All Statuses</option>
          <option value="active">Active</option>
          <option value="archived">Archived</option>
          <option value="reported">Reported</option>
        </select>
      </div>
    </div>

    <!-- Items Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Item
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Type
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Status
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              User
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Date
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="item in paginatedItems" :key="item.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="h-10 w-10 flex-shrink-0">
                  <img :src="item.image" :alt="item.title" class="h-10 w-10 rounded-md object-cover" />
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">{{ item.title }}</div>
                  <div class="text-sm text-gray-500 truncate max-w-xs">{{ item.description }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                :class="item.type === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'">
                {{ item.type }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                :class="getStatusClass(item.status)">
                {{ item.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ item.user.name }}</div>
              <div class="text-xs text-gray-500">{{ item.user.email }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ formatDate(item.created_at) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <div class="flex space-x-2">
                <button @click="viewItem(item)" class="text-indigo-600 hover:text-indigo-900">
                  View
                </button>
                <button v-if="item.status === 'reported'" @click="reviewItem(item)" class="text-yellow-600 hover:text-yellow-900">
                  Review
                </button>
                <button v-if="item.status === 'active'" @click="confirmArchiveItem(item)" class="text-gray-600 hover:text-gray-900">
                  Archive
                </button>
                <button v-if="item.status !== 'deleted'" @click="confirmDeleteItem(item)" class="text-red-600 hover:text-red-900">
                  Delete
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="paginatedItems.length === 0">
            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
              No items found matching your criteria.
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
      <div class="text-sm text-gray-700">
        Showing <span class="font-medium">{{ startIndex + 1 }}</span> to <span class="font-medium">{{ endIndex }}</span> of <span class="font-medium">{{ totalItems }}</span> items
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
          :disabled="endIndex >= totalItems" 
          :class="endIndex >= totalItems ? 'opacity-50 cursor-not-allowed' : ''"
          class="px-3 py-1 border rounded-md hover:bg-gray-50"
        >
          Next
        </button>
      </div>
    </div>

    <!-- Item View Modal -->
    <div v-if="showItemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium">Item Details</h3>
          <button @click="showItemModal = false" class="text-gray-400 hover:text-gray-500">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <img :src="currentItem.image" :alt="currentItem.title" class="w-full h-64 object-cover rounded-lg" />
          </div>
          <div class="space-y-4">
            <div>
              <h4 class="text-xl font-semibold">{{ currentItem.title }}</h4>
              <div class="flex space-x-2 mt-1">
                <span class="px-2 py-1 text-xs rounded-full" 
                  :class="currentItem.type === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'">
                  {{ currentItem.type }}
                </span>
                <span class="px-2 py-1 text-xs rounded-full" :class="getStatusClass(currentItem.status)">
                  {{ currentItem.status }}
                </span>
              </div>
            </div>
            
            <p class="text-gray-700">{{ currentItem.description }}</p>
            
            <div>
              <h5 class="text-sm font-medium text-gray-700">Location</h5>
              <p>{{ currentItem.location }}</p>
            </div>
            
            <div>
              <h5 class="text-sm font-medium text-gray-700">Posted By</h5>
              <p>{{ currentItem.user?.name }} ({{ currentItem.user?.email }})</p>
            </div>
            
            <div>
              <h5 class="text-sm font-medium text-gray-700">Date Posted</h5>
              <p>{{ formatDate(currentItem.created_at) }}</p>
            </div>
            
            <div v-if="currentItem.status === 'reported'">
              <h5 class="text-sm font-medium text-gray-700 text-red-600">Report Reason</h5>
              <p class="text-red-600">{{ currentItem.report_reason }}</p>
            </div>
          </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
          <button @click="showItemModal = false" class="px-4 py-2 border rounded-md hover:bg-gray-50">
            Close
          </button>
          <button v-if="currentItem.status === 'reported'" @click="approveItem(currentItem)" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
            Approve Item
          </button>
          <button v-if="currentItem.status === 'active'" @click="confirmArchiveItem(currentItem)" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            Archive
          </button>
          <button v-if="currentItem.status !== 'deleted'" @click="confirmDeleteItem(currentItem)" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
            Delete
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

// Mock items data - replace with API calls in production
const items = ref([
  {
    id: 1,
    title: 'MacBook Pro 16"',
    description: 'Silver MacBook Pro with stickers on the lid. Last seen in the library.',
    type: 'lost',
    status: 'active',
    location: 'University Library, 3rd Floor',
    image: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
    user: { id: 2, name: 'Jane Smith', email: 'jane@example.com' },
    created_at: '2025-03-15T10:00:00'
  },
  {
    id: 2,
    title: 'iPhone 14 Pro',
    description: 'Black iPhone with red case. Found near the cafeteria.',
    type: 'found',
    status: 'active',
    location: 'Student Center Cafeteria',
    image: 'https://images.unsplash.com/photo-1592286927505-1def25115611?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
    user: { id: 1, name: 'John Doe', email: 'john@example.com' },
    created_at: '2025-03-16T14:30:00'
  },
  {
    id: 3,
    title: 'Car Keys with Red Keychain',
    description: 'Set of car keys with a distinctive red keychain. Lost somewhere on campus.',
    type: 'lost',
    status: 'reported',
    location: 'Campus Parking Lot B',
    image: 'https://images.unsplash.com/photo-1581088387864-699a20fbb0fe?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
    user: { id: 3, name: 'Robert Johnson', email: 'robert@example.com' },
    created_at: '2025-03-17T09:15:00',
    report_reason: 'Suspicious listing - possible scam'
  },
  {
    id: 4,
    title: 'Blue Backpack',
    description: 'Blue North Face backpack with textbooks inside.',
    type: 'found',
    status: 'archived',
    location: 'Science Building, Room 302',
    image: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
    user: { id: 4, name: 'Emily Williams', email: 'emily@example.com' },
    created_at: '2025-03-10T16:45:00'
  }
]);

// Pagination
const itemsPerPage = 10;
const currentPage = ref(1);
const searchQuery = ref('');
const typeFilter = ref('all');
const statusFilter = ref('all');

// Filtered items
const filteredItems = computed(() => {
  return items.value.filter(item => {
    // Apply search filter
    const searchLower = searchQuery.value.toLowerCase();
    const matchesSearch = searchQuery.value === '' || 
      item.title.toLowerCase().includes(searchLower) ||
      item.description.toLowerCase().includes(searchLower) ||
      item.location.toLowerCase().includes(searchLower);
    
    // Apply type filter
    const matchesType = typeFilter.value === 'all' || item.type === typeFilter.value;
    
    // Apply status filter
    const matchesStatus = statusFilter.value === 'all' || item.status === statusFilter.value;
    
    return matchesSearch && matchesType && matchesStatus;
  });
});

// Computed values for pagination
const totalItems = computed(() => filteredItems.value.length);
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage);
const endIndex = computed(() => Math.min(startIndex.value + itemsPerPage, totalItems.value));
const paginatedItems = computed(() => {
  return filteredItems.value.slice(startIndex.value, endIndex.value);
});

// Modal state
const showItemModal = ref(false);
const currentItem = ref({});
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

const filterItems = () => {
  currentPage.value = 1; // Reset to first page on filter change
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (endIndex.value < totalItems.value) {
    currentPage.value++;
  }
};

const viewItem = (item) => {
  currentItem.value = { ...item };
  showItemModal.value = true;
};

const reviewItem = (item) => {
  currentItem.value = { ...item };
  showItemModal.value = true;
};

const approveItem = (item) => {
  // In a real app, you would call an API to approve the item
  const index = items.value.findIndex(i => i.id === item.id);
  if (index !== -1) {
    items.value[index] = { ...item, status: 'active' };
    currentItem.value = { ...items.value[index] };
  }
};

const confirmArchiveItem = (item) => {
  confirmTitle.value = 'Archive Item';
  confirmMessage.value = `Are you sure you want to archive "${item.title}"? It will no longer be visible to users but can be restored later.`;
  confirmButtonText.value = 'Archive';
  confirmButtonClass.value = 'px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700';
  confirmCallback.value = () => archiveItem(item);
  showConfirmModal.value = true;
  showItemModal.value = false;
};

const confirmDeleteItem = (item) => {
  confirmTitle.value = 'Delete Item';
  confirmMessage.value = `Are you sure you want to delete "${item.title}"? This action cannot be undone.`;
  confirmButtonText.value = 'Delete';
  confirmButtonClass.value = 'px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700';
  confirmCallback.value = () => deleteItem(item);
  showConfirmModal.value = true;
  showItemModal.value = false;
};

const confirmAction = () => {
  if (confirmCallback.value) {
    confirmCallback.value();
  }
  showConfirmModal.value = false;
};

const archiveItem = (item) => {
  // In a real app, you would call an API to archive the item
  const index = items.value.findIndex(i => i.id === item.id);
  if (index !== -1) {
    items.value[index] = { ...item, status: 'archived' };
  }
};

const deleteItem = (item) => {
  // In a real app, you would call an API to delete the item
  const index = items.value.findIndex(i => i.id === item.id);
  if (index !== -1) {
    items.value[index] = { ...item, status: 'deleted' };
  }
};

const getStatusClass = (status) => {
  const classes = {
    'active': 'bg-green-100 text-green-800',
    'archived': 'bg-gray-100 text-gray-800',
    'reported': 'bg-yellow-100 text-yellow-800',
    'deleted': 'bg-red-100 text-red-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

// Load items on component mount
onMounted(() => {
  // In a real app, you would fetch items from an API
  // fetchItems();
});
</script>
