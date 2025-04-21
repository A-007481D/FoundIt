<template>
  <div class="admin-layout bg-gray-100 min-h-screen">
    <!-- Admin Navbar -->
<!--    <header class="bg-white border-b border-gray-200 shadow-sm">-->
<!--      <div class="container mx-auto px-4 sm:px-6 lg:px-8">-->
<!--        <div class="flex justify-between h-16 items-center">-->
<!--          <div class="flex items-center">-->
<!--            <router-link to="/admin" class="flex items-center gap-2 font-bold">-->
<!--              <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-white">-->
<!--                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
<!--                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />-->
<!--                </svg>-->
<!--              </div>-->
<!--              <span class="text-xl">Admin</span>-->
<!--            </router-link>-->
<!--          </div>-->

<!--          <div class="flex items-center gap-4">-->
<!--            <span class="text-sm text-gray-600">{{ userFullName }}</span>-->
<!--            <div class="relative" ref="dropdownRef">-->
<!--              <button @click="toggleUserMenu" class="h-8 w-8 rounded-full hover:ring-2 hover:ring-muted">-->
<!--                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">-->
<!--                  {{ userInitials }}-->
<!--                </div>-->
<!--              </button>-->
<!--              <transition name="fade">-->
<!--                <div v-show="showUserMenu" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">-->
<!--                  <div class="py-1">-->
<!--                    <router-link to="/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">-->
<!--                      Main Site-->
<!--                    </router-link>-->
<!--                    <div class="border-t my-1"></div>-->
<!--                    <button-->
<!--                      @click="logout"-->
<!--                      class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"-->
<!--                    >-->
<!--                      Log out-->
<!--                    </button>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </transition>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </header>-->

    <div class="flex">
      <!-- Sidebar -->
      <aside class="w-64 bg-white border-r border-gray-200 min-h-[calc(100vh-4rem)] hidden md:block">
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

      <!-- Mobile sidebar toggle -->
      <div class="md:hidden p-4">
        <button 
          @click="toggleSidebar" 
          class="p-2 rounded-md hover:bg-gray-200 transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>

      <!-- Mobile sidebar -->
      <div 
        v-if="showSidebar" 
        class="fixed inset-0 z-40 md:hidden"
        @click="showSidebar = false"
      >
        <div class="fixed inset-0 bg-black opacity-30"></div>
        <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out">
          <div class="p-4 border-b flex justify-between items-center">
            <h2 class="font-bold text-lg">Admin Menu</h2>
            <button @click="showSidebar = false" class="p-2 rounded-md hover:bg-gray-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <nav class="p-4">
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
                  to="/admin/items" 
                  exact-active-class="bg-primary text-white"
                  class="flex items-center gap-3 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors"
                  @click="showSidebar = false"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                  </svg>
                  Items
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
      <main class="flex-1 p-6">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth.store';

// Refs
const showUserMenu = ref(false);
const showSidebar = ref(false);
const dropdownRef = ref(null);

// Store
const authStore = useAuthStore();
const router = useRouter();

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

// Lifecycle hooks
onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
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
