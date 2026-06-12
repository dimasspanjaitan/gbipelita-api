<template>
    <div class="relative" ref="selectRef">
        <label :for="id" class="block text-sm font-medium text-gray-700 mb-2">
            {{ label }}<span v-if="required" class="text-red-500">*</span>
        </label>

        <button
            type="button"
            @click="isOpen = !isOpen"
            :class="[
                'w-full flex justify-between items-center px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 cursor-pointer',
                { 'bg-white': !isOpen, 'bg-gray-100': isOpen },
            ]"
        >
            <span>{{ selectedLabel || placeholder }}</span>
            <div class="flex items-center space-x-2">
                <!-- <button v-if="selectedLabel" @click.stop="clearSelection" type="button"
                    class="p-1 rounded-full hover:bg-gray-200 border-1 border-gray-200 transition-colors">
                    <X class="w-5 h-5 text-gray-500"></X>
                </button> -->
                <X
                    v-if="selectedLabel"
                    @click.stop="clearSelection"
                    type="button"
                    class="w-6 h-6 text-gray-500 p-1 rounded-full hover:bg-gray-200 border-1 border-gray-200 transition-colors"
                />
                <ChevronDown
                    v-if="!selectedLabel"
                    :class="[
                        'w-6 h-6 text-gray-500 transform duration-200 p-1 rounded-full hover:bg-gray-200 border-1 border-gray-200 transition-colors',
                        { 'rotate-180': isOpen },
                    ]"
                />
            </div>
        </button>

        <div
            v-if="isOpen"
            class="absolute z-10 mt-1 py-4 w-full bg-white border border-gray-300 rounded-lg shadow-lg overflow-hidden transition-all duration-200"
        >
            <div
                v-for="(option, index) in options"
                :key="index"
                @click="selectOption(option)"
                class="px-4 py-2 hover:bg-blue-50 cursor-pointer transition-colors duration-100"
            >
                {{ option.label }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeMount, onBeforeUnmount } from "vue";
import { ChevronDown } from "lucide-vue-next";
import { X } from "lucide-vue-next";

const props = defineProps({
    id: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    modelValue: {
        type: [String, Number],
        default: "",
    },
    options: {
        type: Array,
        required: true,
    },
    placeholder: {
        type: String,
        default: "Pilih salah satu",
    },
    required: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:modelValue"]);

const isOpen = ref(false);
const selectRef = ref(null);

const closeOnOutsideClick = (event) => {
    if (selectRef.value && !selectRef.value.contains(event.target)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", closeOnOutsideClick);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", closeOnOutsideClick);
});

const selectedOption = computed(() => {
    return props.options.find((opt) => opt.value === props.modelValue);
});

const selectedLabel = computed(() => {
    return selectedOption.value ? selectedOption.value.label : null;
});

const selectOption = (option) => {
    emit("update:modelValue", option.value);
    isOpen.value = false;
};

const clearSelection = () => {
    emit("update:modelValue", "");
};
</script>
