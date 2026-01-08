<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <div class="relative w-72">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <SearchIcon class="h-5 w-5 text-gray-400" />
        </span>
        <input 
          v-model="search"
          type="text" 
          placeholder="Search orders..." 
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-slate-500 focus:border-slate-500 sm:text-sm"
        >
      </div>
      <button 
        @click="openCreateModal"
        class="bg-slate-900 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition-colors flex items-center space-x-2"
      >
        <PlusIcon class="w-5 h-5" />
        <span>New Order</span>
      </button>
    </div>

    <div v-if="loading" class="flex justify-center items-center h-64">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-slate-900"></div>
    </div>

    <div v-else class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden text-sm">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
              <th class="px-6 py-4 border-b">Order #</th>
              <th class="px-6 py-4 border-b">Client</th>
              <th class="px-6 py-4 border-b">Status</th>
              <th class="px-6 py-4 border-b">Driver</th>
              <th class="px-6 py-4 border-b text-right">Amount</th>
              <th class="px-6 py-4 border-b text-center">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="order in filteredOrders" :key="order.id" class="hover:bg-gray-50 transition-colors">
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
              <td class="px-6 py-4 text-gray-600">
                {{ order.driver ? order.driver.name : 'Unassigned' }}
              </td>
              <td class="px-6 py-4 text-gray-900 font-semibold text-right">${{ order.amount }}</td>
              <td class="px-6 py-4 text-center">
                <div class="flex justify-center space-x-2">
                  <button @click="editOrder(order)" class="text-blue-600 hover:text-blue-800 p-1">
                    <EditIcon class="w-5 h-5" />
                  </button>
                  <button @click="deleteOrder(order.id)" class="text-red-600 hover:text-red-800 p-1">
                    <TrashIcon class="w-5 h-5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6">
        <h3 class="text-xl font-bold mb-6 text-gray-800">
          {{ editingOrder ? 'Edit Order' : 'Create New Order' }}
        </h3>
        <form @submit.prevent="saveOrder" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Order Number</label>
              <input v-model="form.order_number" type="text" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
              <input v-model="form.client_name" type="text" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500" required>
            </div>
            <div class="col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">Pickup Address</label>
              <input v-model="form.pickup_address" type="text" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500" required>
            </div>
            <div class="col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Address</label>
              <input v-model="form.delivery_address" type="text" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Pickup Date</label>
              <input v-model="form.pickup_date" type="datetime-local" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Date</label>
              <input v-model="form.delivery_date" type="datetime-local" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select v-model="form.status" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500">
                <option value="pending">Pending</option>
                <option value="in_transit">In Transit</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
              <input v-model.number="form.amount" type="number" step="0.01" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500" required>
            </div>
          </div>
          <div class="flex justify-end space-x-3 mt-8">
            <button type="button" @click="showModal = false" class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-50">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 disabled:bg-gray-400" :disabled="submitting">
              {{ submitting ? 'Saving...' : 'Save Order' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, reactive } from 'vue';
import axios from 'axios';
import { 
  PlusIcon, 
  SearchIcon, 
  EditIcon, 
  TrashIcon 
} from 'lucide-vue-next';

const orders = ref<any[]>([]);
const loading = ref(true);
const submitting = ref(false);
const search = ref('');
const showModal = ref(false);
const editingOrder = ref<any>(null);

const form = reactive({
  order_number: '',
  client_name: '',
  pickup_address: '',
  delivery_address: '',
  pickup_date: '',
  delivery_date: '',
  status: 'pending',
  amount: 0,
});

const fetchOrders = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/public/orders');
    orders.value = response.data;
  } catch (err) {
    console.error('Failed to fetch orders', err);
  } finally {
    loading.value = false;
  }
};

const filteredOrders = computed(() => {
  if (!search.value) return orders.value;
  const s = search.value.toLowerCase();
  return orders.value.filter(o => 
    o.order_number.toLowerCase().includes(s) || 
    o.client_name.toLowerCase().includes(s)
  );
});

const openCreateModal = () => {
  editingOrder.value = null;
  Object.assign(form, {
    order_number: 'ORD-' + Math.floor(Math.random() * 100000).toString().padStart(5, '0'),
    client_name: '',
    pickup_address: '',
    delivery_address: '',
    pickup_date: new Date().toISOString().slice(0, 16),
    delivery_date: new Date(Date.now() + 86400000).toISOString().slice(0, 16),
    status: 'pending',
    amount: 0,
  });
  showModal.value = true;
};

const editOrder = (order: any) => {
  editingOrder.value = order;
  Object.assign(form, {
    ...order,
    pickup_date: new Date(order.pickup_date).toISOString().slice(0, 16),
    delivery_date: new Date(order.delivery_date).toISOString().slice(0, 16),
  });
  showModal.value = true;
};

const saveOrder = async () => {
  try {
    submitting.value = true;
    if (editingOrder.value) {
      await axios.put(`/api/public/orders/${editingOrder.value.id}`, form);
    } else {
      await axios.post('/api/public/orders', form);
    }
    await fetchOrders();
    showModal.value = false;
  } catch (err) {
    console.error('Failed to save order', err);
    alert('Error saving order. Please check inputs.');
  } finally {
    submitting.value = false;
  }
};

const deleteOrder = async (id: number) => {
  if (!confirm('Are you sure you want to delete this order?')) return;
  try {
    await axios.delete(`/api/public/orders/${id}`);
    await fetchOrders();
  } catch (err) {
    console.error('Failed to delete order', err);
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

onMounted(fetchOrders);
</script>
