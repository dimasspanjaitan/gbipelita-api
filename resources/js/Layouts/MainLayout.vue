<template>
    <div>
        <Header :settings="settings" />
        <main>
            <slot />
        </main>
        <Footer :settings="settings" />
    </div>
</template>

<script setup>
import Header from '../Components/Header.vue'
import Footer from '../Components/Footer.vue'
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const settings = computed(() => usePage().props.settings)
</script>
