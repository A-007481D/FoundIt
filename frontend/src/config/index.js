// API Configuration
export const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';
export const API_TIMEOUT = 30000; // 30 seconds

// Authentication Configuration
export const AUTH_TOKEN_KEY = 'auth_token';
export const USER_KEY = 'user';
export const REFRESH_TOKEN_KEY = 'refresh_token';

// Storage Configuration
export const STORAGE_BASE_URL = API_BASE_URL.replace('/api', '');
export const DEFAULT_AVATAR = `${STORAGE_BASE_URL}/images/default-avatar.png`;

// Pagination Configuration
export const DEFAULT_PAGE_SIZE = 10;
export const MAX_PAGE_SIZE = 100;

// Error Handling Configuration
export const ERROR_TIMEOUT = 5000; // 5 seconds

// Feature Flags
export const ENABLE_DEBUG_MODE = import.meta.env.VITE_DEBUG_MODE === 'true';
export const ENABLE_NOTIFICATIONS = import.meta.env.VITE_ENABLE_NOTIFICATIONS === 'true';

// Theme Configuration
export const THEME_DARK = 'dark';
export const THEME_LIGHT = 'light';
export const THEME_SYSTEM = 'system';

// Version Information
export const APP_VERSION = import.meta.env.VITE_APP_VERSION || '1.0.0';
export const BUILD_TIMESTAMP = import.meta.env.VITE_BUILD_TIMESTAMP || new Date().toISOString();
