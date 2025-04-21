<template>
  <div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">{{ isEdit ? 'Edit Item' : 'Create New Item' }}</h2>
    
    <form @submit.prevent="submitForm" class="space-y-6">
      <!-- Item Type Selection -->
      <div v-if="!isEdit" class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Item Type</label>
        <div class="flex gap-4">
          <div class="flex items-center">
            <input 
              type="radio" 
              id="type-lost" 
              v-model="formData.type" 
              value="lost" 
              class="h-4 w-4 border-gray-300 text-primary focus:ring-primary"
              required
            >
            <label for="type-lost" class="ml-2 text-sm text-gray-700">Lost Item</label>
          </div>
          <div class="flex items-center">
            <input 
              type="radio" 
              id="type-found" 
              v-model="formData.type" 
              value="found" 
              class="h-4 w-4 border-gray-300 text-primary focus:ring-primary"
              required
            >
            <label for="type-found" class="ml-2 text-sm text-gray-700">Found Item</label>
          </div>
        </div>
      </div>
      
      <!-- Title -->
      <div>
        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
        <input 
          type="text" 
          id="title" 
          v-model="formData.title" 
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
          placeholder="e.g., iPhone 13 Pro - Space Gray"
          required
        >
      </div>
      
      <!-- Category -->
      <div>
        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
        <select 
          id="category" 
          v-model="formData.category_id" 
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
          required
        >
          <option value="" disabled>Select a category</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>
      </div>
      
      <!-- Description -->
      <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea 
          id="description" 
          v-model="formData.description" 
          rows="4" 
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
          placeholder="Provide a detailed description of the item..."
          required
        ></textarea>
      </div>
      
      <!-- Location -->
      <div>
        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
        <input 
          type="text" 
          id="location" 
          v-model="formData.location" 
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
          placeholder="e.g., Central Park, NY"
          required
        >
      </div>
      
      <!-- Date -->
      <div>
        <label :for="dateInputId" class="block text-sm font-medium text-gray-700">
          {{ dateInputLabel }}
        </label>
        <input 
          type="datetime-local" 
          :id="dateInputId" 
          v-model="selectedDate"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
          required
        >
      </div>
      
      <!-- Image Upload -->
      <div>
        <label for="image" class="block text-sm font-medium text-gray-700">Image (Optional)</label>
        <div class="mt-1 flex items-center space-x-4">
          <div v-if="imagePreview" class="relative h-32 w-32 overflow-hidden rounded-md border border-gray-300">
            <img :src="imagePreview" alt="Preview" class="h-full w-full object-cover">
            <button 
              type="button" 
              @click="removeImage" 
              class="absolute top-1 right-1 rounded-full bg-red-500 p-1 text-white hover:bg-red-600"
              title="Remove image"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div v-else class="flex h-32 w-32 items-center justify-center rounded-md border border-dashed border-gray-300 bg-gray-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          <div class="flex-1">
            <input 
              type="file" 
              id="image" 
              ref="imageInput"
              @change="handleImageChange" 
              accept="image/*"
              class="hidden"
            >
            <button 
              type="button" 
              @click="$refs.imageInput.click()" 
              class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
            >
              {{ imagePreview ? 'Change Image' : 'Upload Image' }}
            </button>
            <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
          </div>
        </div>
      </div>
      
      <!-- Submit Button -->
      <div class="flex justify-end space-x-3">
        <button 
          type="button" 
          @click="$emit('cancel')" 
          class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
        >
          Cancel
        </button>
        <button 
          type="submit" 
          :disabled="isSubmitting"
          class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-50"
        >
          <span v-if="isSubmitting">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Submitting...
          </span>
          <span v-else>{{ isEdit ? 'Update Item' : 'Create Item' }}</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import itemService from '@/services/item.service';
import categoryService from '@/services/category.service';

const props = defineProps({
  item: {
    type: Object,
    default: () => null
  },
  isEdit: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['submit', 'cancel']);

// Form data
const formData = ref({
  title: '',
  description: '',
  type: 'lost',
  category_id: '',
  location: '',
  lost_date: '',
  found_date: '',
  image: null
});

// Computed properties for date input
const dateInputLabel = computed(() => {
  return formData.value.type === 'lost' ? 'When did you lose it?' : 'When did you find it?';
});

const dateInputId = computed(() => {
  return formData.value.type === 'lost' ? 'lost_date' : 'found_date';
});

const selectedDate = computed({
  get() {
    return formData.value.type === 'lost' ? formData.value.lost_date : formData.value.found_date;
  },
  set(value) {
    if (formData.value.type === 'lost') {
      formData.value.lost_date = value;
    } else {
      formData.value.found_date = value;
    }
  }
});

// UI state
const categories = ref([]);
const isSubmitting = ref(false);
const imagePreview = ref(null);
const imageInput = ref(null);

// Initialize form data from props if editing
onMounted(async () => {
  try {
    // Load categories
    const response = await categoryService.getCategories();
    categories.value = response;
    
    // Initialize form data if editing
    if (props.isEdit && props.item) {
      formData.value = {
        title: props.item.title,
        description: props.item.description,
        type: props.item.type,
        category_id: props.item.category_id,
        location: props.item.location,
        lost_date: formatDateForInput(props.item.lost_date),
        found_date: formatDateForInput(props.item.found_date),
        image: null
      };
      
      if (props.item.image) {
        imagePreview.value = props.item.image;
      }
    }
  } catch (error) {
    console.error('Error initializing form:', error);
  }
});

// Watch for changes in the item type to reset the date fields
watch(() => formData.value.type, (newType) => {
  if (newType === 'lost') {
    formData.value.found_date = '';
  } else {
    formData.value.lost_date = '';
  }
});

// Handle image selection
const handleImageChange = (event) => {
  const file = event.target.files[0];
  if (!file) return;
  
  // Validate file size (max 2MB)
  if (file.size > 2 * 1024 * 1024) {
    alert('Image size should not exceed 2MB');
    return;
  }
  
  // Set the image file in the form data
  formData.value.image = file;
  
  // Create a preview URL
  imagePreview.value = URL.createObjectURL(file);
};

// Remove the selected image
const removeImage = () => {
  formData.value.image = null;
  imagePreview.value = null;
  if (imageInput.value) {
    imageInput.value.value = '';
  }
};

// Format date for datetime-local input
const formatDateForInput = (dateString) => {
  if (!dateString) return '';
  
  const date = new Date(dateString);
  return date.toISOString().slice(0, 16); // Format as YYYY-MM-DDTHH:MM
};

// Submit the form
const submitForm = async () => {
  try {
    isSubmitting.value = true;
    
    // Create a copy of the form data for submission
    const submitData = { ...formData.value };
    
    // Format dates for API
    if (submitData.type === 'lost') {
      submitData.lost_date = new Date(submitData.lost_date).toISOString();
      delete submitData.found_date;
    } else {
      submitData.found_date = new Date(submitData.found_date).toISOString();
      delete submitData.lost_date;
    }
    
    let response;
    if (props.isEdit) {
      response = await itemService.updateItem(props.item.id, submitData);
    } else {
      response = await itemService.createItem(submitData);
    }
    
    emit('submit', response.data.item);
  } catch (error) {
    console.error('Error submitting form:', error);
    alert(error.response?.data?.message || 'An error occurred while saving the item.');
  } finally {
    isSubmitting.value = false;
  }
};
</script>
