<!-- DiscoverPage.vue -->
<template>
    <div v-if="!isLoaded" class="min-h-screen flex items-center justify-center">
        <div class="animate-spin h-8 w-8 sm:h-10 sm:w-10 border-4 border-purple-600 border-t-transparent rounded-full"></div>
    </div>
    <div v-else class="min-h-screen flex flex-col">
        <main class="relative max-w-[90rem] mx-auto flex-1 container py-4 sm:py-6 px-4 sm:px-6 flex flex-col-reverse md:flex-row items-center">
            <div class="flex flex-col gap-4 sm:gap-6 w-full">
                <div class="flex flex-col gap-1 sm:gap-2">
                    <div class="flex justify-between items-center">
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Discover Items</h1>
                        <!-- Mobile filter button -->
                        <button 
                            class="md:hidden inline-flex items-center justify-center rounded-md bg-muted p-2 text-muted-foreground" 
                            @click="showMobileFilters = true"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                            </svg>
                            <span class="ml-2">Filters</span>
                        </button>
                    </div>
                    <p class="text-muted-foreground text-sm sm:text-base">Browse lost and found items in your area</p>
                </div>

                <!-- Tabs -->
                <div class="w-full">
                <div class="flex items-center justify-between">
                        <div class="inline-flex h-9 sm:h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground overflow-x-auto max-w-full">
                            <button
                                v-for="tab in tabs"
                                :key="tab.value"
                                @click="activeTab = tab.value"
                                :class="[
                  'inline-flex items-center justify-center whitespace-nowrap rounded-sm px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50',
                  activeTab === tab.value ? 'bg-background text-foreground shadow-sm' : ''
                ]"
                            >
                                {{ tab.label }}
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 sm:mt-6 flex gap-6">
                        <!-- Filter Sidebar - Desktop -->
                        <div class="hidden w-64 shrink-0 md:block">
                            <div class="flex flex-col gap-4 sm:gap-6 sticky top-4">
                                <div>
                                    <h3 class="mb-2 text-sm sm:text-base font-medium">Item Type</h3>
                                    <div class="space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="all" name="itemType" value="all" v-model="filters.type" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                            <label for="all" class="text-xs sm:text-sm">All Items</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="lost" name="itemType" value="lost" v-model="filters.type" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                            <label for="lost" class="text-xs sm:text-sm">Lost Items</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="found" name="itemType" value="found" v-model="filters.type" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                            <label for="found" class="text-xs sm:text-sm">Found Items</label>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="mb-2 text-sm sm:text-base font-medium">Categories</h3>
                                    <div class="space-y-2 max-h-45  pr-2">
                                        <div v-for="category in categories" :key="category.id" class="flex items-center space-x-2">
                                             <input
                                                 type="checkbox"
                                                 :id="category.id"
                                                 v-model="filters.categories[category.id]"
                                                 class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                                             >
                                             <label :for="category.id" class="text-xs sm:text-sm">{{ category.name || category.label }}</label>
                                        </div>
                                    </div>
                                </div>

                        <div>
                                    <h3 class="mb-2 text-sm sm:text-base font-medium">Date Posted</h3>
                                    <div class="space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="anytime" name="datePosted" value="anytime" v-model="filters.datePosted" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                            <label for="anytime" class="text-xs sm:text-sm">Anytime</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="today" name="datePosted" value="today" v-model="filters.datePosted" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                            <label for="today" class="text-xs sm:text-sm">Today</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="this-week" name="datePosted" value="this-week" v-model="filters.datePosted" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                            <label for="this-week" class="text-xs sm:text-sm">This Week</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="this-month" name="datePosted" value="this-month" v-model="filters.datePosted" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                            <label for="this-month" class="text-xs sm:text-sm">This Month</label>
                                        </div>
                                    </div>
                                </div>

                                <button 
                                    @click="applyFilters"
                                    class="inline-flex items-center justify-center rounded-md bg-primary px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium text-white shadow transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 mt-2"
                                >
                                    Apply Filters
                                </button>
                            </div>
                        </div>

                        <!-- Content Area -->
                        <div class="flex-1 w-full">
                            <!-- Search Mode: show only search results -->
                            <div v-if="route.query.q" class="flex flex-col items-center justify-center w-full py-6 sm:py-12 text-center">
                                <div v-if="allItems.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6 w-full">
                                    <ItemCard v-for="item in allItems" :key="item.id" :item="item" @click="openItemDetail(item.id)" />
                                </div>
                                <div v-else class="flex flex-col items-center justify-center py-8 sm:py-12 text-center w-full">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 sm:h-16 sm:w-16 text-muted-foreground mb-3 sm:mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                  </svg>
                                    <h3 class="text-base sm:text-lg font-medium">No items found</h3>
                                    
                                    <p class="text-muted-foreground text-sm mt-1">Try adjusting your filters or check back later.</p>
                                    <button @click="showCreateItemForm" class="mt-3 sm:mt-4 inline-flex items-center justify-center rounded-md bg-primary px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium text-white shadow transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                       Create New Item
                                    </button>
                                </div>
                            </div>
                            <!-- Tab Mode: show tabs when not searching -->
                            <div v-else>
                                <!-- All Items Tab Content -->
                                <div v-if="activeTab === 'all'">
                                    <div v-if="isLoading" class="flex justify-center items-center py-8 sm:py-12">
                                        <div class="animate-spin h-8 w-8 border-4 border-primary border-t-transparent rounded-full"></div>
                                    </div>
                                    <div v-else>
                                        <div v-if="featuredItems.length > 0" class="mb-6 sm:mb-8">
                                            <h2 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4">Featured Items</h2>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
                                                <ItemCard v-for="item in featuredItems" :key="item.id" :item="item" @click="openItemDetail(item.id)" />
                                            </div>
                                        </div>
                                        <div>
                                            <h2 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4">Recent Items</h2>
                                            <div v-if="recentItems.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
                                                <ItemCard v-for="item in visibleRecentItems" :key="item.id" :item="item" @click="openItemDetail(item.id)" />
                                            </div>
                                            <div v-else class="flex flex-col items-center justify-center py-8 sm:py-12 text-center w-full">
                                              <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 sm:h-16 sm:w-16 text-muted-foreground mb-3 sm:mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                              </svg>
                                              <h3 class="text-base sm:text-lg font-medium">No items found</h3>
                                              <p class="text-muted-foreground text-sm mt-1">Try adjusting your filters or check back later.</p>
                                              <button @click="showCreateItemForm" class="mt-3 sm:mt-4 inline-flex items-center justify-center rounded-md bg-primary px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium text-white shadow transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                                Create New Item
                                              </button>
                                            </div>
                                            <div v-if="recentItems.length > 0" class="flex justify-center mt-4 sm:mt-6">
                                                <button v-if="visibleCount < recentItems.length" @click="visibleCount += 6" class="inline-flex items-center justify-center rounded-md bg-primary px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium text-white shadow transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                                    Show More Items
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lost Items Tab Content -->
                                <div v-if="activeTab === 'lost'">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
                                        <item-card
                                            v-for="item in lostItems"
                                            :key="item.id"
                                            :item="item"
                                            @click="openItemDetail(item.id)"
                                        />
                                    </div>
                                </div>

                                <!-- Found Items Tab Content -->
                                <div v-if="activeTab === 'found'">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
                                        <item-card
                                            v-for="item in foundItems"
                                            :key="item.id"
                                            :item="item"
                                            @click="openItemDetail(item.id)"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Mobile Filters Drawer -->
        <div v-if="showMobileFilters" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end md:hidden">
            <div class="bg-white w-full rounded-t-xl max-h-[85vh] overflow-y-auto">
                <div class="p-4 flex flex-col gap-4">
                    <div class="flex justify-between items-center border-b pb-2">
                        <h3 class="text-lg font-semibold">Filters</h3>
                        <button @click="showMobileFilters = false" class="p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                </div>

                    <div>
                        <h4 class="mb-2 text-sm font-medium">Item Type</h4>
                        <div class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <input type="radio" id="mobile-all" name="mobileItemType" value="all" v-model="filters.type" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                <label for="mobile-all" class="text-sm">All Items</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="radio" id="mobile-lost" name="mobileItemType" value="lost" v-model="filters.type" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                <label for="mobile-lost" class="text-sm">Lost Items</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="radio" id="mobile-found" name="mobileItemType" value="found" v-model="filters.type" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                <label for="mobile-found" class="text-sm">Found Items</label>
                        </div>
                    </div>
                </div>

                    <div>
                        <h4 class="mb-2 text-sm font-medium">Categories</h4>
                        <div class="space-y-2 grid grid-cols-2">
                            <div v-for="category in categories" :key="`mobile-${category.id}`" class="flex items-center space-x-2">
                                <input
                                    type="checkbox"
                                    :id="`mobile-${category.id}`"
                                    v-model="filters.categories[category.id]"
                                    class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                                >
                                <label :for="`mobile-${category.id}`" class="text-sm">{{ category.name || category.label }}</label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="mb-2 text-sm font-medium">Date Posted</h4>
                        <div class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <input type="radio" id="mobile-anytime" name="mobileDatePosted" value="anytime" v-model="filters.datePosted" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                <label for="mobile-anytime" class="text-sm">Anytime</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="radio" id="mobile-today" name="mobileDatePosted" value="today" v-model="filters.datePosted" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                <label for="mobile-today" class="text-sm">Today</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="radio" id="mobile-this-week" name="mobileDatePosted" value="this-week" v-model="filters.datePosted" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                <label for="mobile-this-week" class="text-sm">This Week</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="radio" id="mobile-this-month" name="mobileDatePosted" value="this-month" v-model="filters.datePosted" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                <label for="mobile-this-month" class="text-sm">This Month</label>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2 sticky bottom-0 bg-white border-t mt-2">
                        <button 
                            @click="clearFilters"
                            class="flex-1 py-2 border border-gray-300 rounded-md text-sm"
                        >
                            Clear All
                        </button>
                        <button 
                            @click="applyMobileFilters"
                            class="flex-1 bg-primary text-white py-2 rounded-md text-sm"
                        >
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Detail Modal -->
        <div v-if="showItemDetail" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-2 sm:p-4 overflow-y-auto">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[95vh] sm:max-h-[90vh] overflow-y-auto">
                <ItemDetail 
                  :itemId="selectedItemId" 
                  @close="closeItemDetail" 
                  @update="refreshItems"
                />
            </div>
        </div>

        <!-- Create/Edit Item Modal -->
        <div v-if="showCreateItemForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-2 sm:p-4 overflow-y-auto">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-3 sm:p-6">
                <ItemForm 
                  @submit="handleItemSubmit" 
                  @cancel="closeCreateForm"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import ItemCard from '@/components/ItemCard.vue'
