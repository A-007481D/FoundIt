<template>
  <div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 md:mb-6 gap-4">
      <h1 class="text-xl md:text-2xl font-bold">Item Management</h1>
      <div class="flex flex-wrap gap-2 w-full sm:w-auto">
        <div class="relative flex-grow sm:flex-grow-0">
          <input 
            type="text" 
            v-model="searchQuery" 
            placeholder="Search items..." 
            class="w-full sm:w-auto pl-10 pr-4 py-2 border rounded-md focus:ring-2 focus:ring-primary focus:border-primary"
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
        <select 
          v-model="visibilityFilter" 
          class="border rounded-md px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary"
          @change="filterItems"
        >
          <option value="all">All Visibility</option>
          <option value="visible">Visible</option>
          <option value="hidden">Hidden</option>
        </select>
      </div>
    </div>

    <div v-if="loading" class="flex justify-center items-center py-8">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
    </div>

    <!-- Desktop Table View - Hidden on small screens -->
    <div v-if="!loading" class="bg-white rounded-lg shadow overflow-hidden hidden md:block">
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
          <tr v-for="item in items" :key="item.id" class="hover:bg-gray-50">
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
              <div class="space-y-1">
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                :class="getStatusClass(item.status)">
                {{ item.status }}
              </span>
              <span v-if="item.visible" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                Visible
              </span>
              <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                Hidden
              </span>
            </div>
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
          <tr v-if="items.length === 0">
            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
              No items found matching your criteria.
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Mobile Card View - visible only on small screens -->
    <div v-if="!loading" class="space-y-4 md:hidden">
      <div v-if="items.length === 0" class="bg-white rounded-lg shadow p-4 text-center text-gray-500">
        No items found matching your criteria.
      </div>
      
      <div v-for="item in items" :key="item.id" class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4">
          <div class="flex items-start space-x-3">
            <img :src="item.image" :alt="item.title" class="h-16 w-16 rounded-md object-cover flex-shrink-0" />
            <div class="flex-1 min-w-0">
              <h3 class="text-sm font-medium text-gray-900 truncate">{{ item.title }}</h3>
              <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ item.description }}</p>
              
              <div class="flex flex-wrap gap-1 mt-2">
                <span class="px-2 py-1 text-xs font-semibold rounded-full" 
                  :class="item.type === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'">
                  {{ item.type }}
                </span>
                <span class="px-2 py-1 text-xs font-semibold rounded-full" 
                  :class="getStatusClass(item.status)">
                  {{ item.status }}
                </span>
                <span v-if="item.visible" class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                  Visible
                </span>
                <span v-else class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                  Hidden
                </span>
              </div>
            </div>
          </div>
          
          <div class="mt-3 pt-3 border-t border-gray-100 text-xs">
            <div class="flex justify-between items-center">
              <div>
                <p class="text-gray-900">{{ item.user.name }}</p>
                <p class="text-gray-500 mt-0.5">{{ formatDate(item.created_at) }}</p>
              </div>
            </div>
          </div>
          
          <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap gap-2">
            <button @click="viewItem(item)" class="px-2 py-1 text-xs bg-indigo-100 text-indigo-800 rounded hover:bg-indigo-200">
              View
            </button>
            <button v-if="item.status === 'reported'" @click="reviewItem(item)" class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200">
              Review
            </button>
            <button v-if="item.status === 'active'" @click="confirmArchiveItem(item)" class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded hover:bg-gray-200">
              Archive
            </button>
            <button v-if="item.status !== 'deleted'" @click="confirmDeleteItem(item)" class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200">
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 gap-3">
      <div class="text-xs md:text-sm text-gray-700 order-2 sm:order-1">
        Showing <span class="font-medium">{{ startIndex + 1 }}</span> to <span class="font-medium">{{ endIndex }}</span> of <span class="font-medium">{{ totalItems }}</span> items
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
          :disabled="endIndex >= totalItems" 
          :class="endIndex >= totalItems ? 'opacity-50 cursor-not-allowed' : ''"
          class="px-3 py-1 border rounded-md hover:bg-gray-50 text-sm"
        >
          Next
        </button>
      </div>
    </div>

    <!-- Item Modal (View/Review) -->
    <div v-if="showItemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto p-4 md:p-6 m-4 md:m-0">
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
              <div class="flex flex-wrap gap-2 mt-1">
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
        
        <div class="mt-6 flex flex-wrap justify-end gap-3">
          <button @click="showItemModal = false" class="px-4 py-2 border rounded-md hover:bg-gray-50">
            Close
          </button>
          <div class="flex flex-wrap gap-2">
            <button 
              v-if="currentItem.status === 'reported' && !currentItem.visible" 
              @click="makeItemVisible(currentItem)" 
              class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md">
              Make Item Visible
            </button>
            <button 
              v-if="currentItem.status === 'reported' && currentItem.visible" 
              @click="makeItemHidden(currentItem)" 
              class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-md">
              Hide Item
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
    </div>

    <!-- Confirmation Modal -->
    <div v-if="showConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-4 md:p-6 m-4 md:m-0">
        <div class="mb-4">
          <h3 class="text-lg font-medium">{{ confirmTitle }}</h3>
          <p class="mt-2 text-sm text-gray-500">{{ confirmMessage }}</p>
        </div>
        <div class="flex justify-end gap-3">
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
import adminItemService from '@/services/admin.item.service';
import { toast } from 'vue3-toastify';

