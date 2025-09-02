<template>
  <div class="min-h-screen bg-bg p-6">

    <DashboardHeader />

    <DashboardStatistics />

    <DashboardCharts />

    <DashboardLoading />

    <DashboardError />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAdminStore } from '@/stores/admin'
import DashboardHeader from './Dashboard/DashboardHeader.vue'
import DashboardStatistics from './Dashboard/DashboardStatistics.vue'
import DashboardCharts from './Dashboard/DashboardCharts.vue'
import DashboardLoading from './Dashboard/DashboardLoading.vue'
import DashboardError from './Dashboard/DashboardError.vue'

const adminStore = useAdminStore()

// Load dashboard data on component mount
onMounted(async () => {
  try {
    await adminStore.fetchDashboardStats()
  } catch (error) {
    console.error('Failed to load dashboard:', error)
  }
})
</script>