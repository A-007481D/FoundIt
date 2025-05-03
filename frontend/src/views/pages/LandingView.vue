<template>
  <div class="bg-white text-gray-800">
    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12 md:py-20 flex flex-col-reverse md:flex-row items-center">
      <div class="w-full md:w-1/2 md:pr-8 mt-6 md:mt-0">
        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-3 sm:mb-4 leading-tight">
          Lost It? Don't Stress, <br class="hidden sm:inline"> Just
          <span class="text-primary">FoundIt!</span>
        </h1>
        <p class="text-gray-600 text-base sm:text-lg md:text-xl mb-4 sm:mb-6">
          Use FoundIt to swiftly report lost items, receive real-time alerts,
          and securely reconnect with your belongings. Experience a smarter,
          community-powered solution anytime, anywhere.
        </p>
        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
          <router-link to="/register" class="inline-block px-5 py-2.5 sm:px-6 sm:py-3 bg-primary text-white font-medium rounded-md hover:bg-primary-dark text-center text-sm sm:text-base">
            Get Started
          </router-link>
          <a href="#" class="inline-block px-5 py-2.5 sm:px-6 sm:py-3 border border-primary text-primary font-medium rounded-md hover:bg-primary-50 text-center text-sm sm:text-base">
            Watch Demo
          </a>
        </div>
        <div class="border-t-2 mt-6 max-w-7xl mx-auto px-2 sm:px-6 py-5 sm:py-8 grid grid-cols-3 gap-2 sm:gap-6 text-center">
          <div>
            <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-primary">2k+</h3>
            <p class="text-gray-600 text-xs sm:text-sm">Items Found</p>
          </div>
          <div>
            <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-primary">99%</h3>
            <p class="text-gray-600 text-xs sm:text-sm">Return Rate</p>
          </div>
          <div>
            <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-primary">24H</h3>
            <p class="text-gray-600 text-xs sm:text-sm">Avg Return Time</p>
          </div>
        </div>
      </div>

      <div class="relative w-full max-w-xs sm:max-w-sm md:max-w-lg mx-auto md:mx-0 mb-6 md:mb-0">
        <div class="absolute inset-0 transform rotate-[-4deg] bg-gray-200 rounded-xl shadow-lg"></div>

        <div class="relative bg-white rounded-xl shadow-lg p-4 sm:p-6 transition-transform duration-300 transform rotate-[-4deg] hover:rotate-0 hover:-translate-y-2">

          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-purple-50 rounded-md p-3 sm:p-4 mb-3 sm:mb-4">
            <div>
              <h4 class="text-primary font-semibold text-base sm:text-lg">Lost: Student ID Card</h4>
              <p class="text-xs sm:text-sm text-gray-600">Last seen: Library 3rd floor</p>
            </div>
            <span class="text-primary font-medium text-xs sm:text-sm mt-1 sm:mt-0">
              Match Found!
            </span>
          </div>

          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-green-50 rounded-md p-3 sm:p-4 mb-3 sm:mb-4">
            <div>
              <h4 class="text-green-600 font-semibold text-base sm:text-lg">Found: Student ID Card</h4>
              <p class="text-xs sm:text-sm text-gray-600">Location: Library 3rd floor</p>
            </div>
            <span class="text-green-500 font-medium text-xs sm:text-sm mt-1 sm:mt-0">
              Owner Notified
            </span>
          </div>

          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-red-50 rounded-md p-3 sm:p-4">
            <div>
              <h4 class="text-red-500 font-semibold text-base sm:text-lg">Item Alert</h4>
              <p class="text-xs sm:text-sm text-gray-600">New match for your lost Student ID Card</p>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- How It Works Section -->
    <div id="how-it-works" class="py-10 sm:py-16 md:py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-sm sm:text-base text-primary font-semibold tracking-wide uppercase">Process</h2>
          <p class="mt-2 text-2xl sm:text-3xl md:text-4xl leading-8 font-extrabold tracking-tight text-gray-900">
            How FoundIt Works
          </p>
          <p class="mt-4 max-w-2xl text-base sm:text-lg md:text-xl text-gray-500 mx-auto">
            Our platform connects people who've lost items with those who've found them, making the return process simple and efficient.
          </p>
        </div>

        <div class="mt-10 sm:mt-16">
          <div class="space-y-10 lg:space-y-0 lg:grid lg:grid-cols-3 lg:gap-x-8">
            <div class="relative">
              <div class="absolute flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-md bg-primary text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                </svg>
              </div>
              <div class="relative pl-12 sm:pl-16">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900">1. Report Item</h3>
                <p class="mt-2 text-sm sm:text-base text-gray-500">
                  Lost something? Submit a detailed description, including where and when you last had it. Found something? Upload photos and details about where you found it.
                </p>
              </div>
            </div>

            <div class="relative mt-10 lg:mt-0">
              <div class="absolute flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-md bg-primary text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
              </div>
              <div class="relative pl-12 sm:pl-16">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900">2. Match & Notify</h3>
                <p class="mt-2 text-sm sm:text-base text-gray-500">
                  Our system automatically matches lost items with found ones based on descriptions, locations, and dates. Both parties receive notifications when a potential match is found.
                </p>
              </div>
            </div>

            <div class="relative mt-10 lg:mt-0">
              <div class="absolute flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-md bg-primary text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
              </div>
              <div class="relative pl-12 sm:pl-16">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900">3. Verify & Return</h3>
                <p class="mt-2 text-sm sm:text-base text-gray-500">
                  After verification, we facilitate a safe exchange through our campus lost and found office. The finder receives confirmation when the item is successfully returned.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Features Section -->
    <div class="py-10 sm:py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-sm sm:text-base text-primary font-semibold tracking-wide uppercase">Features</h2>
          <p class="mt-2 text-2xl sm:text-3xl md:text-4xl leading-8 font-extrabold tracking-tight text-gray-900">
            Why Use FoundIt?
          </p>
        </div>

        <div class="mt-10 sm:mt-16">
          <div class="space-y-8 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
            <div class="flex">
              <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-md bg-primary text-white">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                  </svg>
                </div>
              </div>
              <div class="ml-3 sm:ml-4">
                <h3 class="text-base sm:text-lg font-medium text-gray-900">Fast & Efficient</h3>
                <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-500">
                  Our algorithm quickly connects lost items with found ones, saving you time and stress.
                </p>
              </div>
            </div>

            <div class="flex">
              <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-md bg-primary text-white">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                  </svg>
                </div>
              </div>
              <div class="ml-3 sm:ml-4">
                <h3 class="text-base sm:text-lg font-medium text-gray-900">Secure & Reliable</h3>
                <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-500">
                  All exchanges are verified and occur through our campus lost and found office for maximum security.
                </p>
              </div>
            </div>

            <div class="flex">
              <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-md bg-primary text-white">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                  </svg>
                </div>
              </div>
              <div class="ml-3 sm:ml-4">
                <h3 class="text-base sm:text-lg font-medium text-gray-900">Real-time Notifications</h3>
                <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-500">
                  Receive immediate alerts when your item is found or when someone claims the item you found.
                </p>
              </div>
            </div>

            <div class="flex">
              <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-md bg-primary text-white">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                  </svg>
                </div>
              </div>
              <div class="ml-3 sm:ml-4">
                <h3 class="text-base sm:text-lg font-medium text-gray-900">Campus-Specific</h3>
                <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-500">
                  Designed specifically for our campus community, making it easier to find items in familiar locations.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Testimonials Section -->
    <div class="py-10 sm:py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-sm sm:text-base text-primary font-semibold tracking-wide uppercase">Testimonials</h2>
          <p class="mt-2 text-2xl sm:text-3xl md:text-4xl leading-8 font-extrabold tracking-tight text-gray-900">
            What Students Say
          </p>
        </div>
        
        <div class="mt-8 sm:mt-12">
          <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <div class="bg-gray-50 rounded-lg shadow-md p-4 sm:p-6">
              <div class="flex items-center mb-3 sm:mb-4">
                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-purple-200 flex items-center justify-center text-primary font-bold text-sm sm:text-base">
                  AB
                </div>
                <div class="ml-3 sm:ml-4">
                  <h4 class="text-base sm:text-lg font-medium text-gray-900">Anass B.</h4>
                  <p class="text-xs sm:text-sm text-gray-500">Biology Major</p>
                </div>
              </div>
              <p class="text-sm sm:text-base text-gray-600">"Found someone's laptop in the library. Posted it on FoundIt and within an hour was able to return it to its rightful owner. Great to see the relief on their face!"</p>
              <div class="mt-2 sm:mt-3 flex text-yellow-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.006Z" clip-rule="evenodd" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.006Z" clip-rule="evenodd" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.006Z" clip-rule="evenodd" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.006Z" clip-rule="evenodd" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.006Z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg shadow-md p-4 sm:p-6">
              <div class="flex items-center mb-3 sm:mb-4">
                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-purple-200 flex items-center justify-center text-primary font-bold text-sm sm:text-base">
                  WD
                </div>
                <div class="ml-3 sm:ml-4">
                  <h4 class="text-base sm:text-lg font-medium text-gray-900">Wissam D.</h4>
                  <p class="text-xs sm:text-sm text-gray-500">Computer Science Major</p>
                </div>
              </div>
              <p class="text-sm sm:text-base text-gray-600">"Lost my case keys on last Friday and was so stressed about my locked in laptop. Someone found them and posted on FoundIt. Process was super smooth!"</p>
              <div class="mt-2 sm:mt-3 flex text-yellow-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.006Z" clip-rule="evenodd" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.006Z" clip-rule="evenodd" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.006Z" clip-rule="evenodd" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.006Z" clip-rule="evenodd" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.006Z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg shadow-md p-4 sm:p-6">
              <div class="flex items-center mb-3 sm:mb-4">
                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-purple-200 flex items-center justify-center text-primary font-bold text-sm sm:text-base">
                  HA
                </div>
                <div class="ml-3 sm:ml-4">
                  <h4 class="text-base sm:text-lg font-medium text-gray-900">Hissoune A.</h4>
                  <p class="text-xs sm:text-sm text-gray-500">Business Administration</p>
                </div>
              </div>
              <p class="text-sm sm:text-base text-gray-600">"I lost my phone at the hour of exit and was freaking out. Posted it on FoundIt and had it back next day. Absolute lifesaver!"</p>
              <div class="mt-2 sm:mt-3 flex text-yellow-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.006Z" clip-rule="evenodd" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.006Z" clip-rule="evenodd" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.006Z" clip-rule="evenodd" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.006Z" clip-rule="evenodd" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.006Z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CTA Section -->
    <section class="bg-primary text-white py-16">
      <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row items-center md:justify-between">
        <!-- Text Block -->
        <div class="text-center md:text-left">
          <h2 class="text-3xl md:text-4xl font-bold mb-2">
            Ready to find what you've lost?
          </h2>
          <h3 class="text-white/80 text-xl md:text-2xl font-bold">
            Sign up for FoundIt today.
          </h3>
        </div>
        <!-- Button -->
        <div class="mt-6 md:mt-0">
          <router-link to="/register"
            class="bg-white text-primary px-5 py-3 rounded-md font-semibold hover:bg-gray-100">
            Get Started - It's Free
          </router-link>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-10">
      <div class="max-w-7xl mx-auto px-4 md:flex md:justify-between">
        <div class="mb-6 md:mb-0">
          <h3 class="text-2xl font-bold text-white mb-2">FoundIt</h3>
          <p class="max-w-sm text-gray-400">
            The campus lost and found platform that helps you reconnect with your belongings quickly and easily.
          </p>
        </div>
        <div class="mb-6 md:mb-0">
          <h4 class="text-lg font-semibold text-white mb-2">Quick Links</h4>
          <ul class="space-y-1">
            <li><router-link to="/" class="hover:text-white hover:border-white hover:border-b-2">Home</router-link></li>
            <li><a href="#how-it-works" class="hover:text-white hover:border-white hover:border-b-2">How it works</a></li>
            <li><a href="#" class="hover:text-white hover:border-white hover:border-b-2">Forum</a></li>
            <li><a href="#" class="hover:text-white hover:border-white hover:border-b-2">FAQs</a></li>
          </ul>
        </div>
        <div>
          <h4 class="text-lg font-semibold text-white mb-2">Contact info</h4>
          <ul class="space-y-1 text-gray-400">
            <li>Reception, Main entrance</li>
            <li>YouCode CCI</li>
            <li>+212 010101010</li>
            <li>help@foundit.ma</li>
            <li>Mon-Fri: 9am - 5pm</li>
          </ul>
        </div>
      </div>
      <div class="border-t border-gray-800 mt-6 pt-6 text-center text-gray-500">
        &copy; {{ new Date().getFullYear() }} FoundIt. All rights reserved.
      </div>
    </footer>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';

// Add Font Awesome if needed
onMounted(() => {
  // Load Font Awesome if not already loaded
  if (!document.getElementById('font-awesome-css')) {
    const link = document.createElement('link');
    link.id = 'font-awesome-css';
    link.rel = 'stylesheet';
    link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css';
    document.head.appendChild(link);
  }
});
</script>

<style scoped>
/* Add any component-specific styles here */
html {
  scroll-behavior: smooth;
}

/* Animations for the card */
.rotate-[-4deg]:hover {
  transform: rotate(0deg) translateY(-8px);
  transition: transform 0.3s ease-in-out;
}
</style>