const items = ref([]);

const loading = ref(false);

// Pagination
const itemsPerPage = 10;
const currentPage = ref(1);
const totalItems = ref(0);

// Filters
const searchQuery = ref('');
const typeFilter = ref('all');
const statusFilter = ref('all');
const visibilityFilter = ref('all');

// Fetch items from API
const fetchItems = async () => {
  try {
    loading.value = true;
    const response = await adminItemService.getItems({
      search: searchQuery.value,
      type: typeFilter.value,
      status: statusFilter.value,
      visibility: visibilityFilter.value,
      page: currentPage.value,
      per_page: itemsPerPage
    });
    
    console.log('API Response:', response.data);
    
    // Handle the data based on the API response structure
    if (response.data.data) {
      // Laravel pagination response structure
      items.value = response.data.data || [];
      totalItems.value = response.data.total || 0;
      currentPage.value = response.data.current_page || 1;
    } else {
      // Simple array response
      items.value = response.data || [];
      totalItems.value = response.data.length || 0;
      currentPage.value = 1;
    }
    
    loading.value = false;
  } catch (error) {
    console.error('Error fetching items:', error);
    toast.error('Failed to load items');
    loading.value = false;
    items.value = [];
  }
};

// We don't need this local filtering anymore as we're using server-side filtering

// Computed values for pagination
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage);
const endIndex = computed(() => Math.min(startIndex.value + itemsPerPage, totalItems.value));

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
  fetchItems();
};

const filterItems = () => {
  currentPage.value = 1; // Reset to first page on filter change
  fetchItems();
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    fetchItems();
  }
};

const nextPage = () => {
  if (endIndex.value < totalItems.value) {
    currentPage.value++;
    fetchItems();
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

const makeItemVisible = async (item) => {
  try {
    const response = await adminItemService.updateVisibility(item.id, true);
    toast.success('Item is now visible to users');
    
    // Update both visibility and status in the current view
    if (response.data && response.data.item) {
      currentItem.value = { ...response.data.item };
    } else {
      // If response doesn't include the updated item, update manually
      currentItem.value = { 
        ...currentItem.value, 
        visible: true, 
        status: 'active' // Status will change to active on the backend
      };
    }
    
    fetchItems();
  } catch (error) {
    toast.error('Failed to update item visibility: ' + (error.response?.data?.message || error.message));
  }
};

const makeItemHidden = async (item) => {
  try {
    await adminItemService.updateVisibility(item.id, false);
    toast.success('Item is now hidden from users');
    currentItem.value = { ...currentItem.value, visible: false };
    fetchItems();
  } catch (error) {
    toast.error('Failed to update item visibility: ' + (error.response?.data?.message || error.message));
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

const archiveItem = async (item) => {
  try {
    await adminItemService.archiveItem(item.id);
    toast.success('Item archived successfully');
    fetchItems();
  } catch (error) {
    toast.error('Failed to archive item: ' + (error.response?.data?.message || error.message));
  }
};

const deleteItem = async (item) => {
  try {
    await adminItemService.deleteItem(item.id);
    toast.success('Item deleted successfully');
    fetchItems();
  } catch (error) {
    toast.error('Failed to delete item: ' + (error.response?.data?.message || error.message));
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
  fetchItems();
});
</script>
