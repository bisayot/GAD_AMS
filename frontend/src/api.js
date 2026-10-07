import axios from 'axios';

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL; // || 'https://gad-ams-2-1.onrender.com/api/';

const api = axios.create({
  baseURL: API_BASE_URL,
  timeout: 60000,
  headers: {
    'Content-Type': 'application/json'
  }
});

// Request interceptor
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('authToken');
    const userStr = localStorage.getItem('user');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    if (userStr) {
      try {
        const user = JSON.parse(userStr);
        if (user && user.id) {
          config.headers['X-User-Id'] = user.id;
        }
      } catch(e) {}
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Response interceptor
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response) {
      // 401 Unauthorized — token missing, invalid, or expired.
      // Clear local storage and redirect to login automatically.
      if (error.response.status === 401) {
        localStorage.removeItem('user');
        localStorage.removeItem('authToken');
        // Only redirect if not already on a public page
        const currentPath = window.location.pathname;
        const publicPaths = ['/login', '/register', '/forgot-password', '/reset-password', '/', '/about', '/resources', '/gad-corner', '/contact'];
        const isPublic = publicPaths.some(p => currentPath === p || currentPath.startsWith('/gad-corner'));
        if (!isPublic) {
          window.location.href = '/login';
        }
        return Promise.reject(error.response.data);
      }

      return Promise.reject(error.response.data);
    } else if (error.request) {
      // Request made but no response
      return Promise.reject({
        message: 'Please Refresh the page and try again',
        details: error.message,
        url: error.config?.url
      });
    } else {
      return Promise.reject({
        message: 'Error preparing request',
        details: error.message
      });
    }
  }
);

export default api;
