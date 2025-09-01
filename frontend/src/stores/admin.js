import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/config/api'

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
  const isLoading = ref(false)
  const error = ref(null)

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
    isLoading.value = true
    error.value = null
    
    try {
      const response = await api.get('/api/admin/dashboard')
      dashboardStats.value = response.data.data
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch dashboard stats'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const fetchAllUsers = async (params = {}) => {
    isLoading.value = true
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
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const fetchAllTasks = async (params = {}) => {
    isLoading.value = true
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
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const fetchUserStats = async (userId) => {
    isLoading.value = true
    error.value = null
    
    try {
      const response = await api.get(`/api/admin/users/${userId}/stats`)
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch user stats'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const deleteTaskAsAdmin = async (taskId) => {
    isLoading.value = true
    error.value = null
    
    try {
      const response = await api.delete(`/api/admin/tasks/${taskId}`)
      
      // Remove from allTasks if it exists there
      allTasks.value = allTasks.value.filter(task => task.id !== taskId)
      
      // Update dashboard stats
      await fetchDashboardStats()
      
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to delete task'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const clearError = () => {
    error.value = null
  }

  return {
    // State
    dashboardStats,
    allUsers,
    allTasks,
    isLoading,
    error,
    
    // Getters
    userStats,
    
    // Actions
    fetchDashboardStats,
    fetchAllUsers,
    fetchAllTasks,
    fetchUserStats,
    deleteTaskAsAdmin,
    clearError
  }
})
