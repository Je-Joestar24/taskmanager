import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/config/api'
import { useNotifStore } from './notif'

export const useAdminStore = defineStore('admin', () => {
  // State
  const dashboardStats = ref({
    total_users: 0,
    total_tasks: 0,
    pending_tasks: 0,
    completed_tasks: 0,
    completion_rate: 0
  })
  const allUsers = ref([])
  const allTasks = ref([])
  const error = ref(null)
  
  // Search state
  const userSearch = ref('')
  const userSearchTimeout = ref(null)

  // Notification store
  const notifStore = useNotifStore()

  // Getters
  const userStats = computed(() => {
    const stats = {}
    allUsers.value.forEach(user => {
      stats[user.id] = {
        total_tasks: user.total_tasks || 0,
        pending_tasks: user.pending_tasks || 0,
        completed_tasks: user.completed_tasks || 0,
        completion_rate: user.total_tasks > 0 
          ? ((user.completed_tasks || 0) / user.total_tasks * 100).toFixed(2)
          : 0
      }
    })
    return stats
  })

  // Actions
  const fetchDashboardStats = async () => {
    notifStore.setLoadingState('dashboard', true)
    error.value = null
    
    try {
      const response = await api.get('/api/admin/dashboard')
      dashboardStats.value = response.data.data
      notifStore.toastSuccess('Dashboard stats updated successfully')
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch dashboard stats'
      notifStore.toastError(error.value)
      throw err
    } finally {
      notifStore.setLoadingState('dashboard', false)
    }
  }

  const fetchAllUsers = async (params = {}) => {
    notifStore.setLoadingState('users', true)
    error.value = null
    
    try {
      const queryParams = new URLSearchParams()
      Object.entries(params).forEach(([key, value]) => {
        if (value) queryParams.append(key, value)
      })

      const response = await api.get(`/api/admin/users?${queryParams}`)
      allUsers.value = response.data.data
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch users'
      notifStore.toastError(error.value)
      throw err
    } finally {
      notifStore.setLoadingState('users', false)
    }
  }

  const searchUsers = async (searchTerm) => {
    // Clear previous timeout
    if (userSearchTimeout.value) {
      clearTimeout(userSearchTimeout.value)
    }

    // Set new timeout for debounced search
    userSearchTimeout.value = setTimeout(async () => {
      try {
        await fetchAllUsers({ search: searchTerm })
      } catch (error) {
        console.error('Search failed:', error)
      }
    }, 300) // 300ms delay
  }

  const fetchAllTasks = async (params = {}) => {
    notifStore.setLoadingState('admin', true)
    error.value = null
    
    try {
      const queryParams = new URLSearchParams()
      Object.entries(params).forEach(([key, value]) => {
        if (value) queryParams.append(key, value)
      })

      const response = await api.get(`/api/admin/tasks?${queryParams}`)
      allTasks.value = response.data.data
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch all tasks'
      notifStore.toastError(error.value)
      throw err
    } finally {
      notifStore.setLoadingState('admin', false)
    }
  }

  const fetchUserStats = async (userId) => {
    notifStore.setLoadingState('users', true)
    error.value = null
    
    try {
      const response = await api.get(`/api/admin/users/${userId}/stats`)
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch user stats'
      notifStore.toastError(error.value)
      throw err
    } finally {
      notifStore.setLoadingState('users', false)
    }
  }

  const deleteTaskAsAdmin = async (taskId) => {
    notifStore.setLoadingState('admin', true)
    error.value = null
    
    try {
      const response = await api.delete(`/api/admin/tasks/${taskId}`)
      
      // Remove from allTasks if it exists there
      allTasks.value = allTasks.value.filter(task => task.id !== taskId)
      
      // Update dashboard stats
      await fetchDashboardStats()
      
      notifStore.toastSuccess('Task deleted successfully')
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to delete task'
      notifStore.toastError(error.value)
      throw err
    } finally {
      notifStore.setLoadingState('admin', false)
    }
  }

  const clearError = () => {
    error.value = null
  }

  const clearUserSearch = () => {
    userSearch.value = ''
    fetchAllUsers()
  }

  return {
    // State
    dashboardStats,
    allUsers,
    allTasks,
    error,
    userSearch,
    
    // Getters
    userStats,
    
    // Actions
    fetchDashboardStats,
    fetchAllUsers,
    fetchAllTasks,
    fetchUserStats,
    deleteTaskAsAdmin,
    searchUsers,
    clearUserSearch,
    clearError
  }
})
