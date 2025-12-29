<script setup lang="ts">
import SidebarOnlyLayout from "@/Layouts/SidebarOnlyLayout.vue";
import {Grupo} from "@/interfaces.ts";
import {Link} from "@inertiajs/vue3";
import {onBeforeMount, ref, shallowRef} from "vue";
import {useAxios} from "@/composable/useAxios";
import {useUser} from "@/composable/useUser";
import {router} from "@inertiajs/vue3";
import Medal from "@/Components/Medal.vue";
import MedalGroup from "@/Components/MedalGroup.vue";
// --- NUEVOS IMPORTS PARA EL MODAL ---
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const { axios: client } = useAxios()
const user = useUser()

interface Props {
    grupo: Grupo
}

const props = defineProps({
    grupo: Object,
    grupos: Array,
});

enum Tabs {
    Miembros,
    Retos
}

const miembros = shallowRef([])
const miembro = ref()
const retos = shallowRef([])
const tab = ref<Tabs>(Tabs.Retos)

// --- LÓGICA PARA ELIMINAR ALUMNO ---
const confirmingRemoval = ref(false);
const studentToRemove = ref(null);

const confirmRemoval = (student: any) => {
    studentToRemove.value = student;
    confirmingRemoval.value = true;
};

const closeModal = () => {
    confirmingRemoval.value = false;
    setTimeout(() => { studentToRemove.value = null; }, 200);
};

const executeRemoval = () => {
    if (!studentToRemove.value) return;

    router.delete(route('grupos.miembros.destroy', {
        grupo: props.grupo.id,
        user: studentToRemove.value.id
    }), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            // IMPORTANTE: Volvemos a pedir los miembros para actualizar la lista
            // ya que 'miembros' no es una prop, sino un estado local.
            getMembers();
        }
    });
};
// -----------------------------------

const getMembers = async () => {
    // Nota: Asegúrate de que tu backend responda a esto.
    // Si usas el código anterior, POST está bien, aunque usualmente es GET.
    const {data} = await client.post(`/grupo/${props.grupo.id}/miembros`)
    miembros.value = data
}

const puntajeMax = async () => {
    const {data} = await client.post(`/grupo/${props.grupo.id}/miembro/${user.id}`)
    miembro.value = data
}

const getRetos = async () => {
    const {data} = await client.post(`/grupo/${props.grupo.id}/retos`)
    retos.value = data
}


const select = async (t: Tabs) => {
    switch (t) {
        case Tabs.Miembros:
            await getMembers()
            tab.value = Tabs.Miembros
            break
        case Tabs.Retos:
            await getRetos()
            tab.value = Tabs.Retos
            break
    }
}

onBeforeMount(() => {
    getRetos()
    puntajeMax()
})


const abrirReto = (r: any) => {
    if (user.rol.clave === 'PROFESOR') {
        router.visit('/construction')
    } else {
        router.visit(`/reto/${r.id}`)
    }
}

</script>

