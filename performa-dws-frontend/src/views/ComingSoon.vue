<template>
  <div class="p-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12">
      <div class="text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-6">
          <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-3">Coming Soon</h2>
        <p class="text-gray-600 mb-6">
          Halaman ini sedang dalam pengembangan dan akan segera tersedia.
        </p>
        <router-link
          to="/beranda"
          class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Kembali ke Beranda
        </router-link>
      </div>
    </div>
  </div>
</template>