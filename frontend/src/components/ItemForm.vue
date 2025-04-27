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
                v-model="formData.type" 
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
                v-model="formData.type" 
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
            v-model="formData.title" 
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
              v-model="formData.category_id" 
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
        
        <div class="grid grid-cols-2 gap-3">
          <!-- Location -->
          <div>
            <label for="location" class="block font-medium text-gray-700 mb-1">Location</label>
            <input 
              type="text" 
              id="location" 
              v-model="formData.location" 
              class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-indigo-500"
              placeholder="E.g., Library"
              required
            >
          </div>
          
          <!-- Date -->
          <div>
            <label :for="dateInputId" class="block font-medium text-gray-700 mb-1">Date</label>
            <div class="relative">
              <input 
                type="date" 
                :id="dateInputId" 
                v-model="selectedDate"
                class="appearance-none bg-white w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-primary"
                required
              >
              <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <!-- Time -->
          <div>
            <label for="time" class="block font-medium text-gray-700 mb-1">Approximate Time</label>
            <div class="relative">
              <input 
                type="time" 
                id="time" 
                v-model="selectedTime"
                class="appearance-none bg-white w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-primary"
              >
              <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
          </div>
          
          <!-- Contact Preference -->
          <div>
            <label for="contact" class="block font-medium text-gray-700 mb-1">Contact Preference</label>
            <div class="relative">
              <select 
                id="contact" 
                v-model="formData.contactPreference" 
                class="bg-white w-full px-3 py-2 pr-8 border border-gray-300 rounded appearance-none focus:outline-none focus:ring-1 focus:ring-primary"
              >
                <option value="app_messaging">App Messaging</option>
                <option value="email">Email</option>
                <option value="phone">Phone</option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Description -->
        <div>
          <label for="description" class="block font-medium text-gray-700 mb-1">Detailed Description</label>
          <textarea 
            id="description" 
            v-model="formData.description" 
            rows="3" 
            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-indigo-500"
            placeholder="Describe the item with as many details as possible..."
            required
          ></textarea>
        </div>
        
        <!-- Image Upload -->
        <div>
          <label class="block font-medium text-gray-700 mb-1">Image (optional)</label>
          <input 
            type="file" 
            id="image" 
            ref="imageInput"
            @change="handleImageChange" 
            accept="image/*"
            class="hidden"
          >
          <div class="border border-gray-300 rounded p-2">
            <div v-if="imagePreview" class="mb-2">
              <div class="relative h-24 w-full overflow-hidden">
                <img :src="imagePreview" alt="Preview" class="h-full w-auto object-contain mx-auto">
                <button 
                  type="button" 
                  @click="removeImage" 
                  class="absolute top-1 right-1 bg-white rounded-full p-1 shadow-sm hover:bg-gray-100"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-700" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
            </div>
            <div class="flex flex-col items-center">
              <button 
                type="button" 
                @click="$refs.imageInput.click()" 
                class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-indigo-500"
              >
                <span>Choose File</span>
                <span class="ml-2 text-gray-500 truncate max-w-xs">{{ formData.image ? formData.image.name : 'No file chosen' }}</span>
              </button>
              <p class="mt-1 text-xs text-gray-500">An image can help identify the item more easily.</p>
            </div>
          </div>
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
            :disabled="isSubmitting"
            class="px-4 py-2 border border-transparent rounded text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-1 focus:ring-primary disabled:opacity-50"
          >
            <span v-if="isSubmitting">
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
  image: null,
  contactPreference: 'app_messaging'
});

// New field for time
const selectedTime = ref('');

// Computed properties for date input
const dateInputLabel = computed(() => {
  return formData.value.type === 'lost' ? 'When did you lose it?' : 'When did you find it?';
});

const dateInputId = computed(() => {
  return formData.value.type === 'lost' ? 'lost_date' : 'found_date';
});

const selectedDate = computed({
  get() {
    if (formData.value.type === 'lost') {
      return formData.value.lost_date ? formData.value.lost_date.split('T')[0] : '';
    } else {
      return formData.value.found_date ? formData.value.found_date.split('T')[0] : '';
    }
  },
  set(value) {
    const timeComponent = selectedTime.value || '00:00';
    const dateTimeStr = `${value}T${timeComponent}`;
    
    if (formData.value.type === 'lost') {
      formData.value.lost_date = dateTimeStr;
    } else {
      formData.value.found_date = dateTimeStr;
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
        lost_date: props.item.lost_date,
        found_date: props.item.found_date,
        image: null,
        contactPreference: props.item.contactPreference || 'app_messaging'
      };
      
      // Extract time from date
      if (props.item.type === 'lost' && props.item.lost_date) {
        const dateTime = new Date(props.item.lost_date);
        selectedTime.value = `${String(dateTime.getHours()).padStart(2, '0')}:${String(dateTime.getMinutes()).padStart(2, '0')}`;
      } else if (props.item.type === 'found' && props.item.found_date) {
        const dateTime = new Date(props.item.found_date);
        selectedTime.value = `${String(dateTime.getHours()).padStart(2, '0')}:${String(dateTime.getMinutes()).padStart(2, '0')}`;
      }
      
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

// Watch for changes in time
watch(selectedTime, (newTime) => {
  if (!newTime) return;
  
  const dateStr = selectedDate.value;
  if (!dateStr) return;
  
  const dateTimeStr = `${dateStr}T${newTime}`;
  
  if (formData.value.type === 'lost') {
    formData.value.lost_date = dateTimeStr;
  } else {
    formData.value.found_date = dateTimeStr;
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

// Submit the form
const submitForm = async () => {
  try {
    isSubmitting.value = true;
    
    // Create a copy of the form data for submission
    const submitData = { ...formData.value };
    
    // Format dates for API
    if (submitData.type === 'lost') {
      // For lost items, set lost_date from form and set found_date to a default value
      submitData.lost_date = new Date(submitData.lost_date).toISOString();
      submitData.found_date = new Date().toISOString(); // Default to current date
      submitData.status = 'active'; // Ensure status is set
    } else {
      // For found items, set found_date from form and set lost_date to a default value
      submitData.found_date = new Date(submitData.found_date).toISOString();
      submitData.lost_date = new Date().toISOString(); // Default to current date
      submitData.status = 'active'; // Ensure status is set
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
    alert(error.response?.data?.message || 'An error occurred while saving the item. See console for details.');
  } finally {
    isSubmitting.value = false;
  }
};
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