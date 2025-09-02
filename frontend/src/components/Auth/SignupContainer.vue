<template>
  <div class="min-h-screen bg-bg flex items-center justify-center p-4">
    <div class="w-full max-w-6xl bg-bg-card rounded-card shadow-xl border border-border overflow-hidden">
      <div class="grid lg:grid-cols-2 min-h-[700px]">
        <LeftPanel />
        <RightPanel />
      </div>
    </div>
  </div>
</template>
<script setup>
import LeftPanel from './Signup/LeftPanel.vue';
import RightPanel from './Signup/RightPanel.vue';
</script>