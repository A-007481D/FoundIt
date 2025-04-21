<template>
  <div class="bg-white text-gray-800">
    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 py-12 md:py-20 flex flex-col-reverse md:flex-row items-center">
      <div class="w-full md:w-1/2 md:pr-8 mt-8 md:mt-0">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 leading-tight">
          Lost It? Don't Stress, <br> Just
          <span class="text-primary">FoundIt!</span>
        </h1>
        <p class="text-gray-600 text-lg md:text-xl mb-6">
          Use FoundIt to swiftly report lost items, receive real-time alerts,
          and securely reconnect with your belongings. Experience a smarter,
          community-powered solution anytime, anywhere.
        </p>
        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
          <router-link to="/register" class="inline-block px-6 py-3 bg-primary text-white font-medium rounded-md hover:bg-primary-dark text-center">
            Get Started
          </router-link>
          <a href="#" class="inline-block px-6 py-3 border border-primary text-primary font-medium rounded-md hover:bg-primary-50 text-center">
            Watch Demo
          </a>
        </div>
        <div class="border-t-2 mt-5 max-w-7xl mx-auto px-6 py-8 grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
          <div>
            <h3 class="text-2xl sm:text-3xl font-bold text-primary">2k+</h3>
            <p class="text-gray-600">Items Found</p>
          </div>
          <div>
            <h3 class="text-2xl sm:text-3xl font-bold text-primary">99%</h3>
            <p class="text-gray-600">Return Rate</p>
          </div>
          <div>
            <h3 class="text-2xl sm:text-3xl font-bold text-primary">24H</h3>
            <p class="text-gray-600">Avg Return Time</p>
          </div>
        </div>
      </div>

      <div class="relative w-full max-w-lg">
        <div class="absolute inset-0 transform rotate-[-4deg] bg-gray-200 rounded-xl shadow-lg"></div>

        <div class="relative bg-white rounded-xl shadow-lg p-6 transition-transform duration-300 transform rotate-[-4deg] hover:rotate-0 hover:-translate-y-2">

          <div class="flex items-start md:items-center justify-between bg-purple-50 rounded-md p-4 mb-4">
            <div>
              <h4 class="text-primary font-semibold text-lg">Lost: Student ID Card</h4>
              <p class="text-sm text-gray-600">Last seen: Library 3rd floor</p>
            </div>
            <span class="text-primary font-medium mt-2 md:mt-0">
              Match Found!
            </span>
          </div>

          <div class="flex items-start md:items-center justify-between bg-green-50 rounded-md p-4 mb-4">
            <div>
              <h4 class="text-green-600 font-semibold text-lg">Found: Student ID Card</h4>
              <p class="text-sm text-gray-600">Location: Library 3rd floor</p>
            </div>
            <span class="text-green-500 font-medium mt-2 md:mt-0">
              Owner Notified
            </span>
          </div>

          <div class="flex items-start md:items-center justify-between bg-red-50 rounded-md p-4">
            <div>
              <h4 class="text-red-500 font-semibold text-lg">Item Alert</h4>
              <p class="text-sm text-gray-600">New match for your lost Student ID Card</p>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- How It Works Section -->
    <div id="how-it-works" class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
          <h2 class="text-base text-primary font-semibold tracking-wide uppercase">Process</h2>
          <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
            How FoundIt Works
          </p>
          <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
            Our platform connects people who've lost items with those who've found them, making the return process simple and efficient.
          </p>
        </div>

        <div class="mt-16">
          <div class="space-y-12 lg:space-y-0 lg:grid lg:grid-cols-3 lg:gap-x-8">
            <div class="relative">
              <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                <i class="fas fa-clipboard-list text-xl"></i>
              </div>
              <div class="relative pl-16">
                <h3 class="text-xl font-bold text-gray-900">1. Report Item</h3>
                <p class="mt-2 text-base text-gray-500">
                  Lost something? Submit a detailed description, including where and when you last had it. Found something? Upload photos and details about where you found it.
                </p>
              </div>
            </div>

            <div class="relative mt-10 lg:mt-0">
              <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                <i class="fas fa-search text-xl"></i>
              </div>
              <div class="relative pl-16">
                <h3 class="text-xl font-bold text-gray-900">2. Match & Notify</h3>
                <p class="mt-2 text-base text-gray-500">
                  Our system automatically matches lost items with found ones based on descriptions, locations, and dates. Both parties receive notifications when a potential match is found.
                </p>
              </div>
            </div>

            <div class="relative mt-10 lg:mt-0">
              <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                <i class="fas fa-handshake text-xl"></i>
              </div>
              <div class="relative pl-16">
                <h3 class="text-xl font-bold text-gray-900">3. Verify & Return</h3>
                <p class="mt-2 text-base text-gray-500">
                  After verification, we facilitate a safe exchange through our campus lost and found office. The finder receives confirmation when the item is successfully returned.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
          <h2 class="text-base text-primary font-semibold tracking-wide uppercase">Features</h2>
          <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
            Why Use FoundIt?
          </p>
        </div>

        <div class="mt-16">
          <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
            <div class="flex">
              <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                  <i class="fas fa-bolt text-xl"></i>
                </div>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-medium text-gray-900">Fast & Efficient</h3>
                <p class="mt-2 text-base text-gray-500">
                  Our algorithm quickly connects lost items with found ones, saving you time and stress.
                </p>
              </div>
            </div>

            <div class="flex">
              <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                  <i class="fas fa-shield-alt text-xl"></i>
                </div>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-medium text-gray-900">Secure & Reliable</h3>
                <p class="mt-2 text-base text-gray-500">
                  All exchanges are verified and occur through our campus lost and found office for maximum security.
                </p>
              </div>
            </div>

            <div class="flex">
              <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                  <i class="fas fa-bell text-xl"></i>
                </div>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-medium text-gray-900">Real-time Notifications</h3>
                <p class="mt-2 text-base text-gray-500">
                  Receive immediate alerts when your item is found or when someone claims the item you found.
                </p>
              </div>
            </div>

            <div class="flex">
              <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                  <i class="fas fa-building text-xl"></i>
                </div>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-medium text-gray-900">Campus-Specific</h3>
                <p class="mt-2 text-base text-gray-500">
                  Designed specifically for our campus community, making it easier to find items in familiar locations.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Testimonials Section -->
    <div class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
          <h2 class="text-base text-primary font-semibold tracking-wide uppercase">Testimonials</h2>
          <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
            What Students Say
          </p>
        </div>
        
        <div class="mt-12">
          <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            <div class="bg-gray-50 rounded-lg shadow-md p-6">
              <div class="flex items-center mb-4">
                <div class="h-10 w-10 rounded-full bg-purple-200 flex items-center justify-center text-primary font-bold">
                  AB
                </div>
                <div class="ml-4">
                  <h4 class="text-lg font-medium text-gray-900">Anass B.</h4>
                  <p class="text-sm text-gray-500">Biology Major</p>
                </div>
              </div>
              <p class="text-gray-600">"Found someone's laptop in the library. Posted it on FoundIt and within an hour was able to return it to its rightful owner. Great to see the relief on their face!"</p>
              <div class="mt-3 flex text-yellow-400">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg shadow-md p-6">
              <div class="flex items-center mb-4">
                <div class="h-10 w-10 rounded-full bg-purple-200 flex items-center justify-center text-primary font-bold">
                  WD
                </div>
                <div class="ml-4">
                  <h4 class="text-lg font-medium text-gray-900">Wissam D.</h4>
                  <p class="text-sm text-gray-500">Computer Science Major</p>
                </div>
              </div>
              <p class="text-gray-600">"Lost my case keys on last Friday and was so stressed about my locked in laptop. Someone found them and posted on FoundIt. Process was super smooth!"</p>
              <div class="mt-3 flex text-yellow-400">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
              </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg shadow-md p-6">
              <div class="flex items-center mb-4">
                <div class="h-10 w-10 rounded-full bg-purple-200 flex items-center justify-center text-primary font-bold">
                  HA
                </div>
                <div class="ml-4">
                  <h4 class="text-lg font-medium text-gray-900">Hissoune A.</h4>
                  <p class="text-sm text-gray-500">Business Administration</p>
                </div>
              </div>
              <p class="text-gray-600">"I lost my phone at the hour of exit and was freaking out. Posted it on FoundIt and had it back next day. Absolute lifesaver!"</p>
              <div class="mt-3 flex text-yellow-400">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
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