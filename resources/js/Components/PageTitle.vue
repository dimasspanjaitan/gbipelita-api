<template>
    <div class="text-center mb-16 mt-6">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">
            {{ title }}
        </h2>
        <p v-if="description" class="text-xl text-gray-600 max-w-3xl mx-auto">
            {{ description }}
        </p>
    </div>
</template>

<script setup>
const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: false,
    },
});
</script>
3
