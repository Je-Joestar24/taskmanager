import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/config/api'
import { useNotifStore } from './notif'

export const useTasksStore = defineStore('tasks', () => {
  // State
  const tasks = ref([])
  const error = ref(null)
  const filters = ref({
    status: '',
    priority: '',
    search: ''
  })
  const statistics = ref({
    total: 0,
    pending: 0,
    completed: 0,
    low_priority: 0,
    medium_priority: 0,
    high_priority: 0,
    completion_rate: 0
  })
  const editingTask = ref(null)

  // Notification store
  const notifStore = useNotifStore()

  // Getters
  const filteredTasks = computed(() => {
    let filtered = [...tasks.value]

    // Filter by status
    if (filters.value.status) {
      filtered = filtered.filter(task => task.status === filters.value.status)
    }

    // Filter by priority
    if (filters.value.priority) {
      filtered = filtered.filter(task => task.priority === filters.value.priority)
    }

    // Filter by search
    if (filters.value.search) {
      const searchTerm = filters.value.search.toLowerCase()
      filtered = filtered.filter(task => 
        task.title.toLowerCase().includes(searchTerm) ||
        task.description.toLowerCase().includes(searchTerm)
      )
    }

    return filtered
  })

  const pendingTasks = computed(() => 
    tasks.value.filter(task => task.status === 'pending')
  )

  const completedTasks = computed(() => 
    tasks.value.filter(task => task.status === 'completed')
  )

  // Actions
  const fetchTasks = async (params = {}) => {
    notifStore.setLoadingState('tasks', true)
    error.value = null
    
    try {
      const queryParams = new URLSearchParams()
      
      // Add filters to query params
      if (filters.value.status) queryParams.append('status', filters.value.status)
      if (filters.value.priority) queryParams.append('priority', filters.value.priority)
      if (filters.value.search) queryParams.append('search', filters.value.search)
      
      // Add additional params
      Object.entries(params).forEach(([key, value]) => {
        if (value) queryParams.append(key, value)
      })

      const response = await api.get(`/api/tasks?${queryParams}`)
      tasks.value = response.data.data
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch tasks'
      notifStore.toastError(error.value)
      throw err
    } finally {
      notifStore.setLoadingState('tasks', false)
    }
  }

  const createTask = async (taskData) => {
    notifStore.setLoadingState('tasks', true)
    error.value = null
    
    try {
      const response = await api.post('/api/tasks', taskData)
      const newTask = response.data.data
      tasks.value.push(newTask)
      await fetchStatistics()
      notifStore.toastSuccess('Task created successfully!')
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to create task'
      notifStore.toastError(error.value)
      throw err
    } finally {
      notifStore.setLoadingState('tasks', false)
    }
  }

  const updateTask = async (taskId, taskData) => {
    notifStore.setLoadingState('tasks', true)
    error.value = null
    
    try {
      const response = await api.put(`/api/tasks/${taskId}`, taskData)
      const updatedTask = response.data.data
      
      const index = tasks.value.findIndex(task => task.id === taskId)
      if (index !== -1) {
        tasks.value[index] = updatedTask
      }
      
      await fetchStatistics()
      notifStore.toastSuccess('Task updated successfully!')
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to update task'
      notifStore.toastError(error.value)
      throw err
    } finally {
      notifStore.setLoadingState('tasks', false)
    }
  }

  const deleteTask = async (taskId) => {
    notifStore.setLoadingState('tasks', true)
    error.value = null
    
    try {
      await api.delete(`/api/admin/tasks/${taskId}`)
      tasks.value = tasks.value.filter(task => task.id !== taskId)
      await fetchStatistics()
      notifStore.toastSuccess('Task deleted successfully!')
      return { success: true }
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to delete task'
      notifStore.toastError(error.value)
      throw err
    } finally {
      notifStore.setLoadingState('tasks', false)
    }
  }

  const toggleTaskStatus = async (taskId) => {
    notifStore.setLoadingState('tasks', true)
    error.value = null
    
    try {
      const response = await api.patch(`/api/tasks/${taskId}/toggle-status`)
      const updatedTask = response.data.data
      
      const index = tasks.value.findIndex(task => task.id === taskId)
      if (index !== -1) {
        tasks.value[index] = updatedTask
      }
      
      await fetchStatistics()
      const status = updatedTask.status === 'completed' ? 'completed' : 'marked as pending'
      notifStore.toastSuccess(`Task ${status}!`)
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to toggle task status'
      notifStore.toastError(error.value)
      throw err
    } finally {
      notifStore.setLoadingState('tasks', false)
    }
  }

  const reorderTasks = async (reorderedTasks) => {
    notifStore.setLoadingState('tasks', true)
    error.value = null
    
    try {
      const tasksData = reorderedTasks.map((task, index) => ({
        id: task.id,
        order: index + 1
      }))
      
      await api.post('/api/tasks/reorder', { tasks: tasksData })
      
      // Update local order
      reorderedTasks.forEach((task, index) => {
        const taskIndex = tasks.value.findIndex(t => t.id === task.id)
        if (taskIndex !== -1) {
          tasks.value[taskIndex].order = index + 1
        }
      })
      
      notifStore.toastSuccess('Tasks reordered successfully!')
      return { success: true }
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to reorder tasks'
      notifStore.toastError(error.value)
      throw err
    } finally {
      notifStore.setLoadingState('tasks', false)
    }
  }

  const fetchStatistics = async () => {
    try {
      const response = await api.get('/api/tasks/statistics')
      statistics.value = response.data.data
      return response.data
    } catch (err) {
      console.error('Failed to fetch statistics:', err)
    }
  }

  const setFilters = (newFilters) => {
    filters.value = { ...filters.value, ...newFilters }
  }

  const clearFilters = () => {
    filters.value = {
      status: '',
      priority: '',
      search: ''
    }
  }

  const clearError = () => {
    error.value = null
  }

  const setEditingTask = (task) => {
    editingTask.value = { ...task }
  }

  const clearEditingTask = () => {
    editingTask.value = null
  }

  return {
    // State
    tasks,
    error,
    filters,
    statistics,
    editingTask,
    
    // Getters
    filteredTasks,
    pendingTasks,
    completedTasks,
    
    // Actions
    fetchTasks,
    createTask,
    updateTask,
    deleteTask,
    toggleTaskStatus,
    reorderTasks,
    fetchStatistics,
    setFilters,
    clearFilters,
    clearError,
    setEditingTask,
    clearEditingTask
  }
})
