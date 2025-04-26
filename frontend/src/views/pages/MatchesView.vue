<!-- MatchesPage.vue -->
<template>
    <div v-if="!isLoaded" class="min-h-screen flex items-center justify-center">
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


                    <a href="/dashboard/new-item">
                        <button class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white shadow transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                                <path d="M5 12h14"></path>
                                <path d="M12 5v14"></path>
                            </svg>
                            New Listing
                        </button>
                    </a>
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
                        <span class="text-sm text-muted-foreground hidden sm:inline">Sort by:</span>
                        <div class="relative">
                            <select v-model="sortBy" class="h-9 w-[180px] rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                                <option value="match-score">Match Score</option>
                                <option value="date-new">Date (Newest)</option>
                                <option value="date-old">Date (Oldest)</option>
                                <option value="status">Status</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Filters Panel -->
                <div v-if="showFilters" class="mb-6 rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="p-6">
                        <div class="grid gap-6 md:grid-cols-3">
                            <div class="space-y-2">
                                <h3 class="text-sm font-medium">Status</h3>
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="flex items-center space-x-2">
                                        <div class="relative inline-flex h-4 w-8 items-center rounded-full bg-muted transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=checked]:bg-primary" :class="{ 'bg-primary': filters.status.new }">
                                            <span class="inline-block h-3 w-3 rounded-full bg-white transition-transform data-[state=checked]:translate-x-4" :class="{ 'translate-x-4': filters.status.new }" @click="filters.status.new = !filters.status.new"></span>
                                        </div>
                                        <label class="text-sm cursor-pointer" @click="filters.status.new = !filters.status.new">
                                            New
                                        </label>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="relative inline-flex h-4 w-8 items-center rounded-full bg-muted transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=checked]:bg-primary" :class="{ 'bg-primary': filters.status.inProgress }">
                                            <span class="inline-block h-3 w-3 rounded-full bg-white transition-transform data-[state=checked]:translate-x-4" :class="{ 'translate-x-4': filters.status.inProgress }" @click="filters.status.inProgress = !filters.status.inProgress"></span>
                                        </div>
                                        <label class="text-sm cursor-pointer" @click="filters.status.inProgress = !filters.status.inProgress">
                                            In Progress
                                        </label>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="relative inline-flex h-4 w-8 items-center rounded-full bg-muted transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=checked]:bg-primary" :class="{ 'bg-primary': filters.status.resolved }">
                                            <span class="inline-block h-3 w-3 rounded-full bg-white transition-transform data-[state=checked]:translate-x-4" :class="{ 'translate-x-4': filters.status.resolved }" @click="filters.status.resolved = !filters.status.resolved"></span>
                                        </div>
                                        <label class="text-sm cursor-pointer" @click="filters.status.resolved = !filters.status.resolved">
                                            Resolved
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <h3 class="text-sm font-medium">Match Score</h3>
                                <input type="range" min="0" max="100" step="5" v-model="filters.matchScore" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <div class="flex justify-between text-xs text-muted-foreground">
                                    <span>0%</span>
                                    <span>{{ filters.matchScore }}%</span>
                                    <span>100%</span>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <h3 class="text-sm font-medium">Categories</h3>
                                <div class="relative">
                                    <select v-model="filters.category" class="w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
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
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
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
                </div>

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
                        <match-card v-for="match in matchedItems" :key="match.id" :match="match" />
                    </div>

                    <!-- New Matches Tab -->
                    <div v-if="activeTab === 'new'" class="space-y-6">
                        <match-card v-for="match in newMatches" :key="match.id" :match="match" />
                    </div>

                    <!-- In Progress Matches Tab -->
                    <div v-if="activeTab === 'in-progress'" class="space-y-6">
                        <match-card v-for="match in inProgressMatches" :key="match.id" :match="match" />
                    </div>

                    <!-- Resolved Matches Tab -->
                    <div v-if="activeTab === 'resolved'" class="space-y-6">
                        <match-card v-for="match in resolvedMatches" :key="match.id" :match="match" />
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
import { ref, onMounted, nextTick } from 'vue'
import { useAuthStore } from '@/stores/auth.store'

// Ensure authentication state is properly loaded
const authStore = useAuthStore()

