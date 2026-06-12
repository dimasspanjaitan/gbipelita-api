<script setup>
import { computed } from "vue";
import { Facebook, Instagram, Youtube } from "lucide-vue-next";

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    },
    variant: {
        type: String,
        default: "dark", // dark | color
    },
    size: {
        type: String,
        default: "md", // sm | md | lg
    },
});

const sizes = {
    sm: {
        wrapper: "w-8 h-8",
        icon: "w-4 h-4",
        text: "text-xs",
    },
    md: {
        wrapper: "w-10 h-10",
        icon: "w-5 h-5",
        text: "text-sm",
    },
    lg: {
        wrapper: "w-12 h-12",
        icon: "w-6 h-6",
        text: "text-base",
    },
};

const sizeStyle = computed(() => sizes[props.size]);

const variants = {
    dark: {
        base: "w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center transition-colors",
        facebook: "hover:bg-blue-600",
        instagram: "hover:bg-pink-600",
        youtube: "hover:bg-red-600",
        tiktok: "hover:bg-black",
        wrapper: "text-gray-400 hover:text-white",
    },
    color: {
        base: "w-10 h-10 text-white rounded-full flex items-center justify-center transition-colors",
        facebook: "bg-blue-600 hover:bg-blue-700",
        instagram: "bg-pink-600 hover:bg-pink-700",
        youtube: "bg-red-600 hover:bg-red-700",
        tiktok: "bg-black hover:bg-gray-900",
        wrapper: "",
    },
};

const style = computed(() => variants[props.variant]);

const socials = computed(() =>
    [
        {
            name: "Facebook",
            url: props.settings.facebook,
            icon: Facebook,
            color: "facebook",
        },
        {
            name: "Instagram",
            url: props.settings.instagram,
            icon: Instagram,
            color: "instagram",
        },
        {
            name: "YouTube",
            url: props.settings.youtube,
            icon: Youtube,
            color: "youtube",
        },
        {
            name: "TikTok",
            url: props.settings.tiktok,
            text: "T",
            color: "tiktok",
        },
    ].filter((s) => s.url),
);
</script>

<template>
    <div class="flex space-x-4">
        <a
            v-for="social in socials"
            :key="social.name"
            :href="social.url"
            target="_blank"
            rel="noopener noreferrer"
        >
            <div :class="[style.base, style[social.color], sizeStyle.wrapper]">
                <component
                    v-if="social.icon"
                    :is="social.icon"
                    :class="sizeStyle.icon"
                />

                <span v-else :class="['font-bold', sizeStyle.text]">
                    {{ social.text }}
                </span>
            </div>
        </a>
    </div>
</template>
