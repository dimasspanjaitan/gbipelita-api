<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    icon: {
        type: Object,
        required: true,
    },
    bgColor: {
        type: String,
        default: "bg-blue-100",
    },
    iconColor: {
        type: String,
        default: "text-blue-600",
    },
    href: {
        type: String,
        default: null,
    },
});
</script>

<template>
    <div class="flex items-start space-x-4">
        <div
            :class="[
                'w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0',
                bgColor,
            ]"
        >
            <component :is="icon" :class="['w-6 h-6', iconColor]" />
        </div>

        <div class="flex-1">
            <h4 class="text-lg font-semibold mb-1">
                {{ title }}
            </h4>

            <a
                v-if="href"
                :href="href"
                target="_blank"
                rel="noopener noreferrer"
                class="text-gray-600 hover:text-emerald-600"
            >
                <slot />
            </a>

            <div v-else class="text-gray-600">
                <slot />
            </div>
        </div>
    </div>
</template>
