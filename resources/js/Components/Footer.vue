<template>
  <footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="col-span-1 md:col-span-2">
          <div class="flex items-center space-x-3 mb-4">
            <div
              class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-lg">G</span>
            </div>
            <div>
              <h3 class="text-xl font-bold">{{ settings.app_name }}</h3>
              <p class="text-gray-400 text-sm">{{ settings.app_description }}</p>
            </div>
          </div>
          <p class="text-gray-400 mb-4 max-w-md">
            {{ settings.history }}
          </p>
          <div class="flex space-x-4">
            <a :href="settings.facebook" target="_blank" class="text-gray-400 hover:text-white transition-colors">
              <span class="sr-only">Facebook</span>
              <div
                class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center hover:bg-blue-600 transition-colors">
                <span class="text-xs font-bold">f</span>
              </div>
            </a>
            <a :href="settings.instagram" target="_blank" class="text-gray-400 hover:text-white transition-colors">
              <span class="sr-only">Instagram</span>
              <div
                class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center hover:bg-pink-600 transition-colors">
                <span class="text-xs font-bold">IG</span>
              </div>
            </a>
            <a :href="settings.youtube" target="_blank" class="text-gray-400 hover:text-white transition-colors">
              <span class="sr-only">YouTube</span>
              <div
                class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                <span class="text-xs font-bold">YT</span>
              </div>
            </a>
          </div>
        </div>

        <div>
          <h4 class="text-lg font-semibold mb-4">Navigasi</h4>
          <ul class="space-y-2">
            <li>
              <Link href="/" class="font-medium transition-colors"
                :class="{ 'text-emerald-600': $page.url === '/', 'text-slate-400 hover:text-emerald-600': $page.url !== '/' }">
              Beranda
              </Link>
            </li>
            <li>
              <Link href="/about" class="font-medium transition-colors"
                :class="{ 'text-emerald-600': $page.url === '/about', 'text-slate-400 hover:text-emerald-600': $page.url !== '/about' }">
              Tentang
              </Link>
            </li>
            <li>
              <Link href="/service" class="font-medium transition-colors"
                :class="{ 'text-emerald-600': $page.url === '/service', 'text-slate-400 hover:text-emerald-600': $page.url !== '/service' }">
              Ibadah
              </Link>
            </li>
            <li>
              <Link href="/contact" class="font-medium transition-colors"
                :class="{ 'text-emerald-600': $page.url === '/contact', 'text-slate-400 hover:text-emerald-600': $page.url !== '/contact' }">
              Kontak
              </Link>
            </li>
          </ul>
        </div>

        <div>
          <h4 class="text-lg font-semibold mb-4">Kontak</h4>
          <ul class="space-y-2 text-gray-400">
            <li>{{ settings.address }}</li>
            <li>
              <Link :href="whatsappLink" target="_blank" rel="noopener noreferrer"
                class="text-gray-400 hover:text-white transition-colors">
              {{ whatsapp }}
              </Link>
            </li>
            <li>
              <Link :href="mailLink" target="_blank" rel="noopener noreferrer"
                class="text-gray-400 hover:text-white transition-colors">
              {{ settings.email }}
              </Link>
            </li>
          </ul>
        </div>
      </div>

      <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
        <p>&copy;{{ currentYear }} {{ settings.app_name }}. Semua hak dilindungi undang-undang.</p>
      </div>
    </div>
  </footer>
</template>

<script setup>
import moment from 'moment'
import { computed } from 'vue'
import { formatPhone, createWhatsAppLink, createMailLink } from '../utils/socialUtils'
import { Link } from '@inertiajs/vue3'

const { settings } = defineProps({
  settings: {
    type: Object,
    required: true
  }
})

const currentYear = moment().format("YYYY")

const whatsapp = computed(() =>
  formatPhone(settings.whatsapp)
)

const whatsappLink = computed(() =>
  createWhatsAppLink(settings.whatsapp, "Syalom pak Pdt. Jayanta Bangun.")
)
const mailLink = computed(() =>
  createMailLink(
    settings.email,
    "Pesan dari Website",
    "Syalom, bapak Pdt. Suheri Gultom/Pdt. Jayanta Bangun."
  )
)
</script>
