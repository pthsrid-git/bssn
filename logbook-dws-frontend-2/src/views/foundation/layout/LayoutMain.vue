<template>
  <div class="flex flex-col w-full h-full min-h-screen antialiased min-w-80">
    <!-- Nav -->
    <nav>
      <div class="bg-primary-500">
        <div class="max-w-screen-xl p-4 m-auto">
          <div class="flex flex-row items-center justify-between">
            <router-link :to="{ name: 'main' }">
              <LogoBssnLight />
            </router-link>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main -->
    <main
      class="flex flex-row items-center justify-center flex-1 w-full max-w-screen-xl p-4 m-auto xl:p-6"
    >
      <RouterView />
    </main>

    <!-- Footer -->
    <footer>
      <div class="p-4 text-xs text-center text-gray-500 bg-primary-500">
        Hak Cipta © 2025
        <a href="https://www.bssn.go.id/" class="hover:underline">Badan Siber dan Sandi Negara</a>
        · Pusat Data dan Teknologi Informasi Komunikasi
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import LogoBssnLight from '../logo/LogoBssnLight.vue'
</script>
