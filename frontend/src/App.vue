<script setup>
import { computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useTasksStore } from '@/stores/tasks'
import { useAdminStore } from '@/stores/admin'

const router = useRouter()
const authStore = useAuthStore()
const tasksStore = useTasksStore()
const adminStore = useAdminStore()

// Global error state
const globalError = computed(() => {
  return authStore.error || tasksStore.error || adminStore.error
})

const clearGlobalError = () => {
  authStore.clearError()
  tasksStore.clearError()
  adminStore.clearError()
}

const logout = async () => {
  try {
    await authStore.logout()
    router.push('/login')
  } catch (error) {
    console.error('Logout error:', error)
  }
}

onMounted(async () => {
  // Check authentication status on app load
  if (authStore.token) {
    await authStore.checkAuth()
  }
})
</script>

<template>
  <div id="app" class="min-h-screen bg-bg">
    <!-- Navigation -->
    <nav v-if="authStore.isAuthenticated" class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center">
            <router-link to="/" class="flex-shrink-0">
              <h1 class="text-xl font-bold text-indigo-600">Task Manager</h1>
            </router-link>
            <div class="hidden md:block ml-10">
              <div class="flex items-baseline space-x-4">
                <router-link
                  to="/dashboard"
                  class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                  :class="{ 'text-indigo-600 bg-indigo-50': $route.name === 'dashboard' }"
                >
                  My Tasks
                </router-link>
                <router-link
                  v-if="authStore.isAdmin"
                  to="/admin"
                  class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                  :class="{ 'text-indigo-600 bg-indigo-50': $route.path.startsWith('/admin') }"
                >
                  Admin Panel
                </router-link>
              </div>
            </div>
          </div>
          
          <div class="flex items-center space-x-4">
            <div class="hidden md:block">
              <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">Welcome, {{ authStore.user?.name }}</span>
                <span
                  :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    authStore.isAdmin ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'
                  ]"
                >
                  {{ authStore.user?.role }}
                </span>
              </div>
            </div>
            <button
              @click="logout"
              class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Mobile Navigation -->
    <nav v-if="authStore.isAuthenticated" class="md:hidden bg-white shadow-sm border-b border-gray-200">
      <div class="px-2 pt-2 pb-3 space-y-1">
        <router-link
          to="/dashboard"
          class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200"
          :class="{ 'text-indigo-600 bg-indigo-50': $route.name === 'dashboard' }"
        >
          My Tasks
        </router-link>
        <router-link
          v-if="authStore.isAdmin"
          to="/admin"
          class="text-gray-600 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200"
          :class="{ 'text-indigo-600 bg-indigo-50': $route.path.startsWith('/admin') }"
        >
          Admin Panel
        </router-link>
      </div>
    </nav>

    <!-- Main Content -->
    <main>
      <router-view />
    </main>

    <!-- Global Error Toast -->
    <div
      v-if="globalError"
      class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 max-w-sm"
    >
      <div class="flex items-center justify-between">
        <span>{{ globalError }}</span>
        <button
          @click="clearGlobalError"
          class="ml-4 text-white hover:text-gray-200 focus:outline-none"
        >
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<style>
/* Global styles */
#app {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Smooth transitions */
.transition-colors {
  transition-property: color, background-color, border-color;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

/* Focus styles */
.focus\:outline-none:focus {
  outline: 2px solid transparent;
  outline-offset: 2px;
}

.focus\:ring-2:focus {
  --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
  --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
  box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
}

.focus\:ring-indigo-500:focus {
  --tw-ring-color: rgb(99 102 241);
}

.focus\:ring-red-500:focus {
  --tw-ring-color: rgb(239 68 68);
}
</style>