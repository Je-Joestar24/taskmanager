<template>

    <!-- Login Button -->
    <button type="submit" :disabled="authStore.isLoading"
        class="w-full bg-primary text-text-inverse py-3 px-4 rounded-card font-medium hover:bg-accent focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
        <span v-if="authStore.isLoading" class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-text-inverse" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            Signing in...
        </span>
        <span v-else>Sign In</span>
    </button>

    <!-- Divider -->
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-border"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-bg-card text-text-muted">or</span>
        </div>
    </div>

    <!-- Sign Up Link -->
    <div class="text-center">
        <p class="text-text-muted">
            Don't have an account?
            <RouterLink to="/signup" class="text-primary hover:text-accent font-medium transition-colors duration-200">
                Create Account
            </RouterLink>
        </p>
    </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { RouterLink } from 'vue-router';

const authStore = useAuthStore();
</script>