<template>
  <div class="min-h-screen flex flex-col">
    <main class="max-w-[90rem] mx-auto flex-1 container py-6">
      <!-- Loading state -->
      <div v-if="loading" class="flex items-center justify-center h-64">
        <div class="animate-spin h-10 w-10 border-4 border-primary border-t-transparent rounded-full"></div>
      </div>
      
      <!-- Content -->
      <div v-else class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
          <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold tracking-tight">My Items</h1>
            <button 
              @click="showCreateForm = true" 
              class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white shadow transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              New Listing
            </button>
          </div>
          <p class="text-muted-foreground">Manage your lost and found items</p>
        </div>
        
        <!-- Search and filters -->
        <div class="flex flex-col gap-2">
          <input 
            v-model="searchQuery" 
            type="search" 
            class="block w-full rounded-md border border-gray-300 bg-white py-2 pl-10 text-sm text-gray-700 placeholder-gray-400 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary" 
            placeholder="Search items..."
          />
          <div class="flex gap-2">
            <select 
              v-model="typeFilter" 
              class="block w-full rounded-md border border-gray-300 bg-white py-2 pl-10 text-sm text-gray-700 placeholder-gray-400 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
            >
              <option value="all">All types</option>
              <option value="lost">Lost</option>
              <option value="found">Found</option>
            </select>
            <select 
              v-model="statusFilter" 
              class="block w-full rounded-md border border-gray-300 bg-white py-2 pl-10 text-sm text-gray-700 placeholder-gray-400 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
            >
              <option value="all">All statuses</option>
              <option value="active">Active</option>
              <option value="archived">Archived</option>
            </select>
          </div>
        </div>
        
        <!-- No items state -->
        <div v-if="filteredItems.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-muted-foreground mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
          </svg>
          <h3 class="text-lg font-medium">No items found</h3>
          <p class="text-muted-foreground mt-1">
            {{ 
              typeFilter === 'all' && statusFilter === 'all' 
                ? "You haven't created any items yet." 
                : "No items match your filters."
            }}
          </p>
          <button 
            @click="typeFilter = 'all'; statusFilter = 'all'" 
            class="mt-4 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
          >
            Clear filters
          </button>
        </div>
        
        <!-- Items grid -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <ItemCard 
            v-for="item in filteredItems" 
            :key="item.id" 
            :item="item" 
            :showStatus="true"
            @click="openItemDetail(item.id)" 
          />
        </div>
        
        <!-- Pagination -->
        <div class="flex justify-center gap-2">
          <button 
            @click="prevPage" 
            class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
          >
            Prev
          </button>
          <span class="text-sm font-medium text-gray-700">{{ currentPage }} of {{ Math.ceil(items.length / itemsPerPage) }}</span>
          <button 
            @click="nextPage" 
            class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
          >
            Next
          </button>
        </div>
      </div>
    </main>
    
    <!-- Item Detail Modal -->
    <div v-if="showItemDetail" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
      <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <ItemDetail 
          :itemId="selectedItemId" 
          @close="closeItemDetail" 
          @edit="editItem" 
          @update="refreshItems"
        />
      </div>
    </div>
    
    <!-- Create/Edit Item Modal -->
    <div v-if="showCreateForm || showEditForm" class="fixed inset-0 flex items-center justify-center p-6 bg-black bg-opacity-50 z-50">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[85vh] overflow-auto p-6">
        <ItemForm 
          :item="editingItem" 
          :isEdit="showEditForm" 
          @submit="handleFormSubmit" 
          @cancel="closeForm"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { toast } from 'vue3-toastify';
import { getMyItems, createItem, updateItem, deleteItem, updateItemStatus } from '@/services/api/item';
import { useAuthStore } from '@/stores/auth.store';
import ItemCard from '@/components/ItemCard.vue';
import ItemDetail from '@/components/ItemDetail.vue';
import ItemForm from '@/components/ItemForm.vue';

const authStore = useAuthStore();
const loading = ref(false);
const items = ref([]);
const searchQuery = ref('');
const typeFilter = ref('all');
const statusFilter = ref('all');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const showItemDetail = ref(false);
const selectedItemId = ref(null);
const showCreateForm = ref(false);
const showEditForm = ref(false);
const editingItem = ref(null);

// Computed values for pagination
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage.value);
const endIndex = computed(() => {
  if (!items.value || !Array.isArray(items.value)) return 0;
  return Math.min(startIndex.value + itemsPerPage.value, items.value.length);
});
const paginatedItems = computed(() => {
  if (!items.value || !Array.isArray(items.value)) return [];
  return items.value.slice(startIndex.value, endIndex.value);
});

// Computed values for filters
const filteredItems = computed(() => {
  if (!items.value || !Array.isArray(items.value)) return [];
  
  return items.value.filter(item => {
    if (!item) return false;
    
    const matchesSearch = 
      (item.title && item.title.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
      (item.description && item.description.toLowerCase().includes(searchQuery.value.toLowerCase()));
    const matchesType = typeFilter.value === 'all' || item.type === typeFilter.value;
    const matchesStatus = statusFilter.value === 'all' || item.status === statusFilter.value;
    return matchesSearch && matchesType && matchesStatus;
  });
});

// Methods
async function fetchItems() {
  loading.value = true;
  try {
    const response = await getMyItems({
      search: searchQuery.value,
      type: typeFilter.value === 'all' ? '' : typeFilter.value,
      status: statusFilter.value === 'all' ? '' : statusFilter.value,
      page: currentPage.value,
      per_page: itemsPerPage.value
    });
    
    // Safely handle response data
    if (response && response.data) {
      items.value = Array.isArray(response.data) ? response.data : (response.data.data || []);
    } else {
      items.value = [];
    }
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to fetch items');
    console.error('Failed to fetch items:', err);
    items.value = []; // Ensure items is always an array even on error
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

async function handleItemDelete(item) {
  if (!confirm('Are you sure you want to delete this item?')) {
    return;
  }

  try {
    await deleteItem(item.id);
    items.value = items.value.filter(i => i.id !== item.id);
    toast.success('Item deleted successfully');
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to delete item');
    console.error('Failed to delete item:', err);
  }
}

async function handleUpdateStatus(item, status) {
  try {
    await updateItemStatus(item.id, status);
    item.status = status;
    toast.success('Item status updated successfully');
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to update item status');
    console.error('Failed to update item status:', err);
  }
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleString();
}

function getStatusClass(status) {
  const classes = {
    'lost': 'bg-red-100 text-red-800',
    'found': 'bg-green-100 text-green-800',
    'resolved': 'bg-blue-100 text-blue-800',
    'pending': 'bg-yellow-100 text-yellow-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
}

function openItemDetail(itemId) {
  selectedItemId.value = itemId;
  showItemDetail.value = true;
}

function closeItemDetail() {
  showItemDetail.value = false;
  selectedItemId.value = null;
}

function editItem(item) {
  editingItem.value = item;
  showEditForm.value = true;
  showItemDetail.value = false;
}

function closeForm() {
  showCreateForm.value = false;
  showEditForm.value = false;
  editingItem.value = null;
}

async function handleFormSubmit(item) {
  await refreshItems();
  closeForm();
}

function refreshItems() {
  fetchItems();
}

// Load items on component mount
onMounted(() => {
  fetchItems();
});
</script>
