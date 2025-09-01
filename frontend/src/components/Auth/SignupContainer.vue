<template>
  <div>
    <h2>Register</h2>
    
    <form @submit.prevent="handleSignup">
      <div>
        <label for="name">Name:</label>
        <input 
          id="name"
          v-model="form.name"
          type="text"
          required
        />
      </div>
      
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
          minlength="8"
        />
      </div>
      
      <div>
        <label for="password_confirmation">Confirm Password:</label>
        <input 
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          required
        />
      </div>
      
      <div v-if="authStore.error">
        <p>{{ authStore.error }}</p>
      </div>
      
      <div v-if="passwordError">
        <p>{{ passwordError }}</p>
      </div>
      
      <button type="submit" :disabled="authStore.isLoading || !isFormValid">
        {{ authStore.isLoading ? 'Creating account...' : 'Register' }}
      </button>
    </form>
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