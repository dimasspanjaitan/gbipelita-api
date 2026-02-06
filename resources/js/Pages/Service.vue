<template>
  <section class="pt-24 pb-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Jadwal Ibadah & Kegiatan</h2>
        <p class="text-xl text-gray-600">Bergabunglah dengan kami dalam berbagai kegiatan rohani dan persekutuan</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Sunday Service -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
              </path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-2">Ibadah Minggu</h3>
          <p class="text-gray-600 mb-4">Ibadah utama dengan pujian, penyembahan, dan firman Tuhan</p>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="font-medium">Ibadah I:</span>
              <span>08.00 - 09.30 WIB</span>
            </div>
            <div class="flex justify-between">
              <span class="font-medium">Ibadah II:</span>
              <span>10.30 - 12.00 WIB</span>
            </div>
          </div>
        </div>

        <!-- Wednesday Service -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
              </path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-2">Ibadah Rabu</h3>
          <p class="text-gray-600 mb-4">Ibadah doa dan pembelajaran firman Tuhan</p>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="font-medium">Waktu:</span>
              <span>19.30 - 21.00 WIB</span>
            </div>
            <div class="flex justify-between">
              <span class="font-medium">Fokus:</span>
              <span>Doa & Firman</span>
            </div>
          </div>
        </div>

        <!-- Youth Service -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
          <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
              </path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-2">Ibadah Pemuda</h3>
          <p class="text-gray-600 mb-4">Persekutuan khusus untuk generasi muda</p>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="font-medium">Waktu:</span>
              <span>Sabtu 19.00 WIB</span>
            </div>
            <div class="flex justify-between">
              <span class="font-medium">Usia:</span>
              <span>17-35 tahun</span>
            </div>
          </div>
        </div>

        <!-- Children Service -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
          <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a2.5 2.5 0 002.5-2.5V6a2.5 2.5 0 00-2.5-2.5H9V10z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707">
              </path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-2">Sekolah Minggu</h3>
          <p class="text-gray-600 mb-4">Pembelajaran Alkitab untuk anak-anak</p>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="font-medium">Waktu:</span>
              <span>Minggu 08.00 WIB</span>
            </div>
            <div class="flex justify-between">
              <span class="font-medium">Usia:</span>
              <span>3-16 tahun</span>
            </div>
          </div>
        </div>

        <!-- Cell Group -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
          <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
              </path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-2">Kelompok Sel</h3>
          <p class="text-gray-600 mb-4">Persekutuan kecil di rumah-rumah jemaat</p>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="font-medium">Waktu:</span>
              <span>Berbagai hari</span>
            </div>
            <div class="flex justify-between">
              <span class="font-medium">Lokasi:</span>
              <span>Rumah jemaat</span>
            </div>
          </div>
        </div>

        <!-- Special Events -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
          <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-2">Acara Khusus</h3>
          <p class="text-gray-600 mb-4">Perayaan dan kegiatan khusus sepanjang tahun</p>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="font-medium">Natal:</span>
              <span>Desember</span>
            </div>
            <div class="flex justify-between">
              <span class="font-medium">Paskah:</span>
              <span>Maret/April</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const settings = computed(() => usePage().props.settings)
</script>
