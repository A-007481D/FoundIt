// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';

// Auth views
import LoginView from '@/views/auth/LoginView.vue';
import RegisterView from '@/views/auth/RegisterView.vue';
import ForgotPasswordView from '@/views/auth/ForgotPasswordView.vue';
import ResetPasswordView from '@/views/auth/ResetPasswordView.vue';
import VerifyEmailView from '@/views/auth/VerifyEmailView.vue';
import ProfileView from '@/views/auth/ProfileView.vue';

// Page views
import LandingView from '@/views/pages/LandingView.vue';
import DiscoverView from "@/views/pages/DiscoverView.vue";
import MatchesView from "@/views/pages/MatchesView.vue";
import MyItemsView from "@/views/pages/MyItemsView.vue";
import ChatView from "@/views/chat/ChatView.vue";
import NotificationsView from '@/views/pages/NotificationsView.vue';

// Admin views
import AdminLayout from '@/views/admin/AdminLayout.vue';
import AdminDashboard from '@/views/admin/DashboardView.vue';
import AdminUsers from '@/views/admin/UsersView.vue';
import AdminItems from '@/views/admin/ItemsView.vue';
import AdminReports from '@/views/admin/ReportsView.vue';
import SettingsView from '@/views/admin/SettingsView.vue';

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
        path: '/',
        name: 'Landing',
        component: LandingView,
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
        path: '/my-items',
        name: 'MyItems',
        component: MyItemsView,
        meta: { requiresAuth: true }
    },
    {
        path: '/profile',
        name: 'Profile',
        component: ProfileView,
        meta: { requiresAuth: true }
    },
    {
        path: '/notifications',
        name: 'Notifications',
        component: () => import('@/views/pages/NotificationsView.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/chat/:conversationId?',
        name: 'Chat',
        component: ChatView,
        meta: { requiresAuth: true }
    },
    {
        path: '/admin',
        component: AdminLayout,
        meta: { requiresAuth: true, requiresAdmin: true },
        children: [
            {
                path: '',
                name: 'AdminDashboard',
                component: AdminDashboard
            },
            {
                path: 'users',
                name: 'AdminUsers',
                component: AdminUsers
            },
            {
                path: 'items',
                name: 'AdminItems',
                component: AdminItems
            },
            {
                path: 'reports',
                name: 'AdminReports',
                component: AdminReports
            },
            {
                path: 'settings',
                name: 'AdminSettings',
                component: SettingsView
            }
        ]
    },
    {
        path: '/item-detective',
        name: 'ItemDetective',
        component: () => import('@/views/ItemDetectiveView.vue'),
        meta: {
            requiresAuth: true,
            title: 'Item Detective'
        }
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/'
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior() {
        // Always scroll to top
        return { top: 0 };
    }
});

// Global navigation guard
router.beforeEach((to, from, next) => {
    // Set page title
    document.title = to.meta.title ? `${to.meta.title} | Found It` : 'Found It';
    
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