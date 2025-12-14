<template>
  <div class="min-h-screen bg-gradient-to-br from-[#ECF1F9] to-blue-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <!-- Logo & Title -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-[#19203B] rounded-full mb-4 shadow-lg">
          <svg class="w-10 h-10 text-[#FBC143]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
        </div>
        <h1 class="text-3xl font-bold text-[#19203B] mb-2">ePerforma BSSN</h1>
        <p class="text-gray-600">Sistem Manajemen Kinerja</p>
      </div>

      <!-- Login Form Card -->
      <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="p-8">
          <form @submit.prevent="handleLogin" class="space-y-5">
            <!-- Email Field -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email
              </label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                  </svg>
                </div>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  required
                  autocomplete="email"
                  class="block w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-[#FBC143] focus:border-[#FBC143] transition-all duration-200"
                  placeholder="admin@bssn.go.id"
                  :disabled="loading"
                />
              </div>
            </div>

            <!-- Password Field -->
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Password
              </label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                </div>
                <input
                  id="password"
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  required
                  autocomplete="current-password"
                  class="block w-full pl-11 pr-12 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-[#FBC143] focus:border-[#FBC143] transition-all duration-200"
                  placeholder="••••••••"
                  :disabled="loading"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute inset-y-0 right-0 pr-3.5 flex items-center"
                  tabindex="-1"
                  :disabled="loading"
                >
                  <svg v-if="!showPassword" class="w-5 h-5 text-gray-400 hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  <svg v-else class="w-5 h-5 text-gray-400 hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Error Message -->
            <transition name="slide-down">
              <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-3.5 flex items-start gap-2.5">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-red-800 leading-relaxed">{{ error }}</p>
              </div>
            </transition>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
              <label class="flex items-center cursor-pointer select-none">
                <input
                  v-model="rememberMe"
                  type="checkbox"
                  class="w-4 h-4 text-[#FBC143] border-gray-300 rounded focus:ring-2 focus:ring-[#FBC143] transition-colors"
                  :disabled="loading"
                />
                <span class="ml-2 text-sm text-gray-700">Ingat saya</span>
              </label>
              <a href="#" class="text-sm text-[#19203B] hover:text-[#FBC143] font-medium transition-colors hover:underline">
                Lupa password?
              </a>
            </div>

            <!-- Submit Button -->
            <button
              type="submit"
              :disabled="loading"
              class="w-full bg-[#FBC143] text-[#19203B] py-3.5 px-4 rounded-lg font-semibold hover:bg-[#f5b634] focus:outline-none focus:ring-4 focus:ring-[#FBC143] focus:ring-opacity-50 transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2.5 shadow-md hover:shadow-lg"
            >
              <svg v-if="loading" class="animate-spin h-5 w-5 text-[#19203B]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ loading ? 'Memproses...' : 'Masuk' }}</span>
            </button>
          </form>
        </div>

        <!-- Credentials Info -->
        <div class="bg-gradient-to-r from-[#ECF1F9] to-blue-50 px-8 py-5 border-t border-gray-100">
          <p class="text-xs font-semibold text-[#19203B] mb-3 text-center uppercase tracking-wide">
            Akun Testing
          </p>
          <div class="grid grid-cols-1 gap-2 text-xs">
            <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2 shadow-sm">
              <span class="font-medium text-gray-700">Admin:</span>
              <code class="text-[#19203B] font-mono">admin@bssn.go.id</code>
            </div>
            <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2 shadow-sm">
              <span class="font-medium text-gray-700">PKO:</span>
              <code class="text-[#19203B] font-mono">pko.d3@bssn.go.id</code>
            </div>
            <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2 shadow-sm">
              <span class="font-medium text-gray-700">Password:</span>
              <code class="text-[#19203B] font-mono">password123</code>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100">
          <p class="text-center text-sm text-gray-600">
            Belum punya akun? 
            <a href="#" class="text-[#19203B] hover:text-[#FBC143] font-semibold transition-colors hover:underline ml-1">
              Hubungi Administrator
            </a>
          </p>
        </div>
      </div>

      <!-- Copyright -->
      <div class="text-center mt-6 text-sm text-gray-600">
        <p>&copy; 2024 ePerforma BSSN. All rights reserved.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { authService } from '../../services/auth'

const router = useRouter()

const form = reactive({
  email: '',
  password: ''
})

const loading = ref(false)
const error = ref('')
const showPassword = ref(false)
const rememberMe = ref(false)

const handleLogin = async () => {
  loading.value = true
  error.value = ''

  try {
    await authService.login({
      email: form.email,
      password: form.password
    })

    if (rememberMe.value) {
      localStorage.setItem('rememberMe', 'true')
      localStorage.setItem('email', form.email)
    } else {
      localStorage.removeItem('rememberMe')
      localStorage.removeItem('email')
    }

    router.push('/beranda')
  } catch (err) {
    console.error('Login error:', err)
    error.value = err.message || 'Email atau password salah. Silakan coba lagi.'
  } finally {
    loading.value = false
  }
}

const checkRememberMe = () => {
  const remembered = localStorage.getItem('rememberMe')
  if (remembered) {
    rememberMe.value = true
    form.email = localStorage.getItem('email') || ''
  }
}

onMounted(() => {
  checkRememberMe()
})
</script>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.3s ease;
}

.slide-down-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}

.slide-down-leave-to {
  opacity: 0;
  transform: translateY(-5px);
}

input:focus {
  outline: none;
}

button[type="submit"]:not(:disabled):hover {
  transform: translateY(-1px);
}

button[type="submit"]:not(:disabled):active {
  transform: translateY(0);
}

input:disabled,
button:disabled {
  cursor: not-allowed;
  opacity: 0.6;
}
</style>