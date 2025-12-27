<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="En Construcción" />

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg text-center">
            <div class="mb-4 text-6xl">🚧</div>
            <h1 class="text-2xl font-bold text-gray-900 mb-4">
                Página en Construcción
            </h1>
            <p class="text-gray-600 mb-6">
                Estamos trabajando duro para traerte esta funcionalidad pronto. ¡Vuelve más tarde!
            </p>
            <Link :href="route('dashboard')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                Volver al Dashboard
            </Link>
        </div>
    </div>
</template>
