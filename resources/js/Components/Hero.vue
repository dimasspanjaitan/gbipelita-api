<template>
    <!-- Hero Slider -->
    <div class="relative h-screen overflow-hidden">
        <div class="absolute inset-0 bg-black/30 z-10"></div>

        <!-- Slides -->
        <div class="relative h-full">
            <div v-for="(slide, index) in slides" :key="index"
                :class="currentSlide === index ? 'opacity-100' : 'opacity-0'"
                class="absolute inset-0 transition-opacity duration-1000 ease-in-out">
                <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover">
            </div>
        </div>

        <!-- Hero Content -->
        <div class="absolute inset-0 z-20 flex items-center justify-center">
            <div class="text-center text-white px-4 max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 animate-fade-in-up">
                    {{ slides[currentSlide].title }}
                </h1>
                <p class="text-xl md:text-2xl mb-8 animate-fade-in-up animation-delay-300">
                    {{ slides[currentSlide].subtitle }}
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 animate-fade-in-up animation-delay-600 gap-2">
                    <Link href="/about">
                    <button
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-full font-semibold transition-all duration-300 transform hover:scale-105">
                        Pelajari Lebih Lanjut
                    </button>
                    </Link>
                    <Link href="/contact">
                    <button
                        class="border-2 border-white text-white hover:bg-white hover:text-gray-900 px-8 py-3 rounded-full font-semibold transition-all duration-300 transform hover:scale-105">
                        Hubungi Kami
                    </button>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Slide Indicators -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-30 flex space-x-2">
            <button v-for="(slide, index) in slides" :key="index" @click="currentSlide = index"
                :class="currentSlide === index ? 'bg-white' : 'bg-white/50'"
                class="w-3 h-3 rounded-full transition-all duration-300 hover:bg-white">
            </button>
        </div>

        <!-- Navigation Arrows -->
        <button @click="previousSlide"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 z-30 bg-white/20 hover:bg-white/30 text-white p-2 rounded-full transition-all duration-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                </path>
            </svg>
        </button>
        <button @click="nextSlide"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 z-30 bg-white/20 hover:bg-white/30 text-white p-2 rounded-full transition-all duration-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'

const currentSlide = ref(0)
// Slides data
const slides = ref([
    {
        // Gambar acak untuk slide sambutan
        image: 'https://picsum.photos/id/24/1200/800',
        title: 'Selamat Datang di GBI Pelita Medan',
        subtitle: 'Rumah bagi setiap jiwa yang mencari Tuhan'
    },
    {
        // Gambar acak lain untuk slide pujian dan penyembahan
        image: 'https://picsum.photos/id/104/1200/800',
        title: 'Bersama Memuji dan Menyembah',
        subtitle: 'Bergabunglah dalam ibadah yang penuh sukacita'
    },
    {
        // Gambar acak lain untuk slide komunitas
        image: 'https://picsum.photos/id/108/1200/800',
        title: 'Komunitas yang Mengasihi',
        subtitle: 'Membangun persekutuan yang kuat dalam kasih Kristus'
    },
    {
        // Gambar acak lain untuk slide generasi penerus
        image: 'https://picsum.photos/id/115/1200/800',
        title: 'Generasi Penerus Iman',
        subtitle: 'Membentuk pemimpin masa depan yang berkarakter'
    }
])

// Auto slide functionality
let slideInterval = null

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % slides.value.length
}

const previousSlide = () => {
    currentSlide.value = currentSlide.value === 0 ? slides.value.length - 1 : currentSlide.value - 1
}

const startAutoSlide = () => {
    slideInterval = setInterval(nextSlide, 5000) // Change slide every 5 seconds
}

const stopAutoSlide = () => {
    if (slideInterval) {
        clearInterval(slideInterval)
        slideInterval = null
    }
}

// Lifecycle hooks
onMounted(() => {
    startAutoSlide()
})

onUnmounted(() => {
    stopAutoSlide()
})
</script>