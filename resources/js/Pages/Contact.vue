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
                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0"
                            >
                                <MapPin class="w-6 h-6 text-blue-600" />
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold mb-1">
                                    Alamat
                                </h4>
                                <p class="text-gray-600">
                                    {{ settings.address }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0"
                            >
                                <Phone class="w-6 h-6 text-emerald-600" />
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold mb-1">
                                    Telepon
                                </h4>
                                <p class="text-gray-600 hover:text-emerald-600">
                                    <a :href="whatsappLink" target="_blank">{{
                                        whatsapp
                                    }}</a>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0"
                            >
                                <MailIcon class="w-6 h-6 text-purple-600" />
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold mb-1">
                                    Email
                                </h4>
                                <p class="text-gray-600 hover:text-emerald-600">
                                    <a :href="mailLink" target="_blank">{{
                                        settings.email
                                    }}</a>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0"
                            >
                                <Clock class="w-6 h-6 text-red-600" />
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold mb-1">
                                    Jam Operasional
                                </h4>
                                <p class="text-gray-600">
                                    Senin - Jumat: 09.00 - 17.00 WIB<br />Sabtu:
                                    09.00 - 12.00 WIB<br />Minggu: 07.00 - 22.00
                                    WIB
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold mb-4">Ikuti Kami</h4>
                        <div class="flex space-x-4">
                            <a
                                :href="settings.facebook"
                                target="_blank"
                                class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors"
                            >
                                <span class="text-sm font-bold">f</span>
                            </a>
                            <a
                                :href="settings.instagram"
                                target="_blank"
                                class="w-10 h-10 bg-pink-600 text-white rounded-full flex items-center justify-center hover:bg-pink-700 transition-colors"
                            >
                                <span class="text-sm font-bold">IG</span>
                            </a>
                            <a
                                :href="settings.youtube"
                                target="_blank"
                                class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors"
                            >
                                <span class="text-sm font-bold">YT</span>
                            </a>
                        </div>
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
            <div class="mt-16">
                <h3 class="text-2xl font-semibold mb-6 text-center">
                    Lokasi {{ settings.app_name }}
                </h3>
                <div
                    class="bg-gray-200 h-96 rounded-xl flex items-center justify-center"
                >
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3981.9115191584083!2d98.6846479759476!3d3.607723350160575!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30313193d47f59c7%3A0xd5fd05c18e4d0f63!2sGereja%20Bethel%20Indonesia%20Pelita%20Medan!5e0!3m2!1sid!2sid!4v1755097182857!5m2!1sid!2sid"
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
        "Syalom, bapak Pdt. Jayanta Bangun.",
    ),
);
const mailLink = computed(() =>
    createMailLink(
        settings.value.email,
        "Pesan dari Website",
        "Syalom, bapak Pdt. Suheri Gultom/Pdt. Jayanta Bangun.",
    ),
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
        `Syalom, bapak Pdt. Suheri Gultom/Pdt. Jayanta Bangun, saya ${data.value.name}.\n${data.value.message}`,
    );

    if (data.value.subject && data.value.message) {
        window.open(viaMail, "_blank");
    } else {
        alert("Harap isi subjek dan pesan.");
    }
};
</script>
