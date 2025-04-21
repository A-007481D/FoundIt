<!-- DiscoverPage.vue -->
<template>
    <div class="min-h-screen flex flex-col">

        <main class="max-w-[90rem] mx-auto flex-1 container py-6 flex flex-col-reverse md:flex-row items-center">
            <div class="flex flex-col gap-6">
                <div class="flex flex-col gap-2">
                    <h1 class="text-3xl font-bold tracking-tight">Discover Items</h1>
                    <p class="text-muted-foreground">Browse lost and found items in your area</p>
                </div>

                <!-- Tabs -->
                <div class="w-full">
                    <div class="flex items-center justify-between">
                        <div class="inline-flex h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground">
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
                        <div class="hidden items-center gap-2 md:flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-muted-foreground">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <span class="text-sm text-muted-foreground">New York, NY (5 mile radius)</span>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-6">
                        <!-- Filter Sidebar -->
                        <div class="hidden w-64 shrink-0 md:block">
                            <div class="flex flex-col gap-6">
                                <div>
                                    <h3 class="mb-2 font-medium">Item Type</h3>
                                    <div class="space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="all" name="itemType" value="all" v-model="filters.itemType" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                            <label for="all" class="text-sm">All Items</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="lost" name="itemType" value="lost" v-model="filters.itemType" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                            <label for="lost" class="text-sm">Lost Items</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="found" name="itemType" value="found" v-model="filters.itemType" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                            <label for="found" class="text-sm">Found Items</label>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="mb-2 font-medium">Categories</h3>
                                    <div class="space-y-2">
                                        <div v-for="category in categories" :key="category.id" class="flex items-center space-x-2">
                                            <input
                                                type="checkbox"
                                                :id="category.id"
                                                v-model="filters.categories[category.id]"
                                                class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                                            >
                                            <label :for="category.id" class="text-sm">{{ category.label }}</label>
                                        </div>
                                    </div>
                                </div>

                                <!--                <div>-->
                                <!--                  <h3 class="mb-2 font-medium">Distance (miles)</h3>-->
                                <!--                  <input-->
                                <!--                      type="range"-->
                                <!--                      min="1"-->
                                <!--                      max="50"-->
                                <!--                      v-model="filters.distance"-->
                                <!--                      class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"-->
                                <!--                  >-->
                                <!--                  <div class="flex items-center justify-between mt-2">-->
                                <!--                    <span class="text-sm text-muted-foreground">1 mile</span>-->
                                <!--                    <span class="font-medium text-sm">{{ filters.distance }} miles</span>-->
                                <!--                    <span class="text-sm text-muted-foreground">50 miles</span>-->
                                <!--                  </div>-->
                                <!--                </div>-->

                                <div>
                                    <h3 class="mb-2 font-medium">Date Posted</h3>
                                    <div class="space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="anytime" name="datePosted" value="anytime" v-model="filters.datePosted" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                            <label for="anytime" class="text-sm">Anytime</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="today" name="datePosted" value="today" v-model="filters.datePosted" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                            <label for="today" class="text-sm">Today</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="this-week" name="datePosted" value="this-week" v-model="filters.datePosted" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                            <label for="this-week" class="text-sm">This Week</label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" id="this-month" name="datePosted" value="this-month" v-model="filters.datePosted" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                            <label for="this-month" class="text-sm">This Month</label>
                                        </div>
                                    </div>
                                </div>

                                <button class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white shadow transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 mt-2">
                                    Apply Filters
                                </button>
                            </div>
                        </div>

                        <!-- Content Area -->
                        <div class="flex-1">
                            <!-- All Items Tab Content -->
                            <div v-if="activeTab === 'all'">
                                <div class="mb-8">
                                    <div class="mb-4 flex items-center justify-between">
                                        <h2 class="text-xl font-semibold">Featured Items</h2>
                                        <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3 text-primary gap-1">
                                            View all
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                                <path d="M5 12h14"></path>
                                                <path d="m12 5 7 7-7 7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                        <item-card
                                            v-for="item in featuredItems"
                                            :key="item.id"
                                            :item="item"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <div class="mb-4 flex items-center justify-between">
                                        <h2 class="text-xl font-semibold">Recent Items</h2>
                                        <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3 text-primary gap-1">
                                            View all
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                                <path d="M5 12h14"></path>
                                                <path d="m12 5 7 7-7 7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                        <item-card
                                            v-for="item in recentItems"
                                            :key="item.id"
                                            :item="item"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Lost Items Tab Content -->
                            <div v-if="activeTab === 'lost'">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    <item-card
                                        v-for="item in lostItems"
                                        :key="item.id"
                                        :item="item"
                                    />
                                </div>
                            </div>

                            <!-- Found Items Tab Content -->
                            <div v-if="activeTab === 'found'">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    <item-card
                                        v-for="item in foundItems"
                                        :key="item.id"
                                        :item="item"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Location Permission -->
        <!--    <div v-if="showLocationPrompt" class="fixed bottom-4 left-0 right-0 z-40 mx-auto w-full max-w-md rounded-lg border bg-white p-4 shadow-lg md:bottom-8">-->
        <!--      <div class="flex items-start justify-between">-->
        <!--        <div class="flex items-start gap-3">-->
        <!--          <div class="rounded-full bg-primary/10 p-2">-->
        <!--            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-primary">-->
        <!--              <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>-->
        <!--              <circle cx="12" cy="10" r="3"></circle>-->
        <!--            </svg>-->
        <!--          </div>-->
        <!--          <div>-->
        <!--            <h3 class="font-medium">Enable location services</h3>-->
        <!--            <p class="mt-1 text-sm text-muted-foreground">See lost & found items near you by enabling location access.</p>-->
        <!--          </div>-->
        <!--        </div>-->
        <!--        <button @click="showLocationPrompt = false" class="rounded-md p-1 text-muted-foreground hover:bg-muted">-->
        <!--          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">-->
        <!--            <path d="M18 6 6 18"></path>-->
        <!--            <path d="m6 6 12 12"></path>-->
        <!--          </svg>-->
        <!--          <span class="sr-only">Dismiss</span>-->
        <!--        </button>-->
        <!--      </div>-->
        <!--      <div class="mt-4 flex gap-2">-->
        <!--        <button @click="showLocationPrompt = false" class="inline-flex items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 flex-1">Not now</button>-->
        <!--        <button @click="enableLocation" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white shadow transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 flex-1">Enable location</button>-->
        <!--      </div>-->
        <!--    </div>-->
    </div>
