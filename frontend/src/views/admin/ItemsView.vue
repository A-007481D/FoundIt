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

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
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

    <div class="flex justify-between items-center mt-4">
      <div class="text-sm text-gray-700">
        Showing <span class="font-medium">{{ startIndex + 1 }}</span> to <span class="font-medium">{{ endIndex }}</span> of <span class="font-medium">{{ items.length }}</span> items
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
          :disabled="endIndex >= items.length" 
          :class="endIndex >= items.length ? 'opacity-50 cursor-not-allowed' : ''"
          class="px-3 py-1 border rounded-md hover:bg-gray-50"
        >
          Next
        </button>
      </div>
    </div>

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
          <button @click="confirmAction" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
            {{ confirmButtonText }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AdminItemService from '@/services/api/admin/item';
import { toast } from 'vue3-toastify';

const loading = ref(false);
const items = ref([]);
const searchQuery = ref('');
const typeFilter = ref('all');
const statusFilter = ref('all');
const visibilityFilter = ref('all');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const showConfirmModal = ref(false);
const selectedItem = ref(null);
const confirmTitle = ref('');
const confirmMessage = ref('');
const confirmButtonText = ref('');

// Computed values for pagination
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage.value);
const endIndex = computed(() => Math.min(startIndex.value + itemsPerPage.value, items.value.length));
const paginatedItems = computed(() => items.value.slice(startIndex.value, endIndex.value));

// Methods
async function fetchItems() {
  loading.value = true;
  try {
    const response = await AdminItemService.getItems({
      search: searchQuery.value,
      type: typeFilter.value === 'all' ? '' : typeFilter.value,
      status: statusFilter.value === 'all' ? '' : statusFilter.value,
      visibility: visibilityFilter.value === 'all' ? '' : visibilityFilter.value,
      page: currentPage.value,
      per_page: itemsPerPage.value
    });
    items.value = response.data.data;
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to fetch items');
    console.error('Failed to fetch items:', err);
  } finally {
    loading.value = false;
  }
}

function handleSearch() {
  currentPage.value = 1;
  fetchItems();
}

function filterItems() {
  currentPage.value = 1;
  fetchItems();
}

function prevPage() {
  if (currentPage.value > 1) {
    currentPage.value--;
    fetchItems();
  }
}

function nextPage() {
  currentPage.value++;
  fetchItems();
}

function viewItem(item) {
  // Implement view item functionality
}

function reviewItem(item) {
  // Implement review item functionality
}

async function makeItemVisible(item) {
  try {
    await AdminItemService.updateItemVisibility(item.id, true);
    toast.success('Item made visible');
    fetchItems();
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to make item visible');
    console.error('Failed to make item visible:', err);
  }
}

async function makeItemHidden(item) {
  try {
    await AdminItemService.updateItemVisibility(item.id, false);
    toast.success('Item hidden');
    fetchItems();
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to hide item');
    console.error('Failed to hide item:', err);
  }
}

function confirmArchiveItem(item) {
  selectedItem.value = item;
  confirmTitle.value = 'Archive Item';
  confirmMessage.value = 'Are you sure you want to archive this item? This action cannot be undone.';
  confirmButtonText.value = 'Archive';
  showConfirmModal.value = true;
}

function confirmDeleteItem(item) {
  selectedItem.value = item;
  confirmTitle.value = 'Delete Item';
  confirmMessage.value = 'Are you sure you want to delete this item? This action cannot be undone.';
  confirmButtonText.value = 'Delete';
  showConfirmModal.value = true;
}

async function confirmAction() {
  try {
    if (confirmTitle.value === 'Archive Item') {
      await AdminItemService.archiveItem(selectedItem.value.id);
      toast.success('Item archived successfully');
    } else if (confirmTitle.value === 'Delete Item') {
      await AdminItemService.deleteItem(selectedItem.value.id);
      toast.success('Item deleted successfully');
    }
    showConfirmModal.value = false;
    fetchItems();
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to perform action');
    console.error('Failed to perform action:', err);
  }
}

function getStatusClass(status) {
  switch (status) {
    case 'active':
      return 'bg-green-100 text-green-800';
    case 'archived':
      return 'bg-gray-100 text-gray-800';
    case 'reported':
      return 'bg-yellow-100 text-yellow-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString();
}

// Load items on component mount
onMounted(() => {
  fetchItems();
});
</script>
