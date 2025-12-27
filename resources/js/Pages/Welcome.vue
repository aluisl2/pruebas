<script setup>
import {Head, Link} from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import InicioAppLayout from "@/Layouts/InicioAppLayout.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import { Dropdown, Tooltip, Menu, vTooltip } from 'floating-vue'
</script>

<template>
    <Head title="welcome"/>
    <InicioAppLayout>
        <template #inic>
            <a href="/login"
               class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2B67A3]">
                Iniciar sesión
            </a>
            <a href="/register"
               class="inline-flex items-center px-4 py-2 bg-[#E17101] border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-[#e78728] focus:bg-[#e78728] active:bg-[#c46100] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                Registrate
            </a>
        </template>
        <div
            class="flex flex-col-reverse lg:flex-row items-center lg:items-start justify-end lg:justify-center pt-20 lg:pt-35 bg-[#E5EDF9] min-h-screen px-5 gap-10 2xl:gap-20">
            <div
                class="flex flex-col gap-10 w-full sm:min-w-md xl:min-w-xl 2xl:min-w-2xl sm:max-w-md xl:max-w-xl 2xl:max-w-2xl">
                <div class="flex justify-center text-5xl 2xl:text-6xl font-bold text-[#E17101]">BITLAB</div>
                <div class="flex justify-baseline text-2xl xl:text-3xl 2xl:text-4xl font-semibold">Aprender haciendo,
                    juntos
                </div>
                <div class="flex justify-baseline text-md xl:text-xl 2xl:text-2xl">
                    Combinamos la práctica, la colaboración y la motivación en una experiencia de aprendizaje
                    innovadora.
                </div>
                <div class="flex flex-col justify-center items-center gap-7 pb-10">
                    <a href="/register"
                       class="flex items-center justify-center w-full sm:w-fit px-6 py-3 bg-[#E17101] border border-transparent rounded-md font-semibold text-xs sm:text-sm text-white tracking-widest hover:bg-[#e78728] focus:bg-[#e78728] active:bg-[#c46100] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        Aventúrate al conocimiento
                    </a>
                    <a href="/login"
                       class="flex items-center justify-center w-full sm:w-fit px-6 py-3 bg-white border border-[#3B3F48] hover:border-hidden rounded-md font-semibold text-xs sm:text-sm text-black hover:text-white focus:text-white tracking-widest hover:bg-[#2B67A3] focus:bg-[#2B67A3] active:bg-[#14375B] focus:outline-none focus:ring-2 focus:ring-[#2B67A3] focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        ¿Ya tienes una cuenta? Inicia sesión
                    </a>
                </div>
            </div>
            <AuthenticationCardLogo
                class="flex h-50 sm:h-70 xl:h-90 w-80 sm:w-120 xl:w-140 justify-center items-center"/>
        </div>
    </InicioAppLayout>
</template>

<style scoped>

</style>
