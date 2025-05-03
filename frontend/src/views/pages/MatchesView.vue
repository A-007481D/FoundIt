<!-- MatchesPage.vue -->
<template>
    <div v-if="loading" class="min-h-screen flex items-center justify-center">
        <div class="animate-spin h-10 w-10 border-4 border-purple-600 border-t-transparent rounded-full"></div>
    </div>
    <template v-else>
    <div class="min-h-screen flex flex-col">
        <!-- Main content -->
        <div class="flex-1">
            <main class="max-w-[90rem] mx-auto py-6 md:p-6">
                <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex flex-col gap-2">
                        <h1 class="text-3xl font-bold tracking-tight">Recent Matches</h1>
                        <p class="text-muted-foreground">
                            Our algorithm found potential matches between lost and found items
                        </p>
                    </div>

                    <div class="flex items-center gap-2 ml-auto">
                        <span class="text-sm text-muted-foreground hidden sm:inline">Search:</span>
                        <input v-model="searchQuery" @input="handleSearch" type="search" class="w-full h-9 rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                    </div>
                </div>

                <!-- Filters -->
                <div class="mb-6 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                    <div class="flex flex-1 max-w-md gap-3">
                        <button @click="showFilters = !showFilters" class="inline-flex items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            Filters
                        </button>
                    </div>

                    <div class="flex items-center gap-2 ml-auto">
                        <span class="text-sm text-muted-foreground hidden sm:inline">Status:</span>
                        <div class="relative">
                            <select v-model="statusFilter" @change="filterMatches" class="h-9 w-[180px] rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                                <option value="all">All</option>
                                <option value="new">New</option>
                                <option value="in-progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Filters Panel -->
                <div v-if="showFilters" class="mb-6 rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="p-6">
                        <div class="grid gap-6 md:grid-cols-3">
                            <div class="space-y-2">
                                <h3 class="text-sm font-medium">Match Score</h3>
                                <input type="range" min="0" max="100" step="5" v-model="matchScoreFilter" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <div class="flex justify-between text-xs text-muted-foreground">
                                    <span>0%</span>
                                    <span>{{ matchScoreFilter }}%</span>
                                    <span>100%</span>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <h3 class="text-sm font-medium">Categories</h3>
                                <div class="relative">
                                    <select v-model="categoryFilter" class="w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                                        <option value="all">All categories</option>
                                        <option value="electronics">Electronics</option>
                                        <option value="jewelry">Jewelry</option>
                                        <option value="accessories">Accessories</option>
                                        <option value="bags">Bags</option>
                                        <option value="documents">Documents</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end gap-2">
                            <button @click="resetFilters" class="inline-flex items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-8">
                                Reset
                            </button>
                            <button class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white shadow transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-8">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Match Stats -->
                <!-- <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm text-muted-foreground">Total</p>
                                <p class="text-2xl font-bold">237</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm text-muted-foreground">New</p>
                                <p class="text-2xl font-bold">42</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                    <circle cx="12" cy="12" r="10"></circle>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm text-muted-foreground">In Progress</p>
                                <p class="text-2xl font-bold">54</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-orange-500/10 flex items-center justify-center text-orange-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm text-muted-foreground">Resolved</p>
                                <p class="text-2xl font-bold">141</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                    <path d="M20 6 9 17l-5-5"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- Tabs -->
                <div>
                    <div class="inline-flex h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground mb-6">
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

                    <!-- All Matches Tab -->
                    <div v-if="activeTab === 'all'" class="space-y-6">
                        <MatchCard v-for="match in filteredMatches" :key="match.id" :match="match" />
                    </div>

                    <!-- New Matches Tab -->
                    <div v-if="activeTab === 'new'" class="space-y-6">
                        <MatchCard v-for="match in filteredMatches" :key="match.id" :match="match" />
                    </div>

                    <!-- In Progress Matches Tab -->
                    <div v-if="activeTab === 'in-progress'" class="space-y-6">
                        <MatchCard v-for="match in filteredMatches" :key="match.id" :match="match" />
                    </div>

                    <!-- Resolved Matches Tab -->
                    <div v-if="activeTab === 'resolved'" class="space-y-6">
                        <MatchCard v-for="match in filteredMatches" :key="match.id" :match="match" />
                    </div>

                    <div class="mt-8 flex justify-center">
                        <button class="inline-flex items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 gap-2">
                            <span>View more matches</span>
                        </button>
                    </div>
                </div>
            </main>
        </div>

        <!-- Footer -->
        <footer class="border-t py-8 bg-muted/30">
            <div class="container">
                <div class="flex flex-col md:flex-row justify-between gap-8">
                    <div class="space-y-4 md:max-w-xs">
                        <div class="flex items-center gap-2 font-bold">
                            <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <span class="text-xl">FoundIt!</span>
                        </div>
                        <p class="text-sm text-muted-foreground">
                            FoundIt! is a platform that helps people find their lost items by connecting lost and found item reports.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                        <div class="space-y-3">
                            <h3 class="font-medium">Platform</h3>
                            <ul class="space-y-2">
                                <li>
                                    <a href="/dashboard" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                                        Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a href="/search" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                                        Search
                                    </a>
                                </li>
                                <li>
                                    <a href="/matches" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                                        Matches
                                    </a>
                                </li>
                                <li>
                                    <a href="/dashboard/new-item" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                                        Report an item
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="space-y-3">
                            <h3 class="font-medium">Resources</h3>
                            <ul class="space-y-2">
                                <li>
                                    <a href="/faq" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                                        FAQ
                                    </a>
                                </li>
                                <li>
                                    <a href="/guide" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                                        User Guide
                                    </a>
                                </li>
                                <li>
                                    <a href="/blog" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                                        Blog
                                    </a>
                                </li>
                                <li>
                                    <a href="/contact" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                                        Contact
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="space-y-3 col-span-2 md:col-span-1">
                            <h3 class="font-medium">Legal</h3>
                            <ul class="space-y-2">
                                <li>
                                    <a href="/terms" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                                        Terms of Service
                                    </a>
                                </li>
                                <li>
                                    <a href="/privacy" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                                        Privacy Policy
                                    </a>
                                </li>
                                <li>
                                    <a href="/cookies" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                                        Cookie Policy
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-muted-foreground"> 2023 FoundIt! All rights reserved.</p>

                    <div class="flex items-center gap-4">
                        <a href="#" class="text-muted-foreground hover:text-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                            <span class="sr-only">Facebook</span>
                        </a>
                        <a href="#" class="text-muted-foreground hover:text-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                            <span class="sr-only">Instagram</span>
                        </a>
                        <a href="#" class="text-muted-foreground hover:text-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path>
                            </svg>
                            <span class="sr-only">Twitter</span>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    </template>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { toast } from 'vue3-toastify';