</template>

<!--<script>-->
<!--export default {-->
<!--  name: 'DiscoverPage',-->
<!--  components: {-->
<!--    ItemCard: {-->
<!--      props: ['item'],-->
<!--      template: `-->
<!--        <a :href="'/items/' + item.id" class="group h-full overflow-hidden rounded-lg border border-border bg-white transition-all hover:shadow-md" :class="{ 'border-2 border-primary': item.featured }">-->
<!--          <div class="relative aspect-video overflow-hidden">-->
<!--            <img :src="item.image" :alt="item.title" class="h-full w-full object-cover transition-transform group-hover:scale-105">-->
<!--            <div class="absolute left-2 top-2 z-10 rounded-md px-2 py-1 text-xs font-semibold uppercase"-->
<!--                 :class="item.type === 'lost' ? 'bg-red-100 text-red-500' : 'bg-green-100 text-green-600'">-->
<!--              {{ item.type }}-->
<!--            </div>-->
<!--            <div v-if="item.featured" class="absolute right-2 top-2 z-10 rounded-md bg-purple-100 px-2 py-1 text-xs font-semibold text-primary-dark">-->
<!--              Featured-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="p-4">-->
<!--            <div class="flex items-start justify-between">-->
<!--              <h3 class="line-clamp-1 font-semibold">{{ item.title }}</h3>-->
<!--              <span class="ml-2 shrink-0 rounded-full bg-gray-100 px-2 py-0.5 text-xs text-muted-foreground">{{ item.category }}</span>-->
<!--            </div>-->
<!--            <p class="line-clamp-2 mt-2 text-sm text-muted-foreground">{{ item.description }}</p>-->
<!--            <div class="mt-4 flex items-center justify-between text-xs text-muted-foreground">-->
<!--              <div class="flex items-center gap-1">-->
<!--                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">-->
<!--                  <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>-->
<!--                  <circle cx="12" cy="10" r="3"></circle>-->
<!--                </svg>-->
<!--                <span>{{ item.location }}</span>-->
<!--              </div>-->
<!--              <div class="flex items-center gap-1">-->
<!--                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">-->
<!--                  <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>-->
<!--                  <line x1="16" x2="16" y1="2" y2="6"></line>-->
<!--                  <line x1="8" x2="8" y1="2" y2="6"></line>-->
<!--                  <line x1="3" x2="21" y1="10" y2="10"></line>-->
<!--                </svg>-->
<!--                <span>{{ item.date }}</span>-->
<!--              </div>-->
<!--              <div class="flex items-center gap-1">-->
<!--                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">-->
<!--                  <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>-->
<!--                  <circle cx="12" cy="12" r="3"></circle>-->
<!--                </svg>-->
<!--                <span>{{ item.views }}</span>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </a>-->
<!--      `-->
<!--    }-->
<!--  },-->
<!--  data() {-->
<!--    return {-->
<!--      activeTab: 'all',-->
<!--      showLocationPrompt: true,-->
<!--      tabs: [-->
<!--        { value: 'all', label: 'All Items' },-->
<!--        { value: 'lost', label: 'Lost Items' },-->
<!--        { value: 'found', label: 'Found Items' }-->
<!--      ],-->
<!--      filters: {-->
<!--        itemType: 'all',-->
<!--        categories: {},-->
<!--        distance: 5,-->
<!--        datePosted: 'anytime'-->
<!--      },-->
<!--      categories: [-->
<!--        { id: "electronics", label: "Electronics" },-->
<!--        { id: "jewelry", label: "Jewelry" },-->
<!--        { id: "pets", label: "Pets" },-->
<!--        { id: "documents", label: "Documents" },-->
<!--        { id: "clothing", label: "Clothing" },-->
<!--        { id: "accessories", label: "Accessories" },-->
<!--        { id: "keys", label: "Keys" },-->
<!--        { id: "other", label: "Other" },-->
<!--      ],-->
<!--      featuredItems: [-->
<!--        {-->
<!--          id: "1",-->
<!--          title: "iPhone 13 Pro - Space Gray",-->
<!--          description:-->
<!--              "Lost my iPhone at Central Park yesterday afternoon. It has a black case with a photo of my dog on the back.",-->
<!--          image: "/placeholder.svg?height=200&width=300",-->
<!--          type: "lost",-->
<!--          category: "Electronics",-->
<!--          location: "Central Park, NY",-->
<!--          date: "2 hours ago",-->
<!--          views: 124,-->
<!--          featured: true,-->
<!--        },-->
<!--        {-->
<!--          id: "2",-->
<!--          title: "Gold Ring with Diamond",-->
<!--          description: "Found a gold ring with a small diamond near the subway entrance at Times Square station.",-->
<!--          image: "/placeholder.svg?height=200&width=300",-->
<!--          type: "found",-->
<!--          category: "Jewelry",-->
<!--          location: "Times Square, NY",-->
<!--          date: "5 hours ago",-->
<!--          views: 87,-->
<!--          featured: true,-->
<!--        },-->
<!--        {-->
<!--          id: "3",-->
<!--          title: "Brown Leather Wallet",-->
<!--          description: "Lost my wallet somewhere between the coffee shop and the library. Contains ID and credit cards.",-->
<!--          image: "/placeholder.svg?height=200&width=300",-->
<!--          type: "lost",-->
<!--          category: "Accessories",-->
<!--          location: "Downtown, NY",-->
<!--          date: "Yesterday",-->
<!--          views: 56,-->
<!--          featured: true,-->
<!--        },-->
<!--      ],-->
<!--      recentItems: [-->
<!--        {-->
<!--          id: "4",-->
<!--          title: "Car Keys with Red Keychain",-->
<!--          description:-->
<!--              "Lost my car keys with a distinctive red keychain and Volkswagen logo. Last seen at the shopping mall.",-->
<!--          image: "/placeholder.svg?height=200&width=300",-->
<!--          type: "lost",-->
<!--          category: "Keys",-->
<!--          location: "Shopping Mall, NY",-->
<!--          date: "3 hours ago",-->
<!--          views: 42,-->
<!--        },-->
<!--        {-->
<!--          id: "5",-->
<!--          title: "Black Backpack - North Face",-->
<!--          description: "Found a black North Face backpack at the park bench. Contains some books and a water bottle.",-->
<!--          image: "/placeholder.svg?height=200&width=300",-->
<!--          type: "found",-->
<!--          category: "Accessories",-->
<!--          location: "City Park, NY",-->
<!--          date: "4 hours ago",-->
<!--          views: 31,-->
<!--        },-->
<!--        {-->
<!--          id: "6",-->
<!--          title: "Prescription Glasses",-->
<!--          description:-->
<!--              "Found prescription glasses with tortoise shell frames on the subway. Left them with the station attendant.",-->
<!--          image: "/placeholder.svg?height=200&width=300",-->
<!--          type: "found",-->
<!--          category: "Accessories",-->
<!--          location: "Subway Station, NY",-->
<!--          date: "6 hours ago",-->
<!--          views: 28,-->
<!--        },-->
<!--        {-->
<!--          id: "7",-->
<!--          title: "Blue Umbrella",-->
<!--          description:-->
<!--              "Lost my blue umbrella at the coffee shop on Main Street. It has a wooden handle with my initials (JD) carved on it.",-->
<!--          image: "/placeholder.svg?height=200&width=300",-->
<!--          type: "lost",-->
<!--          category: "Other",-->
<!--          location: "Main Street, NY",-->
<!--          date: "Yesterday",-->
<!--          views: 19,-->
<!--        },-->
<!--        {-->
<!--          id: "8",-->
<!--          title: "Passport - Canadian",-->
<!--          description:-->
<!--              "Found a Canadian passport near the international terminal at the airport. Turned it in to airport security.",-->
<!--          image: "/placeholder.svg?height=200&width=300",-->
<!--          type: "found",-->
<!--          category: "Documents",-->
<!--          location: "Airport, NY",-->
<!--          date: "Yesterday",-->
<!--          views: 45,-->
<!--        },-->
<!--        {-->
<!--          id: "9",-->
<!--          title: "AirPods Pro with Case",-->
<!--          description: "Lost my AirPods Pro with the charging case at the gym. The case has a blue silicone cover.",-->
<!--          image: "/placeholder.svg?height=200&width=300",-->
<!--          type: "lost",-->
<!--          category: "Electronics",-->
<!--          location: "Fitness Center, NY",-->
<!--          date: "2 days ago",-->
<!--          views: 67,-->
<!--        },-->
<!--      ]-->
<!--    }-->
<!--  },-->
<!--  computed: {-->
<!--    allItems() {-->
<!--      return [...this.featuredItems, ...this.recentItems];-->
<!--    },-->
<!--    lostItems() {-->
<!--      return this.allItems.filter(item => item.type === 'lost');-->
<!--    },-->
<!--    foundItems() {-->
<!--      return this.allItems.filter(item => item.type === 'found');-->
<!--    }-->
<!--  },-->
<!--  methods: {-->
<!--    enableLocation() {-->
<!--      if (navigator.geolocation) {-->
<!--        navigator.geolocation.getCurrentPosition(-->
<!--            (position) => {-->
<!--              // In a real app, you would use a geocoding service API-->
<!--              // For now, just hide the prompt-->
<!--              this.showLocationPrompt = false;-->

<!--              // You could show a success message or update the UI-->
<!--              console.log("Location enabled successfully");-->
<!--            },-->
<!--            (error) => {-->
<!--              console.error("Error getting location:", error);-->
<!--              // You could show an error message-->
<!--            }-->
<!--        );-->
<!--      } else {-->
<!--        console.error("Geolocation is not supported by this browser");-->
<!--        // You could show an error message-->
<!--      }-->
<!--    }-->
<!--  }-->
<!--}-->
<!--</script>-->


