<template>
  <div class="max-w-screen-2xl m-auto">
    <!-- Header Section -->
    <div class="mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-text">My Tasks</h1>
          <p class="text-text-secondary mt-2">Manage and organize your tasks efficiently</p>
        </div>
        <button
          @click="showCreateModal = true"
          class="inline-flex items-center px-4 py-2 bg-primary text-text-inverse font-medium rounded-card shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Add New Task
        </button>
      </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid sm:grid-cols-1  md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-bg-card rounded-card p-6 shadow-sm border border-border">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
              <svg class="w-5 h-5 text-text-inverse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-text-muted text-sm font-medium">Total Tasks</p>
            <p class="text-2xl font-bold text-text">{{ tasksStore.statistics.total }}</p>
          </div>
        </div>
      </div>

      <div class="bg-bg-card rounded-card p-6 shadow-sm border border-border">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-warning rounded-full flex items-center justify-center">
              <svg class="w-5 h-5 text-text-inverse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-text-muted text-sm font-medium">Pending</p>
            <p class="text-2xl font-bold text-text">{{ tasksStore.statistics.pending }}</p>
          </div>
        </div>
      </div>

      <div class="bg-bg-card rounded-card p-6 shadow-sm border border-border">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-success rounded-full flex items-center justify-center">
              <svg class="w-5 h-5 text-text-inverse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-text-muted text-sm font-medium">Completed</p>
            <p class="text-2xl font-bold text-text">{{ tasksStore.statistics.completed }}</p>
          </div>
        </div>
      </div>

      <div class="bg-bg-card rounded-card p-6 shadow-sm border border-border">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center">
              <svg class="w-5 h-5 text-text-inverse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-text-muted text-sm font-medium">Completion Rate</p>
            <p class="text-2xl font-bold text-text">{{ tasksStore.statistics.completion_rate }}%</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-bg-card rounded-card p-6 shadow-sm border border-border mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        
        <div>
          <label for="status-filter" class="block text-sm font-medium text-text  mb-2">
            Status
          </label>
          <select
            id="status-filter"
            v-model="filters.status"
            @change="applyFilters"
            class="w-full text-text px-3 py-2 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
          >
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
          </select>
        </div>
        
        <div>
          <label for="priority-filter" class="block text-sm font-medium text-text mb-2">
            Priority
          </label>
          <select
            id="priority-filter"
            v-model="filters.priority"
            @change="applyFilters"
            class="w-full text-text px-3 py-2 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
          >
            <option value="">All Priorities</option>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
          </select>
        </div>
        
        <div>
          <label for="search" class="block text-sm font-medium text-text mb-2">
            Search
          </label>
          <input
            id="search"
            v-model="filters.search"
            @input="applyFilters"
            type="text"
            placeholder="Search tasks..."
            class="w-full px-3 py-2 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
          />
        </div>
        
        <div class="flex items-end">
          <button
            @click="clearFilters"
            class="w-full px-4 py-2 text-sm font-medium text-text bg-bg-secondary border border-border rounded-card hover:bg-bg-tertiary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200"
          >
            Clear Filters
          </button>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="tasksStore.isLoading" class="flex justify-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="tasksStore.error" class="bg-notification-error border border-error rounded-card p-4 mb-6">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-error" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-error">Error loading tasks</h3>
          <p class="mt-1 text-sm text-error">{{ tasksStore.error }}</p>
        </div>
      </div>
    </div>

    <!-- Tasks List -->
    <div v-else class="space-y-4">
      <div v-if="tasksStore.filteredTasks.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-text">No tasks found</h3>
        <p class="mt-1 text-sm text-text-secondary">Get started by creating a new task.</p>
      </div>

      <div v-else class="grid gap-4">
        <div
          v-for="task in tasksStore.filteredTasks"
          :key="task.id"
          class="bg-bg-card rounded-card p-6 shadow-sm border border-border hover:shadow-md transition-all duration-200"
        >
          <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
              <div class="flex items-center space-x-3 mb-2">
                <h3 class="text-lg font-medium text-text truncate">
                  {{ task.title }}
                </h3>
                <span
                  :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    task.status === 'completed' 
                      ? 'bg-success text-text-inverse' 
                      : 'bg-warning text-text-inverse'
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
              <p class="text-text-secondary mb-3">{{ task.description }}</p>
              <div class="flex items-center text-sm text-text-muted">
                <span>Order: {{ task.order }}</span>
                <span class="mx-2">•</span>
                <span>{{ formatDate(task.created_at) }}</span>
              </div>
            </div>
            
            <div class="flex items-center space-x-2 ml-4">
              <button
                @click="toggleTaskStatus(task.id)"
                :disabled="tasksStore.isLoading"
                class="inline-flex items-center px-3 py-2 border border-border text-sm leading-4 font-medium rounded-card text-text bg-bg hover:bg-bg-secondary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200"
              >
                {{ task.status === 'completed' ? 'Mark Pending' : 'Mark Complete' }}
              </button>
              <button
                @click="editTask(task)"
                class="inline-flex items-center px-3 py-2 border border-border text-sm leading-4 font-medium rounded-card text-text bg-bg hover:bg-bg-secondary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200"
              >
                Edit
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create Task Modal -->
    <div
      v-if="showCreateModal"
      class="fixed inset-0 bg-bg-overlay overflow-y-auto h-full w-full z-50"
      @click="showCreateModal = false"
    >
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-card bg-bg-card" @click.stop>
        <div class="mt-3">
          <h3 class="text-lg font-medium text-text mb-4">Create New Task</h3>
          <form @submit.prevent="createTask">
            <div class="space-y-4">
              <div>
                <label for="title" class="block text-sm font-medium text-text mb-2">Title</label>
                <input
                  id="title"
                  v-model="newTask.title"
                  type="text"
                  placeholder="Enter title"
                  required
                  class="w-full text-text px-3 py-2 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
                />
              </div>
              
              <div>
                <label for="description" class="block text-sm font-medium text-text mb-2">Description</label>
                <textarea
                  id="description"
                  v-model="newTask.description"
                  placeholder="Input descriptions..."
                  rows="3"
                  class="w-full text-text px-3 py-2 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
                ></textarea>
              </div>
              
              <div>
                <label for="priority" class="block text-sm font-medium text-text mb-2">Priority</label>
                <select
                  id="priority"
                  v-model="newTask.priority"
                  required
                  class="w-full text-text px-3 py-2 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"
                >
                  <option value="low">Low</option>
                  <option value="medium">Medium</option>
                  <option value="high">High</option>
                </select>
              </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
              <button
                type="button"
                @click="showCreateModal = false"
                class="px-4 py-2 text-sm font-medium text-text bg-bg-secondary border border-border rounded-card hover:bg-bg-tertiary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="tasksStore.isLoading"
                class="px-4 py-2 text-sm font-medium text-text-inverse bg-primary border border-transparent rounded-card hover:shadow-md focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200"
              >
                {{ tasksStore.isLoading ? 'Creating...' : 'Create Task' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useTasksStore } from '@/stores/tasks'

const tasksStore = useTasksStore()

// Local state
const showCreateModal = ref(false)
const filters = ref({
  status: '',
  priority: '',
  search: ''
})

const newTask = ref({
  title: '',
  description: '',
  priority: 'medium'
})

// Computed properties
const priorityClasses = computed(() => ({
  low: 'bg-success text-text-inverse',
  medium: 'bg-warning text-text-inverse',
  high: 'bg-error text-text-inverse'
}))

// Methods
const applyFilters = () => {
  tasksStore.setFilters(filters.value)
  tasksStore.fetchTasks()
}

const clearFilters = () => {
  filters.value = {
    status: '',
    priority: '',
    search: ''
  }
  tasksStore.clearFilters()
  tasksStore.fetchTasks()
}

const toggleTaskStatus = async (taskId) => {
  try {
    await tasksStore.toggleTaskStatus(taskId)
  } catch (error) {
    console.error('Failed to toggle task status:', error)
  }
}

const createTask = async () => {
  try {
    await tasksStore.createTask(newTask.value)
    showCreateModal.value = false
    newTask.value = {
      title: '',
      description: '',
      priority: 'medium'
    }
  } catch (error) {
    console.error('Failed to create task:', error)
  }
}

const editTask = (task) => {
  // TODO: Implement edit functionality
  console.log('Edit task:', task)
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString()
}

// Watchers
watch(filters, () => {
  applyFilters()
}, { deep: true })

// Lifecycle
onMounted(async () => {
  await tasksStore.fetchTasks()
  await tasksStore.fetchStatistics()
})
</script>