<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoundIt! - Matches</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
    <div class="min-h-screen" x-data="{ activeTab: 'profile', mobileMenuOpen: false }">
        <!-- Header -->
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
                                <a href="/profile" class="flex items-center px-4 py-2 text-sm hover:bg-muted">
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

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="py-6">
                <div class="flex flex-col md:flex-row">

                    <div class="hidden md:block md:w-64 md:shrink-0">
                        <div class="py-5 mb-1  border-b border-gray-200">
                            <h3 class="text-xl leading-6 font-bold text-gray-900">Settings</h3>
                        </div>
                        <nav class="space-y-1">
                            <button
                                @click="activeTab = 'profile'"
                                :class="activeTab === 'profile' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                class="group flex items-center px-3 py-2 text-sm font-medium rounded-md w-full">
                                <svg class="text-gray-500 mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profile
                            </button>

                            <button
                                @click="activeTab = 'account'"
                                :class="activeTab === 'account' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                class="group flex items-center px-3 py-2 text-sm font-medium rounded-md w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                </svg>
                                Account
                            </button>

                            <button
                                @click="activeTab = 'security'"
                                :class="activeTab === 'security' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                class="group flex items-center px-3 py-2 text-sm font-medium rounded-md w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Security
                            </button>

                            <button
                                @click="activeTab = 'privacy'"
                                :class="activeTab === 'privacy' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                class="group flex items-center px-3 py-2 text-sm font-medium rounded-md w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-4.5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Privacy
                            </button>

                            <button
                                @click="activeTab = 'notifications'"
                                :class="activeTab === 'notifications' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                class="group flex items-center px-3 py-2 text-sm font-medium rounded-md w-full">
                                <svg class="text-gray-500 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                Notifications
                            </button>
                            <div class=" border-b border-gray-200">
                        </div>
                            <button
                                @click="activeTab = 'delete'"
                                :class="activeTab === 'delete' ? 'bg-red-50 text-red-500' : 'text-red-500 hover:bg-red-50 hover:text-red-500'"
                                class="group flex items-center px-3 py-2 text-sm font-medium rounded-md w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash mr-2 h-4 w-4">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                </svg>
                                Delete account
                            </button>
                        </nav>
                    </div>

                    <div
                        x-show="mobileMenuOpen"
                        class="md:hidden fixed inset-0 flex z-40"
                        x-transition:enter="transition-opacity ease-linear duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity ease-linear duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0">
                        <div
                            @click="mobileMenuOpen = false"
                            class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>

                        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                            <div class="absolute top-0 right-0 -mr-12 pt-2">
                                <button
                                    @click="mobileMenuOpen = false"
                                    class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                                    <span class="sr-only">Close sidebar</span>
                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">

                                <nav class="mt-5 px-2 space-y-1">
                                    <button
                                        @click="activeTab = 'profile'; mobileMenuOpen = false"
                                        :class="activeTab === 'profile' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                        class="group flex items-center px-3 py-2 text-base font-medium rounded-md w-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Profile
                                    </button>

                                    <button
                                        @click="activeTab = 'account'; mobileMenuOpen = false"
                                        :class="activeTab === 'account' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                        class="group flex items-center px-3 py-2 text-base font-medium rounded-md w-full">
                                        <svg class="text-gray-500 mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Account
                                    </button>

                                    <button
                                        @click="activeTab = 'security'; mobileMenuOpen = false"
                                        :class="activeTab === 'security' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                        class="group flex items-center px-3 py-2 text-base font-medium rounded-md w-full">
                                        <svg class="text-gray-500 mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Security
                                    </button>

                                    <button
                                        @click="activeTab = 'privacy'; mobileMenuOpen = false"
                                        :class="activeTab === 'privacy' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                        class="group flex items-center px-3 py-2 text-base font-medium rounded-md w-full">
                                        <svg class="text-gray-500 mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                        </svg>
                                        Privacy
                                    </button>

                                    <button
                                        @click="activeTab = 'notifications'; mobileMenuOpen = false"
                                        :class="activeTab === 'notifications' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                        class="group flex items-center px-3 py-2 text-base font-medium rounded-md w-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                        Notifications
                                    </button>

                                    <button
                                        @click="activeTab = 'delete'; mobileMenuOpen = false"
                                        :class="activeTab === 'delete' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                                        class="group flex items-center px-3 py-2 text-base font-medium rounded-md w-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete account
                                    </button>

                                </nav>
                            </div>
                        </div>

                        <div class="flex-shrink-0 w-14"></div>
                    </div>

                    <div class="mt-5 md:mt-0 md:ml-6 w-full">
                        <div x-show="activeTab === 'profile'" class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Personal Information</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">Personal Information and profile picture.</p>
                            </div>

                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex flex-col md:flex-row">
                                    <div class="md:w-1/3">
                                        <div class="flex flex-col items-center">
                                            <div class="relative">
                                                <img class="h-32 w-32 rounded-full object-cover" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Profile picture">
                                                <button class="absolute bottom-0 right-0 bg-white rounded-full p-1 shadow-md hover:bg-gray-100">
                                                    <svg class="h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="mt-4 flex">
                                                <button class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-purple-500">
                                                    Delete
                                                </button>
                                                <button class="ml-3 bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-purple-500">
                                                    Change
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="md:w-2/3 mt-6 md:mt-0">
                                        <form>
                                            <div class="grid grid-cols-6 gap-6">
                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="first-name" class="block text-sm font-medium text-gray-700">Name</label>
                                                    <input type="text" name="first-name" id="first-name" value="Abdelmalek" placeholder="abdelmalek"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 focus:ring-2 ring-purple-600 outline-none">
                                                </div>

                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="last-name" class="block text-sm font-medium text-gray-700">Last name</label>
                                                    <input type="text" name="last-name" id="last-name" value="Labid" placeholder="Labid"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 focus:ring-2 ring-purple-600 outline-none">
                                                </div>

                                                <div class="col-span-6 sm:col-span-4">
                                                    <label for="email-address" class="block text-sm font-medium text-gray-700">Email address</label>
                                                    <input type="email" name="email-address" id="email-address" value="abdelmalek@gmail.com" placeholder="abdelmalek@gmail.com"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 focus:ring-2 ring-purple-600 outline-none">
                                                </div>

                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                                    <input type="tel" name="phone" id="phone" value="+33 6 12 34 56 78"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 focus:ring-2 ring-purple-600 outline-none">
                                                </div>

                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                                    <input type="text" name="location" id="location" value="Paris, France"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 focus:ring-2 ring-purple-600 outline-none">
                                                </div>

                                                <div class="col-span-6">
                                                    <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>

                                                    <textarea id="bio" name="bio" rows="3" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 focus:ring-2 ring-purple-600 outline-none ">Développeur web passionné par les nouvelles technologies et le design d'interface utilisateur.</textarea>
                                                    <p class="mt-2 text-[10px] text-gray-500">0/250 characters</p>


                                                </div>
                                            </div>

                                            <div class="mt-6 flex justify-end">
                                                <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-purple-500">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md bg-primary hover:bg-primary/90 text-white focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-purple-500">
                                                    Save changes
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Tab -->
                        <div x-show="activeTab === 'account'" class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Account Information</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">Details about your account

                                </p>
                            </div>

                            <div class="px-4 py-5 sm:p-6">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Account ID
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">AC-123456789</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Join Date
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">12 January 2023</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Last Profile Update
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">15 March 2023</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                                        <dd class="mt-1 text-sm text-gray-900">Active</dd>
                                    </div>
                                </dl>

                                <div class="mt-8 border-t border-gray-200 pt-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Region & Language</h3>
                                    <div class="mt-4">
                                        <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
                                        <select id="language" name="language" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            <option selected>English</option>
                                            <option>Francais</option>
                                            <option>Español</option>
                                            <option>Deutsch</option>
                                        </select>
                                    </div>
                                    <div class="mt-4">
                                        <label for="timezone" class="block text-sm font-medium text-gray-700">Time zone</label>
                                        <select id="timezone" name="timezone" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            <option selected>(GMT+01:00) Casablanca</option>
                                            <option>(GMT+00:00) London</option>
                                            <option>(GMT-05:00) New York</option>
                                            <option>(GMT-08:00) Los Angeles</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-8 border-t border-gray-200 pt-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Data & export</h3>
                                    <p class="mt-1 text-sm text-gray-500">Upload a copy of your data or delete your account.
                                    </p>
                                    <div class="mt-4">
                                        <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-purple-500">
                                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Export data
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-purple-500">
                                        Cancel
                                    </button>
                                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md bg-primary hover:bg-primary/90 text-white focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-purple-500">
                                        Save changes
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div x-show="activeTab === 'security'" class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Security</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">Gérez votre mot de passe et la Security de votre compte.</p>
                            </div>

                            <div class="px-4 py-5 sm:p-6">
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Change password</h3>
                                    <div class="mt-4">
                                        <form class="space-y-4">
                                            <div>
                                                <label for="current-password" class="block text-sm font-medium text-gray-700">Current password</label>
                                                <input type="password" name="current-password" id="current-password" placeholder="••••••••"
                                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 focus:ring-2 ring-purple-600 outline-none">
                                            </div>
                                            <div>
                                                <label for="new-password" class="block text-sm font-medium text-gray-700">New password</label>
                                                <input type="password" name="new-password" id="new-password" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 focus:ring-2 ring-purple-600 outline-none">
                                            </div>
                                            <div>
                                                <label for="confirm-password" class="block text-sm font-medium text-gray-700">Confirm password</label>
                                                <input type="password" name="confirm-password" id="confirm-password" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 focus:ring-2 ring-purple-600 outline-none">
                                            </div>

                                            <div>
                                                <h4 class="text-sm font-medium text-gray-700">Password strength</h4>
                                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2.5">
                                                    <div class="bg-green-500 h-2.5 rounded-full" style="width: 75%"></div>
                                                </div>
                                                <p class="mt-2 text-sm text-gray-500">Utilisez au moins 8 caractères avec des lettres majuscules, minuscules, des chiffres et des symboles.</p>
                                            </div>

                                            <div class="flex justify-end">
                                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-purple-500">
                                                    Update the password
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="mt-6 border-b border-gray-200 pb-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Two factor authentication</h3>
                                    <p class="mt-1 text-sm text-gray-500">Add an extra layer of security to your account.

                                    </p>

                                    <div class="mt-4 flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700">Status</h4>
                                            <p class="text-sm text-gray-500">Two-factor authentication is currently disabled.</p>
                                        </div>
                                        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-purple-500">
                                            Activate
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Account activity
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">Here are the devices that have been connected to your account recently.</p>

                                    <div class="mt-4 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Device</th>
                                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Location</th>
                                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
                                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                        <span class="sr-only">Actions</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                <tr>
                                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">Chrome on Windows</td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Paris, France</td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Today, 14:32</td>
                                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Current
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">Safari on iPhone</td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Paris, France</td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">Yesterday, 09:15</td>
                                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Disconnect</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Privacy Tab -->
                        <div x-show="activeTab === 'privacy'" class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Privacy</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">Gérez qui peut voir vos informations et comment vos données sont utilisées.</p>
                            </div>

                            <div class="px-4 py-5 sm:p-6">
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Visibilité du profil</h3>
                                    <p class="mt-1 text-sm text-gray-500">Contrôlez qui peut voir vos informations de profil.</p>

                                    <div class="mt-4 space-y-4">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="profile-public" name="profile-visibility" type="radio" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="profile-public" class="font-medium text-gray-700">Public</label>
                                                <p class="text-gray-500">Tout le monde peut voir votre profil complet.</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="profile-contacts" name="profile-visibility" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="profile-contacts" class="font-medium text-gray-700">Contacts uniquement</label>
                                                <p class="text-gray-500">Seuls vos contacts peuvent voir votre profil complet.</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="profile-private" name="profile-visibility" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="profile-private" class="font-medium text-gray-700">Privé</label>
                                                <p class="text-gray-500">Personne ne peut voir votre profil sauf vous.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 border-b border-gray-200 pb-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Informations visibles</h3>
                                    <p class="mt-1 text-sm text-gray-500">Choisissez quelles informations sont visibles sur votre profil.</p>

                                    <div class="mt-4 space-y-4">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="show-email" name="show-email" type="checkbox" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="show-email" class="font-medium text-gray-700">Adresse email</label>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="show-phone" name="show-phone" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="show-phone" class="font-medium text-gray-700">Numéro de téléphone</label>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="show-location" name="show-location" type="checkbox" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="show-location" class="font-medium text-gray-700">Localisation</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Cookies et données</h3>
                                    <p class="mt-1 text-sm text-gray-500">Gérez comment nous utilisons les cookies et vos données.</p>

                                    <div class="mt-4 space-y-4">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="analytics-cookies" name="analytics-cookies" type="checkbox" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="analytics-cookies" class="font-medium text-gray-700">Cookies d'analyse</label>
                                                <p class="text-gray-500">Nous permettent d'améliorer notre site en comprenant comment vous l'utilisez.</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="marketing-cookies" name="marketing-cookies" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="marketing-cookies" class="font-medium text-gray-700">Cookies marketing</label>
                                                <p class="text-gray-500">Permettent de vous montrer des publicités pertinentes sur d'autres sites.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-purple-500">
                                        Cancel
                                    </button>
                                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md bg-primary hover:bg-primary/90 text-white focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-purple-500">
                                        <!-- class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-md flex items-center"> -->
                                        Save changes
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications Tab -->
                        <div x-show="activeTab === 'notifications'" class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Notifications</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage how and when you receive notifications.</p>
                            </div>

                            <div class="px-4 py-5 sm:p-6">
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Notifications by email</h3>

                                    <div class="mt-4 space-y-4">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="email-comments" name="email-comments" type="checkbox" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="email-comments" class="font-medium text-gray-700">Commentaries</label>
                                                <p class="text-gray-500">Receive an email when someone comments on your posts..</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="email-mentions" name="email-mentions" type="checkbox" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="email-mentions" class="font-medium text-gray-700">Mentions</label>
                                                <p class="text-gray-500">Receive an email when someone mentions you.</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="email-updates" name="email-updates" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="email-updates" class="font-medium text-gray-700">Updates to the product</label>
                                                <p class="text-gray-500">Receive emails about new features and improvements.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 border-b border-gray-200 pb-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Push notifications</h3>

                                    <div class="mt-4 space-y-4">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="push-comments" name="push-comments" type="checkbox" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="push-comments" class="font-medium text-gray-700">Commentaires</label>
                                                <p class="text-gray-500">Get a push notification when someone comments on your posts.

                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="push-mentions" name="push-mentions" type="checkbox" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="push-mentions" class="font-medium text-gray-700">Mentions</label>
                                                <p class="text-gray-500">Get a push notification when someone mentions you..</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="push-messages" name="push-messages" type="checkbox" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="push-messages" class="font-medium text-gray-700">Messages</label>
                                                <p class="text-gray-500">Receive a push notification when you receive a new message.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Notification preferences
                                    </h3>

                                    <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                        <div>
                                            <label for="notification-frequency" class="block text-sm font-medium text-gray-700">Set how often you want to receive notifications

                                            </label>
                                            <select id="notification-frequency" name="notification-frequency" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                <option selected>Real-time</option>
                                                <option>Every hour</option>
                                                <option>Once a day</option>
                                                <option>Once a week</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="quiet-hours-start" class="block text-sm font-medium text-gray-700">Quiet Hours (start)</label>
                                            <input type="time" name="quiet-hours-start" id="quiet-hours-start" value="22:00"
                                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 focus:ring-2 ring-purple-600 outline-none">
                                        </div>

                                        <div>
                                            <label for="quiet-hours-end" class="block text-sm font-medium text-gray-700">Quiet Hours (end)</label>
                                            <input type="time" name="quiet-hours-end" id="quiet-hours-end" value="07:00" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 focus:ring-2 ring-purple-600 outline-none">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-purple-500">
                                        Cancel
                                    </button>
                                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md bg-primary hover:bg-primary/90 text-white focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-purple-500">
                                        Save changes
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Account Tab -->
                        <div x-show="activeTab === 'delete'" class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete account</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">Permanently delete your account and all your data.

                                </p>
                            </div>

                            <div class="px-4 py-5 sm:p-6">
                                <div class="rounded-md bg-red-50 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800">Caution: This action is irreversible</h3>
                                            <div class="mt-2 text-sm text-red-700">
                                                <p>Deleting your account will result in the permanent loss of all your data, including:
                                                </p>
                                                <ul class="list-disc pl-5 mt-1 space-y-1">
                                                    <li>Your personal information</li>
                                                    <li>Your posts and comments
                                                    </li>
                                                    <li>Your activity history
                                                    </li>
                                                    <li>All files and documents downloaded
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Confirmation</h3>
                                    <p class="mt-1 text-sm text-gray-500">Please enter your password to confirm the deletion of your account.</p>

                                    <div class="mt-4">
                                        <label for="delete-password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                                        <input type="password" name="delete-password" id="delete-password" placeholder="••••••••"
                                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-3 py-2 focus:ring-2 ring-purple-600 outline-none">
                                    </div>

                                    <div class="mt-4">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="confirm-delete" name="confirm-delete" type="checkbox" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="confirm-delete" class="font-medium text-gray-700">I understand that this action is irreversible and I want to permanently delete my account.</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        <button type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Permanently delete my account
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/profile.js') }} "></script>

</body>

</html>