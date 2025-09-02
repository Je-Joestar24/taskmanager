<template>
  <div class="min-h-screen bg-bg flex items-center justify-center p-4">
    <div class="w-full max-w-6xl bg-bg-card rounded-card shadow-xl border border-border overflow-hidden">
      <div class="grid lg:grid-cols-2 min-h-[600px]">
        <!-- Left Panel - Decorative -->
        <div class="bg-gradient-to-br from-primary to-accent p-8 flex flex-col justify-center items-center relative overflow-hidden">
          <!-- Background Pattern -->
          <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-20 h-20 bg-text-inverse rounded-full animate-pulse"></div>
            <div class="absolute top-32 right-16 w-16 h-16 bg-text-inverse rounded-full animate-bounce"></div>
            <div class="absolute bottom-20 left-20 w-12 h-12 bg-text-inverse rounded-full animate-pulse"></div>
            <div class="absolute bottom-32 right-10 w-8 h-8 bg-text-inverse rounded-full animate-bounce"></div>
          </div>
          
          <!-- Main Illustration -->
          <div class="relative z-10 text-center">
            <div class="w-32 h-32 mx-auto mb-6 bg-text-inverse rounded-full flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-300">
              <svg class="w-16 h-16 text-primary" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-text-inverse mb-4">TaskManager</h3>
            <p class="text-text-inverse/80 text-sm leading-relaxed">
              Organize your tasks efficiently and boost your productivity with our intuitive task management system.
            </p>
          </div>
          
          <!-- Decorative Elements -->
          <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <div class="w-2 h-2 bg-text-inverse/60 rounded-full"></div>
            <div class="w-2 h-2 bg-text-inverse rounded-full"></div>
            <div class="w-2 h-2 bg-text-inverse/60 rounded-full"></div>
          </div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="p-8 flex flex-col justify-center">
          <!-- Header -->
          <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
              <h1 class="text-3xl font-bold text-text">Welcome Back</h1>
              <RouterLink 
                to="/" 
                class="text-text-muted hover:text-primary transition-colors duration-200 flex items-center group"
              >
                <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Home
              </RouterLink>
            </div>
            <p class="text-text-muted">Sign in to your account to continue</p>
          </div>

          <!-- Login Form -->
          <form @submit.prevent="handleLogin" class="space-y-6">
            <!-- Email Field -->
            <div class="space-y-2">
              <label for="email" class="block text-sm font-medium text-text">
                Email Address
              </label>
              <div class="relative">
                <input 
                  id="email"
                  v-model="form.email"
                  type="email"
                  required
                  placeholder="Enter your email"
                  class="w-full px-4 py-3 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 text-text placeholder-text-muted"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
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
                <input 
                  id="password"
                  v-model="form.password"
                  type="password"
                  required
                  placeholder="Enter your password"
                  class="w-full px-4 py-3 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 text-text placeholder-text-muted"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                </div>
              </div>
            </div>

            <!-- Error Message -->
            <div v-if="authStore.error" class="bg-error/10 border border-error/20 rounded-card p-4">
              <div class="flex items-center">
                <svg class="w-5 h-5 text-error mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="text-error text-sm">{{ authStore.error }}</p>
              </div>
            </div>

            <!-- Login Button -->
            <button 
              type="submit" 
              :disabled="authStore.isLoading"
              class="w-full bg-primary text-text-inverse py-3 px-4 rounded-card font-medium hover:bg-accent focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
            >
              <span v-if="authStore.isLoading" class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-text-inverse" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
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
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

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