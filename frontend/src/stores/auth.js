import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/config/api'
import { useNotifStore } from './notif'

export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref(JSON.parse(localStorage.getItem('user')) || null)
  const token = ref(localStorage.getItem('token') || null)
  const error = ref(null)

  // Notification store
  const notifStore = useNotifStore()

  // Getters
  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.role === 'admin')
  const isUser = computed(() => user.value?.role === 'user')

  // Actions
  const login = async (credentials) => {
    notifStore.setLoadingState('auth', true)
    error.value = null
    
    try {
      const response = await api.post('/api/login', credentials)
      const { access_token, user: userData } = response.data
      
      token.value = access_token
      user.value = userData
      localStorage.setItem('token', access_token)
      localStorage.setItem('user', JSON.stringify(userData))
      
      // Set default authorization header for future requests
      api.defaults.headers.common['Authorization'] = `Bearer ${access_token}`
      
      notifStore.toastSuccess('Login successful!')
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Login failed'
      notifStore.toastError(error.value)
      throw err
    } finally {
      notifStore.setLoadingState('auth', false)
    }
  }

  const register = async (userData) => {
    notifStore.setLoadingState('auth', true)
    error.value = null
    
    try {
      const response = await api.post('/api/register', userData)
      const { access_token, user: newUser } = response.data
      
      token.value = access_token
      user.value = newUser
      localStorage.setItem('token', access_token)
      localStorage.setItem('user', JSON.stringify(newUser))
      
      // Set default authorization header for future requests
      api.defaults.headers.common['Authorization'] = `Bearer ${access_token}`
      
      notifStore.toastSuccess('Registration successful!')
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Registration failed'
      notifStore.toastError(error.value)
      throw err
    } finally {
      notifStore.setLoadingState('auth', false)
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
      localStorage.removeItem('user')
      delete api.defaults.headers.common['Authorization']
      
      notifStore.toastSuccess('Logged out successfully')
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
    } catch {
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
