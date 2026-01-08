<template>
  <nav class="flex mb-4" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
      <li class="inline-flex items-center">
        <router-link to="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-slate-900">
          <HomeIcon class="w-4 h-4 mr-2" />
          Dashboard
        </router-link>
      </li>
      <li v-for="(crumb, index) in crumbs" :key="index">
        <div class="flex items-center">
          <ChevronRightIcon class="w-5 h-5 text-gray-400" />
          <router-link 
            :to="crumb.to" 
            class="ml-1 text-sm font-medium text-gray-700 hover:text-slate-900 md:ml-2"
            :class="{ 'font-bold text-slate-900': index === crumbs.length - 1 }"
          >
            {{ crumb.name }}
          </router-link>
        </div>
      </li>
    </ol>
  </nav>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { HomeIcon, ChevronRightIcon } from 'lucide-vue-next';

const route = useRoute();

const crumbs = computed(() => {
  const pathArray = route.path.split('/').filter(p => p);
  return pathArray.map((path, idx) => {
    return {
      name: path.charAt(0).toUpperCase() + path.slice(1),
      to: '/' + pathArray.slice(0, idx + 1).join('/')
    };
  });
});
</script>
