<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import GlobalNavs from './components/Navs/GlobalNavs.vue'
import NotifError from './components/Notif/NotifError.vue'
import NotifLoading from './components/Notif/NotifLoading.vue'

const authStore = useAuthStore()

onMounted(async () => {
  // Check authentication status on app load
  if (authStore.token) {
    await authStore.checkAuth()
  }
})
</script>

<template>
  <div id="app" class="min-h-screen bg-bg">
    <GlobalNavs />
    <!-- Main Content -->
    <main>
      <router-view />
    </main>
    <NotifError />
    <NotifLoading />
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