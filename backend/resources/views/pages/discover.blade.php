<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover Items - Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#9a75eb',
                        // 'primary-dark': '#4f46e5',
                        border: '#e5e7eb',
                        muted: {
                            DEFAULT: '#6b7280',
                            foreground: '#6b7280'
                        },
                        foreground: '#031a52',
                        background: '#ffffff',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-white text-gray-800">
    <nav class="sticky top-0 z-50 bg-white/70 backdrop-blur-md border-b border-gray-200 flex items-center justify-between py-3 px-28">
        <div class="flex items-center space-x-2">
            <a href="#" class="flex items-center space-x-1">
                <img src="" alt="Logo" class="h-8 w-8 object-cover">
                <span class="font-bold text-xl text-purple-600">Foundit!</span>
            </a>
        </div>
        <ul class="flex space-x-6 text-gray-700 font-medium">
            <li><a href="#" class="hover:text-purple-600 hover:border-purple-500 hover:border-b-2">Home</a></li>
            <li><a href="#" class="hover:text-purple-600 hover:border-purple-500 hover:border-b-2">How it works</a></li>
            <li><a href="#" class="hover:text-purple-600 hover:border-purple-500 hover:border-b-2">Community Forum</a></li>
            <li><a href="#" class="hover:text-purple-600 hover:border-purple-500 hover:border-b-2">Contact Us</a></li>
            <li><a href="#" class="hover:text-purple-600 hover:border-purple-500 hover:border-b-2">FAQ</a></li>
        </ul>
        <div class="flex items-center space-x-4">
            <a href="{{ route('login') }}" class="rounded-md font-medium bg-white border-2 border-purple-600 text-purple-600 px-4 py-2 hover:bg-purple-50">
                Log in
            </a>
            <a href="{{ route('register') }}" class="rounded-md text-white font-medium bg-purple-600 hover:bg-purple-700 border-2 border-purple-600 px-4 py-2">
                Sign up
            </a>
        </div>
    </nav>

    <main class="max-w-[90rem] mx-auto px-6 py-12 md:py-20 flex flex-col-reverse md:flex-row items-center">
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <h1 class="text-3xl font-bold tracking-tight">Discover Items</h1>
                <p class="text-muted-foreground">Browse lost and found items in your area</p>
            </div>
            <div class="w-full">
                <div class="flex items-center justify-between">
                    <div class="inline-flex h-10 items-center justify-center rounded-md bg-gray-200 p-1 text-muted">
                        <button class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm bg-background text-foreground shadow-sm" data-state="active" data-tab="all">All Items</button>
                        <button class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm" data-tab="lost">Lost Items</button>
                        <button class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm" data-tab="found">Found Items</button>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex gap-6">
                <aside class="hidden w-64 shrink-0 md:block">
                    <div class="flex flex-col gap-6">
                        <div>
                            <h3 class="mb-2 font-medium">Item Type</h3>
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <input type="radio" id="all" name="itemType" value="all" checked class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                    <label for="all" class="text-sm">All Items</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" id="lost" name="itemType" value="lost" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                    <label for="lost" class="text-sm">Lost Items</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" id="found" name="itemType" value="found" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                    <label for="found" class="text-sm">Found Items</label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="mb-2 font-medium">Categories</h3>
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="electronics" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                    <label for="electronics" class="text-sm">Electronics</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="jewelry" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                    <label for="jewelry" class="text-sm">Jewelry</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="documents" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                    <label for="documents" class="text-sm">Documents</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="clothing" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                    <label for="clothing" class="text-sm">Clothing</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="accessories" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                    <label for="accessories" class="text-sm">Accessories</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="keys" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                    <label for="keys" class="text-sm">Keys</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="other" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                    <label for="other" class="text-sm">Other</label>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="mb-2 font-medium">Date Posted</h3>
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <input type="radio" id="anytime" name="datePosted" value="anytime" checked class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                    <label for="anytime" class="text-sm">Anytime</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" id="today" name="datePosted" value="today" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                    <label for="today" class="text-sm">Today</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" id="this-week" name="datePosted" value="this-week" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                    <label for="this-week" class="text-sm">This Week</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" id="this-month" name="datePosted" value="this-month" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                                    <label for="this-month" class="text-sm">This Month</label>
                                </div>
                            </div>
                        </div>

                        <button class="inline-flex items-center justify-center rounded-md bg-purple-600 px-4 py-2 text-sm font-medium text-white shadow transition-colors hover:bg-purple-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 mt-2">Apply Filters</button>
                    </div>
                </aside>

                <div class="flex-1">
                    <div class="tab-content active" id="all-tab">
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
                            <!-- featured items -->
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3" id="featured-items">
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
                            <!-- recent items -->
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3" id="recent-items">
                            </div>
                        </div>
                    </div>
                    <!-- tabbed lost items-->
                    <div class="tab-content hidden" id="lost-tab">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3" id="lost-items">
                        </div>
                    </div>
                    <!-- tabbed found items -->
                    <div class="tab-content hidden" id="found-tab">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3" id="found-items">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
     <!-- enable location permission -->
     <div id="locationPrompt" class="fixed bottom-4 left-0 right-0 z-40 mx-auto w-full max-w-md rounded-lg border bg-white p-4 shadow-lg md:bottom-8">
        <div class="flex items-start justify-between">
            <div class="flex items-start gap-3">
                <div class="rounded-full bg-primary/10 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-primary">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <div>
                    <h3 class="font-medium">Enable location services</h3>
                    <p class="mt-1 text-sm text-muted-foreground">See lost & found items near you by enabling location access.</p>
                </div>
            </div>
            <button id="closeLocationPrompt" class="rounded-md p-1 text-muted-foreground hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
                <span class="sr-only">Dismiss</span>
            </button>
        </div>
        <div class="mt-4 flex gap-2">
            <button class="inline-flex items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 flex-1">Not now</button>
            <button class="inline-flex items-center justify-center rounded-md bg-purple-600 px-4 py-2 text-sm font-medium text-white shadow transition-colors hover:bg-purple-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 flex-1">Enable location</button>
        </div>
    </div>
    <!--  card template -->
    <template id="item-card-template">
        <a href="#" class="group h-full overflow-hidden rounded-lg border border-border bg-white transition-all hover:shadow-md">
            <div class="relative aspect-video overflow-hidden">
                <img src="https://placehold.co/400" alt="" class="h-full w-full object-cover transition-transform group-hover:scale-105">
                <div class="item-badge absolute left-2 top-2 z-10 rounded-full px-2 py-1 text-xs font-semibold uppercase"></div>
                <div class="featured-badge absolute right-2 top-2 z-10 hidden rounded-md bg-purple-100 px-2 py-1 text-xs font-semibold text-purple-500">Featured</div>
            </div>
            <div class="p-4">
                <div class="flex items-start justify-between">
                    <h3 class="line-clamp-1 font-semibold"></h3>
                    <span class="ml-2 shrink-0 rounded-full bg-gray-100 px-2 py-0.5 text-xs text-muted-foreground"></span>
                </div>
                <p class="line-clamp-2 mt-2 text-sm text-muted-foreground"></p>
                <div class="mt-4 flex items-center justify-between text-xs text-muted-foreground">
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span class="location"></span>
                    </div>
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                            <line x1="16" x2="16" y1="2" y2="6"></line>
                            <line x1="8" x2="8" y1="2" y2="6"></line>
                            <line x1="3" x2="21" y1="10" y2="10"></line>
                        </svg>
                        <span class="date"></span>
                    </div>
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <span class="views"></span>
                    </div>
                </div>
            </div>
        </a>
    </template>
    
</body>

</html>