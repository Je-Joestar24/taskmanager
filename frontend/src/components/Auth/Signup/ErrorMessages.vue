<template>
    <!-- Error Messages -->
    <div v-if="authStore.error" class="bg-error/10 border border-error/20 rounded-card p-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-error mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            <p class="text-error text-sm">{{ authStore.error }}</p>
        </div>
    </div>

    <div v-if="props.passwordError" class="bg-warning/10 border border-warning/20 rounded-card p-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-warning mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            <p class="text-warning text-sm">{{ props.passwordError }}</p>
        </div>
    </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { defineProps } from 'vue';

const authStore = useAuthStore()

const props = defineProps({
    passwordError: {
        type: String,
        required: true
    }
})
</script>