import MatchCard from '@components/MatchCard.vue';
import { getMatches, updateStatus } from '@services/api/match';
import { getMyItems } from '@services/api/item';

const loading = ref(false);
const matches = ref([]);
const searchQuery = ref('');
const statusFilter = ref('all');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const matchScoreFilter = ref(60);
const categoryFilter = ref('all');
const showFilters = ref(false);

// Computed values for pagination
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage.value);
const endIndex = computed(() => {
  if (!matches.value || !Array.isArray(matches.value)) return 0;
  return Math.min(startIndex.value + itemsPerPage.value, matches.value.length);
});

// Computed filters
const filteredMatches = computed(() => {
  if (!matches.value || !Array.isArray(matches.value)) return [];
  
  return matches.value.filter(match => {
    if (!match || !match.lost_item || !match.found_item) return false;
    
    const matchesSearch = (
      (match.lost_item.title && match.lost_item.title.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
      (match.found_item.title && match.found_item.title.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
      (match.lost_item.description && match.lost_item.description.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
      (match.found_item.description && match.found_item.description.toLowerCase().includes(searchQuery.value.toLowerCase()))
    );
    
    const matchesStatus = statusFilter.value === 'all' || match.status === statusFilter.value;
    
    const matchesScore = match.match_score >= matchScoreFilter.value;
    
    const matchesCategory = categoryFilter.value === 'all' || 
      (match.lost_item.category && match.lost_item.category === categoryFilter.value) ||
      (match.found_item.category && match.found_item.category === categoryFilter.value);
    
    return matchesSearch && matchesStatus && matchesScore && matchesCategory;
  });
});

// Load matches
async function loadMatches() {
  try {
    loading.value = true;
    const response = await getMatches({
      page: currentPage.value,
      per_page: itemsPerPage.value,
      search: searchQuery.value,
      status: statusFilter.value === 'all' ? null : statusFilter.value,
      min_score: matchScoreFilter.value,
      category: categoryFilter.value === 'all' ? null : categoryFilter.value
    });
    
    // Safely handle the response data structure
    if (response && Array.isArray(response)) {
      matches.value = response;
    } else if (response && response.data) {
      matches.value = Array.isArray(response.data) ? response.data : (response.data.data || []);
    } else {
      matches.value = [];
    }
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to load matches');
    console.error('Failed to load matches:', err);
    matches.value = []; // Ensure matches is always an array even on error
  } finally {
    loading.value = false;
  }
}

// Update match status
async function updateMatchStatus(matchId, status) {
  try {
    await updateStatus(matchId, status);
    toast.success('Match status updated successfully');
    loadMatches();
  } catch (err) {
    toast.error(err.response?.data?.message || 'Failed to update match status');
    console.error('Failed to update match status:', err);
  }
}

// Handle pagination
function handlePageChange(page) {
  currentPage.value = page;
  loadMatches();
}

// Handle filters
function handleFilterChange() {
  currentPage.value = 1;
  loadMatches();
}

// Handle search
function handleSearch() {
  currentPage.value = 1;
  loadMatches();
}

// Reset filters
function resetFilters() {
  statusFilter.value = 'all';
  matchScoreFilter.value = 60;
  categoryFilter.value = 'all';
  searchQuery.value = '';
  currentPage.value = 1;
  handleFilterChange();
}

// Load matches on mount
onMounted(() => {
  loadMatches();
});
</script>

<script>
import MatchCard from '@components/MatchCard.vue';

export default {
    name: 'MatchesPage',
    components: {
        MatchCard
    },
    data() {
        return {
            activeTab: 'all',
            tabs: [
                { value: 'all', label: 'All' },
                { value: 'new', label: 'New' },
                { value: 'in-progress', label: 'In Progress' },
                { value: 'resolved', label: 'Resolved' }
            ]
        }
    }
}
</script>