import ItemDetail from '@/components/ItemDetail.vue'
import ItemForm from '@/components/ItemForm.vue'
import itemService from '@/services/item.service'
import categoryService from '@/services/category.service'

const route = useRoute()
const router = useRouter()
const items = ref([])
const isLoading = ref(true)
const isLoaded = ref(false)
const showLocationPrompt = ref(true)
const showItemDetail = ref(false)
const showCreateItemForm = ref(false)
const selectedItem = ref(null)
const selectedItemId = ref(null)

const activeTab = ref('all')
// Define tabs for the UI
const tabs = [
  { value: 'all', label: 'All Items' },
  { value: 'lost', label: 'Lost Items' },
  { value: 'found', label: 'Found Items' }
]
const filters = ref({
  type: 'all',
  category_id: null,
  distance: 5,
  datePosted: 'anytime',
  categories: {},
  search: '',
  location: ''
})

const categories = ref([])

// Computed properties
const allItems = computed(() => items.value)
const lostItems = computed(() => items.value.filter(item => item.type === 'lost'))
const foundItems = computed(() => items.value.filter(item => item.type === 'found'))
const featuredItems = computed(() => items.value.filter(item => item.featured))
const recentItems = computed(() => items.value.filter(item => !item.featured))
const visibleCount = ref(6)
const visibleRecentItems = computed(() => recentItems.value.slice(0, visibleCount.value))

