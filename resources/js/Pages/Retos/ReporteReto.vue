<script setup>
import SidebarOnlyLayout from "@/Layouts/SidebarOnlyLayout.vue";
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    reto: Object,
    stats: Object,
    grupos: Array,
});
const getScoreColor = (score) => {
    if (score >= 90) return 'text-green-600';
    if (score >= 70) return 'text-blue-600';
    return 'text-red-600';
};
</script>

<template>
    <SidebarOnlyLayout
        hrefHome="/dashboard"
        :groups="grupos"
        :activeGroupId="reto.grupo_id"
    >
        <div class="max-w-7xl mx-auto py-8 px-4">

            <div class="flex justify-between items-center mb-8 border-b border-gray-200 pb-5">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ reto.titulo }}</h1>
                    <p class="text-gray-500 mt-1">Reporte de rendimiento del grupo</p>
                </div>
                <div class="flex gap-3">
                    <Link :href="`/grupo/${reto.grupo_id}`" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                        Volver
                    </Link>

                    <a :href="route('retos.reporte.pdf', reto.id)" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium flex items-center gap-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Descargar PDF
                    </a>
                </div>
            </div>

            <div v-if="stats">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                        <div class="text-sm font-bold text-gray-400 uppercase tracking-wider">Promedio General</div>
                        <div class="text-4xl font-black text-indigo-600 mt-2">{{ stats.promedio_calificacion }}</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                        <div class="text-sm font-bold text-gray-400 uppercase tracking-wider">Tiempo Promedio</div>
                        <div class="text-4xl font-black text-gray-800 mt-2">{{ stats.promedio_tiempo }}</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                        <div class="text-sm font-bold text-gray-400 uppercase tracking-wider">Aprobados / Total</div>
                        <div class="text-4xl font-black text-green-600 mt-2">{{ stats.aprobados }} <span class="text-xl text-gray-400">/ {{ stats.total_alumnos }}</span></div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Alumno</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Matrícula</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tiempo</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Calificación</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="d in stats.detalles" :key="d.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold mr-3 border border-orange-200">
                                        {{ d.user?.nombre ? d.user.nombre.charAt(0) : '?' }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ d.user?.nombre || 'Usuario' }} {{ d.user?.apellido_paterno || 'Eliminado' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500 font-mono text-sm">
                                {{ d.user?.matricula || '---' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ d.tiempo_tomado || '00:00:00' }}
                            </td>
                            <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold border"
                                          :class="d.calificacion >= 70
                                            ? 'bg-green-50 text-green-700 border-green-200'
                                            : 'bg-red-50 text-red-700 border-red-200'">
                                        {{ d.calificacion }} pts
                                    </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-else class="text-center py-20 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                <p class="text-gray-500 text-lg">Aún no hay alumnos que hayan completado este reto.</p>
            </div>
        </div>
    </SidebarOnlyLayout>
</template>
