<template>

    <!-- Mobile Navigation -->
    <nav v-if="authStore.isAuthenticated" class="md:hidden lg:hidden bg-bg-card shadow-sm border-b border-border">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <router-link to="/tasks"
                class="text-text-muted hover:text-text block px-3 py-2 rounded-card text-base font-medium transition-colors duration-200"
                :class="{ 'text-primary bg-primary/10': $route.name === 'tasks' }">
                My Tasks
            </router-link>
            <router-link v-if="authStore.isAdmin" to="/admin"
                class="text-text-muted hover:text-text block px-3 py-2 rounded-card text-base font-medium transition-colors duration-200"
                :class="{ 'text-primary bg-primary/10': $route.path.startsWith('/admin') }">
                Admin Panel
            </router-link>
        </div>
    </nav>

</template>
<script setup>
import { useAuthStore } from '@/stores/auth';
import { RouterLink } from 'vue-router';

const authStore = useAuthStore()
</script>