// Fetch initial data
onMounted(async () => {
  try {
    await fetchCategories()
    // initialize search from URL and fetch items
    filters.value.search = route.query.q || ''
    applyFilters()
    isLoaded.value = true
  } catch (error) {
    console.error('Error initializing data:', error)
  } finally {
    isLoading.value = false
  }
})

// Watch navbar query to trigger search
watch(() => route.query.q, (q) => {
  filters.value.search = q || ''
  applyFilters()
})

// Methods for fetching data
const fetchItems = async (customParams) => {
  try {
    isLoading.value = true
    console.log('Fetching items...')
    const params = customParams || {}
    
    // If no custom params are provided, apply filters from state
    if (!customParams) {
      // Apply filters
      if (filters.value.type !== 'all') {
        params.type = filters.value.type
      }
      
      if (filters.value.category_id) {
        params.category_id = filters.value.category_id
      }
      
      // Always filter by active status for discover page
      params.status = 'active'
    }
    
    console.log('Items request params:', params)
    const response = await itemService.getItems(params)
    console.log('Items response:', response)
    // Log the actual data structure to understand the API response format
    console.log('Raw API response data:', JSON.stringify(response.data, null, 2))
    
    // Check different possible data formats and handle appropriately
    if (Array.isArray(response.data)) {
      items.value = response.data
    } else if (response.data?.items && Array.isArray(response.data.items)) {
      items.value = response.data.items
    } else if (response.data?.data && Array.isArray(response.data.data)) {
      // Handle Laravel resource collections format
      items.value = response.data.data
    } else {
      // If all else fails, look for any array in the response
      const anyArrayProperty = Object.keys(response.data || {}).find(key => 
        Array.isArray(response.data[key]))
      
      if (anyArrayProperty) {
        items.value = response.data[anyArrayProperty]
      } else {
        console.warn('Could not find items array in API response')
        items.value = []  
      }
    }
    console.log('Items processed:', items.value)
  } catch (error) {
    console.error('Error fetching items:', error)
    items.value = []
  } finally {
    isLoading.value = false
  }
}

