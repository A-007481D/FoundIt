<template>
    <div class="app-container">
        <NavbarView />
        
        <main class="flex-1">
            <router-view v-slot="{ Component }">
                <transition name="page" mode="out-in">
                    <div class="page-wrapper" :key="$route.fullPath">
                        <component :is="Component" />
                    </div>
                </transition>
            </router-view>
        </main>
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

main {
    flex: 1;
}

/* We're now using the external transition.css file for transitions */
</style>
