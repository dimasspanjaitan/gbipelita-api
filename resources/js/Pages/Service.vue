<template>
    <section class="pt-24 pb-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Jadwal Ibadah & Kegiatan
                </h2>
                <p class="text-xl text-gray-600">
                    Bergabunglah dengan kami dalam berbagai kegiatan rohani dan
                    persekutuan
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Sunday Service -->
                <div
                    class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300"
                >
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4"
                    >
                        <svg
                            class="w-6 h-6 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Ibadah Minggu</h3>
                    <p class="text-gray-600 mb-4">
                        Ibadah utama dengan pujian, penyembahan, dan firman
                        Tuhan
                    </p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="font-medium">Ibadah 1</span>
                            <span>09.00 - 10.30 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Ibadah 2</span>
                            <span>11.00 - 12.30 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium"
                                >Ibadah 3 (LICC Youth)</span
                            >
                            <span>14.00 - 15.30 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Ibadah 4 </span>
                            <span>16.00 - 17.30 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Ibadah 5</span>
                            <span>18.00 - 19.30 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Ibadah 6 (LICC Pro)</span>
                            <span>20.00 - 21.30 WIB</span>
                        </div>
                    </div>
                    <p class="text-gray-600 mt-4 text-sm">
                        Lokasi: Main Hall
                    </p>
                </div>

                <!-- Children Service -->
                <div
                    class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300"
                >
                    <div
                        class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4"
                    >
                        <svg
                            class="w-6 h-6 text-yellow-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a2.5 2.5 0 002.5-2.5V6a2.5 2.5 0 00-2.5-2.5H9V10z"
                            ></path>
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Sekolah Minggu</h3>
                    <p class="text-gray-600 mb-4">
                        Pembelajaran Alkitab untuk anak-anak
                    </p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="font-medium">Ibadah 1</span>
                            <span>09.00 - 10.30 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Ibadah 2</span>
                            <span>11.00 - 12.30 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Ibadah 4</span>
                            <span>16.00 - 17.30 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Ibadah 5</span>
                            <span>18.00 - 19.30 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Ibadah 6</span>
                            <span>20.00 - 21.30 WIB</span>
                        </div>
                    </div>
                    <p class="text-gray-600 mt-4 text-sm">
                        Lokasi: Gendung Starkids
                    </p>
                </div>

                <!-- Family Cell Service -->
                <div
                    class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300"
                >
                    <div
                        class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4"
                    >
                        <svg
                            class="w-6 h-6 text-red-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">
                        Ibadah Family Cell
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Ibadah doa dan pembelajaran firman Tuhan
                    </p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="font-medium">KELUARGA</span>
                            <span>Hub. Leader GA</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">MILENIAL</span>
                            <span>Hub. Leader GA</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">LICC PRO</span>
                            <span>Kamis, 19.30 - 21.00 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">LICC YOUTH</span>
                            <span>Kamis, 19.30 - 21.00 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">LICC NEXTGEN</span>
                            <span>Sabtu, 19.00 - 20.30 WIB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">LICC STARTKIDS</span>
                            <span>Rabu, 17.00 - 18.30 WIB</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const settings = computed(() => usePage().props.settings);
</script>
