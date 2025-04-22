<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Report Management</h1>
      <div class="flex gap-2">
        <div class="relative">
          <input 
            type="text" 
            v-model="searchQuery" 
            placeholder="Search reports..." 
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
          @change="filterReports"
        >
          <option value="all">All Types</option>
          <option value="user">User Reports</option>
          <option value="item">Item Reports</option>
        </select>
        <select 
          v-model="statusFilter" 
          class="border rounded-md px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary"
          @change="filterReports"
        >
          <option value="all">All Statuses</option>
          <option value="pending">Pending</option>
          <option value="resolved">Resolved</option>
          <option value="dismissed">Dismissed</option>
        </select>
      </div>
    </div>

    <!-- Loading indicator -->
    <div v-if="loading" class="flex justify-center items-center py-8">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
    </div>

    <!-- Reports Table -->
    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              ID
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Type
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Subject
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Reason
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Reporter
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Date
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Status
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="report in paginatedReports" :key="report.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              #{{ report.id }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                :class="report.type === 'user' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'">
                {{ report.type }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="h-8 w-8 flex-shrink-0">
                  <img v-if="report.subject.image" :src="report.subject.image" :alt="report.subject.name" class="h-8 w-8 rounded-full object-cover" />
                  <div v-else class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                    {{ report.subject.name ? report.subject.name[0].toUpperCase() : 'U' }}
                  </div>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">{{ report.subject.name }}</div>
                  <div class="text-xs text-gray-500">{{ report.subject.email || report.subject.title }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900 max-w-xs truncate">{{ report.reason }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ report.reporter.name }}</div>
              <div class="text-xs text-gray-500">{{ report.reporter.email }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ formatDate(report.created_at) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                :class="getStatusClass(report.status)">
                {{ report.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <div class="flex space-x-2">
                <button @click="viewReport(report)" class="text-indigo-600 hover:text-indigo-900">
                  View
                </button>
                <button v-if="report.type === 'user'" @click="navigateToUser" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md">
                  View User Profile
                </button>
                <button v-if="report.type === 'user'" @click="suspendUser" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-md ml-2">
                  Suspend User
                </button>
                <button v-if="report.type === 'user'" @click="banUser" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md ml-2">
                  Ban User
                </button>
                <button v-if="report.type === 'item'" @click="navigateToItem" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md">
                  View Item Details
                </button>
                <button v-if="report.type === 'item'" @click="deleteItem" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md ml-2">
                  Delete Item
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="paginatedReports.length === 0">
            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
              No reports found matching your criteria.
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
      <div class="text-sm text-gray-700">
        Showing <span class="font-medium">{{ startIndex + 1 }}</span> to <span class="font-medium">{{ endIndex }}</span> of <span class="font-medium">{{ totalItems }}</span> results
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

    <!-- Report View Modal -->
    <div v-if="showReportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium">Report Details</h3>
          <button @click="showReportModal = false" class="text-gray-400 hover:text-gray-500">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div class="space-y-4">
          <div class="flex items-center gap-2">
            <span class="px-2 py-1 text-xs rounded-full" 
              :class="currentReport.type === 'user' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'">
              {{ currentReport.type }} Report
            </span>
            <span class="px-2 py-1 text-xs rounded-full" :class="getStatusClass(currentReport.status)">
              {{ currentReport.status }}
            </span>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
              <div>
                <h4 class="text-sm font-medium text-gray-700">Reported {{ currentReport.type === 'user' ? 'User' : 'Item' }}</h4>
                <div class="flex items-center mt-1">
                  <div class="h-10 w-10 flex-shrink-0">
                    <img v-if="currentReport.subject?.image" :src="currentReport.subject.image" :alt="currentReport.subject.name" class="h-10 w-10 rounded-full object-cover" />
                    <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                      {{ currentReport.subject?.name ? currentReport.subject.name[0].toUpperCase() : 'U' }}
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ currentReport.subject?.name || currentReport.subject?.title }}</div>
                    <div class="text-sm text-gray-500">{{ currentReport.subject?.email || '' }}</div>
                  </div>
                </div>
              </div>
              
              <div>
                <h4 class="text-sm font-medium text-gray-700">Reported By</h4>
                <div class="flex items-center mt-1">
                  <div class="h-10 w-10 flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                      {{ currentReport.reporter?.name ? currentReport.reporter.name[0].toUpperCase() : 'U' }}
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ currentReport.reporter?.name }}</div>
                    <div class="text-sm text-gray-500">{{ currentReport.reporter?.email }}</div>
                  </div>
                </div>
              </div>
              
              <div>
                <h4 class="text-sm font-medium text-gray-700">Date Reported</h4>
                <p class="text-sm text-gray-900">{{ formatDate(currentReport.created_at) }}</p>
              </div>
            </div>
            
            <div class="space-y-4">
              <div>
                <h4 class="text-sm font-medium text-gray-700">Report Reason</h4>
                <p class="text-sm text-gray-900 mt-1">{{ currentReport.reason }}</p>
              </div>
              
              <div>
                <h4 class="text-sm font-medium text-gray-700">Additional Details</h4>
                <p class="text-sm text-gray-900 mt-1">{{ currentReport.details || 'No additional details provided.' }}</p>
              </div>
              
              <div v-if="currentReport.status !== 'pending'">
                <h4 class="text-sm font-medium text-gray-700">Resolution</h4>
                <p class="text-sm text-gray-900 mt-1">{{ currentReport.resolution || 'No resolution notes provided.' }}</p>
              </div>
            </div>
          </div>
          
          <div v-if="currentReport.status === 'pending'" class="mt-4">
            <h4 class="text-sm font-medium text-gray-700">Resolution Notes</h4>
            <textarea 
              v-model="resolutionNotes" 
              rows="3" 
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
              placeholder="Add notes about how this report was handled..."
            ></textarea>
          </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
          <button @click="showReportModal = false" class="px-4 py-2 border rounded-md hover:bg-gray-50">
            Close
          </button>
          <button 
            v-if="currentReport.status === 'pending'" 
            @click="dismissReportWithNotes" 
            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
          >
            Dismiss Report
          </button>
          <button 
            v-if="currentReport.status === 'pending'" 
            @click="resolveReportWithNotes" 
            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
          >
            Resolve Report
          </button>
          <button 
            v-if="currentReport.type === 'user' && currentReport.status === 'pending'" 
            @click="navigateToUser" 
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
          >
            Manage User
          </button>
          <button 
            v-if="currentReport.type === 'item' && currentReport.status === 'pending'" 
            @click="navigateToItem" 
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
          >
            Manage Item
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import reportService from '@/services/report.service';
import { toast } from 'vue3-toastify';

const router = useRouter();

// Reports data
const reports = ref([]);

// Basic state variables
const searchQuery = ref('');
const typeFilter = ref('all');
const statusFilter = ref('all');
const currentPage = ref(1);
const itemsPerPage = 10;
const loading = ref(false);
const totalItems = ref(0);

// Modal state
const showReportModal = ref(false);
const currentReport = ref({});
const resolutionNotes = ref('');

// Computed values for pagination
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage);
const endIndex = computed(() => Math.min(startIndex.value + itemsPerPage, totalItems.value));
const paginatedReports = computed(() => reports.value);

