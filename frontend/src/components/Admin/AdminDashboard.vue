<template>
  <div class="min-h-screen bg-bg p-6">
    <!-- Header Section -->
    <div class="mb-8">
      <h1 class="text-4xl font-bold text-text mb-2">Admin Dashboard</h1>
      <p class="text-text-secondary">Monitor system performance and user activities</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Total Users Card -->
      <div class="bg-bg-card border border-border rounded-card p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-text-muted text-sm font-medium">Total Users</p>
            <p class="text-3xl font-bold text-text mt-2">{{ adminStore.dashboardStats.total_users }}</p>
          </div>
          <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-success">+12%</span>
          <span class="text-text-muted ml-2">from last month</span>
        </div>
      </div>

      <!-- Total Tasks Card -->
      <div class="bg-bg-card border border-border rounded-card p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-text-muted text-sm font-medium">Total Tasks</p>
            <p class="text-3xl font-bold text-text mt-2">{{ adminStore.dashboardStats.total_tasks }}</p>
          </div>
          <div class="w-12 h-12 bg-secondary/10 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-success">+8%</span>
          <span class="text-text-muted ml-2">from last month</span>
        </div>
      </div>

      <!-- Pending Tasks Card -->
      <div class="bg-bg-card border border-border rounded-card p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-text-muted text-sm font-medium">Pending Tasks</p>
            <p class="text-3xl font-bold text-warning mt-2">{{ adminStore.dashboardStats.pending_tasks }}</p>
          </div>
          <div class="w-12 h-12 bg-warning/10 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-error">-5%</span>
          <span class="text-text-muted ml-2">from last month</span>
        </div>
      </div>

      <!-- Completion Rate Card -->
      <div class="bg-bg-card border border-border rounded-card p-6 shadow-md hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-text-muted text-sm font-medium">Completion Rate</p>
            <p class="text-3xl font-bold text-success mt-2">{{ adminStore.dashboardStats.completion_rate }}%</p>
          </div>
          <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-success">+15%</span>
          <span class="text-text-muted ml-2">from last month</span>
        </div>
      </div>
    </div>

    <!-- Charts and Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Task Status Chart -->
      <div class="bg-bg-card border border-border rounded-card p-6 shadow-md">
        <h3 class="text-lg font-semibold text-text mb-4">Task Status Distribution</h3>
        <div class="space-y-4">
          <!-- Pending Tasks Bar -->
          <div>
            <div class="flex justify-between text-sm mb-2">
              <span class="text-text-secondary">Pending</span>
              <span class="text-text font-medium">{{ adminStore.dashboardStats.pending_tasks }}</span>
            </div>
            <div class="w-full bg-bg-secondary rounded-full h-3">
              <div 
                class="bg-warning h-3 rounded-full transition-all duration-500 ease-out"
                :style="{ width: `${(adminStore.dashboardStats.pending_tasks / adminStore.dashboardStats.total_tasks) * 100}%` }"
              ></div>
            </div>
          </div>

          <!-- Completed Tasks Bar -->
          <div>
            <div class="flex justify-between text-sm mb-2">
              <span class="text-text-secondary">Completed</span>
              <span class="text-text font-medium">{{ adminStore.dashboardStats.completed_tasks }}</span>
            </div>
            <div class="w-full bg-bg-secondary rounded-full h-3">
              <div 
                class="bg-success h-3 rounded-full transition-all duration-500 ease-out"
                :style="{ width: `${(adminStore.dashboardStats.completed_tasks / adminStore.dashboardStats.total_tasks) * 100}%` }"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="notifStore.loadingStates.dashboard" class="fixed inset-0 bg-bg-overlay/50 flex items-center justify-center z-50">
      <div class="bg-bg-card p-6 rounded-card shadow-lg">
        <div class="flex items-center space-x-3">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
          <span class="text-text">Loading dashboard...</span>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-bg-card border border-border rounded-card p-6 shadow-md">
      <h3 class="text-lg font-semibold text-text mb-4">Quick Actions</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <button 
          @click="navigateToUsers"
          class="flex items-center justify-center p-4 bg-primary/10 hover:bg-primary/20 text-primary rounded-lg transition-colors duration-200 group"
        >
          <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
          </svg>
          Manage Users
        </button>
        
        <button 
          @click="navigateToTasks"
          class="flex items-center justify-center p-4 bg-secondary/10 hover:bg-secondary/20 text-secondary rounded-lg transition-colors duration-200 group"
        >
          <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
          </svg>
          Manage Tasks
        </button>
        
        <button 
          @click="refreshDashboard"
          class="flex items-center justify-center p-4 bg-accent/10 hover:bg-accent/20 text-accent rounded-lg transition-colors duration-200 group"
        >
          <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
          </svg>
          Refresh Data
        </button>
      </div>
    </div>

    <!-- Error State -->
    <div v-if="adminStore.error" class="fixed top-4 right-4 bg-error text-text-inverse p-4 rounded-card shadow-lg z-50">
      <div class="flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ adminStore.error }}</span>
        <button @click="adminStore.clearError" class="ml-2 hover:opacity-75">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAdminStore } from '@/stores/admin'
import { useNotifStore } from '@/stores/notif'

const router = useRouter()
const adminStore = useAdminStore()
const notifStore = useNotifStore()

// Navigation methods
const navigateToUsers = () => {
  router.push('/admin/users')
}

const navigateToTasks = () => {
  router.push('/admin/tasks')
}

const refreshDashboard = async () => {
  try {
    await adminStore.fetchDashboardStats()
  } catch (error) {
    console.error('Failed to refresh dashboard:', error)
  }
}

// Load dashboard data on component mount
onMounted(async () => {
  try {
    await adminStore.fetchDashboardStats()
  } catch (error) {
    console.error('Failed to load dashboard:', error)
  }
})
</script>