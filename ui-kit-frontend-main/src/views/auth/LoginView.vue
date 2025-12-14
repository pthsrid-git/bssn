<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-50 to-primary-100">
    <div class="max-w-md w-full mx-4">
      <!-- Card -->
      <div class="bg-white rounded-2xl shadow-xl p-8">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
          <div class="mb-4">
            <img 
              src="@/assets/images/logo-bssn.png" 
              alt="BSSN Logo" 
              class="h-20 mx-auto"
              @error="handleImageError"
            />
          </div>
          <h1 class="text-2xl font-bold text-gray-900 mb-2">Logbook DWS</h1>
          <p class="text-sm text-gray-600">Badan Siber dan Sandi Negara</p>
        </div>

        <!-- Alert Error -->
        <div 
          v-if="errorMessage"
          class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3"
        >
          <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
          <div class="flex-1">
            <p class="text-sm font-medium text-red-800">{{ errorMessage }}</p>
          </div>
        </div>

        <!-- Login Form -->
        <form @submit.prevent="handleLogin" class="space-y-6">
          <!-- NIP Field -->
          <div>
            <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
              NIP
            </label>
            <input
              id="nip"
              v-model="form.nip"
              type="text"
              required
              :disabled="isLoading"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
              placeholder="Masukkan NIP"
            />
          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
              Password
            </label>
            <div class="relative">
              <input
                id="password"
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                :disabled="isLoading"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                placeholder="Masukkan password"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
              >
                <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Remember Me -->
          <div class="flex items-center">
            <input
              id="remember"
              v-model="form.remember"
              type="checkbox"
              :disabled="isLoading"
              class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded disabled:cursor-not-allowed"
            />
            <label for="remember" class="ml-2 block text-sm text-gray-700">
              Ingat saya
            </label>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="isLoading"
            class="w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center justify-center gap-2"
          >
            <svg 
              v-if="isLoading" 
              class="animate-spin h-5 w-5 text-white" 
              xmlns="http://www.w3.org/2000/svg" 
              fill="none" 
              viewBox="0 0 24 24"
            >
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ isLoading ? 'Memproses...' : 'Masuk' }}</span>
          </button>
        </form>

        <!-- Footer -->
        <div class="mt-8 text-center text-xs text-gray-500">
          <p>© 2025 Badan Siber dan Sandi Negara</p>
          <p class="mt-1">Pusat Data dan Teknologi Informasi Komunikasi</p>
        </div>
      </div>

      <!-- Demo Credentials (only in dev) -->
      <div v-if="isDev" class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <p class="text-xs font-semibold text-yellow-800 mb-2">Demo Credentials:</p>
        <div class="space-y-1 text-xs text-yellow-700">
          <p><strong>PKO:</strong> nip: 482716594203857194 / password: password</p>
          <p><strong>PMK:</strong> nip: 482716594203857195 / password: password</p>
          <p><strong>Ka-unit:</strong> nip: 482716594203857196 / password: password</p>
          <p><strong>Admin:</strong> nip: 482716594203857197 / password: password</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useUserDwsStore } from '@/stores/userDwsStore'

const router = useRouter()
const userDwsStore = useUserDwsStore()

// Form data
const form = ref({
  nip: '',
  password: '',
  remember: false
})

// UI state
const isLoading = ref(false)
const showPassword = ref(false)
const errorMessage = ref('')

const isDev = computed(() => import.meta.env.DEV)

// Handle image error
const handleImageError = (e: Event) => {
  const img = e.target as HTMLImageElement
  img.style.display = 'none'
}

// Handle login
const handleLogin = async () => {
  errorMessage.value = ''
  isLoading.value = true

  try {
    // Call Laravel API
    const apiUrl = `${import.meta.env.VITE_API_BASE_URL}/auth/login`
    
    const response = await fetch(apiUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        nip: form.value.nip,
        password: form.value.password
      })
    })

    const data = await response.json()

    // Check if request was successful
    if (!response.ok) {
      throw new Error(data.message || 'Login gagal. Silakan coba lagi.')
    }

    // Check success flag
    if (!data.success) {
      throw new Error(data.message || 'Login gagal. Silakan coba lagi.')
    }

    // Verify user data exists
    if (!data.data || !data.data.user) {
      throw new Error('Data user tidak ditemukan dalam response')
    }

    // Save JWT token
    localStorage.setItem('auth_token', data.data.token)
    
    // Save NIP if remember me is checked
    if (form.value.remember) {
      localStorage.setItem('remember_nip', form.value.nip)
    } else {
      localStorage.removeItem('remember_nip')
    }

    // Set user in store (data already in correct format from UserResource)
    userDwsStore.setUser(data.data.user)

    // Redirect to dashboard
    console.log('🚀 Redirecting to dashboard...')
    router.push('/dashboard-dws/ruang-pribadi/dashboard')
  } catch (error: any) {
    console.error('❌ Login error:', error)
    
    // Handle different error types
    if (error.message) {
      errorMessage.value = error.message
    } else if (error.response) {
      errorMessage.value = 'Server error. Silakan coba lagi.'
    } else if (error.request) {
      errorMessage.value = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.'
    } else {
      errorMessage.value = 'Login gagal. Silakan coba lagi.'
    }
  } finally {
    isLoading.value = false
  }
}

// Load remembered NIP on mount
const rememberedNip = localStorage.getItem('remember_nip')
if (rememberedNip) {
  form.value.nip = rememberedNip
  form.value.remember = true
}
</script>