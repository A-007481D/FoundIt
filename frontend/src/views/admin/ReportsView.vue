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
                <!-- <button v-if="report.type === 'item'" @click="navigateToItem" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md">
                  View Item Details
                </button> -->
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
              :class="selectedReport.type === 'user' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'">
              {{ selectedReport.type }} Report
            </span>
            <span class="px-2 py-1 text-xs rounded-full" :class="getStatusClass(selectedReport.status)">
              {{ selectedReport.status }}
            </span>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
              <div>
                <h4 class="text-sm font-medium text-gray-700">Reported {{ selectedReport.type === 'user' ? 'User' : 'Item' }}</h4>
                <div class="flex items-center mt-1">
                  <div class="h-10 w-10 flex-shrink-0">
                    <img v-if="selectedReport.subject?.image" :src="selectedReport.subject.image" :alt="selectedReport.subject.name" class="h-10 w-10 rounded-full object-cover" />
                    <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                      {{ selectedReport.subject?.name ? selectedReport.subject.name[0].toUpperCase() : 'U' }}
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ selectedReport.subject?.name || selectedReport.subject?.title }}</div>
                    <div class="text-sm text-gray-500">{{ selectedReport.subject?.email || '' }}</div>
                  </div>
                </div>
              </div>
              
              <div>
                <h4 class="text-sm font-medium text-gray-700">Reported By</h4>
                <div class="flex items-center mt-1">
                  <div class="h-10 w-10 flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                      {{ selectedReport.reporter?.name ? selectedReport.reporter.name[0].toUpperCase() : 'U' }}
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ selectedReport.reporter?.name }}</div>
                    <div class="text-sm text-gray-500">{{ selectedReport.reporter?.email }}</div>
                  </div>
                </div>
              </div>
              
              <div>
                <h4 class="text-sm font-medium text-gray-700">Date Reported</h4>
                <p class="text-sm text-gray-900">{{ formatDate(selectedReport.created_at) }}</p>
              </div>
            </div>
            
            <div class="space-y-4">
              <div>
                <h4 class="text-sm font-medium text-gray-700">Report Reason</h4>
                <p class="text-sm text-gray-900 mt-1">{{ selectedReport.reason }}</p>
              </div>
              
              <div>
                <h4 class="text-sm font-medium text-gray-700">Additional Details</h4>
                <p class="text-sm text-gray-900 mt-1">{{ selectedReport.details || 'No additional details provided.' }}</p>
              </div>
              
              <div v-if="selectedReport.status !== 'pending'">
                <h4 class="text-sm font-medium text-gray-700">Resolution</h4>
                <p class="text-sm text-gray-900 mt-1">{{ selectedReport.resolution || 'No resolution notes provided.' }}</p>
              </div>
            </div>
          </div>
          
          <div v-if="selectedReport.status === 'pending'" class="mt-4">
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
            v-if="selectedReport.status === 'pending'" 
            @click="dismissReportWithNotes" 
            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
          >
            Dismiss Report
          </button>
          <button 
            v-if="selectedReport.status === 'pending'" 
            @click="resolveReportWithNotes" 
            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
          >
            Resolve Report
          </button>
          <button 
            v-if="selectedReport.type === 'user' && selectedReport.status === 'pending'" 
            @click="navigateToUser" 
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
          >
            Manage User
          </button>
          <button 
            v-if="selectedReport.type === 'item' && selectedReport.status === 'pending'" 
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
import ReportService from '@/services/api/report';
import { toast } from 'vue3-toastify';

const router = useRouter();
const loading = ref(false);
const reports = ref([]);
const searchQuery = ref('');
const typeFilter = ref('all');
const statusFilter = ref('all');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const showReportModal = ref(false);
const selectedReport = ref(null);
const resolutionNotes = ref('');

