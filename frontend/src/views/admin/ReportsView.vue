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

    <!-- Reports Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
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
                <button v-if="report.status === 'pending'" @click="resolveReport(report)" class="text-green-600 hover:text-green-900">
                  Resolve
                </button>
                <button v-if="report.status === 'pending'" @click="dismissReport(report)" class="text-red-600 hover:text-red-900">
                  Dismiss
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
        Showing <span class="font-medium">{{ startIndex + 1 }}</span> to <span class="font-medium">{{ endIndex }}</span> of <span class="font-medium">{{ totalReports }}</span> reports
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
          :disabled="endIndex >= totalReports" 
          :class="endIndex >= totalReports ? 'opacity-50 cursor-not-allowed' : ''"
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

const router = useRouter();

// Mock reports data - replace with API calls in production
const reports = ref([
  {
    id: 1,
    type: 'user',
    subject: {
      id: 5,
      name: 'Michael Brown',
      email: 'michael@example.com',
      image: null
    },
    reason: 'Inappropriate behavior in messages',
    details: 'This user has been sending harassing messages to multiple users.',
    reporter: {
      id: 2,
      name: 'Jane Smith',
      email: 'jane@example.com'
    },
    status: 'pending',
    created_at: '2025-03-15T10:00:00',
    resolution: null
  },
  {
    id: 2,
    type: 'item',
    subject: {
      id: 3,
      title: 'iPhone 14 Pro',
      image: 'https://images.unsplash.com/photo-1592286927505-1def25115611?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80'
    },
    reason: 'Suspicious listing - possible scam',
    details: 'The price is too low and the seller is asking for payment outside the platform.',
    reporter: {
      id: 3,
      name: 'Robert Johnson',
      email: 'robert@example.com'
    },
    status: 'resolved',
    created_at: '2025-03-16T14:30:00',
    resolution: 'Item was removed and user was warned.'
  },
  {
    id: 3,
    type: 'user',
    subject: {
      id: 6,
      name: 'David Wilson',
      email: 'david@example.com',
      image: null
    },
    reason: 'Fake profile',
    details: 'This profile appears to be using stolen photos and information.',
    reporter: {
      id: 4,
      name: 'Emily Williams',
      email: 'emily@example.com'
    },
    status: 'dismissed',
    created_at: '2025-03-17T09:15:00',
    resolution: 'After investigation, the profile appears legitimate.'
  },
  {
    id: 4,
    type: 'item',
    subject: {
      id: 7,
      title: 'MacBook Pro 16"',
      image: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80'
    },
    reason: 'Item not as described',
    details: 'The listing claims the item is new, but photos show it is clearly used.',
    reporter: {
      id: 1,
      name: 'John Doe',
      email: 'john@example.com'
    },
    status: 'pending',
    created_at: '2025-03-18T16:45:00',
    resolution: null
  }
]);

// Pagination
const itemsPerPage = 10;
const currentPage = ref(1);
const searchQuery = ref('');
const typeFilter = ref('all');
const statusFilter = ref('all');
const resolutionNotes = ref('');

// Modal state
const showReportModal = ref(false);
const currentReport = ref({});

// Filtered reports
const filteredReports = computed(() => {
  return reports.value.filter(report => {
    // Apply search filter
    const searchLower = searchQuery.value.toLowerCase();
    const matchesSearch = searchQuery.value === '' || 
      (report.subject.name && report.subject.name.toLowerCase().includes(searchLower)) ||
      (report.subject.title && report.subject.title.toLowerCase().includes(searchLower)) ||
      (report.subject.email && report.subject.email.toLowerCase().includes(searchLower)) ||
      report.reason.toLowerCase().includes(searchLower) ||
      (report.details && report.details.toLowerCase().includes(searchLower));
    
    // Apply type filter
    const matchesType = typeFilter.value === 'all' || report.type === typeFilter.value;
    
    // Apply status filter
    const matchesStatus = statusFilter.value === 'all' || report.status === statusFilter.value;
    
    return matchesSearch && matchesType && matchesStatus;
  });
});

// Computed values for pagination
const totalReports = computed(() => filteredReports.value.length);
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage);
const endIndex = computed(() => Math.min(startIndex.value + itemsPerPage, totalReports.value));
const paginatedReports = computed(() => {
  return filteredReports.value.slice(startIndex.value, endIndex.value);
});

// Methods
const handleSearch = () => {
  currentPage.value = 1; // Reset to first page on search
};

const filterReports = () => {
  currentPage.value = 1; // Reset to first page on filter change
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (endIndex.value < totalReports.value) {
    currentPage.value++;
  }
};

const viewReport = (report) => {
  currentReport.value = { ...report };
  resolutionNotes.value = '';
  showReportModal.value = true;
};

const resolveReport = (report) => {
  // In a real app, you would call an API to resolve the report
  const index = reports.value.findIndex(r => r.id === report.id);
  if (index !== -1) {
    reports.value[index] = { 
      ...report, 
      status: 'resolved',
      resolution: 'Report was reviewed and action was taken.'
    };
  }
};

const dismissReport = (report) => {
  // In a real app, you would call an API to dismiss the report
  const index = reports.value.findIndex(r => r.id === report.id);
  if (index !== -1) {
    reports.value[index] = { 
      ...report, 
      status: 'dismissed',
      resolution: 'Report was reviewed and no action was needed.'
    };
  }
};

const resolveReportWithNotes = () => {
  const index = reports.value.findIndex(r => r.id === currentReport.value.id);
  if (index !== -1) {
    reports.value[index] = { 
      ...currentReport.value, 
      status: 'resolved',
      resolution: resolutionNotes.value || 'Report was reviewed and action was taken.'
    };
    showReportModal.value = false;
  }
};

const dismissReportWithNotes = () => {
  const index = reports.value.findIndex(r => r.id === currentReport.value.id);
  if (index !== -1) {
    reports.value[index] = { 
      ...currentReport.value, 
      status: 'dismissed',
      resolution: resolutionNotes.value || 'Report was reviewed and no action was needed.'
    };
    showReportModal.value = false;
  }
};

const navigateToUser = () => {
  if (currentReport.value.subject && currentReport.value.subject.id) {
    router.push(`/admin/users?id=${currentReport.value.subject.id}`);
  }
};

const navigateToItem = () => {
  if (currentReport.value.subject && currentReport.value.subject.id) {
    router.push(`/admin/items?id=${currentReport.value.subject.id}`);
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

// Load reports on component mount
onMounted(() => {
  // In a real app, you would fetch reports from an API
  // fetchReports();
});
</script>
