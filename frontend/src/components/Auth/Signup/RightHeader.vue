<template>
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-text">Create Account</h1>
            <RouterLink to="/"
                class="text-text-muted hover:text-primary transition-colors duration-200 flex items-center group">
                <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Home
            </RouterLink>
        </div>
        <p class="text-text-muted">Sign up to get started with TaskManager</p>
    </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';
</script>