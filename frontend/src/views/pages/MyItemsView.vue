<template>
  <div class="min-h-screen flex flex-col">
    <main class="max-w-[90rem] mx-auto flex-1 container py-6">
      <!-- Loading state -->
      <div v-if="isLoading" class="flex items-center justify-center h-64">
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
        
        <!-- Tabs -->
        <div class="w-full">
          <div class="inline-flex h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground">
            <button
              v-for="tab in tabs"
              :key="tab.value"
              @click="activeTab = tab.value"
              :class="[
                'inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50',
                activeTab === tab.value ? 'bg-background text-foreground shadow-sm' : ''
              ]"
            >
              {{ tab.label }}
            </button>
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
              activeTab === 'all' 
                ? "You haven't created any items yet." 
                : activeTab === 'active' 
                  ? "You don't have any active items." 
                  : "You don't have any archived items."
            }}
          </p>
          <button 
            v-if="activeTab !== 'all'" 
            @click="activeTab = 'all'" 
            class="mt-4 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
          >
            View all items
          </button>
          <button 
            v-else
            @click="showCreateForm = true" 
            class="mt-4 inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white shadow transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create New Item
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
import { useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth.store';
import ItemCard from '@/components/ItemCard.vue';
import ItemDetail from '@/components/ItemDetail.vue';
import ItemForm from '@/components/ItemForm.vue';
import itemService from '@/services/item.service';

// Auth store
const authStore = useAuthStore();

// State
const items = ref([]);
const isLoading = ref(true);
const activeTab = ref('all');
const showItemDetail = ref(false);
const selectedItemId = ref(null);
const showCreateForm = ref(false);
const showEditForm = ref(false);
const editingItem = ref(null);

// Tabs
const tabs = [
  { value: 'all', label: 'All Items' },
  { value: 'active', label: 'Active Items' },
  { value: 'archived', label: 'Archived Items' }
];

// Computed
const filteredItems = computed(() => {
  if (activeTab.value === 'all') {
    return items.value;
  } else if (activeTab.value === 'active') {
    return items.value.filter(item => item.status === 'active');
  } else if (activeTab.value === 'archived') {
    return items.value.filter(item => item.status === 'archived');
  }
  return items.value;
});

const route = useRoute();

// Lifecycle
onMounted(async () => {
  try {
    await fetchUserItems();
    const editId = route.query.editItemId;
    if (editId) {
      const itemToEdit = items.value.find(item => item.id.toString() === editId);
      if (itemToEdit) editItem(itemToEdit);
    }
  } catch (error) {
    console.error('Error in mounted hook:', error);
    isLoading.value = false;
  }
});

// Methods
async function fetchUserItems() {
  isLoading.value = true;
  try {
    const response = await itemService.getUserItems();
    items.value = response.data.data || response.data.items || [];
  } catch (error) {
    console.error('Error fetching user items:', error);
    items.value = [];
  } finally {
    isLoading.value = false;
  }
}

function refreshItems() {
  fetchUserItems();
}

const openItemDetail = (itemId) => {
  selectedItemId.value = itemId;
  showItemDetail.value = true;
};

const closeItemDetail = () => {
  showItemDetail.value = false;
  selectedItemId.value = null;
};

const editItem = (item) => {
  editingItem.value = item;
  showEditForm.value = true;
  showItemDetail.value = false;
};

const closeForm = () => {
  showCreateForm.value = false;
  showEditForm.value = false;
  editingItem.value = null;
};

const handleFormSubmit = async (item) => {
  await refreshItems();
  closeForm();
};
</script>
