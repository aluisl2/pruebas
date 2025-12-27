<script setup>
import { watch } from 'vue';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useAxios } from '@/composable/useAxios';

const { modalState, closeModal } = useAxios();

watch(() => modalState.value.status, (newStatus) => {
    if (newStatus === 'success') {
        closeModal();
    }
});
</script>

<template>
    <div>
        <!-- Main Content Slot -->
        <slot />

        <!-- Feedback Modal -->
        <DialogModal
            :show="modalState.show"
            :closeable="modalState.status === 'error'"
            @close="closeModal"
            max-width="md"
        >
            <template #title>
                <span v-if="modalState.status === 'loading'">Cargando</span>
                <span v-else-if="modalState.status === 'error'">Error</span>
            </template>

            <template #content>
                <div class="flex flex-col items-center justify-center p-4 space-y-4">

                    <!-- Loading State -->
                    <div v-if="modalState.status === 'loading'" class="flex flex-col items-center">
                        <svg class="animate-spin h-10 w-10 text-slate-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-slate-600 font-medium">Cargando...</p>
                    </div>

                    <!-- Error State -->
                    <div v-else-if="modalState.status === 'error'" class="flex flex-col items-center text-center">
                        <div class="text-4xl mb-2 text-red-500">⚠️</div>
                        <p class="text-slate-700">{{ modalState.message }}</p>
                    </div>

                </div>
            </template>

            <template #footer>
                <SecondaryButton
                    v-if="modalState.status === 'error'"
                    @click="closeModal"
                >
                    Cerrar
                </SecondaryButton>
            </template>
        </DialogModal>
    </div>
</template>
