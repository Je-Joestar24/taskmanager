<template>

    <!-- Charts and Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Task Status Chart -->
        <div class="bg-bg-card border border-border rounded-card p-6 shadow-md">
            <h3 class="text-lg font-semibold text-text mb-4">Task Status Distribution</h3>
            <div class="space-y-4">
                <!-- Pending Tasks Bar -->
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-text-secondary">Pending</span>
                        <span class="text-text font-medium">{{ adminStore.dashboardStats.pending_tasks }}</span>
                    </div>
                    <div class="w-full bg-bg-secondary rounded-full h-3">
                        <div class="bg-warning h-3 rounded-full transition-all duration-500 ease-out"
                            :style="{ width: `${(adminStore.dashboardStats.pending_tasks / adminStore.dashboardStats.total_tasks) * 100}%` }">
                        </div>
                    </div>
                </div>

                <!-- Completed Tasks Bar -->
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-text-secondary">Completed</span>
                        <span class="text-text font-medium">{{ adminStore.dashboardStats.completed_tasks }}</span>
                    </div>
                    <div class="w-full bg-bg-secondary rounded-full h-3">
                        <div class="bg-success h-3 rounded-full transition-all duration-500 ease-out"
                            :style="{ width: `${(adminStore.dashboardStats.completed_tasks / adminStore.dashboardStats.total_tasks) * 100}%` }">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useAdminStore } from '@/stores/admin';

const adminStore = useAdminStore()
</script>