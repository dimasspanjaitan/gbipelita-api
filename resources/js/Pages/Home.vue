<template v-slot="{ settings }">
    <section class="pt-16">
        <Hero />

        <!-- Quick Info Cards -->
        <div class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <CardService
                        v-if="settings.main"
                        title="Ibadah Minggu"
                        :icon="LucideChurch"
                        bgColor="bg-blue-100"
                        iconColor="text-blue-600"
                        :services="settings.main.services"
                        :location="settings.main.location"
                    />

                    <div
                        class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2"
                    >
                        <div
                            class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4"
                        >
                            <MapPin class="w-6 h-6 text-emerald-600" />
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Lokasi</h3>
                        <p class="text-gray-600">{{ settings.address }}</p>
                    </div>

                    <div
                        class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2"
                    >
                        <div
                            class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4"
                        >
                            <Phone class="w-6 h-6 text-purple-600" />
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Kontak</h3>
                        <p class="text-gray-600">
                            <a :href="whatsappLink" target="_blank">{{
                                `${whatsapp} (${settings.whatsapp_name})`
                            }}</a>
                        </p>
                        <p class="text-gray-600">
                            <a :href="mailLink" target="_blank">{{
                                settings.email
                            }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import Hero from "../Components/Hero.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import {
    createMailLink,
    createWhatsAppLink,
    formatPhone,
} from "../utils/socialUtils";
import { ClockIcon } from "lucide-vue-next";
import { MapPin } from "lucide-vue-next";
import { Phone } from "lucide-vue-next";
import CardService from "../Components/CardService.vue";
import { LucideChurch } from "lucide-vue-next";

const settings = computed(() => usePage().props.settings);
const whatsapp = computed(() => formatPhone(settings.value.whatsapp));
const whatsappLink = computed(() =>
    createWhatsAppLink(
        settings.value.whatsapp,
        `Syalom, ${settings.value.whatsapp_name}`,
    ),
);
const mailLink = computed(() =>
    createMailLink(settings.value.email, null, "Syalom, GBI PELITA MEDAN"),
);
</script>
