import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useNotifStore = defineStore('notif', () => {
  // Loading states
  const loading = ref(false)
  const loadingStates = ref({
    auth: false,
    tasks: false,
    admin: false,
    dashboard: false,
    users: false
  })

  // Notification states
  const notifications = ref([])
  const showToast = ref(false)
  const toastMessage = ref('')
  const toastType = ref('success') // success, error, warning, info

  // Actions for loading states
  const setLoading = (state = true) => {
    loading.value = state
  }

  const setLoadingState = (key, state = true) => {
    if (key in loadingStates.value) {
      loadingStates.value[key] = state
    }
  }

  const setMultipleLoadingStates = (states) => {
    Object.entries(states).forEach(([key, value]) => {
      setLoadingState(key, value)
    })
  }

  const clearAllLoading = () => {
    loading.value = false
    Object.keys(loadingStates.value).forEach(key => {
      loadingStates.value[key] = false
    })
  }

  // Actions for notifications
  const addNotification = (notification) => {
    const id = Date.now() + Math.random()
    const newNotification = {
      id,
      type: notification.type || 'info',
      title: notification.title || '',
      message: notification.message || '',
      duration: notification.duration || 5000,
      ...notification
    }
    
    notifications.value.push(newNotification)
    
    // Auto-remove notification after duration
    if (newNotification.duration > 0) {
      setTimeout(() => {
        removeNotification(id)
      }, newNotification.duration)
    }
    
    return id
  }

  const removeNotification = (id) => {
    const index = notifications.value.findIndex(n => n.id === id)
    if (index !== -1) {
      notifications.value.splice(index, 1)
    }
  }

  const clearNotifications = () => {
    notifications.value = []
  }

  // Toast notifications
  const showToastNotification = (message, type = 'success', duration = 3000) => {
    toastMessage.value = message
    toastType.value = type
    showToast.value = true
    
    setTimeout(() => {
      showToast.value = false
    }, duration)
  }

  const hideToast = () => {
    showToast.value = false
  }

  // Convenience methods for common notification types
  const success = (message, title = 'Success') => {
    return addNotification({ type: 'success', title, message })
  }

  const error = (message, title = 'Error') => {
    return addNotification({ type: 'error', title, message })
  }

  const warning = (message, title = 'Warning') => {
    return addNotification({ type: 'warning', title, message })
  }

  const info = (message, title = 'Info') => {
    return addNotification({ type: 'info', title, message })
  }

  // Toast convenience methods
  const toastSuccess = (message) => showToastNotification(message, 'success')
  const toastError = (message) => showToastNotification(message, 'error')
  const toastWarning = (message) => showToastNotification(message, 'warning')
  const toastInfo = (message) => showToastNotification(message, 'info')

  return {
    // Loading states
    loading,
    loadingStates,
    
    // Notifications
    notifications,
    showToast,
    toastMessage,
    toastType,
    
    // Loading actions
    setLoading,
    setLoadingState,
    setMultipleLoadingStates,
    clearAllLoading,
    
    // Notification actions
    addNotification,
    removeNotification,
    clearNotifications,
    
    // Toast actions
    showToastNotification,
    hideToast,
    
    // Convenience methods
    success,
    error,
    warning,
    info,
    toastSuccess,
    toastError,
    toastWarning,
    toastInfo
  }
})