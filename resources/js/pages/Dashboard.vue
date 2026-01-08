<template>
  <div>
    <div v-if="loading" class="flex justify-center items-center h-64">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-slate-900"></div>
    </div>

    <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
      {{ error }}
    </div>

    <div v-else>
      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div v-for="stat in statsCards" :key="stat.label" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center justify-between mb-4">
            <div class="p-2 rounded-lg" :class="stat.colorClass">
              <component :is="stat.icon" class="w-6 h-6 text-white" />
            </div>
          </div>
          <h3 class="text-gray-500 text-sm font-medium">{{ stat.label }}</h3>
          <p class="text-2xl font-bold text-gray-900">{{ stat.value }}</p>
        </div>
      </div>

      <!-- Recent Orders Table -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
          <h3 class="text-lg font-bold text-gray-800">Recent Orders</h3>
          <router-link to="/orders" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</router-link>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead>
              <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                <th class="px-6 py-4">Order #</th>
                <th class="px-6 py-4">Client</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4">Amount</th>
                <th class="px-6 py-4 text-right">Date</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
              <tr v-for="order in recentOrders" :key="order.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 font-medium text-slate-900">{{ order.order_number }}</td>
                <td class="px-6 py-4 text-gray-600">{{ order.client_name }}</td>
                <td class="px-6 py-4">
                  <span 
                    class="px-2 py-1 rounded-full text-xs font-medium"
                    :class="getStatusClass(order.status)"
                  >
                    {{ order.status }}
                  </span>
                </td>
                <td class="px-6 py-4 text-gray-900 font-semibold">${{ order.amount }}</td>
                <td class="px-6 py-4 text-gray-500 text-right">{{ formatDate(order.created_at) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { 
  ShoppingCartIcon, 
  UsersIcon, 
  TruckIcon, 
  DollarSignIcon 
} from 'lucide-vue-next';
import { format } from 'date-fns';

const loading = ref(true);
const error = ref<string | null>(null);
const dashboardData = ref<any>(null);

const statsCards = computed(() => {
  if (!dashboardData.value) return [];
  const s = dashboardData.value.stats;
  return [
    { label: 'Total Orders', value: s.total_orders, icon: ShoppingCartIcon, colorClass: 'bg-blue-500' },
    { label: 'Active Drivers', value: s.active_drivers, icon: UsersIcon, colorClass: 'bg-green-500' },
    { label: 'Available Vehicles', value: s.available_vehicles, icon: TruckIcon, colorClass: 'bg-purple-500' },
    { label: 'Total Revenue', value: `$${s.total_revenue.toLocaleString()}`, icon: DollarSignIcon, colorClass: 'bg-orange-500' },
  ];
});

const recentOrders = computed(() => dashboardData.value?.recent_orders || []);

const fetchDashboardData = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/public/dashboard'); // Using public for ease of demo
    dashboardData.value = response.data;
  } catch (err: any) {
    error.value = 'Failed to load dashboard data. Please try again.';
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const getStatusClass = (status: string) => {
  const classes: Record<string, string> = {
    pending: 'bg-yellow-100 text-yellow-700',
    in_transit: 'bg-blue-100 text-blue-700',
    delivered: 'bg-green-100 text-green-700',
    cancelled: 'bg-red-100 text-red-700',
  };
  return classes[status] || 'bg-gray-100 text-gray-700';
};

const formatDate = (date: string) => {
  return format(new Date(date), 'MMM dd, yyyy');
};

onMounted(fetchDashboardData);
</script>
