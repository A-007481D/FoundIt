<template>
  <div class="bg-white rounded-lg shadow max-w-lg mx-auto">
    <div class="border-b p-3">
      <div class="flex items-center">
        <button @click="$emit('cancel')" class="mr-2 text-gray-500 hover:text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
          </svg>
        </button>
        <h2 class="text-xl font-medium text-gray-800">
          {{ isEdit ? 'Edit Item' : 'New Item' }}
        </h2>
      </div>
      <p class="text-gray-500 mt-1 text-sm">
        Report a lost or found item to share with the community.
      </p>
    </div>
    
    <div class="p-3">
      <form @submit.prevent="submitForm" class="space-y-3">
        <!-- Item Type Selection -->
        <div>
          <p class="font-medium text-gray-700 mb-1">Item Type</p>
          <div class="flex gap-4">
            <div class="flex items-center">
              <input 
                type="radio" 
                id="type-lost" 
                v-model="form.type" 
                value="lost" 
                class="h-4 w-4 accent-indigo-600"
                required
              >
              <label for="type-lost" class="ml-2 text-gray-700">Lost Item</label>
            </div>
            <div class="flex items-center">
              <input 
                type="radio" 
                id="type-found" 
                v-model="form.type" 
                value="found" 
                class="h-4 w-4 accent-indigo-600"
                required
              >
              <label for="type-found" class="ml-2 text-gray-700">Found Item</label>
            </div>
          </div>
        </div>
        
        <!-- Title -->
        <div>
          <label for="title" class="block font-medium text-gray-700 mb-1">Item Title</label>
          <input 
            type="text" 
            id="title" 
            v-model="form.title" 
            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-indigo-500"
            placeholder="E.g., Black USB-C Charger"
            required
          >
        </div>
        
        <!-- Category -->
        <div>
          <div class="flex items-center mb-1">
            <label for="category" class="block font-medium text-gray-700">Category</label>
            <div class="ml-1 text-gray-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
          <div class="relative">
            <select 
              id="category" 
              v-model="form.category" 
              class="block bg-white w-full px-3 py-2 pr-8 border border-gray-300 rounded appearance-none focus:outline-none focus:ring-1 focus:ring-primary"
              required
            >
              <option value="" disabled selected>Select a category</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name || category.label }}
              </option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
              <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </div>
          </div>
        </div>
        
        <!-- Location -->
        <div>
          <label for="location" class="block font-medium text-gray-700 mb-1">Location</label>
          <input 
            type="text" 
            id="location" 
            v-model="form.location" 
            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-indigo-500"
            placeholder="E.g., Library"
            required
          >
        </div>
        
        <!-- Date -->
        <div>
          <label for="date" class="block font-medium text-gray-700 mb-1">Date</label>
          <input 
            type="date" 
            id="date" 
            v-model="form.date" 
            class="appearance-none bg-white w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-primary"
            required
          >
          <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
        </div>
        
        <!-- Description -->
        <div>
          <label for="description" class="block font-medium text-gray-700 mb-1">Detailed Description</label>
          <textarea 
            id="description" 
            v-model="form.description" 
            rows="3" 
            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-indigo-500"
            placeholder="Describe the item with as many details as possible..."
            required
          ></textarea>
        </div>
        
        <!-- Submit Buttons -->
        <div class="flex justify-between pt-2 border-t mt-3">
          <button 
            type="button" 
            @click="$emit('cancel')" 
            class="px-4 py-2 border border-gray-300 rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-indigo-500"
          >
            Cancel
          </button>
          <button 
            type="submit" 
            :disabled="loading"
            class="px-4 py-2 border border-transparent rounded text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-1 focus:ring-primary disabled:opacity-50"
          >
            <span v-if="loading">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Loading...
            </span>
            <span v-else>{{ isEdit ? 'Update' : 'Post Item' }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { createItem, updateItem, getCategories } from '@services/api/item';

const props = defineProps({
  item: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['cancel', 'success']);

const isEdit = computed(() => !!props.item);
const form = ref({
  title: '',
  description: '',
  category: '',
  type: 'lost',
  location: '',
  date: '',
  status: 'active',
  visible: true
});

const categories = ref([]);
const loading = ref(false);
const error = ref(null);

// Load categories on mount
onMounted(async () => {
  try {
    const response = await getCategories();
    categories.value = response.data;
    
    // If editing, set initial form values
    if (props.item) {
      Object.assign(form.value, {
        title: props.item.title,
        description: props.item.description,
        category: props.item.category,
        type: props.item.type,
        location: props.item.location,
        date: props.item.date,
        status: props.item.status,
        visible: props.item.visible
      });
    }
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load categories';
  }
});

// Watch for type changes to update date field
watch(() => form.value.type, (newType) => {
  if (newType === 'lost') {
    form.value.date = new Date().toISOString().split('T')[0];
  } else {
    form.value.date = '';
  }
});

// Form submission
async function submitForm() {
  loading.value = true;
  error.value = null;
  
  try {
    const formData = {
      ...form.value,
      date: new Date(form.value.date).toISOString()
    };
    
    let response;
    if (isEdit.value) {
      response = await updateItem(props.item.id, formData);
    } else {
      response = await createItem(formData);
    }
    
    emit('success', response.data);
  } catch (err) {
    error.value = err.response?.data?.message || (isEdit.value ? 'Failed to update item' : 'Failed to create item');
  } finally {
    loading.value = false;
  }
}

// Cancel form
function cancelForm() {
  emit('cancel');
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity .3s }
.fade-enter-from, .fade-leave-to { opacity: 0 }
/* Hide native calendar and time picker icons */
input[type="date"]::-webkit-calendar-picker-indicator,
input[type="time"]::-webkit-calendar-picker-indicator {
  display: none;
}
</style>