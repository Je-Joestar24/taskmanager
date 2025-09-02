<template>
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-text">My Tasks</h1>
                <p class="text-text-secondary mt-2">Manage and organize your tasks efficiently</p>
            </div>
            <button @click="props.toggleCreateModal"
                class="inline-flex items-center px-4 py-2 bg-primary text-text-inverse font-medium rounded-card shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Task
            </button>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    toggleCreateModal: {
        type: Function,
        required: true
    }
})
</script>