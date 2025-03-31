<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover Items - Lost & Found</title>
    <link rel="stylesheet" href="{{ asset('css/component.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/discover.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
<!-- Navbar -->
<nav class="bg-white shadow">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <div class="flex shrink-0 items-center">
                    <span class="font-bold text-lg">Lost & Found</span>
                </div>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                <!-- Add navigation links here if needed -->
            </div>
        </div>
    </div>
</nav>

<!-- Location Prompt -->
<div id="locationPrompt" class="location-prompt">
    <div class="location-prompt-content">
        <i data-lucide="map-pin" class="location-icon"></i>
        <div class="location-text">
            <p>Enable location services</p>
            <p class="location-subtext">See lost & found items near you by enabling location access.</p>
        </div>
        <button class="close-button" id="closeLocationPrompt">
            <i data-lucide="x" size="16"></i>
        </button>
    </div>
    <div class="location-actions">
        <button class="button outline" id="dismissLocationPrompt">Not now</button>
        <button class="bg-red-500 p-6" id="enableLocation">Enable location</button>
    </div>
</div>

<main class="discover-main">
    <div class="discover-container">
        <div class="discover-header">
            <h1 class="discover-title">Discover Items</h1>
            <p class="discover-subtitle">Browse lost and found items in your area</p>
        </div>

        <div class="tabs-container">
            <div class="tabs-header">
                <div class="tabs-list">
                    <button class="tab-button active" data-tab="all">All Items</button>
                    <button class="tab-button" data-tab="lost">Lost Items</button>
                    <button class="tab-button" data-tab="found">Found Items</button>
                </div>
                <div class="location-indicator">
                    <i data-lucide="map-pin" class="location-icon"></i>
                    <span class="location-text">New York, NY (5 mile radius)</span>
                </div>
            </div>

            <div class="content-container">
                <!-- Filter sidebar - commented out as requested -->
                <!-- <div class="filter-sidebar">...</div> -->

                <div class="items-container">
                    <!-- All Items Tab Content -->
                    <div class="tab-content active" id="all-tab">
                        <div class="featured-section">
                            <div class="section-header">
                                <h2 class="section-title">Featured Items</h2>
                                <button class="view-all-button">
                                    View all <i data-lucide="arrow-right" class="arrow-icon"></i>
                                </button>
                            </div>
                            <div class="items-grid" id="featured-items">
                                <!-- Featured items will be inserted here by JavaScript -->
                            </div>
                        </div>

                        <div class="recent-section">
                            <div class="section-header">
                                <h2 class="section-title">Recent Items</h2>
                                <button class="view-all-button">
                                    View all <i data-lucide="arrow-right" class="arrow-icon"></i>
                                </button>
                            </div>
                            <div class="items-grid" id="recent-items">
                                <!-- Recent items will be inserted here by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!-- Lost Items Tab Content -->
                    <div class="tab-content" id="lost-tab">
                        <div class="items-grid" id="lost-items">
                            <!-- Lost items will be inserted here by JavaScript -->
                        </div>
                    </div>

                    <!-- Found Items Tab Content -->
                    <div class="tab-content" id="found-tab">
                        <div class="items-grid" id="found-items">
                            <!-- Found items will be inserted here by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Item Card Template -->
<template id="item-card-template">
    <div class="item-card">
        <div class="item-image-container">
            <img src="/placeholder.svg" alt="" class="item-image">
            <div class="item-badge"></div>
            <div class="featured-badge" style="display: none;">Featured</div>
        </div>
        <div class="item-content">
            <div class="item-header">
                <h3 class="item-title"></h3>
                <span class="item-category"></span>
            </div>
            <p class="item-description"></p>
            <div class="item-meta">
                <div class="item-location">
                    <i data-lucide="map-pin" class="meta-icon"></i>
                    <span></span>
                </div>
                <div class="item-date">
                    <i data-lucide="calendar" class="meta-icon"></i>
                    <span></span>
                </div>
                <div class="item-views">
                    <i data-lucide="eye" class="meta-icon"></i>
                    <span></span>
                </div>
            </div>
        </div>
    </div>
</template>

<script src="{{ asset('js/discover.js') }}"></script>
</body>
</html>

