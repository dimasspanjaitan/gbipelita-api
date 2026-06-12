<template>
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-[50%_10%_auto] gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 flex items-center justify-center">
                            <img
                                :src="settings.app_logo || '/logo.png'"
                                alt="public/logo.png"
                            />
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">
                                {{ settings.app_name }}
                            </h3>
                            <p class="text-gray-400 text-sm">
                                {{ settings.app_description }}
                            </p>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-4 max-w-md">
                        {{ settings.history }}
                    </p>
                    <SocialLinks :settings="settings" size="sm"/>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Navigasi</h4>
                    <ul class="space-y-2">
                        <li>
                            <Link
                                href="/"
                                class="font-medium transition-colors"
                                :class="{
                                    'text-emerald-600': $page.url === '/',
                                    'text-slate-400 hover:text-emerald-600':
                                        $page.url !== '/',
                                }"
                            >
                                Beranda
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/about"
                                class="font-medium transition-colors"
                                :class="{
                                    'text-emerald-600': $page.url === '/about',
                                    'text-slate-400 hover:text-emerald-600':
                                        $page.url !== '/about',
                                }"
                            >
                                Tentang
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/service"
                                class="font-medium transition-colors"
                                :class="{
                                    'text-emerald-600':
                                        $page.url === '/service',
                                    'text-slate-400 hover:text-emerald-600':
                                        $page.url !== '/service',
                                }"
                            >
                                Ibadah
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/contact"
                                class="font-medium transition-colors"
                                :class="{
                                    'text-emerald-600':
                                        $page.url === '/contact',
                                    'text-slate-400 hover:text-emerald-600':
                                        $page.url !== '/contact',
                                }"
                            >
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
                            <Link
                                :href="whatsappLink"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-gray-400 hover:text-white transition-colors"
                            >
                                {{ `${whatsapp} (${settings.whatsapp_name})` }}
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="mailLink"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-gray-400 hover:text-white transition-colors"
                            >
                                {{ settings.email }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>

            <div
                class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400"
            >
                <p>
                    &copy;{{ currentYear }} {{ settings.app_name }}. Semua hak
                    dilindungi undang-undang.
                </p>
            </div>
        </div>
    </footer>
</template>

<script setup>
import moment from "moment";
import { computed } from "vue";
import {
    formatPhone,
    createWhatsAppLink,
    createMailLink,
} from "../utils/socialUtils";
import { Link } from "@inertiajs/vue3";
import SocialLinks from "./SocialLinks.vue";

const { settings } = defineProps({
    settings: {
        type: Object,
        required: true,
    },
});

const currentYear = moment().format("YYYY");
const whatsapp = computed(() => formatPhone(settings.whatsapp));
const whatsappLink = computed(() =>
    createWhatsAppLink(settings.whatsapp, `Syalom ${settings.whatsapp_name}.`),
);
const mailLink = computed(() =>
    createMailLink(settings.email, null, "Syalom, GBI PELITA MEDAN."),
);
</script>
