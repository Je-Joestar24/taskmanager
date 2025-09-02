<template>
  <div class="max-w-screen-2xl m-auto">
    <TaskHeader />

    <TaskStatistics />

    <!-- Filters Section -->
    <div class="bg-bg-card rounded-card p-6 shadow-sm border border-border mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div>
          <label for="status-filter" class="block text-sm font-medium text-text  mb-2">
            Status
          </label>
          <select id="status-filter" v-model="filters.status" @change="applyFilters"
            class="w-full text-text px-3 py-2 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
          </select>
        </div>

        <div>
          <label for="priority-filter" class="block text-sm font-medium text-text mb-2">
            Priority
          </label>
          <select id="priority-filter" v-model="filters.priority" @change="applyFilters"
            class="w-full text-text px-3 py-2 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200">
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
          <input id="search" v-model="filters.search" @input="applyFilters" type="text" placeholder="Search tasks..."
            class="w-full px-3 py-2 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200" />
        </div>

        <div class="flex items-end">
          <button @click="clearFilters"
            class="w-full px-4 py-2 text-sm font-medium text-text bg-bg-secondary border border-border rounded-card hover:bg-bg-tertiary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200">
            Clear Filters
          </button>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="notifStore.loadingStates.tasks" class="flex justify-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="tasksStore.error" class="bg-notification-error border border-error rounded-card p-4 mb-6">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-error" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
              clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-error">Error loading tasks</h3>
          <p class="mt-1 text-sm text-error">{{ tasksStore.error }}</p>
        </div>
      </div>
    </div>

    <!-- Tasks List with Drag & Drop -->
    <div v-else class="space-y-4">
      <div v-if="displayedTasks.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-text">No tasks found</h3>
        <p class="mt-1 text-sm text-text-secondary">Get started by creating a new task.</p>
      </div>

      <transition-group name="list" tag="div" class="grid gap-4">
        <div
          v-for="(task, index) in displayedTasks"
          :key="task.id"
          class="bg-bg-card rounded-card p-6 shadow-sm border border-border hover:shadow-md transition-all duration-200"
          draggable="true"
          @dragstart="onDragStart(index)"
          @dragover.prevent="onDragOver(index)"
          @drop.prevent="onDrop(index)"
        >
          <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
              <div class="flex items-center space-x-3 mb-2">
                <h3 class="text-lg font-medium text-text truncate">
                  {{ task.title }}
                </h3>
                <transition name="fade">
                  <span :key="task.status" :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    task.status === 'completed'
                      ? 'bg-success text-text-inverse'
                      : 'bg-warning text-text-inverse'
                  ]">
                    {{ task.status }}
                  </span>
                </transition>
                <span :class="[
                  'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                  priorityClasses[task.priority]
                ]">
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
              <button @click="toggleTaskStatus(task.id)" :disabled="notifStore.loadingStates.tasks"
                class="inline-flex items-center px-3 py-2 border border-border text-sm leading-4 font-medium rounded-card text-text bg-bg hover:bg-bg-secondary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200">
                {{ task.status === 'completed' ? 'Mark Pending' : 'Mark Complete' }}
              </button>
              <button @click="openEdit(task)"
                class="inline-flex items-center px-3 py-2 border border-border text-sm leading-4 font-medium rounded-card text-text bg-bg hover:bg-bg-secondary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200">
                Edit
              </button>
              <span class="cursor-move select-none text-text-muted" aria-label="Drag handle">≡</span>
            </div>
          </div>
        </div>
      </transition-group>
    </div>

    <CreateModal v-if="showCreateModal" :toggle-create-modal="toggleCreateModal" />
    <EditModal v-if="showEditModal" :toggle-edit-modal="toggleEditModal" :task="selectedTask" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useTasksStore } from '@/stores/tasks'
import { useNotifStore } from '@/stores/notif'
import CreateModal from './Modal/CreateModal.vue'
import EditModal from './Modal/EditModal.vue'
import TaskHeader from './TaskHeader.vue'
import TaskStatistics from './TaskStatistics.vue'

const tasksStore = useTasksStore()
const notifStore = useNotifStore()

// Local state
const showCreateModal = ref(false)
const showEditModal = ref(false)
const selectedTask = ref(null)
const filters = ref({
  status: '',
  priority: '',
  search: ''
})

// Drag & Drop state
const displayedTasks = ref([])
const dragSourceIndex = ref(null)

// Sync displayed tasks with filtered tasks
watch(() => tasksStore.filteredTasks, (val) => {
  displayedTasks.value = [...val]
}, { immediate: true })

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

const openEdit = (task) => {
  selectedTask.value = task
  showEditModal.value = true
}

const toggleEditModal = () => (showEditModal.value = !showEditModal.value)
const toggleCreateModal = () => (showCreateModal.value = !showCreateModal.value)

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString()
}

// Drag handlers
const onDragStart = (index) => {
  dragSourceIndex.value = index
}

const onDragOver = (index) => {
  // Optional: add visual cue later
}

const onDrop = async (targetIndex) => {
  if (dragSourceIndex.value === null || dragSourceIndex.value === targetIndex) return

  const list = [...displayedTasks.value]
  const [moved] = list.splice(dragSourceIndex.value, 1)
  list.splice(targetIndex, 0, moved)
  displayedTasks.value = list
  dragSourceIndex.value = null

  try {
    await tasksStore.reorderTasks(displayedTasks.value)
  } catch (error) {
    console.error('Failed to reorder tasks:', error)
  }
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

<style scoped>
.list-move {
  transition: transform 0.2s ease;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.15s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>