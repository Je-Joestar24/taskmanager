<template>
    <!-- Navigation -->
    <nav v-if="authStore.isAuthenticated" class="bg-bg-card shadow-sm border-b border-border">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <router-link to="/" class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-primary">{{ authStore.isAdmin ? 'Admin Panel' : 'Task Manager'
                            }}
                        </h1>
                    </router-link>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="hidden md:block">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-text-muted">Welcome, {{ authStore.user?.name }}</span>
                            <span :class="[
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                authStore.isAdmin ? 'bg-accent/20 text-accent' : 'bg-bg-secondary text-text-secondary'
                            ]">
                                {{ authStore.user?.role }}
                            </span>
                        </div>
                    </div>
                    <button @click="logout"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-card text-text-inverse bg-error hover:bg-error/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-error transition-colors duration-200">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const logout = async () => {
    try {
        await authStore.logout()
        router.push('/login')
    } catch (error) {
        console.error('Logout error:', error)
    }
}
</script>