// Update current page and refresh data for pagination
const goToPage = (page) => {
  currentPage.value = page;
  fetchReports();
};

// Methods
const handleSearch = () => {
  currentPage.value = 1; // Reset to first page on search
  fetchReports();
};

const filterReports = () => {
  currentPage.value = 1; // Reset to first page on filter change
  fetchReports();
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    fetchReports();
  }
};

const nextPage = () => {
  if (endIndex.value < totalItems.value) {
    currentPage.value++;
    fetchReports();
  }
};

const viewReport = (report) => {
  currentReport.value = { ...report };
  resolutionNotes.value = '';
  showReportModal.value = true;
};

const resolveReport = async (report) => {
  try {
    await reportService.resolveReport(report.id, 'Report was reviewed and action was taken.');
    toast.success('Report resolved successfully');
    fetchReports(); // Refresh the reports list
  } catch (error) {
    toast.error('Failed to resolve report: ' + (error.response?.data?.message || error.message));
  }
};

const dismissReport = async (report) => {
  try {
    await reportService.dismissReport(report.id, 'Report was reviewed and no action was needed.');
    toast.success('Report dismissed successfully');
    fetchReports(); // Refresh the reports list
  } catch (error) {
    toast.error('Failed to dismiss report: ' + (error.response?.data?.message || error.message));
  }
};

