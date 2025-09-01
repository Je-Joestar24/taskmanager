import { useAuthStore } from '@/stores/auth'

// Route guard for authentication
export const requireAuth = async (to, from, next) => {
  const authStore = useAuthStore()
  
  // Check if user is authenticated
  if (!authStore.isAuthenticated) {
    // Try to restore auth from localStorage
    const isAuthValid = await authStore.checkAuth()
    
    if (!isAuthValid) {
      next({ name: 'login', query: { redirect: to.fullPath } })
      return
    }
  }
  
  next()
}

// Route guard for admin access
export const requireAdmin = async (to, from, next) => {
  const authStore = useAuthStore()
  
  // First check authentication
  if (!authStore.isAuthenticated) {
    const isAuthValid = await authStore.checkAuth()
    
    if (!isAuthValid) {
      next({ name: 'login', query: { redirect: to.fullPath } })
      return
    }
  }
  
  // Then check admin role
  if (!authStore.isAdmin) {
    next({ name: 'dashboard', replace: true })
    return
  }
  
  next()
}

// Route guard for guest users (non-authenticated)
export const requireGuest = (to, from, next) => {
  const authStore = useAuthStore()
  
  if (authStore.isAuthenticated) {
    // Redirect to appropriate dashboard based on role
    if (authStore.isAdmin) {
      next({ name: 'admin-dashboard', replace: true })
    } else {
      next({ name: 'dashboard', replace: true })
    }
    return
  }
  
  next()
}
