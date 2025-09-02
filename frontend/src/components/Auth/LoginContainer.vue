<template>
  <div class="min-h-screen bg-bg flex items-center justify-center p-4">
    <div class="w-full max-w-6xl bg-bg-card rounded-card shadow-xl border border-border overflow-hidden">
      <div class="grid lg:grid-cols-2 min-h-[600px]">
        <LeftPanel />
        <RightPanel />
      </div>
    </div>
  </div>
</template>

<script setup>
import LeftPanel from './Login/LeftPanel.vue';
import RightPanel from './Login/RightPanel.vue';
</script>