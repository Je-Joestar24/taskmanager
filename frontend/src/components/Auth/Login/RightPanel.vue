<template>
    <!-- Right Panel - Login Form -->
    <div class="p-8 flex flex-col justify-center">
        <RightHeader />
        <!-- Login Form -->
        <form @submit.prevent="handleLogin" class="space-y-6">
            <!-- Email Field -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-text">
                    Email Address
                </label>
                <div class="relative">
                    <input id="email" v-model="form.email" type="email" required placeholder="Enter your email"
                        class="w-full px-4 py-3 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 text-text placeholder-text-muted" />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-text">
                    Password
                </label>
                <div class="relative">
                    <input id="password" v-model="form.password" type="password" required
                        placeholder="Enter your password"
                        class="w-full px-4 py-3 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 text-text placeholder-text-muted" />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
            </div>
            <ErrorMessage />
            <RightFooter />
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import RightHeader from './RightHeader.vue'
import RightFooter from './RightFooter.vue'
import ErrorMessage from './ErrorMessage.vue'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const form = ref({
    email: '',
    password: ''
})

const handleLogin = async () => {
    try {
        await authStore.login(form.value)

        // Redirect to intended page or dashboard
        const redirectPath = route.query.redirect || (authStore.isAdmin ? '/admin' : '/tasks')
        router.push(redirectPath)
    } catch (error) {
        // Error is already handled in the store
        console.error('Login failed:', error)
    }
}
</script>