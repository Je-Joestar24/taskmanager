<template>
    <!-- Loading State -->
    <div v-if="notifStore.loadingStates.dashboard"
        class="fixed inset-0 bg-bg-overlay/50 flex items-center justify-center z-50">
        <div class="bg-bg-card p-6 rounded-card shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                <span class="text-text">Loading dashboard...</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useNotifStore } from '@/stores/notif';

const notifStore = useNotifStore()
</script>