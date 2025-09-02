<template>

    <!-- Mobile Navigation -->
    <nav
        v-if="authStore.isAuthenticated"
        class="md:hidden lg:hidden bg-bg-card shadow-sm border-b border-border"
        role="navigation"
        aria-label="Primary"
    >
        <!-- Toggle Bar -->
        <div class="flex items-center justify-between px-3 py-2">
            <span class="text-text font-semibold">Menu</span>
            <button
                type="button"
                class="inline-flex items-center justify-center rounded-card p-2 text-text-muted hover:text-text focus:outline-none focus:ring-2 focus:ring-primary transition-colors duration-200"
                :aria-expanded="isOpen ? 'true' : 'false'"
                aria-label="Toggle navigation"
                @click="toggle()"
            >
                <!-- Chevron Arrow -->
                <svg
                    class="h-5 w-5 transition-transform duration-200"
                    :class="{ 'rotate-180': isOpen }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>

        <!-- Collapsible Menu -->
        <div v-show="isOpen" class="px-2 pt-2 pb-3">
            <ul class="space-y-1" role="list">
                <li v-if="!authStore.isAdmin" role="listitem">
                    <router-link
                        to="/tasks"
                        class="block px-3 py-2 rounded-card text-base font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary"
                        :class="[
                            'text-text-muted hover:text-text',
                            $route.name === 'tasks' ? 'text-primary bg-primary/10' : ''
                        ]"
                        aria-label="My Tasks"
                        :aria-current="$route.name === 'tasks' ? 'page' : undefined"
                    >
                        My Tasks
                    </router-link>
                </li>

                <li v-if="authStore.isAdmin" role="listitem">
                    <router-link
                        to="/admin"
                        class="block px-3 py-2 rounded-card text-base font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary"
                        :class="[
                            'text-text-muted hover:text-text',
                            $route.path.startsWith('/admin') ? 'text-primary bg-primary/10' : ''
                        ]"
                        aria-label="Admin Panel"
                        :aria-current="$route.path.startsWith('/admin') ? 'page' : undefined"
                    >
                        Admin Panel
                    </router-link>
                </li>
                <li v-if="authStore.isAdmin" role="listitem">
                    <router-link
                        to="/admin/tasks"
                        class="block px-3 py-2 rounded-card text-base font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary"
                        :class="[
                            'text-text-muted hover:text-text',
                            $route.path.startsWith('/admin') ? 'text-primary bg-primary/10' : ''
                        ]"
                        aria-label="Admin Panel"
                        :aria-current="$route.path.startsWith('/admin') ? 'page' : undefined"
                    >
                        All Tasks
                    </router-link>
                </li>
                <li v-if="authStore.isAdmin" role="listitem">
                    <router-link
                        to="/admin/users"
                        class="block px-3 py-2 rounded-card text-base font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary"
                        :class="[
                            'text-text-muted hover:text-text',
                            $route.path.startsWith('/admin') ? 'text-primary bg-primary/10' : ''
                        ]"
                        aria-label="Admin Panel"
                        :aria-current="$route.path.startsWith('/admin') ? 'page' : undefined"
                    >
                        All Users
                    </router-link>
                </li>
            </ul>
        </div>
    </nav>

</template>
<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth';
import { RouterLink } from 'vue-router';

const authStore = useAuthStore()

// Toggle state for mobile menu
const isOpen = ref(false)
const toggle = () => { isOpen.value = !isOpen.value }
</script>