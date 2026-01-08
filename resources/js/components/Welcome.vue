<template>
  <div class="welcome">
    <h1>{{ greeting }}</h1>
    <p>{{ message }}</p>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const greeting = ref<string>('Welcome to crmprof');
const message = ref<string>('Laravel 11 + Vue 3 + TypeScript');
</script>

<style scoped>
.welcome {
  text-align: center;
  padding: 2rem;
}

.welcome h1 {
  color: #2c3e50;
  margin-bottom: 0.5rem;
}

.welcome p {
  color: #6c757d;
}
</style>
