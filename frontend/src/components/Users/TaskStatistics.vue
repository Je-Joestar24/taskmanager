<template>
    <!-- Statistics Cards -->
    <div class="grid sm:grid-cols-1  md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-bg-card rounded-card p-6 shadow-sm border border-border">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-text-inverse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-text-muted text-sm font-medium">Total Tasks</p>
                    <p class="text-2xl font-bold text-text">{{ tasksStore.statistics.total }}</p>
                </div>
            </div>
        </div>

        <div class="bg-bg-card rounded-card p-6 shadow-sm border border-border">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-warning rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-text-inverse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-text-muted text-sm font-medium">Pending</p>
                    <p class="text-2xl font-bold text-text">{{ tasksStore.statistics.pending }}</p>
                </div>
            </div>
        </div>

        <div class="bg-bg-card rounded-card p-6 shadow-sm border border-border">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-success rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-text-inverse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-text-muted text-sm font-medium">Completed</p>
                    <p class="text-2xl font-bold text-text">{{ tasksStore.statistics.completed }}</p>
                </div>
            </div>
        </div>

        <div class="bg-bg-card rounded-card p-6 shadow-sm border border-border">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-text-inverse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-text-muted text-sm font-medium">Completion Rate</p>
                    <p class="text-2xl font-bold text-text">{{ tasksStore.statistics.completion_rate }}%</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useTasksStore } from '@/stores/tasks';

const tasksStore = useTasksStore()
</script>