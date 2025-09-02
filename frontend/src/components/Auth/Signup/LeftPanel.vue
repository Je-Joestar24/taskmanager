<template> <!-- Left Panel - Decorative -->
    <div
        class="bg-gradient-to-br from-accent to-secondary p-8 flex flex-col justify-center items-center relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-16 left-12 w-24 h-24 bg-text-inverse rounded-full animate-bounce"></div>
            <div class="absolute top-40 right-20 w-18 h-18 bg-text-inverse rounded-full animate-pulse"></div>
            <div class="absolute bottom-24 left-16 w-14 h-14 bg-text-inverse rounded-full animate-bounce"></div>
            <div class="absolute bottom-40 right-12 w-10 h-10 bg-text-inverse rounded-full animate-pulse"></div>
            <div class="absolute top-1/2 left-8 w-6 h-6 bg-text-inverse rounded-full animate-bounce"></div>
        </div>

        <!-- Main Illustration -->
        <div class="relative z-10 text-center">
            <div
                class="w-36 h-36 mx-auto mb-6 bg-text-inverse rounded-full flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-300">
                <svg class="w-20 h-20 text-accent" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-text-inverse mb-4">Join TaskManager</h3>
            <p class="text-text-inverse/80 text-sm leading-relaxed">
                Start your productivity journey today. Create your account and begin organizing your tasks with our
                powerful management system.
            </p>
        </div>

        <!-- Decorative Elements -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <div class="w-2 h-2 bg-text-inverse/60 rounded-full"></div>
            <div class="w-2 h-2 bg-text-inverse/60 rounded-full"></div>
            <div class="w-2 h-2 bg-text-inverse rounded-full"></div>
        </div>
    </div>
</template>