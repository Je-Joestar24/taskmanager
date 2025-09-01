<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">All Tasks</h2>
        <p class="mt-1 text-sm text-gray-600">
          Manage tasks from all users across the system
        </p>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">
            Status
          </label>
          <select
            id="status-filter"
            v-model="filters.status"
            @change="applyFilters"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
          >
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
          </select>
        </div>
        
        <div>
          <label for="priority-filter" class="block text-sm font-medium text-gray-700 mb-1">
            Priority
          </label>
          <select
            id="priority-filter"
            v-model="filters.priority"
            @change="applyFilters"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
          >
            <option value="">All Priorities</option>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
          </select>
        </div>
        
        <div>
          <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
            Search
          </label>
          <input
            id="search"
            v-model="filters.search"
            @input="applyFilters"
            type="text"
            placeholder="Search tasks..."
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>
        
        <div class="flex items-end">
          <button
            @click="clearFilters"
            class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
          >
            Clear Filters
          </button>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="adminStore.isLoading" class="flex justify-center py-8">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="adminStore.error" class="bg-red-50 border border-red-200 rounded-md p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-red-800">Error loading tasks</h3>
          <p class="mt-1 text-sm text-red-700">{{ adminStore.error }}</p>
        </div>
      </div>
    </div>

    <!-- Tasks List -->
    <div v-else class="bg-white shadow-sm rounded-lg border border-gray-200">
      <div v-if="adminStore.allTasks.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks found</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating a new task.</p>
      </div>

      <ul v-else class="divide-y divide-gray-200">
        <li
          v-for="task in adminStore.allTasks"
          :key="task.id"
          class="p-6 hover:bg-gray-50 transition-colors duration-200"
        >
          <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
              <div class="flex items-center space-x-3">
                <h3 class="text-lg font-medium text-gray-900 truncate">
                  {{ task.title }}
                </h3>
                <span
                  :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    task.status === 'completed' 
                      ? 'bg-green-100 text-green-800' 
                      : 'bg-yellow-100 text-yellow-800'
                  ]"
                >
                  {{ task.status }}
                </span>
                <span
                  :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    priorityClasses[task.priority]
                  ]"
                >
                  {{ task.priority }}
                </span>
              </div>
              <p class="mt-1 text-sm text-gray-600">{{ task.description }}</p>
              <div class="mt-2 flex items-center text-sm text-gray-500">
                <span>Created by: {{ task.user?.name }}</span>
                <span class="mx-2">•</span>
                <span>{{ formatDate(task.created_at) }}</span>
                <span class="mx-2">•</span>
                <span>Order: {{ task.order }}</span>
              </div>
            </div>
            
            <div class="flex items-center space-x-2">
              <button
                @click="deleteTask(task.id)"
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
              >
                Delete
              </button>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAdminStore } from '@/stores/admin'

const adminStore = useAdminStore()

const filters = ref({
  status: '',
  priority: '',
  search: ''
})

const priorityClasses = {
  low: 'bg-blue-100 text-blue-800',
  medium: 'bg-yellow-100 text-yellow-800',
  high: 'bg-red-100 text-red-800'
}

const applyFilters = () => {
  const params = {}
  if (filters.value.status) params.status = filters.value.status
  if (filters.value.priority) params.priority = filters.value.priority
  if (filters.value.search) params.search = filters.value.search
  
  adminStore.fetchAllTasks(params)
}

const clearFilters = () => {
  filters.value = {
    status: '',
    priority: '',
    search: ''
  }
  adminStore.fetchAllTasks()
}

const deleteTask = async (taskId) => {
  if (confirm('Are you sure you want to delete this task?')) {
    try {
      await adminStore.deleteTaskAsAdmin(taskId)
    } catch (error) {
      console.error('Failed to delete task:', error)
    }
  }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString()
}

onMounted(() => {
  adminStore.fetchAllTasks()
})
</script>
