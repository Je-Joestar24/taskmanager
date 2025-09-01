<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Admin Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center">
            <h1 class="text-xl font-semibold text-gray-900">Admin Panel</h1>
          </div>
          <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-600">Welcome, {{ authStore.user?.name }}</span>
            <button
              @click="logout"
              class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </header>

    <div class="flex">
      <!-- Admin Sidebar -->
      <aside class="w-64 bg-white shadow-sm min-h-screen">
        <nav class="mt-5 px-2">
          <router-link
            v-for="item in navigation"
            :key="item.name"
            :to="item.href"
            :class="[
              $route.name === item.routeName
                ? 'bg-indigo-100 border-indigo-500 text-indigo-700'
                : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900',
              'group flex items-center px-3 py-2 text-sm font-medium border-l-4 transition-colors duration-200'
            ]"
          >
            <component
              :is="item.icon"
              :class="[
                $route.name === item.routeName ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500',
                'mr-3 flex-shrink-0 h-6 w-6'
              ]"
              aria-hidden="true"
            />
            {{ item.name }}
          </router-link>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-6">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import {
  HomeIcon,
  UsersIcon,
  ClipboardDocumentListIcon
} from '@heroicons/vue/24/outline'

const router = useRouter()
const authStore = useAuthStore()

const navigation = [
  {
    name: 'Dashboard',
    href: '/admin',
    routeName: 'admin-dashboard',
    icon: HomeIcon
  },
  {
    name: 'All Tasks',
    href: '/admin/tasks',
    routeName: 'admin-tasks',
    icon: ClipboardDocumentListIcon
  },
  {
    name: 'All Users',
    href: '/admin/users',
    routeName: 'admin-users',
    icon: UsersIcon
  }
]

const logout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>
