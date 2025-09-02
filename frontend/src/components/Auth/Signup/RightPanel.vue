<template>
    <!-- Right Panel - Signup Form -->
    <div class="p-8 flex flex-col justify-center">
        <RightHeader />
        <!-- Signup Form -->
        <form @submit.prevent="handleSignup" class="space-y-5">

            <div v-for="field in fields" :key="field.id" class="space-y-2">
                <label :for="field.id" class="block text-sm font-medium text-text">
                    {{ field.label }}
                </label>
                <div class="relative">
                    <input :id="field.id" v-model="form[field.id]" :type="field.type" :placeholder="field.placeholder"
                        :required="field.required" :minlength="field.minlength"
                        class="w-full px-4 py-3 bg-bg border border-border rounded-card shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 text-text placeholder-text-muted" />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            v-html="field.icon" />
                    </div>
                </div>
                <p v-if="field.id === 'password'" class="text-xs text-text-muted">
                    Minimum 8 characters
                </p>
            </div>

            <!-- Error Messages -->
            <ErrorMessages />

            <RightFooter :is-form-valid="isFormValid" />
        </form>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import RightHeader from './RightHeader.vue'
import RightFooter from './RightFooter.vue'
import ErrorMessages from './ErrorMessages.vue'
import { useFormFields } from '@/composables/signupFormFields'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
})

const { fields } = useFormFields()

const passwordError = ref('')

const isFormValid = computed(() => {
    return form.value.name &&
        form.value.email &&
        form.value.password &&
        form.value.password_confirmation &&
        form.value.password === form.value.password_confirmation
})

const validatePassword = () => {
    if (form.value.password && form.value.password_confirmation) {
        if (form.value.password !== form.value.password_confirmation) {
            passwordError.value = 'Passwords do not match'
            return false
        } else {
            passwordError.value = ''
            return true
        }
    }
    return true
}

const handleSignup = async () => {
    if (!validatePassword()) {
        return
    }

    try {
        await authStore.register(form.value)

        // Redirect to intended page or dashboard
        const redirectPath = route.query.redirect || (authStore.isAdmin ? '/admin' : '/tasks')
        router.push(redirectPath)
    } catch (error) {
        // Error is already handled in the store
        console.error('Registration failed:', error)
    }
}
</script>