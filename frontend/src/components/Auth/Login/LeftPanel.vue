<template>

    <!-- Left Panel - Decorative -->
    <div
        class="bg-gradient-to-br from-primary to-accent p-8 flex flex-col justify-center items-center relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-20 h-20 bg-text-inverse rounded-full animate-pulse"></div>
            <div class="absolute top-32 right-16 w-16 h-16 bg-text-inverse rounded-full animate-bounce"></div>
            <div class="absolute bottom-20 left-20 w-12 h-12 bg-text-inverse rounded-full animate-pulse"></div>
            <div class="absolute bottom-32 right-10 w-8 h-8 bg-text-inverse rounded-full animate-bounce"></div>
        </div>

        <!-- Main Illustration -->
        <div class="relative z-10 text-center">
            <div
                class="w-32 h-32 mx-auto mb-6 bg-text-inverse rounded-full flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-300">
                <svg class="w-16 h-16 text-primary" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-text-inverse mb-4">TaskManager</h3>
            <p class="text-text-inverse/80 text-sm leading-relaxed">
                Organize your tasks efficiently and boost your productivity with our intuitive task management system.
            </p>
        </div>

        <!-- Decorative Elements -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <div class="w-2 h-2 bg-text-inverse/60 rounded-full"></div>
            <div class="w-2 h-2 bg-text-inverse rounded-full"></div>
            <div class="w-2 h-2 bg-text-inverse/60 rounded-full"></div>
        </div>
    </div>
</template>