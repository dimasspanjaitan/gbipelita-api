<template>
    <section class="pt-24 pb-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <PageTitle
                title="Jadwal Ibadah & Kegiatan"
                description="Bergabunglah dengan kami dalam berbagai kegiatan rohani dan persekutuan"
            />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <CardService
                    v-if="settings.main"
                    title="Ibadah Minggu"
                    description="Ibadah utama dengan pujian, penyembahan, dan firman Tuhan"
                    :icon="LucideChurch"
                    bgColor="bg-blue-100"
                    iconColor="text-blue-600"
                    :services="settings.main.services"
                    :location="settings.main.location"
                />

                <CardService
                    v-if="settings.starkids"
                    title="Sekolah Minggu"
                    description="Pembelajaran Alkitab untuk anak-anak"
                    :icon="LucideHeartHandshake"
                    bgColor="bg-yellow-100"
                    iconColor="text-yellow-600"
                    :services="settings.starkids.services"
                    :location="settings.starkids.location"
                />

                <CardService
                    v-if="settings.family_cell"
                    title="Ibadah Family Cell"
                    description="Ibadah doa dan pembelajaran firman Tuhan"
                    :icon="HouseHeart"
                    bgColor="bg-red-100"
                    iconColor="text-red-600"
                    :services="settings.family_cell"
                />
            </div>
        </div>
    </section>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import PageTitle from "../Components/PageTitle.vue";
import CardService from "../Components/CardService.vue";
import { HouseHeart } from "@lucide/vue";
import { LucideHeartHandshake } from "@lucide/vue";
import { LucideChurch } from "@lucide/vue";

const settings = computed(() => usePage().props.settings);
</script>
