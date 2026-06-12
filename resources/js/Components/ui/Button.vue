<template>
    <button
        :class="[
            'w-full py-3 px-6 rounded-lg font-semibold transition-all duration-300 hover:scale-105 disabled:hover:scale-100 disabled:opacity-50 disabled:cursor-not-allowed',
            className,
        ]"
        :disabled="disabled"
        @click="handleClick"
    >
        {{ text }}
    </button>
</template>

<script setup>
// Definisikan props yang bisa dikirim ke komponen
const props = defineProps({
    text: {
        type: String,
        default: "Tombol",
    },
    className: {
        type: String,
        default: "bg-blue-600 hover:bg-blue-700 text-white",
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

// Definisikan event yang akan di-emit
const emit = defineEmits(["clicked"]);

// Tangani klik dan emit event
const handleClick = () => {
    emit("clicked");
};
</script>
