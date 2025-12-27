<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import ApplicationMark from "@/Components/ApplicationMark.vue";
import InicioAppLayout from "@/Layouts/InicioAppLayout.vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />
    <InicioAppLayout>
        <template #inic>
            <a href="/register" class="inline-flex items-center px-2 sm:px-4 py-2 bg-[#E17101] border border-transparent rounded-md font-semibold text-[10px] sm:text-xs text-white tracking-widest hover:bg-[#e78728] focus:bg-[#e78728] active:bg-[#c46100] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                ¿No tienes cuenta? Registrate
            </a>
        </template>
    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>
        <div class="text-3xl sm:text-4xl font-bold pb-6 text-[#E17101] flex justify-center">
            ¡Hola de nuevo!
        </div>
        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Correo" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    :placeholder="'usuario@ejemplo.com'"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Contraseña" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    :placeholder="'12345678'"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center mt-1">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">Recuerdame</span>
                </label>
            </div>

            <div class="flex items-center justify-center mt-10 lg:mt-4 gap-2 sm:gap-7 lg:gap-0">
                <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Forgot your password?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Iniciar sesión
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
    </InicioAppLayout>
</template>
