<template>
  <div class="min-h-screen bg-bg flex items-center justify-center p-4">
    <div class="w-full max-w-6xl bg-bg-card rounded-card shadow-xl border border-border overflow-hidden">
      <div class="grid lg:grid-cols-2 min-h-[700px]">
        <!-- Left Panel - Decorative -->
        <div class="bg-gradient-to-br from-accent to-secondary p-8 flex flex-col justify-center items-center relative overflow-hidden">
          <!-- Background Pattern -->
          <div class="absolute inset-0 opacity-10">
            <div class="absolute top-16 left-12 w-24 h-24 bg-text-inverse rounded-full animate-bounce"></div>
            <div class="absolute top-40 right-20 w-18 h-18 bg-text-inverse rounded-full animate-pulse"></div>
            <div class="absolute bottom-24 left-16 w-14 h-14 bg-text-inverse rounded-full animate-bounce"></div>
            <div class="absolute bottom-40 right-12 w-10 h-10 bg-text-inverse rounded-full animate-pulse"></div>
            <div class="absolute top-1/2 left-8 w-6 h-6 bg-text-inverse rounded-full animate-bounce"></div>
          </div>
          
          <!-- Main Illustration -->
          <div class="relative z-10 text-center">
            <div class="w-36 h-36 mx-auto mb-6 bg-text-inverse rounded-full flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-300">
              <svg class="w-20 h-20 text-accent" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-text-inverse mb-4">Join TaskManager</h3>
            <p class="text-text-inverse/80 text-sm leading-relaxed">
              Start your productivity journey today. Create your account and begin organizing your tasks with our powerful management system.
            </p>
          </div>
          
          <!-- Decorative Elements -->
          <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <div class="w-2 h-2 bg-text-inverse/60 rounded-full"></div>
            <div class="w-2 h-2 bg-text-inverse/60 rounded-full"></div>
            <div class="w-2 h-2 bg-text-inverse rounded-full"></div>
          </div>
        </div>

        <!-- Right Panel - Signup Form -->
        <div class="p-8 flex flex-col justify-center">
          <!-- Header -->
          <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
              <h1 class="text-3xl font-bold text-text">Create Account</h1>
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
            <p class="text-text-muted">Sign up to get started with TaskManager</p>
          </div>

          <!-- Signup Form -->
          <form @submit.prevent="handleSignup" class="space-y-5">
            <!-- Name Field -->
            <div class="space-y-2">
              <label for="name" class="block text-sm font-medium text-text">
                Full Name
              </label>
              <div class="relative">
                <input 
                  id="name"
                  v-model="form.name"
                  type="text"
                  required
                  placeholder="Enter your full name"
                  class="w-full px-4 py-3 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 text-text placeholder-text-muted"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                </div>
              </div>
            </div>

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
                  minlength="8"
                  placeholder="Create a strong password"
                  class="w-full px-4 py-3 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 text-text placeholder-text-muted"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                </div>
              </div>
              <p class="text-xs text-text-muted">Minimum 8 characters</p>
            </div>

            <!-- Confirm Password Field -->
            <div class="space-y-2">
              <label for="password_confirmation" class="block text-sm font-medium text-text">
                Confirm Password
              </label>
              <div class="relative">
                <input 
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  type="password"
                  required
                  placeholder="Confirm your password"
                  class="w-full px-4 py-3 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 text-text placeholder-text-muted"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
            </div>

            <!-- Error Messages -->
            <div v-if="authStore.error" class="bg-error/10 border border-error/20 rounded-card p-4">
              <div class="flex items-center">
                <svg class="w-5 h-5 text-error mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="text-error text-sm">{{ authStore.error }}</p>
              </div>
            </div>
            
            <div v-if="passwordError" class="bg-warning/10 border border-warning/20 rounded-card p-4">
              <div class="flex items-center">
                <svg class="w-5 h-5 text-warning mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="text-warning text-sm">{{ passwordError }}</p>
              </div>
            </div>

            <!-- Signup Button -->
            <button 
              type="submit" 
              :disabled="authStore.isLoading || !isFormValid"
              class="w-full bg-primary text-text-inverse py-3 px-4 rounded-card font-medium hover:bg-accent focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]"
            >
              <span v-if="authStore.isLoading" class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-text-inverse" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Creating account...
              </span>
              <span v-else>Create Account</span>
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

            <!-- Login Link -->
            <div class="text-center">
              <p class="text-text-muted">
                Already have an account? 
                <RouterLink to="/login" class="text-primary hover:text-accent font-medium transition-colors duration-200">
                  Sign In
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
import { ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const passwordError = ref('')

const isFormValid = computed(() => {
  return form.value.name && 
         form.value.email && 
         form.value.password && 
         form.value.password_confirmation &&
         form.value.password === form.value.password_confirmation
})

const validatePassword = () => {
  if (form.value.password && form.value.password_confirmation) {
    if (form.value.password !== form.value.password_confirmation) {
      passwordError.value = 'Passwords do not match'
      return false
    } else {
      passwordError.value = ''
      return true
    }
  }
  return true
}

const handleSignup = async () => {
  if (!validatePassword()) {
    return
  }
  
  try {
    await authStore.register(form.value)
    
    // Redirect to intended page or dashboard
    const redirectPath = route.query.redirect || (authStore.isAdmin ? '/admin' : '/tasks')
    router.push(redirectPath)
  } catch (error) {
    // Error is already handled in the store
    console.error('Registration failed:', error)
  }
}
</script>