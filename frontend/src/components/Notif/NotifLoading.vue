<template>
  <!-- Global Loading Overlay -->
  <div v-if="notifStore.loading" class="fixed inset-0 bg-bg-overlay/50 flex items-center justify-center z-50">
    <div class="bg-bg-card p-6 rounded-card shadow-lg">
      <div class="flex items-center space-x-3">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
        <span class="text-text">Loading...</span>
      </div>
    </div>
  </div>

  <!-- Toast Notifications -->
  <div
    v-if="notifStore.showToast"
    class="fixed top-4 right-4 z-50 transition-all duration-300 ease-in-out"
    :class="{
      'transform translate-x-0 opacity-100': notifStore.showToast,
      'transform translate-x-full opacity-0': !notifStore.showToast
    }"
  >
    <div
      class="max-w-sm w-full bg-bg-card shadow-lg rounded-card border-l-4 p-4"
      :class="{
        'border-success': notifStore.toastType === 'success',
        'border-error': notifStore.toastType === 'error',
        'border-warning': notifStore.toastType === 'warning',
        'border-info': notifStore.toastType === 'info'
      }"
    >
      <div class="flex items-start">
        <div class="flex-shrink-0">
          <!-- Success Icon -->
          <svg
            v-if="notifStore.toastType === 'success'"
            class="h-5 w-5 text-success"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path
              fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
              clip-rule="evenodd"
            />
          </svg>
          
          <!-- Error Icon -->
          <svg
            v-else-if="notifStore.toastType === 'error'"
            class="h-5 w-5 text-error"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path
              fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
              clip-rule="evenodd"
            />
          </svg>
          
          <!-- Warning Icon -->
          <svg
            v-else-if="notifStore.toastType === 'warning'"
            class="h-5 w-5 text-warning"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path
              fill-rule="evenodd"
              d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
              clip-rule="evenodd"
            />
          </svg>
          
          <!-- Info Icon -->
          <svg
            v-else
            class="h-5 w-5 text-info"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path
              fill-rule="evenodd"
              d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
              clip-rule="evenodd"
            />
          </svg>
        </div>
        
        <div class="ml-3 flex-1">
          <p class="text-sm font-medium text-text">
            {{ notifStore.toastMessage }}
          </p>
        </div>
        
        <div class="ml-4 flex-shrink-0 flex">
          <button
            @click="notifStore.hideToast"
            class="inline-flex text-text-muted hover:text-text focus:outline-none focus:text-text transition-colors duration-200"
          >
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
              <path
                fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Persistent Notifications -->
  <div class="fixed top-4 left-4 z-40 space-y-2">
    <div
      v-for="notification in notifStore.notifications"
      :key="notification.id"
      class="max-w-sm w-full bg-bg-card shadow-lg rounded-card border-l-4 p-4 transition-all duration-300 ease-in-out"
      :class="{
        'border-success': notification.type === 'success',
        'border-error': notification.type === 'error',
        'border-warning': notification.type === 'warning',
        'border-info': notification.type === 'info'
      }"
    >
      <div class="flex items-start">
        <div class="flex-shrink-0">
          <!-- Success Icon -->
          <svg
            v-if="notification.type === 'success'"
            class="h-5 w-5 text-success"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path
              fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
              clip-rule="evenodd"
            />
          </svg>
          
          <!-- Error Icon -->
          <svg
            v-else-if="notification.type === 'error'"
            class="h-5 w-5 text-error"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path
              fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
              clip-rule="evenodd"
            />
          </svg>
          
          <!-- Warning Icon -->
          <svg
            v-else-if="notification.type === 'warning'"
            class="h-5 w-5 text-warning"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path
              fill-rule="evenodd"
              d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
              clip-rule="evenodd"
            />
          </svg>
          
          <!-- Info Icon -->
          <svg
            v-else
            class="h-5 w-5 text-info"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path
              fill-rule="evenodd"
              d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
              clip-rule="evenodd"
            />
          </svg>
        </div>
        
        <div class="ml-3 flex-1">
          <p v-if="notification.title" class="text-sm font-medium text-text">
            {{ notification.title }}
          </p>
          <p class="text-sm text-text-secondary mt-1">
            {{ notification.message }}
          </p>
        </div>
        
        <div class="ml-4 flex-shrink-0 flex">
          <button
            @click="notifStore.removeNotification(notification.id)"
            class="inline-flex text-text-muted hover:text-text focus:outline-none focus:text-text transition-colors duration-200"
          >
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
              <path
                fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useNotifStore } from '@/stores/notif'

const notifStore = useNotifStore()
</script>