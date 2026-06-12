<template>
    <section class="pt-24 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <PageTitle
                title="Tentang GBI Pelita Medan"
                :description="settings.history"
            />

            <div
                class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16"
            >
                <div class="mx-auto p-24 shadow-lg rounded-xl">
                    <img
                        :src="settings.app_logo || '/logo.png'"
                        alt="GBI Pelita Medan"
                        class="h-68"
                    />
                </div>
                <div class="text-center space-y-14">
                    <div>
                        <h2 class="text-4xl font-semibold text-blue-600 mb-2">
                            Visi
                        </h2>
                        <p class="text-gray-600">{{ settings.visi }}</p>
                    </div>
                    <div>
                        <h2 class="text-3xl font-semibold text-blue-600 mb-2">
                            Misi
                        </h2>
                        <p class="text-gray-600">{{ settings.misi }}</p>
                    </div>
                </div>
            </div>

            <!-- Values -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div
                        class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4"
                    >
                        <svg
                            class="w-8 h-8 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Kasih</h3>
                    <p class="text-gray-600">
                        Mengasihi Tuhan dengan segenap hati dan mengasihi sesama
                        seperti diri sendiri.
                    </p>
                </div>

                <div class="text-center p-6">
                    <div
                        class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"
                    >
                        <svg
                            class="w-8 h-8 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Persekutuan</h3>
                    <p class="text-gray-600">
                        Membangun komunitas yang saling mendukung dan bertumbuh
                        bersama dalam iman.
                    </p>
                </div>

                <div class="text-center p-6">
                    <div
                        class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4"
                    >
                        <svg
                            class="w-8 h-8 text-purple-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Pelayanan</h3>
                    <p class="text-gray-600">
                        Melayani dengan setia menggunakan karunia yang Tuhan
                        berikan kepada setiap orang.
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import PageTitle from "../Components/PageTitle.vue";

const settings = computed(() => usePage().props.settings);
</script>
