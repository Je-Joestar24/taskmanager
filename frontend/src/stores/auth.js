import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/config/api'

export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref(null)
  const token = ref(localStorage.getItem('token') || null)
  const isLoading = ref(false)
  const error = ref(null)

  // Getters
  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.role === 'admin')
  const isUser = computed(() => user.value?.role === 'user')

  // Actions
  const login = async (credentials) => {
    isLoading.value = true
    error.value = null
    
    try {
      const response = await api.post('/api/login', credentials)
      const { access_token, user: userData } = response.data
      
      token.value = access_token
      user.value = userData
      localStorage.setItem('token', access_token)
      
      // Set default authorization header for future requests
      api.defaults.headers.common['Authorization'] = `Bearer ${access_token}`
      
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Login failed'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const register = async (userData) => {
    isLoading.value = true
    error.value = null
    
    try {
      const response = await api.post('/api/register', userData)
      const { access_token, user: newUser } = response.data
      
      token.value = access_token
      user.value = newUser
      localStorage.setItem('token', access_token)
      
      // Set default authorization header for future requests
      api.defaults.headers.common['Authorization'] = `Bearer ${access_token}`
      
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Registration failed'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const logout = async () => {
    try {
      if (token.value) {
        await api.post('/api/logout')
      }
    } catch (err) {
      console.error('Logout error:', err)
    } finally {
      // Clear state regardless of API call success
      token.value = null
      user.value = null
      error.value = null
      localStorage.removeItem('token')
      delete api.defaults.headers.common['Authorization']
    }
  }

  const checkAuth = async () => {
    if (!token.value) return false
    
    try {
      // Set the token in headers
      api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
      
      // You could add an endpoint to verify token validity
      // const response = await api.get('/api/user')
      // user.value = response.data
      
      return true
    } catch (err) {
      // Token is invalid, clear it
      logout()
      return false
    }
  }

  const clearError = () => {
    error.value = null
  }

  return {
    // State
    user,
    token,
    isLoading,
    error,
    
    // Getters
    isAuthenticated,
    isAdmin,
    isUser,
    
    // Actions
    login,
    register,
    logout,
    checkAuth,
    clearError
  }
})
