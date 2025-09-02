<template>
    <!-- Global Error Toast -->
    <div v-if="globalError"
        class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 max-w-sm">
        <div class="flex items-center justify-between">
            <span>{{ globalError }}</span>
            <button @click="clearGlobalError" class="ml-4 text-white hover:text-gray-200 focus:outline-none">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>

import { useTasksStore } from '@/stores/tasks'
import { useAdminStore } from '@/stores/admin'
import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

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
</script>