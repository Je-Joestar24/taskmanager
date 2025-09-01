<template>
  <div>
    <h2>Login</h2>
    
    <form @submit.prevent="handleLogin">
      <div>
        <label for="email">Email:</label>
        <input 
          id="email"
          v-model="form.email"
          type="email"
          required
        />
      </div>
      
      <div>
        <label for="password">Password:</label>
        <input 
          id="password"
          v-model="form.password"
          type="password"
          required
        />
      </div>
      
      <div v-if="authStore.error">
        <p>{{ authStore.error }}</p>
      </div>
      
      <button type="submit" :disabled="authStore.isLoading">
        {{ authStore.isLoading ? 'Logging in...' : 'Login' }}
      </button>
    </form>
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