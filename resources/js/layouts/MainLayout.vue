<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex-shrink-0 hidden md:flex flex-col">
      <div class="p-6 text-2xl font-bold border-b border-slate-800">
        CRMProf
      </div>
      <nav class="flex-grow p-4 space-y-2 text-slate-300">
        <router-link
          v-for="item in menuItems"
          :key="item.name"
          :to="item.to"
          class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-800 hover:text-white transition-colors"
          :class="{ 'bg-slate-800 text-white': $route.name === item.routeName }"
        >
          <component :is="item.icon" class="w-5 h-5" />
          <span>{{ item.name }}</span>
        </router-link>
      </nav>
      <div class="p-4 border-t border-slate-800">
        <button @click="logout" class="flex items-center space-x-3 p-3 w-full text-left rounded-lg hover:bg-slate-800 hover:text-white transition-colors">
          <LogOutIcon class="w-5 h-5" />
          <span>Logout</span>
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-grow flex flex-col min-w-0 overflow-hidden">
      <!-- Topbar -->
      <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
        <div class="flex items-center">
          <button class="md:hidden mr-4 text-gray-600">
            <MenuIcon class="w-6 h-6" />
          </button>
          <h2 class="text-xl font-semibold text-gray-800">{{ currentPageTitle }}</h2>
        </div>
        <div class="flex items-center space-x-4">
          <span class="text-sm text-gray-600">Admin</span>
          <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold">
            A
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-grow p-6 overflow-auto">
        <Breadcrumbs />
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { 
  LayoutDashboardIcon, 
  ShoppingCartIcon, 
  UsersIcon, 
  TruckIcon, 
  LogOutIcon,
  MenuIcon
} from 'lucide-vue-next';
import axios from 'axios';
import Breadcrumbs from '../components/Breadcrumbs.vue';

const route = useRoute();
const router = useRouter();

const menuItems = [
  { name: 'Dashboard', to: '/', routeName: 'dashboard', icon: LayoutDashboardIcon },
  { name: 'Orders', to: '/orders', routeName: 'orders', icon: ShoppingCartIcon },
  { name: 'Drivers', to: '/drivers', routeName: 'drivers', icon: DriversIcon },
  { name: 'Vehicles', to: '/vehicles', routeName: 'vehicles', icon: TruckIcon },
];

// Special handle for Drivers icon name clash
const DriversIcon = UsersIcon;

const currentPageTitle = computed(() => {
  const item = menuItems.find(m => m.routeName === route.name);
  return item ? item.name : 'CRMProf';
});

const logout = async () => {
  try {
    await axios.post('/logout');
    router.push('/login');
  } catch (error) {
    console.error('Logout failed', error);
    // Fallback
    router.push('/login');
  }
};
</script>
