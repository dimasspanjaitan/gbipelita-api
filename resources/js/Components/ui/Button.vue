<template>
  <button
    :class="['w-full py-3 px-6 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105', buttonClass]"
    @click="handleClick"
    target="_blank"
  >
    {{ text }}
  </button>
</template>

<script setup>
// Definisikan props yang bisa dikirim ke komponen
const props = defineProps({
  text: {
    type: String,
    default: 'Tombol',
  },
  buttonClass: {
    type: String,
    default: 'bg-blue-600 hover:bg-blue-700 text-white',
  },
});

// Definisikan event yang akan di-emit
const emit = defineEmits(['clicked']);

// Tangani klik dan emit event
const handleClick = () => {
  emit('clicked');
};
</script>