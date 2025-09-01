<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { onMounted } from 'vue';
import api from './config/api';

onMounted(() => api.post('/api/test'))
</script>

<template>
  <header>
    <img alt="Vue logo" class="logo" src="@/assets/logo.svg" width="125" height="125" />

    <div class="wrapper bg-blue-400">
      <nav>
        <RouterLink to="/">Home</RouterLink>
        <RouterLink to="/about">About</RouterLink>
      </nav>
    </div>
  </header>

  <RouterView />
</template>