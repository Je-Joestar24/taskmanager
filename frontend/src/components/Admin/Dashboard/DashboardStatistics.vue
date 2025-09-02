<template>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users Card -->
        <div
            class="bg-bg-card border border-border rounded-card p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-text-muted text-sm font-medium">Total Users</p>
                    <p class="text-3xl font-bold text-text mt-2">{{ adminStore.dashboardStats.total_users }}</p>
                </div>
                <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-success">+12%</span>
                <span class="text-text-muted ml-2">from last month</span>
            </div>
        </div>

        <!-- Total Tasks Card -->
        <div
            class="bg-bg-card border border-border rounded-card p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-text-muted text-sm font-medium">Total Tasks</p>
                    <p class="text-3xl font-bold text-text mt-2">{{ adminStore.dashboardStats.total_tasks }}</p>
                </div>
                <div class="w-12 h-12 bg-secondary/10 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-success">+8%</span>
                <span class="text-text-muted ml-2">from last month</span>
            </div>
        </div>

        <!-- Pending Tasks Card -->
        <div
            class="bg-bg-card border border-border rounded-card p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-text-muted text-sm font-medium">Pending Tasks</p>
                    <p class="text-3xl font-bold text-warning mt-2">{{ adminStore.dashboardStats.pending_tasks }}</p>
                </div>
                <div class="w-12 h-12 bg-warning/10 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-error">-5%</span>
                <span class="text-text-muted ml-2">from last month</span>
            </div>
        </div>

        <!-- Completion Rate Card -->
        <div
            class="bg-bg-card border border-border rounded-card p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-text-muted text-sm font-medium">Completion Rate</p>
                    <p class="text-3xl font-bold text-success mt-2">{{ adminStore.dashboardStats.completion_rate }}%</p>
                </div>
                <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-success">+15%</span>
                <span class="text-text-muted ml-2">from last month</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useAdminStore } from '@/stores/admin';

const adminStore = useAdminStore()
</script>