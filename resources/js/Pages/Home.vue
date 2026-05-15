<template v-slot="{ settings }">
    <section class="pt-16">
        <Hero />

        <!-- Quick Info Cards -->
        <div class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div
                        class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            <ClockIcon class="w-6 h-6 text-blue-600" />
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Jadwal Ibadah Minggu</h3>
                        <div class="grid grid-cols-[1fr_0.2fr_2fr] text-gray-600">
                            <div class="font-bold">Sesi 1</div>
                            <div>:</div>
                            <div>09.00 WIB</div>
                        </div>
                        <div class="grid grid-cols-[1fr_0.2fr_2fr] text-gray-600">
                            <div class="font-bold">Sesi 2</div>
                            <div>:</div>
                            <div>11.00 WIB</div>
                        </div>
                        <div class="grid grid-cols-[1fr_0.2fr_2fr] text-gray-600">
                            <div class="font-bold">Sesi 3</div>
                            <div>:</div>
                            <div>14.00 WIB</div>
                        </div>
                        <div class="grid grid-cols-[1fr_0.2fr_2fr] text-gray-600">
                            <div class="font-bold">Sesi 4</div>
                            <div>:</div>
                            <div>16.00 WIB</div>
                        </div>
                        <div class="grid grid-cols-[1fr_0.2fr_2fr] text-gray-600">
                            <div class="font-bold">Sesi 5</div>
                            <div>:</div>
                            <div>18.00 WIB</div>
                        </div>
                        <div class="grid grid-cols-[1fr_0.2fr_2fr] text-gray-600">
                            <div class="font-bold">Sesi 6</div>
                            <div>:</div>
                            <div>20.00 WIB</div>
                        </div>
                    </div>

                    <div
                        class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                            <MapPin class="w-6 h-6 text-emerald-600" />
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Lokasi</h3>
                        <p class="text-gray-600">{{ settings.address }}</p>
                    </div>

                    <div
                        class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                            <Phone class="w-6 h-6 text-purple-600" />
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Kontak</h3>
                        <p class="text-gray-600">
                            <a :href="whatsappLink" target="_blank">{{ whatsapp }}</a>
                        </p>
                        <p class="text-gray-600">
                            <a :href="mailLink" target="_blank">{{ settings.email }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import Hero from '../Components/Hero.vue'
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { createMailLink, createWhatsAppLink, formatPhone } from '../utils/socialUtils';
import { ClockIcon } from 'lucide-vue-next';
import { MapPin } from 'lucide-vue-next';
import { Phone } from 'lucide-vue-next';

const settings = computed(() => usePage().props.settings)
const whatsapp = computed(() => formatPhone(settings.value.whatsapp))
const whatsappLink = computed(() => createWhatsAppLink(
    settings.value.whatsapp,
    "Syalom, bapak Pdt. Jayanta Bangun."
))
const mailLink = computed(() => createMailLink(
    settings.value.email,
    "Pesan dari Website",
    "Syalom, bapak Pdt. Suheri Gultom/Pdt. Jayanta Bangun."
))

</script>
