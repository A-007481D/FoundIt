<template>
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Loading state -->
    <div v-if="loading" class="p-6 flex justify-center items-center min-h-[300px]">
      <div class="animate-spin h-10 w-10 border-4 border-primary border-t-transparent rounded-full"></div>
    </div>
    
    <!-- Error state -->
    <div v-else-if="error" class="p-6 text-center min-h-[300px] flex flex-col justify-center items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
      </svg>
      <p class="text-lg font-medium text-gray-900">{{ error }}</p>
      <button 
        @click="$emit('close')" 
        class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
      >
        Go Back
      </button>
    </div>
    
    <!-- Item details -->
    <div v-else-if="item" class="flex flex-col md:flex-row">
      <!-- Image section -->
      <div class="md:w-1/2 p-4 flex items-center justify-center bg-gray-50">
        <img 
          v-if="item.image" 
          :src="item.image" 
          :alt="item.title" 
          class="max-h-[400px] object-contain rounded-md"
        >
        <div v-else class="h-64 w-full flex items-center justify-center bg-gray-100 rounded-md">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
      </div>
      
      <!-- Details section -->
      <div class="md:w-1/2 p-6">
        <div class="flex justify-between items-start">
          <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ item.title }}</h2>
            <div class="mt-1 flex items-center">
              <span 
                :class="[
                  'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                  item.type === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'
                ]"
              >
                {{ item.type === 'lost' ? 'Lost' : 'Found' }}
              </span>
              <span class="mx-2 text-gray-300">•</span>
              <span class="text-sm text-gray-500">{{ item.category?.name || 'Uncategorized' }}</span>
            </div>
          </div>
          
          <!-- Close button -->
          <button 
            @click="$emit('close')" 
            class="text-gray-400 hover:text-gray-500"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <!-- Item metadata -->
        <div class="mt-4 border-t border-gray-200 pt-4">
          <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
            <div class="sm:col-span-1">
              <dt class="text-sm font-medium text-gray-500">Status</dt>
              <dd class="mt-1 text-sm text-gray-900">
                <span 
                  :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    getStatusClass(item.status)
                  ]"
                >
                  {{ formatStatus(item.status) }}
                </span>
              </dd>
            </div>
            
            <div class="sm:col-span-1">
              <dt class="text-sm font-medium text-gray-500">Location</dt>
              <dd class="mt-1 text-sm text-gray-900">{{ item.location }}</dd>
            </div>
            
            <div class="sm:col-span-1">
              <dt class="text-sm font-medium text-gray-500">
                {{ item.type === 'lost' ? 'Lost Date' : 'Found Date' }}
              </dt>
              <dd class="mt-1 text-sm text-gray-900">
                {{ formatDate(item.type === 'lost' ? item.lost_date : item.found_date) }}
              </dd>
            </div>
            
            <div class="sm:col-span-1">
              <dt class="text-sm font-medium text-gray-500">Posted By</dt>
              <dd class="mt-1 text-sm text-gray-900">
                {{ item.user ? `${item.user.firstname} ${item.user.lastname}` : 'Unknown' }}
              </dd>
            </div>
          </dl>
        </div>
        
        <!-- Description -->
        <div class="mt-4 border-t border-gray-200 pt-4">
          <h3 class="text-sm font-medium text-gray-500">Description</h3>
          <div class="mt-2 prose prose-sm text-gray-900">
            <p>{{ item.description }}</p>
          </div>
        </div>
        
        <!-- Action buttons -->
        <div class="mt-6 flex flex-wrap gap-3">
          <!-- Contact button -->
          <button 
            v-if="item.status === 'active' && !isOwner"
            @click="contactOwner"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Contact Owner
          </button>
          
          <!-- Report button -->
          <button 
            v-if="item.status === 'active' && !isOwner"
            @click="showReportModal = true"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Report
          </button>
          
          <!-- Edit button -->
          <button 
            v-if="isOwner && item.status === 'active'"
            @click="$emit('edit', item)"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit
          </button>
          
          <!-- Archive button -->
          <button 
            v-if="isOwner && item.status === 'active'"
            @click="confirmArchive = true"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
            </svg>
            Archive
          </button>
          
          <!-- Restore button -->
          <button 
            v-if="isOwner && item.status === 'archived'"
            @click="restoreItem"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Restore
          </button>
        </div>
      </div>
    </div>
    
    <!-- Report Modal -->
    <div v-if="showReportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium">Report Item</h3>
          <button @click="showReportModal = false" class="text-gray-400 hover:text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <form @submit.prevent="submitReport">
          <div class="mb-4">
            <label for="report-reason" class="block text-sm font-medium text-gray-700 mb-1">Reason for Report</label>
            <select 
              id="report-reason" 
              v-model="reportData.reason" 
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
              required
            >
              <option value="" disabled>Select a reason</option>
              <option value="Inappropriate content">Inappropriate content</option>
              <option value="Spam or misleading">Spam or misleading</option>
              <option value="Fraudulent listing">Fraudulent listing</option>
              <option value="Wrong category">Wrong category</option>
              <option value="Other">Other</option>
            </select>
          </div>
          
          <div class="mb-4">
            <label for="report-details" class="block text-sm font-medium text-gray-700 mb-1">Additional Details</label>
            <textarea 
              id="report-details" 
              v-model="reportData.details" 
              rows="3" 
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
              placeholder="Please provide any additional details about this report..."
            ></textarea>
          </div>
          
          <div class="flex justify-end space-x-3">
            <button 
              type="button" 
              @click="showReportModal = false" 
              class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              :disabled="isSubmitting"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50"
            >
              <span v-if="isSubmitting">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Submitting...
              </span>
              <span v-else>Submit Report</span>
            </button>
          </div>
        </form>
      </div>
    </div>
    
    <!-- Archive Confirmation Modal -->
    <div v-if="confirmArchive" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="mb-4">
          <h3 class="text-lg font-medium">Archive Item</h3>
          <p class="mt-2 text-sm text-gray-500">
            Are you sure you want to archive this item? It will no longer be visible to other users but can be restored later.
          </p>
        </div>
        
        <div class="flex justify-end space-x-3">
          <button 
            @click="confirmArchive = false" 
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
          >
            Cancel
          </button>
          <button 
            @click="archiveItem" 
            :disabled="isSubmitting"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary disabled:opacity-50"
          >
            <span v-if="isSubmitting">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Processing...
            </span>
            <span v-else>Archive Item</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth.store';
