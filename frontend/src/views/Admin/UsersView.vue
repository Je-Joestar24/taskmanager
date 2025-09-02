<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
      <div>
        <h2 class="text-2xl font-bold text-text">All Users</h2>
        <p class="mt-1 text-sm text-text-muted">
          View all registered users and their task statistics
        </p>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="adminStore.isLoading" class="flex justify-center py-8">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="adminStore.error" class="bg-error/10 border border-error/20 rounded-card p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-error" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-error">Error loading users</h3>
          <p class="mt-1 text-sm text-error/80">{{ adminStore.error }}</p>
        </div>
      </div>
    </div>

    <!-- Users List -->
    <div v-else class="bg-bg-card shadow-sm rounded-card border border-border">
      <div v-if="adminStore.allUsers.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-text">No users found</h3>
        <p class="mt-1 text-sm text-text-muted">No users have registered yet.</p>
      </div>

      <ul v-else class="divide-y divide-border">
        <li
          v-for="user in adminStore.allUsers"
          :key="user.id"
          class="p-6 hover:bg-bg-secondary transition-colors duration-200"
        >
          <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
              <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                  <div class="h-10 w-10 rounded-full bg-primary/20 flex items-center justify-center">
                    <span class="text-sm font-medium text-primary">
                      {{ user.name.charAt(0).toUpperCase() }}
                    </span>
                  </div>
                </div>
                <div class="flex-1 min-w-0">
                  <h3 class="text-lg font-medium text-text truncate">
                    {{ user.name }}
                  </h3>
                  <p class="text-sm text-text-muted">{{ user.email }}</p>
                </div>
                <span
                  :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    user.role === 'admin' 
                      ? 'bg-accent/20 text-accent' 
                      : 'bg-bg-secondary text-text-secondary'
                  ]"
                >
                  {{ user.role }}
                </span>
              </div>
              
              <!-- Task Statistics -->
              <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                  <div class="text-2xl font-bold text-text">{{ user.total_tasks || 0 }}</div>
                  <div class="text-sm text-text-muted">Total Tasks</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-warning">{{ user.pending_tasks || 0 }}</div>
                  <div class="text-sm text-text-muted">Pending</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-success">{{ user.completed_tasks || 0 }}</div>
                  <div class="text-sm text-text-muted">Completed</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-primary">
                    {{ user.total_tasks > 0 ? ((user.completed_tasks || 0) / user.total_tasks * 100).toFixed(1) : 0 }}%
                  </div>
                  <div class="text-sm text-text-muted">Completion Rate</div>
                </div>
              </div>
              
              <div class="mt-2 text-sm text-text-muted">
                <span>Joined: {{ formatDate(user.created_at) }}</span>
              </div>
            </div>
            
            <div class="flex items-center space-x-2">
              <button
                @click="viewUserStats(user.id)"
                class="inline-flex items-center px-3 py-2 border border-border text-sm leading-4 font-medium rounded-card text-text bg-bg-card hover:bg-bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200"
              >
                View Details
              </button>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <!-- User Stats Modal -->
    <div
      v-if="showUserStatsModal"
      class="fixed inset-0 bg-bg-overlay overflow-y-auto h-full w-full z-50"
      @click="closeUserStatsModal"
    >
      <div class="relative top-20 mx-auto p-5 border border-border w-96 shadow-lg rounded-card bg-bg-card" @click.stop>
        <div class="mt-3">
          <h3 class="text-lg font-medium text-text mb-4">User Statistics</h3>
          <div v-if="selectedUserStats" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div class="text-center p-3 bg-bg-secondary rounded-card">
                <div class="text-2xl font-bold text-text">{{ selectedUserStats.total_tasks }}</div>
                <div class="text-sm text-text-muted">Total Tasks</div>
              </div>
              <div class="text-center p-3 bg-bg-secondary rounded-card">
                <div class="text-2xl font-bold text-success">{{ selectedUserStats.completion_rate }}%</div>
                <div class="text-sm text-text-muted">Completion Rate</div>
              </div>
            </div>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="text-sm text-text-muted">Pending Tasks:</span>
                <span class="text-sm font-medium text-text">{{ selectedUserStats.pending_tasks }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm text-text-muted">Completed Tasks:</span>
                <span class="text-sm font-medium text-text">{{ selectedUserStats.completed_tasks }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm text-text-muted">Low Priority:</span>
                <span class="text-sm font-medium text-text">{{ selectedUserStats.low_priority }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm text-text-muted">Medium Priority:</span>
                <span class="text-sm font-medium text-text">{{ selectedUserStats.medium_priority }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm text-text-muted">High Priority:</span>
                <span class="text-sm font-medium text-text">{{ selectedUserStats.high_priority }}</span>
              </div>
            </div>
          </div>
          <div class="mt-6 flex justify-end">
            <button
              @click="closeUserStatsModal"
              class="px-4 py-2 text-sm font-medium text-text bg-bg-secondary border border-border rounded-card hover:bg-bg-tertiary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAdminStore } from '@/stores/admin'

const adminStore = useAdminStore()

const showUserStatsModal = ref(false)
const selectedUserStats = ref(null)

const viewUserStats = async (userId) => {
  try {
    const response = await adminStore.fetchUserStats(userId)
    selectedUserStats.value = response.data
    showUserStatsModal.value = true
  } catch (error) {
    console.error('Failed to fetch user stats:', error)
  }
}

const closeUserStatsModal = () => {
  showUserStatsModal.value = false
  selectedUserStats.value = null
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString()
}

onMounted(() => {
  adminStore.fetchAllUsers()
})
</script>
