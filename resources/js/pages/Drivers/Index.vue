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
          placeholder="Search drivers..." 
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-slate-500 focus:border-slate-500 sm:text-sm"
        >
      </div>
      <button 
        @click="openCreateModal"
        class="bg-slate-900 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition-colors flex items-center space-x-2"
      >
        <PlusIcon class="w-5 h-5" />
        <span>New Driver</span>
      </button>
    </div>

    <div v-if="loading" class="flex justify-center items-center h-64">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-slate-900"></div>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="driver in filteredDrivers" :key="driver.id" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative group">
        <div class="absolute top-4 right-4 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
          <button @click="editDriver(driver)" class="text-blue-600 hover:text-blue-800 p-1 bg-blue-50 rounded">
            <EditIcon class="w-4 h-4" />
          </button>
          <button @click="deleteDriver(driver.id)" class="text-red-600 hover:text-red-800 p-1 bg-red-50 rounded">
            <TrashIcon class="w-4 h-4" />
          </button>
        </div>
        <div class="flex items-center space-x-4 mb-4">
          <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-lg border-2 border-white shadow-sm">
            {{ driver.name.charAt(0) }}
          </div>
          <div>
            <h3 class="font-bold text-gray-900">{{ driver.name }}</h3>
            <span class="text-xs px-2 py-0.5 rounded-full" :class="getStatusClass(driver.status)">
              {{ driver.status }}
            </span>
          </div>
        </div>
        <div class="space-y-2 text-sm text-gray-600">
          <div class="flex items-center space-x-2">
            <MailIcon class="w-4 h-4 text-gray-400" />
            <span>{{ driver.email }}</span>
          </div>
          <div class="flex items-center space-x-2">
            <PhoneIcon class="w-4 h-4 text-gray-400" />
            <span>{{ driver.phone }}</span>
          </div>
          <div class="flex items-center space-x-2">
            <CreditCardIcon class="w-4 h-4 text-gray-400" />
            <span>License: {{ driver.license_number }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-xl font-bold mb-6 text-gray-800">
          {{ editingDriver ? 'Edit Driver' : 'Add New Driver' }}
        </h3>
        <form @submit.prevent="saveDriver" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input v-model="form.name" type="text" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500" required>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input v-model="form.email" type="email" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500" required>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
            <input v-model="form.phone" type="text" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500" required>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">License Number</label>
            <input v-model="form.license_number" type="text" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500" required>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select v-model="form.status" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          <div class="flex justify-end space-x-3 mt-8">
            <button type="button" @click="showModal = false" class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-50">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 disabled:bg-gray-400" :disabled="submitting">
              {{ submitting ? 'Saving...' : 'Save Driver' }}
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
  TrashIcon,
  MailIcon,
  PhoneIcon,
  CreditCardIcon
} from 'lucide-vue-next';

const drivers = ref<any[]>([]);
const loading = ref(true);
const submitting = ref(false);
const search = ref('');
const showModal = ref(false);
const editingDriver = ref<any>(null);

const form = reactive({
  name: '',
  email: '',
  phone: '',
  license_number: '',
  status: 'active',
});

const fetchDrivers = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/public/drivers');
    drivers.value = response.data;
  } catch (err) {
    console.error('Failed to fetch drivers', err);
  } finally {
    loading.value = false;
  }
};

const filteredDrivers = computed(() => {
  if (!search.value) return drivers.value;
  const s = search.value.toLowerCase();
  return drivers.value.filter(d => 
    d.name.toLowerCase().includes(s) || 
    d.email.toLowerCase().includes(s)
  );
});

const openCreateModal = () => {
  editingDriver.value = null;
  Object.assign(form, {
    name: '',
    email: '',
    phone: '',
    license_number: '',
    status: 'active',
  });
  showModal.value = true;
};

const editDriver = (driver: any) => {
  editingDriver.value = driver;
  Object.assign(form, driver);
  showModal.value = true;
};

const saveDriver = async () => {
  try {
    submitting.value = true;
    if (editingDriver.value) {
      await axios.put(`/api/public/drivers/${editingDriver.value.id}`, form);
    } else {
      await axios.post('/api/public/drivers', form);
    }
    await fetchDrivers();
    showModal.value = false;
  } catch (err: any) {
    console.error('Failed to save driver', err);
    if (err.response?.data?.errors) {
      alert(Object.values(err.response.data.errors).flat().join('\n'));
    } else {
      alert('Error saving driver.');
    }
  } finally {
    submitting.value = false;
  }
};

const deleteDriver = async (id: number) => {
  if (!confirm('Are you sure you want to delete this driver?')) return;
  try {
    await axios.delete(`/api/public/drivers/${id}`);
    await fetchDrivers();
  } catch (err) {
    console.error('Failed to delete driver', err);
  }
};

const getStatusClass = (status: string) => {
  return status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700';
};

onMounted(fetchDrivers);
</script>
