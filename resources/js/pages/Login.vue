<template>
  <div class="min-h-screen bg-slate-900 flex items-center justify-center p-6">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
      <div class="p-8">
        <div class="text-center mb-10">
          <h1 class="text-3xl font-bold text-slate-900 mb-2">CRMProf</h1>
          <p class="text-gray-500">Sign in to manage your fleet</p>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <MailIcon class="h-5 w-5 text-gray-400" />
              </span>
              <input 
                v-model="email" 
                type="email" 
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-slate-500 focus:border-slate-500 sm:text-sm" 
                placeholder="admin@crmprof.local"
                required
              >
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <LockIcon class="h-5 w-5 text-gray-400" />
              </span>
              <input 
                v-model="password" 
                type="password" 
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-slate-500 focus:border-slate-500 sm:text-sm" 
                placeholder="••••••••"
                required
              >
            </div>
          </div>

          <div v-if="error" class="text-red-600 text-sm bg-red-50 p-3 rounded-lg">
            {{ error }}
          </div>

          <button 
            type="submit" 
            class="w-full bg-slate-900 text-white py-3 rounded-lg font-bold hover:bg-slate-800 transition-colors disabled:bg-gray-400"
            :disabled="loading"
          >
            {{ loading ? 'Signing in...' : 'Sign In' }}
          </button>
        </form>

        <div class="mt-8 pt-8 border-t border-gray-100 text-center text-sm text-gray-500">
          <p>Demo Credentials:</p>
          <p class="font-mono mt-1">test@crmprof.local / password</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { MailIcon, LockIcon } from 'lucide-vue-next';

const router = useRouter();
const email = ref('test@crmprof.local');
const password = ref('password');
const loading = ref(false);
const error = ref<string | null>(null);

const handleLogin = async () => {
  try {
    loading.value = true;
    error.value = null;
    await axios.get('/sanctum/csrf-cookie');
    await axios.post('/api/login', {
      email: email.value,
      password: password.value,
    });
    router.push('/');
  } catch (err: any) {
    console.error('Login failed', err);
    error.value = err.response?.data?.message || 'Login failed. Please check your credentials.';
  } finally {
    loading.value = false;
  }
};
</script>
