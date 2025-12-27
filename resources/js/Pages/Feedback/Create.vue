<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import DialogModal from "@/Components/DialogModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const form = useForm({
    comentarios: "",
});

const showSuccessModal = ref(false);

const submit = () => {
    form.post(route('feedback.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showSuccessModal.value = true;
        },
    });
};

const closeModalAndRedirect = () => {
    showSuccessModal.value = false;
    router.visit(route('dashboard'));
};
</script>

<template>
    <AppLayout title="Feedback">
        <div class="py-12 bg-gray-50 min-h-[calc(100vh-65px)]">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-2xl rounded-3xl border border-gray-100 p-8 md:p-12 relative">
                    <!-- Decorative element -->
                    <div class="absolute top-0 right-0 p-8 opacity-5 hidden md:block">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-32">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3h9m-9 3h3m-6.75 4.125a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 8.25h.008v.008H19.5V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                    </div>

                    <div class="relative z-10">
                        <h2 class="text-3xl font-black text-gray-800 mb-2">Ayúdanos a Mejorar</h2>
                        <p class="text-gray-500 mb-10 text-lg">
                            Tus comentarios son fundamentales para que Bitlab siga creciendo. 
                            <span class="font-bold text-primary">¡Gracias por ser parte de nuestra comunidad!</span>
                        </p>

                        <form @submit.prevent="submit" class="space-y-8">
                            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                                <InputLabel for="comentarios" value="¿Qué tienes en mente?" class="text-gray-700 font-bold mb-3" />
                                <textarea
                                    id="comentarios"
                                    v-model="form.comentarios"
                                    rows="6"
                                    class="w-full border-gray-200 focus:border-primary focus:ring-primary rounded-xl shadow-sm transition-all duration-300 resize-none p-4 text-gray-600"
                                    placeholder="Cuéntanos tus sugerencias, problemas o lo que más te gusta de la plataforma..."
                                ></textarea>
                                <InputError :message="form.errors.comentarios" class="mt-2" />
                                <p class="text-xs text-gray-400 mt-3 text-right">Máximo 1000 caracteres</p>
                            </div>

                            <div class="flex items-center justify-between pt-4">
                                <Link :href="route('dashboard')" class="text-gray-400 hover:text-gray-600 font-bold transition">
                                    Cancelar
                                </Link>
                                <button 
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-4 bg-primary text-white rounded-2xl font-black text-lg hover:bg-orange-600 transition shadow-lg shadow-orange-100 flex items-center gap-3"
                                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                    </svg>
                                    Enviar Feedback
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <DialogModal :show="showSuccessModal" @close="closeModalAndRedirect">
            <template #title>
                <div class="flex items-center gap-2 text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>
                    ¡Recibido con éxito!
                </div>
            </template>

            <template #content>
                <div class="py-4">
                    <p class="text-lg text-gray-600">
                        Tus comentarios han sido registrados. <strong>Apreciamos mucho tu tiempo</strong> y el esfuerzo por ayudarnos a ser mejores cada día.
                    </p>
                </div>
            </template>

            <template #footer>
                <PrimaryButton @click="closeModalAndRedirect" class="px-8">
                    Aceptar
                </PrimaryButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>
