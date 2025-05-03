<template>
  <div class="admin-layout bg-gray-100 min-h-screen">
    <!-- Using shared Navbar -->
    <header class="hidden">
    </header>
    
    <div class="flex flex-col md:flex-row">
      <!-- Desktop Sidebar -->
      <aside class="w-64 bg-white border-r border-gray-200 min-h-[calc(100vh-4rem)] hidden md:block sticky top-0">
        <nav class="p-4">
          <ul class="space-y-1">
            <li>
              <router-link 
                to="/admin" 
                exact-active-class="bg-primary text-white"
                class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
              </router-link>
            </li>
            <li>
              <router-link 
                to="/admin/users" 
                exact-active-class="bg-primary text-white"
                class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                User Management
              </router-link>
            </li>
            <li>
              <router-link 
                to="/admin/items" 
                exact-active-class="bg-primary text-white"
                class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Item Management
              </router-link>
            </li>
            <li>
              <router-link 
                to="/admin/reports" 
                exact-active-class="bg-primary text-white"
                class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Reports
              </router-link>
            </li>
            <li>
              <router-link 
                to="/admin/activity-logs" 
                exact-active-class="bg-primary text-white"
                class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Activity Logs
              </router-link>
            </li>
            <li>
              <router-link 
                to="/admin/sessions" 
                exact-active-class="bg-primary text-white"
                class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                User Sessions
              </router-link>
            </li>
            <li>
              <router-link 
                to="/admin/settings" 
                exact-active-class="bg-primary text-white"
                class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Settings
              </router-link>
            </li>
          </ul>
        </nav>
      </aside>

      <!-- Mobile header with menu button -->
      <div class="sticky top-0 z-20 bg-white shadow-sm border-b md:hidden">
        <div class="flex items-center justify-between px-4 py-2">
        <button 
          @click="toggleSidebar" 
          class="p-2 rounded-md hover:bg-gray-200 transition-colors"
            aria-label="Toggle menu"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
          <h1 class="text-lg font-semibold">Admin Panel</h1>
          <div class="w-6"></div> <!-- Empty div for flexbox spacing -->
        </div>
      </div>

      <!-- Mobile sidebar -->
      <div 
        v-if="showSidebar" 
        class="fixed inset-0 z-40 md:hidden"
        aria-modal="true"
      >
        <!-- Backdrop -->
        <div 
          class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" 
          @click="showSidebar = false"
          aria-hidden="true"
        ></div>
        
        <!-- Sidebar panel -->
        <div 
          class="fixed inset-y-0 left-0 w-64 bg-white shadow-xl transform transition-transform duration-300 ease-in-out z-50"
          :class="showSidebar ? 'translate-x-0' : '-translate-x-full'"
        >
          <div class="p-4 border-b flex justify-between items-center">
            <h2 class="font-bold text-lg">Admin Menu</h2>
            <button 
              @click="showSidebar = false" 
              class="p-2 rounded-md hover:bg-gray-200"
              aria-label="Close menu"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <nav class="p-4 overflow-y-auto max-h-[calc(100vh-64px)]">
            <ul class="space-y-1">
              <li>
                <router-link 
                  to="/admin" 
                  exact-active-class="bg-primary text-white"
                  class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors"
                  @click="showSidebar = false"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                  </svg>
                  Dashboard
                </router-link>
              </li>
              <li>
                <router-link 
                  to="/admin/users" 
                  exact-active-class="bg-primary text-white"
                  class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors"
                  @click="showSidebar = false"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                  </svg>
                  User Management
                </router-link>
              </li>
              <li>
                <router-link 
                  to="/admin/items" 
                  exact-active-class="bg-primary text-white"
                  class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors"
                  @click="showSidebar = false"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                  </svg>
                  Item Management
                </router-link>
              </li>
              <li>
                <router-link 
                  to="/admin/reports" 
                  exact-active-class="bg-primary text-white"
                  class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors"
                  @click="showSidebar = false"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                  Reports
                </router-link>
              </li>
              <li>
                <router-link 
                  to="/admin/activity-logs" 
                  exact-active-class="bg-primary text-white"
                  class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors"
                  @click="showSidebar = false"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  Activity Logs
                </router-link>
              </li>
              <li>
                <router-link 
                  to="/admin/sessions" 
                  exact-active-class="bg-primary text-white"
                  class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors"
                  @click="showSidebar = false"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                  User Sessions
                </router-link>
              </li>
              <li>
                <router-link 
                  to="/admin/settings" 
                  exact-active-class="bg-primary text-white"
                  class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors"
                  @click="showSidebar = false"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  Settings
                </router-link>
              </li>
            </ul>
          </nav>
        </div>
      </div>

      <!-- Main content -->
      <main class="flex-1 p-4 md:p-6">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth.store';
import NavbarView from '@/components/NavbarView.vue'

// Refs
const showUserMenu = ref(false);
const showSidebar = ref(false);
const dropdownRef = ref(null);

// Store
const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();

// Close sidebar when route changes
watch(() => route.fullPath, () => {
  showSidebar.value = false;
});

// Computed
const userFullName = computed(() => authStore.userFullName);
const userInitials = computed(() => {
  if (!authStore.user) return '';
  
  const user = authStore.user;
  if (user.firstname && user.lastname) {
    return (user.firstname[0] + user.lastname[0]).toUpperCase();
  } else if (user.name) {
    const nameParts = user.name.split(' ');
    if (nameParts.length >= 2) {
      return (nameParts[0][0] + nameParts[1][0]).toUpperCase();
    } else if (nameParts.length === 1 && nameParts[0].length > 0) {
      return nameParts[0][0].toUpperCase();
    }
  }
  
  // Fallback to first letter of email
  if (user.email) {
    return user.email[0].toUpperCase();
  }
  
  return 'A';
});

// Methods
const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value;
};

const toggleSidebar = () => {
  showSidebar.value = !showSidebar.value;
};

const logout = async () => {
  await authStore.logout();
};

// Handle clicks outside of dropdown
const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    showUserMenu.value = false;
  }
};

// Prevent body scrolling when sidebar is open
watch(showSidebar, (isOpen) => {
  if (isOpen) {
    document.body.classList.add('overflow-hidden');
  } else {
    document.body.classList.remove('overflow-hidden');
  }
});

// Lifecycle hooks
onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
  document.body.classList.remove('overflow-hidden');
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
