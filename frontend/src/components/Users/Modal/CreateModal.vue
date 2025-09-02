<template>
    <!-- Create Task Modal -->
    <div class="fixed inset-0 bg-bg-overlay overflow-y-auto h-full w-full z-50" @click="props.toggleCreateModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-card bg-bg-card" @click.stop>
            <div class="mt-3">
                <h3 class="text-lg font-medium text-text mb-4">Create New Task</h3>
                <form @submit.prevent="createTask">
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-text mb-2">Title</label>
                            <input id="title" v-model="newTask.title" type="text" placeholder="Enter title" required
                                class="w-full text-text px-3 py-2 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200" />
                        </div>

                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-text mb-2">Description</label>
                            <textarea id="description" v-model="newTask.description" placeholder="Input descriptions..."
                                rows="3"
                                class="w-full text-text px-3 py-2 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"></textarea>
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-text mb-2">Priority</label>
                            <select id="priority" v-model="newTask.priority" required
                                class="w-full text-text px-3 py-2 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showCreateModal = false"
                            class="px-4 py-2 text-sm font-medium text-text bg-bg-secondary border border-border rounded-card hover:bg-bg-tertiary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit" :disabled="notifStore.loadingStates.tasks"
                            class="px-4 py-2 text-sm font-medium text-text-inverse bg-primary border border-transparent rounded-card hover:shadow-md focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200">
                            {{ notifStore.loadingStates.tasks ? 'Creating...' : 'Create Task' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>

import { ref } from 'vue'
import { useTasksStore } from '@/stores/tasks'
import { useNotifStore } from '@/stores/notif'

const notifStore = useNotifStore()
const props = defineProps({
    toggleCreateModal: {
        type: Function,
        required: true
    }
})

const tasksStore = useTasksStore()

const newTask = ref({
    title: '',
    description: '',
    priority: 'medium'
})

const createTask = async () => {
    try {
        await tasksStore.createTask(newTask.value)
        newTask.value = {
            title: '',
            description: '',
            priority: 'medium'
        }
        props.toggleCreateModal()
    } catch (error) {
        console.error('Failed to create task:', error)
    }
}

</script>