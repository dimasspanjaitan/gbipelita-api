<template>
    <nav
        class="fixed top-0 w-full bg-white/95 backdrop-blur-sm shadow-sm z-50 transition-all duration-300"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <img :src="settings.app_logo || '/logo.png'" alt="Logo" />
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">
                            {{ settings.app_name }}
                        </h1>
                        <p class="text-xs text-gray-600">
                            {{ settings.app_description }}
                        </p>
                    </div>
                </div>

                <div class="hidden md:flex space-x-8">
                    <Link
                        href="/"
                        class="px-3 py-2 text-sm font-medium transition-colors"
                        :class="{
                            'text-emerald-600': $page.url === '/',
                            'text-gray-700 hover:text-emerald-600':
                                $page.url !== '/',
                        }"
                    >
                        Beranda
                    </Link>
                    <Link
                        href="/about"
                        class="px-3 py-2 text-sm font-medium transition-colors"
                        :class="{
                            'text-emerald-600': $page.url === '/about',
                            'text-gray-700 hover:text-emerald-600':
                                $page.url !== '/about',
                        }"
                    >
                        Tentang
                    </Link>
                    <Link
                        href="/service"
                        class="px-3 py-2 text-sm font-medium transition-colors"
                        :class="{
                            'text-emerald-600': $page.url === '/service',
                            'text-gray-700 hover:text-emerald-600':
                                $page.url !== '/service',
                        }"
                    >
                        Ibadah
                    </Link>
                    <Link
                        href="/contact"
                        class="px-3 py-2 text-sm font-medium transition-colors"
                        :class="{
                            'text-emerald-600': $page.url === '/contact',
                            'text-gray-700 hover:text-emerald-600':
                                $page.url !== '/contact',
                        }"
                    >
                        Kontak
                    </Link>
                </div>

                <div class="md:hidden">
                    <button
                        @click="isOpen = !isOpen"
                        class="text-gray-700 hover:text-emerald-600 focus:outline-none"
                    >
                        <X v-if="isOpen" class="h-6 w-6" />
                        <AlignRight v-else class="h-6 w-6" />
                    </button>
                </div>
            </div>
        </div>
        <div v-if="isOpen" class="md:hidden bg-gray-50/95 shadow-sm">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <Link
                    href="/"
                    class="block px-3 py-2 rounded-md text-base font-medium transition-colors"
                    :class="{
                        'bg-emerald-600 text-white': $page.url === '/',
                        'text-gray-700 hover:bg-emerald-50': $page.url !== '/',
                    }"
                >
                    Beranda
                </Link>
                <Link
                    href="/about"
                    class="block px-3 py-2 rounded-md text-base font-medium transition-colors"
                    :class="{
                        'bg-emerald-600 text-white':
                            $page.url.startsWith('/about'),
                        'text-gray-700 hover:bg-emerald-50':
                            !$page.url.startsWith('/about'),
                    }"
                >
                    Tentang
                </Link>
                <Link
                    href="/service"
                    class="block px-3 py-2 rounded-md text-base font-medium transition-colors"
                    :class="{
                        'bg-emerald-600 text-white':
                            $page.url.startsWith('/service'),
                        'text-gray-700 hover:bg-emerald-50':
                            !$page.url.startsWith('/service'),
                    }"
                >
                    Ibadah
                </Link>
                <Link
                    href="/contact"
                    class="block px-3 py-2 rounded-md text-base font-medium transition-colors"
                    :class="{
                        'bg-emerald-600 text-white':
                            $page.url.startsWith('/contact'),
                        'text-gray-700 hover:bg-emerald-50':
                            !$page.url.startsWith('/contact'),
                    }"
                >
                    Kontak
                </Link>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { X } from "lucide-vue-next";
import { AlignRight } from "lucide-vue-next";
import { CircleChevronDown, CircleChevronUp } from "lucide-vue-next";
import { ref } from "vue";

const { settings } = defineProps({
    settings: {
        type: Object,
        required: true,
    },
});

const isOpen = ref(false);
const page = usePage();
</script>
