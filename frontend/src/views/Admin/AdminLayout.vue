<template>
  <div class="min-h-screen bg-bg">
    <div class="flex">
      <!-- Admin Sidebar -->
      <aside class="w-64 bg-bg-card shadow-sm min-h-screen border-r border-border">
        <nav class="mt-5 px-2">
          <router-link v-for="item in navigation" :key="item.name" :to="item.href" :class="[
            $route.name === item.routeName
              ? 'bg-primary/10 border-primary text-primary'
              : 'border-transparent text-text-muted hover:bg-bg-secondary hover:text-text',
            'group flex items-center px-3 py-2 text-sm font-medium border-l-4 transition-colors duration-200 rounded-r-card'
          ]">
            <component :is="item.icon" :class="[
              $route.name === item.routeName ? 'text-primary' : 'text-text-muted group-hover:text-text',
              'mr-3 flex-shrink-0 h-6 w-6'
            ]" aria-hidden="true" />
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
import {
  HomeIcon,
  UsersIcon,
  ClipboardDocumentListIcon
} from '@heroicons/vue/24/outline'

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
</script>
