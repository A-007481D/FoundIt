<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fourditi - Tableau de bord</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900">
    <div class="max-w-[90rem] mx-auto p-4">

        <div class="mb-6">
            <!-- card template -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <!-- card Header -->
                <div class="p-4 border-b">
                    <div class="flex justify-between items-center">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">New Match</span>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium">87% Match</span>
                            <div class="w-16 h-2 bg-blue-200 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-600" style="width: 87%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x">
                    <!-- Lost Item -->
                    <div class="p-4">
                        <div class="flex items-center space-x-2 mb-3">
                            <span class="px-2 py-0.5 bg-red-100 text-red-800 text-xs font-medium rounded border border-red-200">Lost</span>
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-800 text-xs font-medium rounded border border-gray-200">Électronics</span>
                        </div>

                        <h3 class="font-semibold text-base mb-1">iPhone 13 Pro with Blue Case</h3>

                        <div class="flex items-center space-x-1 text-xs text-gray-500 mb-2">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>YouCode Safi, Cafeteria</span>
                        </div>

                        <p class="text-sm text-gray-600 mb-3">iPhone 13 Pro avec coque silicone bleue foncée. A une petite fissure dans le coin supérieur droit de l'écran. Perdu pendant le jogging.</p>

                        <div class="text-xs text-gray-500 mb-3">4/10/2023 à 14:30</div>

                        <div class="flex items-center space-x-2">
                            <div class="h-6 w-6 rounded-full bg-gray-300 flex items-center justify-center text-xs">AH</div>
                            <span class="text-xs">Khadija O.</span>
                        </div>
                    </div>

                    <!-- Found Item -->
                    <div class="p-4">
                        <div class="flex items-center space-x-2 mb-3">
                            <span class="px-2 py-0.5 bg-green-100 text-green-800 text-xs font-medium rounded border border-green-200">Found</span>
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-800 text-xs font-medium rounded border border-gray-200">Électronics</span>
                        </div>

                        <h3 class="font-semibold text-base mb-1">Found iPhone with Blue Case</h3>

                        <div class="flex items-center space-x-1 text-xs text-gray-500 mb-2">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>YouCode Safi, Cafeteria</span>
                        </div>

                        <p class="text-sm text-gray-600 mb-3">Found an iPhone with blue case near the east entrance fountain. Screen has some damage.
                            Turned off when found.</p>

                        <div class="text-xs text-gray-500 mb-3">4/10/2023 à 16:45</div>

                        <div class="flex items-center space-x-2">
                            <div class="h-6 w-6 rounded-full bg-gray-300 flex items-center justify-center text-xs">SL</div>
                            <span class="text-xs">Sara B.</span>
                        </div>
                    </div>
                </div>
                <!-- cardfooter -->
                <div class="px-4 py-3 bg-gray-50">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-500">Matching Attributes:</span>
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded border border-blue-200">Location Proximity</span>
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded border border-blue-200">Time frame</span>
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded border border-blue-200">Description</span>
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded border border-blue-200">Distinctive features</span>
                    </div>
                </div>
                <div class="p-4 border-t flex justify-between">
                    <div class="flex space-x-2">
                        <!-- <button class="px-3 py-1 text-xs border rounded hover:bg-gray-50">Détails</button> -->
                        <button class="px-3 py-1 text-xs border rounded hover:bg-gray-50">Contact</button>
                    </div>
                    <div class="flex space-x-2">
                        <button class="w-8 h-8 flex items-center justify-center border rounded hover:bg-gray-50">
                            <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <button class="w-8 h-8 flex items-center justify-center border rounded hover:bg-gray-50">
                            <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>