import { format } from 'date-fns';
import itemService from '@/services/item.service';

const props = defineProps({
  itemId: {
    type: [Number, String],
    required: true
  }
});

const emit = defineEmits(['close', 'edit', 'update']);

// State
const authStore = useAuthStore();
const item = ref(null);
const loading = ref(true);
const error = ref(null);
const showReportModal = ref(false);
const confirmArchive = ref(false);
const isSubmitting = ref(false);
const reportData = ref({
  reason: '',
  details: ''
});

// Computed
const isOwner = computed(() => {
  if (!item.value || !authStore.user) return false;
  return item.value.user_id === authStore.user.id;
});

// Fetch item data
onMounted(async () => {
  try {
    loading.value = true;
    const response = await itemService.getItem(props.itemId);
    
    // Log the raw response to debug the structure
    console.log('Raw item detail response:', JSON.stringify(response.data, null, 2));
    
    // Handle different API response structures
    if (response.data?.item) {
      // If response has an 'item' property
      item.value = response.data.item;
    } else if (response.data?.data) {
      // If response is a Laravel resource with 'data' property
      item.value = response.data.data;
    } else {
      // If response itself is the item
      item.value = response.data;
    }
    
    console.log('Processed item data:', item.value);
    
    if (!item.value) {
      throw new Error('Invalid item data received');
    }
  } catch (err) {
    console.error('Error fetching item:', err);
    error.value = 'Failed to load item details. The item may have been removed or you do not have permission to view it.';
  } finally {
    loading.value = false;
  }
});

// Format date
const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  return format(new Date(dateString), 'PPP p'); // e.g., "Apr 29, 2025, 3:00 PM"
};

// Format status
const formatStatus = (status) => {
  const statusMap = {
    'active': 'Active',
    'archived': 'Archived',
    'reported': 'Under Review',
    'deleted': 'Deleted'
  };
  return statusMap[status] || status;
};

// Get status class
const getStatusClass = (status) => {
  const classMap = {
    'active': 'bg-green-100 text-green-800',
    'archived': 'bg-gray-100 text-gray-800',
    'reported': 'bg-yellow-100 text-yellow-800',
    'deleted': 'bg-red-100 text-red-800'
  };
  return classMap[status] || 'bg-gray-100 text-gray-800';
};

// Contact owner
const contactOwner = () => {
  // In a real app, this would open a messaging interface or show contact info
  alert(`Contact functionality would be implemented here. You would be able to message ${item.value.user.firstname} about this item.`);
};

// Submit report
const submitReport = async () => {
  if (!reportData.value.reason) {
    alert('Please select a reason for your report.');
    return;
  }
  
  try {
    isSubmitting.value = true;
    await itemService.reportItem(
      item.value.id, 
      reportData.value.reason, 
      reportData.value.details
    );
    
    showReportModal.value = false;
    alert('Thank you for your report. Our team will review it shortly.');
    
    // Reset report form
    reportData.value = {
      reason: '',
      details: ''
    };
  } catch (err) {
    console.error('Error submitting report:', err);
    alert('Failed to submit report. Please try again later.');
  } finally {
    isSubmitting.value = false;
  }
};

// Archive item
const archiveItem = async () => {
  try {
    isSubmitting.value = true;
    const response = await itemService.archiveItem(item.value.id);
    
    // Update the local item data
    item.value = response.data.item;
    confirmArchive.value = false;
    
    // Emit update event to refresh parent component
    emit('update', item.value);
  } catch (err) {
    console.error('Error archiving item:', err);
    alert('Failed to archive item. Please try again later.');
  } finally {
    isSubmitting.value = false;
  }
};

// Restore item
const restoreItem = async () => {
  try {
    isSubmitting.value = true;
    const response = await itemService.restoreItem(item.value.id);
    
    // Update the local item data
    item.value = response.data.item;
    
    // Emit update event to refresh parent component
    emit('update', item.value);
  } catch (err) {
    console.error('Error restoring item:', err);
    alert('Failed to restore item. Please try again later.');
  } finally {
    isSubmitting.value = false;
  }
};
</script>
