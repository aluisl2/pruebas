<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InicioAppLayout from "@/Layouts/InicioAppLayout.vue";

const form = useForm({
    name: '',
    apellido_paterno: '',
    apellido_materno: '',
    email: '',
    password: '',
    password_confirmation: '',
    tipousr: '',
    matricula: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register"/>
    <InicioAppLayout>
        <template #inic>
            <a href="/login"
               class="inline-flex items-center px-2 sm:px-4 py-2 bg-[#E17101] border border-transparent rounded-md font-semibold text-[10px] sm:text-xs text-white tracking-widest hover:bg-[#e78728] focus:bg-[#e78728] active:bg-[#c46100] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                ¿Ya tienes cuenta? Inicia sesión
            </a>
        </template>
        <AuthenticationCard>
            <template #logo>
                <AuthenticationCardLogo/>
            </template>
            <div class="text-3xl sm:text-4xl font-bold pb-6 text-[#E17101] flex justify-center">
                ¡Te damos la bienvenida!
            </div>
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="name" value="Nombre"/>
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                        :placeholder="'Jose Pedro'"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.name"/>
                </div>

                <div class="mt-4">
                    <InputLabel for="apellido_paterno" value="Apellido paterno"/>
                    <TextInput
                        id="apellido_paterno"
                        v-model="form.apellido_paterno"
                        type="text"
                        class="mt-1 block w-full"
                        :placeholder="'Luis'"
                        required
                        autocomplete=""
                    />
                    <InputError class="mt-2" :message="form.errors.apellido_paterno"/>
                </div>

                <div class="mt-4">
                    <InputLabel for="apellido_materno" value="Apellido materno"/>
                    <TextInput
                        id="apellido_materno"
                        v-model="form.apellido_materno"
                        type="text"
                        class="mt-1 block w-full"
                        :placeholder="'Mendoza'"
                        required
                        autocomplete=""
                    />
                    <InputError class="mt-2" :message="form.errors.apellido_materno"/>
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Correo"/>
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        :placeholder="'usuario@ejemplo.com'"
                        required
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email"/>
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Contraseña"/>
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        :placeholder="'12345678'"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password"/>
                </div>

                <div class="mt-4">
                    <InputLabel for="password_confirmation" value="Confirma tu contraseña"/>
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        :placeholder="'12345678'"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation"/>
                </div>

                <div class="flex items-center gap-6 mt-5">
                    <span class="text-gray-700 font-medium">Tipo de usuario:</span>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" value="PROFESOR" v-model="form.tipousr"
                               class="text-orange-500 focus:ring-orange-500 h-5 w-5">
                        <span class="text-gray-700">Profesor</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" value="ALUMNO" v-model="form.tipousr"
                               class="text-orange-500 focus:ring-orange-500 h-5 w-5">
                        <span class="text-gray-700">Alumno</span>
                    </label>

                </div>
                <div v-if="form.tipousr === 'PROFESOR'"
                     class="flex flex-row items-center gap-3 mt-5 text-gray-700 font-medium">
                    Número de empleado:
                    <TextInput
                        id="matricula"
                        v-model="form.matricula"
                        type="matricula"
                        class="mt-1 block w-auto"
                        :placeholder="'1234567890'"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.matricula"/>
                </div>
                <div v-else-if="form.tipousr === 'ALUMNO'"
                     class="flex flex-row items-center gap-3 mt-5 text-gray-700 font-medium">
                    Número de matricula:
                    <TextInput
                        id="matricula"
                        v-model="form.matricula"
                        type="matricula"
                        class="mt-1 block w-auto"
                        :placeholder="'1234567890'"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.matricula"/>
                </div>
                <div v-else>
                    <InputError class="mt-2" :message="form.errors.matricula"/>
                </div>
                <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                    <InputLabel for="terms">
                        <div class="flex items-center">
                            <Checkbox id="terms" v-model:checked="form.terms" name="terms" required/>

                            <div class="ms-2">
                                I agree to the <a target="_blank" :href="route('terms.show')"
                                                  class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Terms
                                of Service</a> and <a target="_blank" :href="route('policy.show')"
                                                      class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Privacy
                                Policy</a>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.terms"/>
                    </InputLabel>
                </div>

                <div class="flex items-center justify-end mt-4">


                    <PrimaryButton class="ms-4 mt-5" :class="{ 'opacity-25': form.processing }"
                                   :disabled="form.processing">
                        Registrarse
                    </PrimaryButton>
                </div>
            </form>
        </AuthenticationCard>
    </InicioAppLayout>
</template>
