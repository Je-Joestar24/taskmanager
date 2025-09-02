<script setup>
import { computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useTasksStore } from '@/stores/tasks'
import { useAdminStore } from '@/stores/admin'
import GlobalNavs from './components/Navs/GlobalNavs.vue'

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


onMounted(async () => {
  // Check authentication status on app load
  if (authStore.token) {
    await authStore.checkAuth()
  }
})
</script>

<template>
  <div id="app" class="min-h-screen bg-bg">
    <GlobalNavs/>
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