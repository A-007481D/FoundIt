<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoundIt! - Matches</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: "#8a2be2",
                            foreground: "#ffffff"
                        },
                        secondary: {
                            DEFAULT: "#10b981",
                            foreground: "#ffffff"
                        },
                        muted: {
                            DEFAULT: "#f1f5f9",
                            foreground: "#64748b"
                        },
                        background: "#ffffff",
                        foreground: "#0f172a",
                        border: "#e2e8f0"
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body class="flex min-h-screen flex-col bg-muted/30">
    <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
        <div class="container mx-auto px-20 flex h-16 items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="/" class="flex items-center gap-2 font-bold">
                    <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <span class="text-xl">FoundIt!</span>
                </a>

                <nav class="hidden md:flex items-center gap-6">
                    <a href="/feed" class="text-sm font-medium hover:text-primary transition-colors">
                        Feed
                    </a>
                    <a href="/discover" class="text-sm font-medium hover:text-primary transition-colors">
                        Discover
                    </a>
                    <a href="/matches" class="text-sm font-medium text-primary">
                       Recent Matches
                    </a>
                    <a href="/messages" class="text-sm font-medium hover:text-primary transition-colors">
                        Inbox
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative hidden md:block">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                    <input type="search" placeholder="Rechercher..." class="w-[200px] lg:w-[300px] pl-8 py-2 rounded-full bg-muted border-none focus:ring-2 focus:ring-primary">
                </div>

                <button class="relative p-2 rounded-full hover:bg-muted/50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                    </svg>
                    <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-primary text-[10px] text-primary-foreground">
                        2
                    </span>
                </button>

                <button class="relative p-2 rounded-full hover:bg-muted/50 md:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                </button>

                <div class="relative" id="user-dropdown-container">
                    <button class="relative h-8 w-8 rounded-full hover:ring-2 hover:ring-muted" id="user-dropdown-trigger">
                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                            <span class="text-sm font-medium">AH</span>
                        </div>
                    </button>
                    <div class="hidden absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50" id="user-dropdown-menu">
                        <div class="py-1">
                            <div class="px-4 py-2 border-b">
                                <p class="text-sm font-medium leading-none">Ahmed Hamdaoui</p>
                                <p class="text-xs leading-none text-muted-foreground mt-1">ahmed@youcode.ma</p>
                            </div>
                            <a href="#" class="flex items-center px-4 py-2 text-sm hover:bg-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span>Profile</span>
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm hover:bg-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                                <span>Inbox</span>
                                <span class="ml-auto bg-muted text-xs font-medium px-2 py-0.5 rounded">3</span>
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm hover:bg-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                                </svg>
                                <span>Notifications</span>
                                <span class="ml-auto bg-muted text-xs font-medium px-2 py-0.5 rounded">2</span>
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm hover:bg-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                                    <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <span>Settings</span>
                            </a>
                            <div class="border-t my-1"></div>
                            <a href="#" class="flex items-center px-4 py-2 text-sm hover:bg-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4 text-red-500">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                <span class="text-red-500">Log out</span>
                            </a>
                        </div>
                    </div>
                </div>

                <button class="border rounded-md p-2 md:hidden" id="mobile-menu-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" id="mobile-menu-icon">
                        <line x1="4" x2="20" y1="12" y2="12"></line>
                        <line x1="4" x2="20" y1="6" y2="6"></line>
                        <line x1="4" x2="20" y1="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- mobile menu -->
        <div class="container mx-auto px-4 py-4 md:hidden hidden" id="mobile-menu">
            <nav class="flex flex-col space-y-4">
                <a href="/dashboard" class="flex items-center gap-2 text-sm font-medium hover:text-primary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span>Feed</span>
                </a>
                <a href="/search" class="flex items-center gap-2 text-sm font-medium hover:text-primary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                    <span>Discover</span>
                </a>
                <a href="/matches" class="flex items-center gap-2 text-sm font-medium text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                    </svg>
                    <span>Recent Matches</span>
                </a>
                <a href="/dashboard/new-item" class="flex items-center gap-2 text-sm font-medium hover:text-primary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                    <span>Inbox</span>
                </a>
                <div class="pt-2">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                        <input type="search" placeholder="Rechercher..." class="w-full pl-8 py-2 rounded-md bg-muted border-none focus:ring-2 focus:ring-primary">
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <div class="flex-1 mx-20">
        <main class="p-4 md:p-6 container mx-auto">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Recent Matches</h1>
                    <p class="text-muted-foreground">
                        Our algorithm has found potential matches between lost and found items
                    </p>
                </div>
                <a href="">
                    <button class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4">
                            <path d="M5 12h14"></path>
                            <path d="M12 5v14"></path>
                        </svg>
                        New annoncement
                    </button>
                </a>
            </div>

            <div class="mb-6 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                <div class="flex flex-1 max-w-md gap-3">
                    <button class="border rounded-md px-3 py-2 flex items-center gap-2 hover:bg-muted/50" id="filters-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                            <line x1="21" x2="14" y1="4" y2="4"></line>
                            <line x1="10" x2="3" y1="4" y2="4"></line>
                            <line x1="21" x2="12" y1="12" y2="12"></line>
                            <line x1="8" x2="3" y1="12" y2="12"></line>
                            <line x1="21" x2="16" y1="20" y2="20"></line>
                            <line x1="12" x2="3" y1="20" y2="20"></line>
                            <line x1="14" x2="14" y1="2" y2="6"></line>
                            <line x1="8" x2="8" y1="10" y2="14"></line>
                            <line x1="16" x2="16" y1="18" y2="22"></line>
                        </svg>
                        Filters
                    </button>
                </div>

                <div class="flex items-center gap-2 ml-auto">
                    <span class="text-sm text-muted-foreground hidden sm:inline">Sort by:</span>
                    <div class="relative" id="sort-dropdown-container">
                        <button class="border rounded-md px-3 py-2 flex items-center justify-between w-[180px] h-9 text-sm" id="sort-dropdown-trigger">
                            <span>Matching Score</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 ml-2">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </button>
                        <div class="hidden absolute right-0 mt-2 w-[180px] rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50" id="sort-dropdown-menu">
                            <div class="py-1">
                                <a href="#" class="block px-4 py-2 text-sm hover:bg-muted" data-value="match-score">Matching Score</a>
                                <a href="#" class="block px-4 py-2 text-sm hover:bg-muted" data-value="date-new">Date (Most Recent)</a>
                                <a href="#" class="block px-4 py-2 text-sm hover:bg-muted" data-value="date-old">Date (Older)</a>
                                <a href="#" class="block px-4 py-2 text-sm hover:bg-muted" data-value="status">Status</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6 hidden" id="filters-panel">
                <div class="bg-white border rounded-lg shadow-sm">
                    <div class="p-6">
                        <div class="grid gap-6 md:grid-cols-3">
                            <div class="space-y-2">
                                <h3 class="text-sm font-medium">Status</h3>
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" id="new" class="rounded text-primary focus:ring-primary" checked>
                                        <label for="new" class="text-sm cursor-pointer">
                                            New
                                        </label>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" id="in-progress" class="rounded text-primary focus:ring-primary" checked>
                                        <label for="in-progress" class="text-sm cursor-pointer">
                                            In progress
                                        </label>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" id="resolved" class="rounded text-primary focus:ring-primary" checked>
                                        <label for="resolved" class="text-sm cursor-pointer">
                                            Resolved
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <h3 class="text-sm font-medium">Matching Score</h3>
                                <input type="range" min="0" max="100" value="60" step="5" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <div class="flex justify-between text-xs text-muted-foreground">
                                    <span>0%</span>
                                    <span>60%</span>
                                    <span>100%</span>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <h3 class="text-sm font-medium">Catergories</h3>
                                <div class="relative">
                                    <select class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-primary focus:outline-none focus:ring-primary sm:text-sm">
                                        <option value="all">All Catergories</option>
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
                            <button class="border rounded-md px-3 py-1 text-sm">
                                Reset
                            </button>
                            <button class="bg-primary hover:bg-primary/90 text-white px-3 py-1 rounded-md text-sm">
                                Apply filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Match Stats -->
            <!-- <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white border rounded-lg shadow-sm">
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

                <div class="bg-white border rounded-lg shadow-sm">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground">Nouveaux</p>
                            <p class="text-2xl font-bold">42</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white border rounded-lg shadow-sm">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground">En cours</p>
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

                <div class="bg-white border rounded-lg shadow-sm">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground">Résolus</p>
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
            <div class="mb-6">
                <div class="border-b">
                    <div class="flex -mb-px space-x-6 overflow-x-auto" id="tabs">
                        <button class="py-2 border-b-2 border-primary text-primary font-medium text-sm whitespace-nowrap" data-tab="all">
                            All matches
                        </button>
                        <button class="py-2 border-b-2 border-transparent hover:text-primary hover:border-primary/40 font-medium text-sm whitespace-nowrap" data-tab="new">
                            New </button>
                        <button class="py-2 border-b-2 border-transparent hover:text-primary hover:border-primary/40 font-medium text-sm whitespace-nowrap" data-tab="in-progress">
                            In Progress
                        </button>
                        <button class="py-2 border-b-2 border-transparent hover:text-primary hover:border-primary/40 font-medium text-sm whitespace-nowrap" data-tab="resolved">
                            Resolved </button>
                    </div>
                </div>
            </div>

            <!-- match cards -->
            <div id="tab-content-all" class="space-y-6">
                <div class="bg-white border rounded-lg shadow-sm overflow-hidden">
                    <div class="p-4 border-b">
                        <div class="flex justify-between items-center">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">New Match</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-blue-600 font-medium">87% Match</span>
                                <div class="w-16 h-2 bg-blue-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-600" style="width: 87%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-0">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                            <!-- Lost Item -->
                            <div class="p-4 border-b md:border-b-0 md:border-r">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="bg-red-500/10 text-red-500 border border-red-500/20 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        Lost
                                    </span>
                                    <span class="border text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        Electronics
                                    </span>
                                </div>

                                <h3 class="font-semibold text-base mb-1">iPhone 13 Pro with Blue Case</h3>

                                <div class="flex items-center gap-1 text-xs text-muted-foreground mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span>YouCode CCI, Cafeteria</span>
                                </div>

                                <p class="text-sm line-clamp-2 text-muted-foreground mb-3">
                                    iPhone 13 Pro with dark blue silicone case. Has a small crack in the top right corner of the
                                    screen. Lost during jogging.

                                </p>

                                <div class="text-xs text-muted-foreground mb-3">
                                    10/04/2023 à 14:30
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                                        <span class="text-xs">AH</span>
                                    </div>
                                    <span class="text-xs">Ahmed H.</span>
                                </div>
                            </div>

                            <!-- Found Item -->
                            <div class="p-4">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="bg-secondary/10 text-secondary border border-secondary/20 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        Found
                                    </span>
                                    <span class="border text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        Electronics </span>
                                </div>

                                <h3 class="font-semibold text-base mb-1">iPhone avec coque bleue trouvé</h3>

                                <div class="flex items-center gap-1 text-xs text-muted-foreground mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span>Centre-ville, Entrée Est</span>
                                </div>

                                <p class="text-sm line-clamp-2 text-muted-foreground mb-3">

                                    Trouvé un iPhone avec coque bleue près de la fontaine de l'entrée est. L'écran présente quelques dommages. Éteint quand trouvé.
                                </p>

                                <div class="text-xs text-muted-foreground mb-3">
                                    10/04/2023 à 16:45
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                                        <span class="text-xs">SL</span>
                                    </div>
                                    <span class="text-xs">Sara L.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Matching attributes -->
                        <div class="px-4 py-3 bg-muted/50">
                            <div class="flex flex-wrap gap-2">
                                <span class="text-xs text-muted-foreground">Matching Attributes:</span>
                                <span class="bg-primary/10 text-primary border border-primary/20 text-xs font-medium px-2 py-0.5 rounded-full">
                                    Location proximity
                                </span>
                                <span class="bg-primary/10 text-primary border border-primary/20 text-xs font-medium px-2 py-0.5 rounded-full">
                                    Time frame
                                </span>
                                <span class="bg-primary/10 text-primary border border-primary/20 text-xs font-medium px-2 py-0.5 rounded-full">
                                    Description
                                </span>
                                <span class="bg-primary/10 text-primary border border-primary/20 text-xs font-medium px-2 py-0.5 rounded-full">
                                    Distinctive features
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between p-4 border-t">
                        <div class="flex gap-2">

                            <button class="border rounded-md px-3 py-1 text-xs h-8 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                                <span>Contact</span>
                            </button>
                        </div>


                    </div>
                </div>
            </div>

    </div>

    <!-- filtered tab contente -->
    <div id="tab-content-new" class="space-y-6 hidden">
        <!-- new -->
    </div>

    <div id="tab-content-in-progress" class="space-y-6 hidden">
        <!-- in prog -->
    </div>

    <div id="tab-content-resolved" class="space-y-6 hidden">
        <!-- resolved -->
    </div>
    <div class="mt-8 flex justify-center">
        <button class="border rounded-md px-4 py-2 hover:bg-muted/50">
            <span>Load More Matches</span>
        </button>
    </div>
    </main>
    </div>

    <!-- footer -->
    <footer class="py-8 bg-muted/30">
        <div class="container mx-auto px-4">
            

            <div class="mt-8 px-20 pt-8 border-t flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-muted-foreground">© <span id="current-year"></span> FoundIt! All rights reserved.</p>

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
</body>
<script src="{{ asset('js/matches.js') }}"></script>
</html>