import { createRouter, createWebHistory } from 'vue-router'
import routes from './routes'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

// Global navigation guard
router.beforeEach(async (to, from, next) => {
  // Set page title
  if (to.meta.title) {
    document.title = `${to.meta.title} - Task Manager`
  }

  // Check authentication status
  const authStore = useAuthStore()
  
  // If route requires guest access and user is authenticated
  if (to.meta.requireGuest && authStore.isAuthenticated) {
    if (authStore.isAdmin) {
      next({ name: 'admin-dashboard', replace: true })
    } else {
      next({ name: 'tasks', replace: true })
    }
    return
  }

  // If route requires authentication and user is not authenticated
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    const isAuthValid = await authStore.checkAuth()
    if (!isAuthValid) {
      next({ name: 'login', query: { redirect: to.fullPath } })
      return
    }
  }

  // If route requires admin and user is not admin
  if (to.meta.requiresAdmin && !authStore.isAdmin) {
    if (!authStore.isAuthenticated) {
      next({ name: 'login', query: { redirect: to.fullPath } })
    } else {
      next({ name: 'tasks', replace: true })
    }
    return
  }

  next()
})

export default router
