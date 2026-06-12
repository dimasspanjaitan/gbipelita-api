<script setup>
import { MapPin } from "@lucide/vue";

defineProps({
    title: String,
    description: String,
    icon: Object,
    bgColor: String,
    iconColor: String,
    services: Array,
    location: String,
});
</script>

<template>
    <div
        class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300"
    >
        <div
            :class="[
                'w-12 h-12 rounded-lg flex items-center justify-center mb-4',
                bgColor,
            ]"
        >
            <component :is="icon" :class="['w-6 h-6', iconColor]" />
        </div>

        <h3 class="text-xl font-semibold mb-2">
            {{ title }}
        </h3>

        <p class="text-gray-600 mb-4">
            {{ description }}
        </p>

        <div class="space-y-2 text-sm">
            <div
                v-for="service in services"
                :key="service.name"
                class="flex justify-between"
            >
                <span class="font-medium">
                    {{ service.name }}
                </span>
                <span>
                    {{ service.time }}
                </span>
            </div>
        </div>

        <p
            v-if="location"
            class="flex flex-row items-center text-gray-600 mt-4 text-sm gap-1"
        >
            <MapPin class="w-5 text-red-500" />
            <span class="mt-1">
                {{ location }}
            </span>
        </p>
    </div>
</template>
