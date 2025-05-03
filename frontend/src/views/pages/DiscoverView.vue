<!-- DiscoverPage.vue -->
<template>
    <div v-if="loading" class="min-h-screen flex items-center justify-center">
        <div class="animate-spin h-10 w-10 border-4 border-purple-600 border-t-transparent rounded-full"></div>
    </div>
    <div v-else class="min-h-screen flex flex-col">
        <main class="relative max-w-[90rem] mx-auto flex-1 container py-6 flex flex-col-reverse md:flex-row items-center">
            <div class="flex flex-col gap-6">
                <div class="flex flex-col gap-2">
                    <h1 class="text-3xl font-bold tracking-tight">Discover Items</h1>
                    <p class="text-muted-foreground">Browse lost and found items in your area</p>
                </div>

                <!-- Search Bar -->
                <div class="flex items-center justify-between">
                    <input 
                        v-model="searchQuery" 
                        @input="handleSearch" 
                        @keyup.enter="handleSearch" 
                        type="text" 
                        placeholder="Search by title or description" 
                        class="border rounded px-2 py-1 w-full"
                    />
                </div>

                <!-- Filter Sidebar -->
                <div class="hidden w-64 shrink-0 md:block">
                    <div class="flex flex-col gap-6">
                        <div>
                            <h3 class="mb-2 font-medium">Category</h3>
                            <select 
                                v-model="categoryFilter" 
                                @change="handleFilterChange" 
                                class="border rounded px-2 py-1 w-full"
                            >
                                <option value="all">All Categories</option>
                                <!-- Add category options here -->
                            </select>
                        </div>
                        <div>
                            <h3 class="mb-2 font-medium">Type</h3>
                            <select 
                                v-model="typeFilter" 
                                @change="handleFilterChange" 
                                class="border rounded px-2 py-1 w-full"
                            >
                                <option value="all">All Types</option>
                                <option value="lost">Lost</option>
                                <option value="found">Found</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="flex-1">
                    <div v-if="filteredItems.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <ItemCard v-for="item in paginatedItems" :key="item.id" :item="item" />
                    </div>
                    <div v-else class="flex flex-col items-center justify-center py-12 text-center w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-muted-foreground mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="text-lg font-medium">No items found</h3>
                        <p class="text-muted-foreground mt-1">Try adjusting your filters or check back later.</p>
                    </div>
                    <div v-if="filteredItems.length > 0" class="flex justify-center mt-6">
                        <button v-if="currentPage < Math.ceil(filteredItems.length / itemsPerPage)" @click="handlePageChange(currentPage + 1)" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white shadow transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            Show More Items
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { toast } from 'vue3-toastify';
import { getItems } from '@/services/api/item';
import ItemCard from '@/components/ItemCard.vue';

const loading = ref(false);
const items = ref([]);
const searchQuery = ref('');
const categoryFilter = ref('all');
const typeFilter = ref('all');
const currentPage = ref(1);
const itemsPerPage = ref(12);

// Computed filters - with safety checks for undefined items
const filteredItems = computed(() => {
  if (!items.value || !Array.isArray(items.value)) {
    return [];
  }
  
  return items.value.filter(item => {
    if (!item) return false;
    
    const matchesSearch = (
      (item.title && item.title.toLowerCase().includes(searchQuery.value.toLowerCase())) || 
      (item.description && item.description.toLowerCase().includes(searchQuery.value.toLowerCase()))
    );
    
    const matchesCategory = categoryFilter.value === 'all' || 
      (item.category === categoryFilter.value);
    
    const matchesType = typeFilter.value === 'all' || 
      (item.type === typeFilter.value);
    
    return matchesSearch && matchesCategory && matchesType;
  });
});

// Computed values for pagination with safe array access
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage.value);
const endIndex = computed(() => {
  if (!items.value || !Array.isArray(items.value)) {
    return 0;
  }
  return Math.min(startIndex.value + itemsPerPage.value, items.value.length);
});

const paginatedItems = computed(() => {
  if (!items.value || !Array.isArray(items.value)) {
    return [];
  }
  return items.value.slice(startIndex.value, endIndex.value);
});

// Load items
async function loadItems() {
  try {
    loading.value = true;
    const response = await getItems({
      page: currentPage.value,
      per_page: itemsPerPage.value,
      search: searchQuery.value,
      category: categoryFilter.value === 'all' ? null : categoryFilter.value,
      type: typeFilter.value === 'all' ? null : typeFilter.value
    });
    
    // Handle different possible response structures safely
    if (response && response.data) {
      items.value = Array.isArray(response.data) ? response.data : (response.data.data || []);
    } else {
      items.value = [];
    }
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to load items');
    console.error('Failed to load items:', err);
    items.value = []; // Ensure items is always an array even on error
  } finally {
    loading.value = false;
  }
}

// Handle pagination
function handlePageChange(page) {
  currentPage.value = page;
  loadItems();
}

// Handle search
function handleSearch() {
  currentPage.value = 1;
  loadItems();
}

// Handle filter changes
function handleFilterChange() {
  currentPage.value = 1;
  loadItems();
}

// Load items on mount
onMounted(() => {
  loadItems();
});
</script>
