<template>
    <section class="pt-24 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <PageTitle
                title="Hubungi Kami"
                description="Kami senang mendengar dari Anda. Jangan ragu untuk menghubungi kami!"
            />

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Info -->
                <div>
                    <h3 class="text-2xl font-semibold mb-8">
                        Informasi Kontak
                    </h3>

                    <div class="space-y-6">
                        <ContactItem
                            title="Alamat"
                            :icon="MapPin"
                            bgColor="bg-blue-50"
                            iconColor="text-blue-600"
                            :href="settings.address_link"
                        >
                            {{ settings.address }}
                        </ContactItem>

                        <ContactItem
                            title="Telepon"
                            :icon="Phone"
                            bgColor="bg-emerald-100"
                            iconColor="text-emerald-600"
                            :href="whatsappLink"
                        >
                            {{ whatsapp }} ({{ settings.whatsapp_name }})
                        </ContactItem>

                        <ContactItem
                            title="Email"
                            :icon="MailIcon"
                            bgColor="bg-purple-100"
                            iconColor="text-purple-600"
                            :href="mailLink"
                        >
                            {{ settings.email }}
                        </ContactItem>

                        <ContactItem
                            title="Jam Operasional"
                            :icon="Clock"
                            bgColor="bg-red-100"
                            iconColor="text-red-600"
                        >
                            <div
                                v-for="operational in settings.operationals"
                                :key="operational.name"
                                class="grid grid-cols-[30%_auto]"
                            >
                                <p>{{ operational.name }}</p>
                                <p>: {{ operational.time }}</p>
                            </div>
                        </ContactItem>
                    </div>

                    <!-- Social Media -->
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold mb-4">Ikuti Kami</h4>
                        <SocialLinks
                            :settings="settings"
                            variant="color"
                            size="lg"
                        />
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-gray-50 p-8 rounded-xl">
                    <h3 class="text-2xl font-semibold mb-6">Kirim Pesan</h3>
                    <div class="space-y-6">
                        <div class="">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Nama Lengkap<span class="text-red-500"
                                    >*</span
                                ></label
                            >
                            <input
                                id="name"
                                v-model="data.name"
                                type="text"
                                required
                                placeholder="Tulis nama Anda"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            />
                        </div>
                        <div>
                            <Select
                                id="subject"
                                label="Topik"
                                v-model="data.subject"
                                :options="options"
                                placeholder="Pilih Topik"
                                required
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Pesan<span class="text-red-500">*</span></label
                            >
                            <textarea
                                id="message"
                                v-model="data.message"
                                rows="5"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="Tulis pesan Anda di sini..."
                            ></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <Button
                                text="Kirim lewat WA"
                                className="bg-emerald-600 text-white"
                                @clicked="sendViaWhatsapp"
                                :disabled="
                                    !data.name.trim() ||
                                    !data.subject.trim() ||
                                    !data.message.trim()
                                "
                            />
                            <Button
                                text="Kirim lewat Email"
                                buttonClass="bg-emerald-600 text-white"
                                @clicked="sendViaMail"
                                :disabled="
                                    !data.name.trim() ||
                                    !data.subject.trim() ||
                                    !data.message.trim()
                                "
                            />
                        </div>
                        <p class="text-sm text-gray-600 -mt-4 mb-2">
                            (<span class="text-red-500">*</span>)Tidak boleh
                            kosong
                        </p>
                    </div>
                </div>
            </div>

            <!-- Map -->
            <div v-if="settings.address_embed_link" class="mt-16">
                <h3 class="text-2xl font-semibold mb-6 text-center">
                    Lokasi {{ settings.app_name }}
                </h3>
                <div
                    class="bg-gray-200 h-96 rounded-xl flex items-center justify-center"
                >
                    <iframe
                        :src="settings.address_embed_link"
                        class="w-full h-full rounded-xl"
                        style="border: 0"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import {
    createMailLink,
    createWhatsAppLink,
    formatPhone,
} from "../utils/socialUtils";
import Button from "../Components/ui/Button.vue";
import { MapPin, Phone, MailIcon, Clock } from "lucide-vue-next";
import Select from "../Components/ui/Select.vue";
import PageTitle from "../Components/PageTitle.vue";
import SocialLinks from "../Components/SocialLinks.vue";
import ContactItem from "../Components/ContactItem.vue";

const data = ref({
    name: "",
    subject: "",
    message: "",
});

const options = [
    { value: "Informasi Umum", label: "Informasi Umum" },
    { value: "Ibadah", label: "Ibadah" },
    { value: "Pelayanan", label: "Pelayanan" },
    { value: "Family Cell", label: "Famili Cell" },
    { value: "Konseling", label: "Konseling" },
    { value: "Lainnya", label: "Lainnya" },
];

const settings = computed(() => usePage().props.settings);
const whatsapp = computed(() => formatPhone(settings.value.whatsapp));
const whatsappLink = computed(() =>
    createWhatsAppLink(
        settings.value.whatsapp,
        `Syalom, ${settings.value.whatsapp_name}.`,
    ),
);
const mailLink = computed(() =>
    createMailLink(settings.value.email, null, "Syalom, GBI PELITA MEDAN."),
);

const sendViaWhatsapp = () => {
    const viaWhatsapp = createWhatsAppLink(
        settings.value.whatsapp,
        data.value.name,
        data.value.subject,
        data.value.message,
    );

    if (data.value.subject && data.value.message) {
        window.open(viaWhatsapp, "_blank");
    } else {
        alert("Harap isi subjek dan pesan.");
    }
};

const sendViaMail = () => {
    const viaMail = createMailLink(
        settings.value.email,
        data.value.subject,
        `Syalom, GBI PELITA MEDAN, saya ${data.value.name}.\n${data.value.message}`,
    );

    if (data.value.subject && data.value.message) {
        window.open(viaMail, "_blank");
    } else {
        alert("Harap isi subjek dan pesan.");
    }
};
</script>