const resolveReportWithNotes = async () => {
  try {
    await reportService.resolveReport(
      currentReport.value.id, 
      resolutionNotes.value || 'Report was reviewed and action was taken.'
    );
    toast.success('Report resolved successfully');
    showReportModal.value = false;
    fetchReports(); // Refresh the reports list
  } catch (error) {
    toast.error('Failed to resolve report: ' + (error.response?.data?.message || error.message));
  }
};

const dismissReportWithNotes = async () => {
  try {
    await reportService.dismissReport(
      currentReport.value.id, 
      resolutionNotes.value || 'Report was reviewed and no action was needed.'
    );
    toast.success('Report dismissed successfully');
    showReportModal.value = false;
    fetchReports(); // Refresh the reports list
  } catch (error) {
    toast.error('Failed to dismiss report: ' + (error.response?.data?.message || error.message));
  }
};

const navigateToUser = () => {
  if (currentReport.value.subject && currentReport.value.subject.id) {
    router.push(`/admin/users?id=${currentReport.value.subject.id}`);
  }
};

const banUser = async () => {
  if (!currentReport.value.subject || currentReport.value.type !== 'user') return;
  
  try {
    await reportService.banUser(
      currentReport.value.subject.id, 
      resolutionNotes.value || 'User banned due to report'
    );
    toast.success('User banned successfully');
    await resolveReportWithNotes();
  } catch (error) {
    toast.error('Failed to ban user: ' + (error.response?.data?.message || error.message));
  }
};

const suspendUser = async () => {
  if (!currentReport.value.subject || currentReport.value.type !== 'user') return;
  
  try {
    // Suspend for 7 days by default
    await reportService.suspendUser(
      currentReport.value.subject.id,
      7,
      resolutionNotes.value || 'User suspended due to report'
    );
    toast.success('User suspended successfully');
    await resolveReportWithNotes();
  } catch (error) {
    toast.error('Failed to suspend user: ' + (error.response?.data?.message || error.message));
  }
};

const navigateToItem = () => {
  if (currentReport.value.subject && currentReport.value.subject.id) {
    router.push(`/admin/items?id=${currentReport.value.subject.id}`);
  }
};

const deleteItem = async () => {
  if (!currentReport.value.subject || currentReport.value.type !== 'item') return;
  
  try {
    await reportService.deleteItem(
      currentReport.value.subject.id,
      resolutionNotes.value || 'Item deleted due to report'
    );
    toast.success('Item deleted successfully');
    await resolveReportWithNotes();
  } catch (error) {
    toast.error('Failed to delete item: ' + (error.response?.data?.message || error.message));
  }
};

const getStatusClass = (status) => {
  const classes = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'resolved': 'bg-green-100 text-green-800',
    'dismissed': 'bg-gray-100 text-gray-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

// Fetch reports from the API
const fetchReports = async () => {
  try {
    loading.value = true;
    const response = await reportService.getReports({
      search: searchQuery.value,
      type: typeFilter.value === 'all' ? '' : typeFilter.value,
      status: statusFilter.value === 'all' ? '' : statusFilter.value,
      page: currentPage.value,
      per_page: itemsPerPage
    });
    
    reports.value = response.data.data;
    totalItems.value = response.data.total;
    currentPage.value = response.data.current_page;
    loading.value = false;
  } catch (error) {
    console.error('Error fetching reports:', error);
    toast.error('Failed to load reports');
    loading.value = false;
  }
};

// Load reports on component mount
onMounted(() => {
  fetchReports();
});


</script>