<template>
    <SidebarOnlyLayout
        :groups="grupos"
        :activeGroupId="grupo.id"
        hrefHome="/dashboard"
    >
        <section class="mt-4">
            <div v-if="user.rol.clave === 'ALUMNO'" class="rounded-xl overflow-hidden flex flex-col sm:flex-row items-start sm:items-center sm:justify-between" :style="{ background: '#2B2E36'}">
                <div class="pl-8 pr-2 py-5 w-full">
                    <div class="text-2xl lg:text-4xl 2xl:text-5xl font-bold tracking-wide pb-5 text-[#E17101] not-sm:flex not-sm:justify-center not-sm:items-center">
                        {{ user.nombre }} {{user.apellido_paterno}} {{user.apellido_materno}}
                    </div>
                    <div class="mt-2 text-md lg:text-lg 2xl:text-2xl font-semibold" :style="{ color: '#FFFFFF' }">
                        <div>Puntaje total: {{miembro?.puntos_obtenidos}}</div>
                        <div>Porcentaje promedio: %{{miembro?.porcentaje_avance}}</div>
                    </div>
                </div>
                <div class="pr-2 py-2 lg:pr-4 2xl:pr-10 not-sm:w-full not-sm:flex not-sm:items-center not-sm:justify-center min-w-fit">
                    <Medal :porcentaje="miembro?.porcentaje_avance || 0"/>
                </div>
            </div>
            <div class="rounded-xl overflow-hidden flex items-center mt-4" :style="{ background: '#2B2E36', height: '110px' }">
                <div class="px-8">
                    <div class="text-4xl font-bold tracking-wide" :style="{ color: '#FFFFFF' }">
                        {{ grupo.nombre }}
                    </div>
                    <div class="mt-2 text-lg" :style="{ color: '#FFFFFF' }">
                        {{ grupo.created_at }}
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded bg-white mt-4">
            <div class="flex items-center justify-between">
                <div class="flex items-end">
                    <button
                        @click.prevent="select(Tabs.Retos)"
                        :class="[tab === Tabs.Retos ? 'border-orange-500' : 'border-gray-200']"
                        class="tabBase px-4 py-2 border-b-4">
                        Retos
                    </button>

                    <button
                        @click.prevent="select(Tabs.Miembros)"
                        :class="[tab === Tabs.Miembros ? 'border-orange-500' : 'border-gray-200']"
                        class="tabBase px-4 py-2 border-b-4">
                        Miembros
                    </button>
                </div>

                <div class="codePill px-4 py-2 flex flex-row items-center">
                    <div class="not-sm:hidden md:hidden lg:block pr-5">Codigo del grupo: </div><span class="font-extrabold bg-stone-200 px-4 py-1 rounded-lg select-all">{{ grupo.clave }}</span>
                </div>
            </div>
        </section>

        <Transition>
            <section v-if="tab === Tabs.Miembros" class="mt-4 space-y-3">
                <div class="px-6 py-4 bg-white rounded-2xl shadow-sm border border-gray-100 grid items-center gap-4 grid-cols-2 md:grid-cols-12"
                     v-for="m of miembros" :key="m.id">

                    <div class="flex items-center gap-4 col-span-2 md:col-span-4">
                        <div class="size-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-xl border-2 border-orange-200 shrink-0">
                            {{ m.nombre.charAt(0) }}{{ m.apellido_paterno.charAt(0) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-black text-gray-800 text-lg leading-tight truncate">
                                {{ m.nombre }} {{ m.apellido_paterno }} {{ m.apellido_materno }}
                            </p>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">
                                Matrícula: {{ m.matricula }}
                            </p>
                        </div>
                    </div>

                    <div class="w-full col-span-2 md:col-span-4">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-black text-gray-400 uppercase">Avance</span>
                            <span class="text-sm font-black text-orange-600">{{ m.porcentaje_avance }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-orange-500 h-full rounded-full transition-all duration-500" :style="{ width: m.porcentaje_avance + '%' }"></div>
                        </div>
                    </div>

                    <div class="col-span-2 md:col-span-3 flex items-center justify-between md:justify-end gap-4">
                        <div class="flex flex-col md:items-end">
                            <span class="text-xs font-black text-gray-400 uppercase tracking-tighter">Puntos</span>
                            <span class="text-xl font-black text-gray-800">
                        {{ m.puntos_obtenidos }}
                        <span class="text-gray-300 text-sm font-normal">/ {{ m.total_puntos_grupo }}</span>
                    </span>
                        </div>
                        <MedalGroup :porcentaje="m?.porcentaje_avance || 0" class="flex items-center justify-end"/>
                    </div>

                    <div class="col-span-2 md:col-span-1 flex justify-end">
                        <button
                            v-if="user.rol.clave === 'PROFESOR' && grupo.usuario_id === user.id"
                            @click="confirmRemoval(m)"
                            class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-700 hover:bg-gray-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 shadow-sm"
                            title="Eliminar alumno"
                            type="button"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-orange-500">
                                <path fill-rule="evenodd" d="M4.25 12a.75.75 0 0 1 .75-.75h14a.75.75 0 0 1 0 1.5H5a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                </div>
            </section>

            <section class="mt-4" v-else>
                <div
                    v-for="r of retos"
                    :key="r.id"
                    class="bg-white my-3 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row justify-between items-center p-4 transition-all hover:shadow-md"
                >
                    <div
                        @click.prevent="abrirReto(r)"
                        class="flex-1 cursor-pointer w-full sm:w-auto mb-3 sm:mb-0"
                    >
                        <h2 class="font-bold text-lg text-gray-800">{{ r.titulo }}</h2>
                        <div class="flex items-center gap-2 text-sm text-gray-500 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <time>Vence: {{ r.fecha_limite }}</time>
                        </div>
                    </div>

                    <div v-if="user.rol.clave === 'PROFESOR'" class="flex items-center gap-3">

                        <Link
                            :href="route('retos.reporte', r.id)"
                            class="flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors font-semibold text-sm"
                            title="Ver Estadísticas y Reporte"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605" />
                            </svg>
                            Reporte
                        </Link>

                    </div>
                </div>

                <Link class="fabBtn" v-if="user.rol.clave === 'PROFESOR'"
                      :href="`/retos/${grupo.id}/crear`"
                      aria-label="Crear reto">
                    <span class="plusV"></span>
                    <span class="plusH"></span>
                </Link>
            </section>
        </Transition>

        <ConfirmationModal :show="confirmingRemoval" @close="closeModal">
            <template #title>
                Eliminar Alumno
            </template>

            <template #content>
                ¿Estás seguro de que deseas eliminar a
                <b>{{ studentToRemove?.nombre }} {{ studentToRemove?.apellido_paterno }}</b>
                del grupo? Esta acción es irreversible y eliminará su progreso en este grupo.
            </template>

            <template #footer>
                <SecondaryButton @click="closeModal">
                    Cancelar
                </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    @click="executeRemoval"
                    :class="{ 'opacity-25': !studentToRemove }"
                    :disabled="!studentToRemove"
                >
                    Eliminar
                </DangerButton>
            </template>
        </ConfirmationModal>

    </SidebarOnlyLayout>
</template>

<style scoped>
.tabBase{
    position:relative;
    display:inline-block;
    font-weight:600;
    color:#000000;
    padding-bottom:10px;
    cursor:pointer;
    text-decoration:none;
}
.tabBase::after{
    content:"";
    position:absolute;
    left:0;
    bottom:0;
    width:100%;
    height:6px;
    background:#E17101;
    border-radius:4px;
    transform:scaleX(0);
    transform-origin:left;
    transition:transform 120ms ease;
}
</style>