<script setup>
import { ref, computed, defineComponent } from 'vue'
import ItemCard from '@/components/ItemCard.vue'
const activeTab = ref('all')
const showLocationPrompt = ref(true)

const tabs = [
    { value: 'all', label: 'All Items' },
    { value: 'lost', label: 'Lost Items' },
    { value: 'found', label: 'Found Items' }
]

const filters = ref({
    itemType: 'all',
    categories: {},
    distance: 5,
    datePosted: 'anytime'
})

const categories = [
    { id: "electronics", label: "Electronics" },
    { id: "jewelry", label: "Jewelry" },
    { id: "documents", label: "Documents" },
    { id: "clothing", label: "Clothing" },
    { id: "accessories", label: "Accessories" },
    { id: "keys", label: "Keys" },
    { id: "other", label: "Other" },
]

const featuredItems = ref([
    {
        id: "1",
        title: "iPhone 13 Pro - Space Gray",
        description:
            "Lost my iPhone at Central Park yesterday afternoon. It has a black case with a photo of my dog on the back.",
        image: "/placeholder.svg?height=200&width=300",
        type: "lost",
        category: "Electronics",
        location: "Central Park, NY",
        date: "2 hours ago",
        views: 124,
        featured: true,
    },
    {
        id: "2",
        title: "Gold Ring with Diamond",
        description: "Found a gold ring with a small diamond near the subway entrance at Times Square station.",
        image: "/placeholder.svg?height=200&width=300",
        type: "found",
        category: "Jewelry",
        location: "Times Square, NY",
        date: "5 hours ago",
        views: 87,
        featured: true,
    }
])

const recentItems = ref([
    {
        id: "4",
        title: "Car Keys with Red Keychain",
        description:
            "Lost my car keys with a distinctive red keychain and Volkswagen logo. Last seen at the shopping mall.",
        image: "/placeholder.svg?height=200&width=300",
        type: "lost",
        category: "Keys",
        location: "Shopping Mall, NY",
        date: "3 hours ago",
        views: 42,
    },
])

const allItems = computed(() => [...featuredItems.value, ...recentItems.value])
const lostItems = computed(() => allItems.value.filter(item => item.type === 'lost'))
const foundItems = computed(() => allItems.value.filter(item => item.type === 'found'))

function enableLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                showLocationPrompt.value = false
                console.log("Location enabled successfully")
            },
            (error) => {
                console.error("Error getting location:", error)
            }
        )
    } else {
        console.error("Geolocation is not supported by this browser")
    }
}
</script>
