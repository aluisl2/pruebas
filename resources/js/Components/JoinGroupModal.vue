<script setup>
import { useForm } from "@inertiajs/vue3";
import { watch } from "vue";
import DialogModal from "@/Components/DialogModal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import {useAxios} from "@/composable/useAxios.ts";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    show: Boolean,
    onSuccess: Function
});

const emit = defineEmits(["close"]);


const { axios: client } = useAxios()

const form = useForm({
    codigo: "",
});

watch(() => props.show, (newVal) => {
    if (newVal) {
        form.clearErrors();
        form.codigo = "";
    }
});

function submitJoin() {
    client.post('/grupos/inscribir',{codigo: form.codigo})
        .then(()=>{
            emit('close')
            router.reload()
        })
}

function close() {
    emit("close");
}
</script>

<template>
    <DialogModal :show="show" @close="close" maxWidth="md">
        <template #title>
            Unirme a un grupo
        </template>

        <template #content>
            <p class="text-slate-600 mb-4">
                Ingresa el código del grupo que tu profesor te compartió.
            </p>

            <div class="mt-4">
                <InputLabel for="codigo" value="Código del grupo" />

                <TextInput
                    id="codigo"
                    v-model="form.codigo"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Ej: 18473938"
                    @keydown.enter.prevent="submitJoin"
                    autofocus
                />

                <InputError :message="form.errors.codigo" class="mt-2" />
            </div>
        </template>

        <template #footer>
            <SecondaryButton @click="close">
                Cancelar
            </SecondaryButton>

            <PrimaryButton
                class="ml-3"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                @click="submitJoin"
            >
                Unirme
            </PrimaryButton>
        </template>
    </DialogModal>
</template>
