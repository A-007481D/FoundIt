// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';

// Auth views
import LoginView from '@/views/auth/LoginView.vue';
import RegisterView from '@/views/auth/RegisterView.vue';
import ForgotPasswordView from '@/views/auth/ForgotPasswordView.vue';
import ResetPasswordView from '@/views/auth/ResetPasswordView.vue';
import VerifyEmailView from '@/views/auth/VerifyEmailView.vue';

// Page views
import LandingView from '@/views/pages/LandingView.vue';
import DiscoverView from "@/views/pages/DiscoverView.vue";
import MatchesView from "@/views/pages/MatchesView.vue";

// Admin views
import AdminLayout from '@/views/admin/AdminLayout.vue';
import AdminDashboard from '@/views/admin/DashboardView.vue';

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
        name: 'Home',
        component: LandingView,
        meta: { guest: true }
    },
    {
        path: '/home',
        name: 'HomePage',
        component: LandingView,
        meta: { guest: true }
    },
    // Admin routes
    {
        path: '/admin',
        component: AdminLayout,
        meta: { requiresAuth: true, adminOnly: true },
        children: [
            {
                path: '',
                name: 'AdminDashboard',
                component: AdminDashboard,
                meta: { requiresAuth: true, adminOnly: true }
            }
        ]
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/'
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Navigation guards
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    const userStr = localStorage.getItem('user');
    let user = null;
    
    if (userStr) {
        try {
            user = JSON.parse(userStr);
        } catch (e) {
            console.error('Error parsing user from localStorage', e);
        }
    }
    
    // For protected routes
    if (to.meta.requiresAuth && !token) {
        // Redirect to login if not authenticated
        return next({ 
            name: 'Login',
            query: { redirect: to.fullPath } // Store the path user was trying to access
        });
    }
    
    // For admin-only routes
    if (to.meta.adminOnly && (!user || user.role !== 'admin')) {
        // Redirect to discover if not an admin
        return next({ name: 'Discover' });
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