// Track component loading state
const isLoaded = ref(false)
const sortBy = ref('match-score')
const showFilters = ref(false)

// Force component to load data on mount
onMounted(async () => {
  // Simulate data loading
  await nextTick()
  isLoaded.value = true
})

// Status label helper function
function statusLabel(status) {
  switch (status) {
    case 'new': return 'New match';
    case 'in-progress': return 'In Progress';
    case 'resolved': return 'Resolved';
    default: return '';
  }
}

// Date formatting helper
function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString();
}
</script>

<script>
import MatchService from '@/services/match.service';

export default {
    name: 'MatchesPage',
    components: {
        MatchCard: {
            props: ['match'],
            template: `
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-hidden">
          <div class="p-4 pb-3 border-b">
            <div class="flex items-center justify-between">
              <div class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold"
                   :class="{
                     'bg-primary/10 text-primary': match.status === 'new',
                     'bg-orange-500/10 text-orange-500': match.status === 'in-progress',
                     'bg-secondary/10 text-secondary': match.status === 'resolved'
                   }">
                {{ statusLabel(match.status) }}
              </div>
              <div class="flex items-center gap-2">
                <span class="text-sm font-medium"
                      :class="{
                        'text-secondary': match.matchScore >= 90,
                        'text-primary': match.matchScore >= 80 && match.matchScore < 90,
                        'text-orange-500': match.matchScore >= 70 && match.matchScore < 80,
                        'text-red-500': match.matchScore < 70
                      }">
                  {{ match.matchScore }}% Match
                </span>
                <div class="h-2 w-14 rounded-full bg-gray-200 overflow-hidden">
                  <div class="h-full rounded-full"
                       :class="{
                         'bg-secondary': match.matchScore >= 90,
                         'bg-primary': match.matchScore >= 80 && match.matchScore < 90,
                         'bg-orange-500': match.matchScore >= 70 && match.matchScore < 80,
                         'bg-red-500': match.matchScore < 70
                       }"
                       :style="{ width: match.matchScore + '%' }">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
            <!-- Lost Item -->
            <div class="p-4 border-b md:border-b-0 md:border-r">
              <div class="flex items-center gap-2 mb-3">
                <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold bg-red-500/10 text-red-500 border-red-500/20">
                  Lost
                </div>
                <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold border-input">
                  {{ match.lostItem.category }}
                </div>
              </div>

              <h3 class="font-semibold text-base mb-1">{{ match.lostItem.title }}</h3>

              <div class="flex items-center gap-1 text-xs text-muted-foreground mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
                  <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <span>{{ match.lostItem.location }}</span>
              </div>

              <p class="text-sm line-clamp-2 text-muted-foreground mb-3">{{ match.lostItem.description }}</p>

              <div class="text-xs text-muted-foreground mb-3">
                {{ formatDate(match.lostItem.date) }} at {{ match.lostItem.time }}
              </div>

              <div class="flex items-center gap-2">
                <div class="relative flex h-6 w-6 shrink-0 overflow-hidden rounded-full">
                  <img v-if="match.lostItem.user.avatar" :src="match.lostItem.user.avatar" :alt="match.lostItem.user.name" class="aspect-square h-full w-full">
                  <div v-else class="flex h-full w-full items-center justify-center rounded-full bg-muted">
                    {{ match.lostItem.user.initials }}
                  </div>
                </div>
                <span class="text-xs">{{ match.lostItem.user.name }}</span>
              </div>
            </div>

            <!-- Found Item -->
            <div class="p-4">
              <div class="flex items-center gap-2 mb-3">
                <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold bg-secondary/10 text-secondary border-secondary/20">
                  Found
                </div>
                <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold border-input">
                  {{ match.foundItem.category }}
                </div>
              </div>

              <h3 class="font-semibold text-base mb-1">{{ match.foundItem.title }}</h3>

              <div class="flex items-center gap-1 text-xs text-muted-foreground mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
                  <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <span>{{ match.foundItem.location }}</span>
              </div>

              <p class="text-sm line-clamp-2 text-muted-foreground mb-3">{{ match.foundItem.description }}</p>

              <div class="text-xs text-muted-foreground mb-3">
                {{ formatDate(match.foundItem.date) }} at {{ match.foundItem.time }}
              </div>

              <div class="flex items-center gap-2">
                <div class="relative flex h-6 w-6 shrink-0 overflow-hidden rounded-full">
                  <img v-if="match.foundItem.user.avatar" :src="match.foundItem.user.avatar" :alt="match.foundItem.user.name" class="aspect-square h-full w-full">
                  <div v-else class="flex h-full w-full items-center justify-center rounded-full bg-muted">
                    {{ match.foundItem.user.initials }}
                  </div>
                </div>
                <span class="text-xs">{{ match.foundItem.user.name }}</span>
              </div>
            </div>
          </div>

          <!-- Matching attributes -->
          <div class="px-4 py-3 bg-muted/50">
            <div class="flex flex-wrap gap-2">
              <span class="text-xs text-muted-foreground">Matching Attributes:</span>
              <div v-for="(attr, idx) in match.matchingAttributes" :key="idx"
                   class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold bg-primary/10 text-primary border-primary/20">
                {{ attr }}
              </div>
            </div>
          </div>

          <div class="flex justify-between p-4 border-t">
            <div class="flex gap-2">
              <button class="inline-flex items-center justify-center rounded-md border border-input bg-background px-3 py-2 text-xs font-medium h-8 shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50">
                <span>Details</span>
              </button>
              <button class="inline-flex items-center justify-center rounded-md border border-input bg-background px-3 py-2 text-xs font-medium h-8 shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <span>Contact</span>
              </button>
            </div>

            <div class="flex gap-2">
              <template v-if="match.status === 'new'">
                <button class="inline-flex items-center justify-center rounded-md border border-input bg-background w-8 h-8 p-0 shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-red-500">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                  </svg>
                </button>
                <button class="inline-flex items-center justify-center rounded-md border border-input bg-background w-8 h-8 p-0 shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-secondary">
                    <path d="M20 6 9 17l-5-5"></path>
                  </svg>
                </button>
              </template>
              <button v-if="match.status === 'in-progress'" class="inline-flex items-center justify-center rounded-md border border-input bg-background px-3 py-2 text-xs font-medium h-8 shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50">
                <span>Mark as Resolved</span>
              </button>
              <button v-if="match.status === 'resolved'" class="inline-flex items-center justify-center rounded-md text-xs h-8 px-3 gap-1 text-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
                  <path d="M20 6 9 17l-5-5"></path>
                </svg>
                <span>Resolved</span>
              </button>
            </div>
          </div>
        </div>
      `,
            methods: {
                statusLabel(status) {
                    switch (status) {
                        case 'new': return 'New match';
                        case 'in-progress': return 'In Progress';
                        case 'resolved': return 'Resolved';
                        default: return '';
                    }
                },
                formatDate(dateString) {
                    return new Date(dateString).toLocaleDateString();
                }
            }
        }
    },
    data() {
        return {
            mobileMenuOpen: false,
            userMenuOpen: false,
            showFilters: false,
            activeTab: 'all',
            sortBy: 'match-score',
            tabs: [
                { value: 'all', label: 'All' },
                { value: 'new', label: 'New' },
                { value: 'in-progress', label: 'In Progress' },
                { value: 'resolved', label: 'Resolved' }
            ],
            filters: {
                status: {
                    new: true,
                    inProgress: true,
                    resolved: true
                },
                matchScore: 60,
                category: 'all'
            },
            matchedItems: []
        }
    },
    created() {
        MatchService.getMatches()
            .then(response => { this.matchedItems = response.data; })
            .catch(error => console.error('Error loading matches:', error));
    },
    computed: {
        newMatches() {
            return this.matchedItems.filter(match => match.status === 'new');
        },
        inProgressMatches() {
            return this.matchedItems.filter(match => match.status === 'in-progress');
        },
        resolvedMatches() {
            return this.matchedItems.filter(match => match.status === 'resolved');
        }
    },
    methods: {
        resetFilters() {
            this.filters = {
                status: {
                    new: true,
                    inProgress: true,
                    resolved: true
                },
                matchScore: 60,
                category: 'all'
            };
        }
    }
}
</script>