const fetchCategories = async () => {
  try {
    console.log('Fetching categories...')
    const response = await categoryService.getCategories()
    console.log('Categories response:', response)
    categories.value = response || []
    
    // Initialize filters.categories object for each category
    if (!filters.value.categories) {
      filters.value.categories = {}
    }
    
    categories.value.forEach(cat => {
      // Make sure we're using a proper category ID as the key
      const categoryId = cat.id ? cat.id : cat._id || cat.category_id
      if (categoryId) {
        filters.value.categories[categoryId] = false
      }
    })
    
    console.log('Categories initialized:', categories.value)
    console.log('Category filters initialized:', filters.value.categories)
  } catch (error) {
    console.error('Error fetching categories:', error)
  }
}

const refreshItems = () => {
  applyFilters();
}

const applyFilters = () => {
  // Build filter parameters based on selections
  const params = {
    status: 'active'
  }
  
  if (filters.value.search) params.search = filters.value.search
  if (filters.value.location) params.location = filters.value.location
  
  // Handle item type filter
  if (filters.value.type !== 'all') {
    params.type = filters.value.type
  }
  
  // Handle category filters
  const selectedCategories = Object.entries(filters.value.categories || {})
    .filter(([_, selected]) => selected)
    .map(([id]) => id)
  
  if (selectedCategories.length > 0) {
    params.category_ids = selectedCategories.join(',')
}

  // Handle date posted filter
  if (filters.value.datePosted !== 'anytime') {
    params.date_filter = filters.value.datePosted
  }
  
  console.log('Applying filters:', params)
  fetchItems(params)
}

const openItemDetail = (itemId) => {
  showItemDetail.value = true
  selectedItemId.value = itemId
  selectedItem.value = items.value.find(item => item.id === itemId)
}

const closeItemDetail = () => {
  showItemDetail.value = false
  selectedItem.value = null
  selectedItemId.value = null
}

// Open create item form
const showCreateForm = () => {
  showCreateItemForm.value = true
}

// Close create item form
const closeCreateForm = () => {
  showCreateItemForm.value = false
}

// Properly handle item creation with refreshing data
const handleItemSubmit = async (item) => {
  await refreshItems()
  closeCreateForm()
}

const navigateToEdit = (item) => {
  router.push({ name: 'MyItems', query: { editItemId: item.id } });
}

const enableLocation = () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        // In a real app, you would use a geocoding service API
        // For now, just hide the prompt
        showLocationPrompt.value = false

        // You could show a success message or update the UI
      },
      (error) => {
        console.error('Error getting location:', error)
        showLocationPrompt.value = false
      }
    )
  } else {
    showLocationPrompt.value = false
  }
}

// Add mobile filter state
const showMobileFilters = ref(false);

// Add clearFilters function
function clearFilters() {
    filters.value.type = 'all';
    filters.value.datePosted = 'anytime';
    Object.keys(filters.value.categories).forEach(key => {
        filters.value.categories[key] = false;
});
}

// Apply filters from mobile view
function applyMobileFilters() {
    applyFilters();
    showMobileFilters.value = false;
}
</script>