// Computed properties
const filteredReports = computed(() => {
  let filtered = [...reports.value];
  
  if (searchQuery.value) {
    filtered = filtered.filter(report => 
      report.id.toString().includes(searchQuery.value) ||
      report.reason.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      report.details?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      report.reportable_type.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }
  
  if (typeFilter.value !== 'all') {
    filtered = filtered.filter(report => report.reportable_type === typeFilter.value);
  }
  
  if (statusFilter.value !== 'all') {
    filtered = filtered.filter(report => report.status === statusFilter.value);
  }
  
  return filtered;
});

const paginatedReports = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredReports.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(filteredReports.value.length / itemsPerPage.value);
});

// Methods
async function fetchReports() {
  loading.value = true;
  try {
    const response = await ReportService.getReports();
    reports.value = response.data.data;
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to fetch reports');
    console.error('Failed to fetch reports:', err);
  } finally {
    loading.value = false;
  }
}

function goToPage(page) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
}

function handleSearch() {
  currentPage.value = 1;
}

function filterReports() {
  currentPage.value = 1;
}

function prevPage() {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
}

function nextPage() {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
}

function viewReport(report) {
  selectedReport.value = report;
  showReportModal.value = true;
}

async function resolveReport(report) {
  try {
    await ReportService.updateReportStatus(report.id, 'resolved');
    toast.success('Report resolved successfully');
    fetchReports();
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to resolve report');
    console.error('Failed to resolve report:', err);
  }
}

async function dismissReport(report) {
  try {
    await ReportService.updateReportStatus(report.id, 'dismissed');
    toast.success('Report dismissed successfully');
    fetchReports();
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to dismiss report');
    console.error('Failed to dismiss report:', err);
  }
}

async function resolveReportWithNotes() {
  try {
    await ReportService.updateReportStatus(selectedReport.value.id, 'resolved', resolutionNotes.value);
    toast.success('Report resolved successfully');
    showReportModal.value = false;
    fetchReports();
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to resolve report');
    console.error('Failed to resolve report:', err);
  }
}

async function dismissReportWithNotes() {
  try {
    await ReportService.updateReportStatus(selectedReport.value.id, 'dismissed', resolutionNotes.value);
    toast.success('Report dismissed successfully');
    showReportModal.value = false;
    fetchReports();
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to dismiss report');
    console.error('Failed to dismiss report:', err);
  }
}

function navigateToUser() {
  router.push({ name: 'AdminUserDetails', params: { id: selectedReport.value.user_id } });
}

async function banUser() {
  try {
    await ReportService.updateReportStatus(selectedReport.value.id, 'resolved', resolutionNotes.value);
    await ReportService.suspendUser(selectedReport.value.user_id, 30, resolutionNotes.value);
    toast.success('User banned successfully');
    showReportModal.value = false;
    fetchReports();
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to ban user');
    console.error('Failed to ban user:', err);
  }
}

async function suspendUser() {
  try {
    await ReportService.updateReportStatus(selectedReport.value.id, 'resolved', resolutionNotes.value);
    await ReportService.suspendUser(selectedReport.value.user_id, 7, resolutionNotes.value);
    toast.success('User suspended successfully');
    showReportModal.value = false;
    fetchReports();
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to suspend user');
    console.error('Failed to suspend user:', err);
  }
}

function navigateToItem() {
  router.push({ name: 'ItemDetails', params: { id: selectedReport.value.reportable_id } });
}

async function deleteItem() {
  try {
    await ReportService.deleteItem(selectedReport.value.reportable_id, resolutionNotes.value);
    toast.success('Item deleted successfully');
    showReportModal.value = false;
    fetchReports();
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to delete item');
    console.error('Failed to delete item:', err);
  }
}

function getStatusClass(status) {
  switch (status) {
    case 'pending':
      return 'bg-yellow-100 text-yellow-800';
    case 'resolved':
      return 'bg-green-100 text-green-800';
    case 'dismissed':
      return 'bg-red-100 text-red-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString();
}

// Load reports on component mount
onMounted(() => {
  fetchReports();
});
</script>
