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
          placeholder="Search vehicles..." 
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-slate-500 focus:border-slate-500 sm:text-sm"
        >
      </div>
      <button 
        @click="openCreateModal"
        class="bg-slate-900 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition-colors flex items-center space-x-2"
      >
        <PlusIcon class="w-5 h-5" />
        <span>Add Vehicle</span>
      </button>
    </div>

    <div v-if="loading" class="flex justify-center items-center h-64">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-slate-900"></div>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
      <div v-for="vehicle in filteredVehicles" :key="vehicle.id" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group">
        <div class="p-5">
          <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-slate-100 rounded-lg text-slate-600">
              <TruckIcon class="w-6 h-6" />
            </div>
            <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
              <button @click="editVehicle(vehicle)" class="text-blue-600 hover:text-blue-800 p-1 bg-blue-50 rounded">
                <EditIcon class="w-4 h-4" />
              </button>
              <button @click="deleteVehicle(vehicle.id)" class="text-red-600 hover:text-red-800 p-1 bg-red-50 rounded">
                <TrashIcon class="w-4 h-4" />
              </button>
            </div>
          </div>
          <h3 class="font-bold text-gray-900 text-lg">{{ vehicle.year }} {{ vehicle.make }} {{ vehicle.model }}</h3>
          <p class="text-gray-500 font-mono mb-4">{{ vehicle.plate_number }}</p>
          <div class="flex justify-between items-center">
            <span class="px-2 py-1 rounded-full text-xs font-medium" :class="getStatusClass(vehicle.status)">
              {{ vehicle.status }}
            </span>
            <span class="text-gray-400 text-xs italic">ID: {{ vehicle.id }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-xl font-bold mb-6 text-gray-800">
          {{ editingVehicle ? 'Edit Vehicle' : 'Add New Vehicle' }}
        </h3>
        <form @submit.prevent="saveVehicle" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">Make</label>
              <input v-model="form.make" type="text" placeholder="e.g. Toyota" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500" required>
            </div>
            <div class="col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
              <input v-model="form.model" type="text" placeholder="e.g. Camry" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
              <input v-model.number="form.year" type="number" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Plate Number</label>
              <input v-model="form.plate_number" type="text" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500" required>
            </div>
            <div class="col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select v-model="form.status" class="w-full border rounded-lg p-2 focus:ring-slate-500 focus:border-slate-500">
                <option value="available">Available</option>
                <option value="busy">Busy</option>
                <option value="maintenance">Maintenance</option>
              </select>
            </div>
          </div>
          <div class="flex justify-end space-x-3 mt-8">
            <button type="button" @click="showModal = false" class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-50">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 disabled:bg-gray-400" :disabled="submitting">
              {{ submitting ? 'Saving...' : 'Save Vehicle' }}
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
  TruckIcon
} from 'lucide-vue-next';

const vehicles = ref<any[]>([]);
const loading = ref(true);
const submitting = ref(false);
const search = ref('');
const showModal = ref(false);
const editingVehicle = ref<any>(null);

const form = reactive({
  make: '',
  model: '',
  year: new Date().getFullYear(),
  plate_number: '',
  status: 'available',
});

const fetchVehicles = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/public/vehicles');
    vehicles.value = response.data;
  } catch (err) {
    console.error('Failed to fetch vehicles', err);
  } finally {
    loading.value = false;
  }
};

const filteredVehicles = computed(() => {
  if (!search.value) return vehicles.value;
  const s = search.value.toLowerCase();
  return vehicles.value.filter(v => 
    v.make.toLowerCase().includes(s) || 
    v.model.toLowerCase().includes(s) ||
    v.plate_number.toLowerCase().includes(s)
  );
});

const openCreateModal = () => {
  editingVehicle.value = null;
  Object.assign(form, {
    make: '',
    model: '',
    year: new Date().getFullYear(),
    plate_number: '',
    status: 'available',
  });
  showModal.value = true;
};

const editVehicle = (vehicle: any) => {
  editingVehicle.value = vehicle;
  Object.assign(form, vehicle);
  showModal.value = true;
};

const saveVehicle = async () => {
  try {
    submitting.value = true;
    if (editingVehicle.value) {
      await axios.put(`/api/public/vehicles/${editingVehicle.value.id}`, form);
    } else {
      await axios.post('/api/public/vehicles', form);
    }
    await fetchVehicles();
    showModal.value = false;
  } catch (err: any) {
    console.error('Failed to save vehicle', err);
    if (err.response?.data?.errors) {
      alert(Object.values(err.response.data.errors).flat().join('\n'));
    } else {
      alert('Error saving vehicle.');
    }
  } finally {
    submitting.value = false;
  }
};

const deleteVehicle = async (id: number) => {
  if (!confirm('Are you sure you want to delete this vehicle?')) return;
  try {
    await axios.delete(`/api/public/vehicles/${id}`);
    await fetchVehicles();
  } catch (err) {
    console.error('Failed to delete vehicle', err);
  }
};

const getStatusClass = (status: string) => {
  const classes: Record<string, string> = {
    available: 'bg-green-100 text-green-700',
    busy: 'bg-blue-100 text-blue-700',
    maintenance: 'bg-red-100 text-red-700',
  };
  return classes[status] || 'bg-gray-100 text-gray-700';
};

onMounted(fetchVehicles);
</script>
