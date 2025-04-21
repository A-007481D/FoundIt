<template>
    <div class="app-container">
        <!-- Always show navbar, the component will handle different states -->
        <NavbarView />
        
        <!-- Use transition for smooth page changes -->
        <router-view v-slot="{ Component }">
            <transition name="fade" mode="out-in">
                <component :is="Component" :key="$route.fullPath" />
            </transition>
        </router-view>
    </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth.store';
import NavbarView from "@/components/NavbarView.vue";
import { computed, watch } from 'vue';
import { useRoute } from 'vue-router';

const authStore = useAuthStore();
const route = useRoute();

// Force component re-rendering when route changes
const currentPath = computed(() => route.fullPath);
</script>

<style>
/* Global styles */
.app-container {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Transition effects */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.15s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
