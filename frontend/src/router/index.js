// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';

// Auth views
import LoginView from '@/views/auth/LoginView.vue';
import RegisterView from '@/views/auth/RegisterView.vue';
import ForgotPasswordView from '@/views/auth/ForgotPasswordView.vue';
import ResetPasswordView from '@/views/auth/ResetPasswordView.vue';
import VerifyEmailView from '@/views/auth/VerifyEmailView.vue';
import DiscoverView from "@/views/pages/DiscoverView.vue";
import MatchesView from "@/views/pages/MatchesView.vue";
// Protected area example (dashboard)
// import DashboardView from '@/views/DashboardView.vue';

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: LoginView,
        meta: { guest: true }
    },
    {
        path: '/register',
        name: 'Register',
        component: RegisterView,
        meta: { guest: true }
    },
    {
        path: '/forgot-password',
        name: 'ForgotPassword',
        component: ForgotPasswordView,
        meta: { guest: true }
    },
    {
        path: '/reset-password',
        name: 'ResetPassword',
        component: ResetPasswordView,
        meta: { guest: true }
    },
    {
        path: '/verify-email/:id/:hash',
        name: 'VerifyEmail',
        component: VerifyEmailView,
        meta: { guest: true }
    },
    {
        path: '/discover',
        name: 'Discover',
        component: DiscoverView,
        meta: { requiresAuth: true }
    },
    {
        path: '/matches',
        name: 'Matches',
        component: MatchesView,
        meta: { requiresAuth: true }
    },
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/login'
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Navigation guards
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    const user = localStorage.getItem('user');
    
    // For protected routes
    if (to.meta.requiresAuth && !token) {
        // Redirect to login if not authenticated
        return next({ 
            name: 'Login',
            query: { redirect: to.fullPath } // Store the path user was trying to access
        });
    }
    
    // For guest-only routes (login, register, etc.)
    if (to.meta.guest && token) {
        // If already authenticated, redirect to discover page
        return next({ name: 'Discover' });
    }
    
    // Continue navigation
    next();
});

// After navigation completes
router.afterEach((to, from) => {
    // Scroll to top after navigation
    window.scrollTo(0, 0);
});

export default router;