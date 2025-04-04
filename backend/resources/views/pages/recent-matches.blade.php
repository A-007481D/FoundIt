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


    <!-- Main content -->
    <div class="flex-1">
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
            <div class="mt-8 flex justify-center">
                <button class="border rounded-md px-4 py-2 hover:bg-muted/50">
                    <span>Load More Matches</span>
                </button>
            </div>
        </main>
    </div>

</body>

</html>
