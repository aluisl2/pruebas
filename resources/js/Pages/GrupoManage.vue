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

const { axios: client } = useAxios()

const user = useUser()

interface Props {
    grupo: Grupo
}

const props = defineProps<Props>()

enum Tabs {
    Miembros,
    Retos
}

const miembros = shallowRef([])
const miembro = ref()
const retos = shallowRef([])

const tab = ref<Tabs>(Tabs.Retos)

const getMembers = async () => {
    const {data} = await client.post(`/grupo/${props.grupo.id}/miembros`)
    miembros.value = data
}

const puntajeMax = async () => {
    const {data} = await client.post(`/grupo/${props.grupo.id}/miembro/${user.id}`)
    miembro.value = data
    console.log(miembro.value)
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

// por defecto debemos cargar los retos

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
    <SidebarOnlyLayout>
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
                <div class="px-6 py-4 bg-white rounded-2xl shadow-sm border border-gray-100 grid items-center gap-4"
                     :class="user.rol.clave === 'PROFESOR' ? 'md:grid-cols-3' : 'grid-cols-2'"
                     v-for="m of miembros" :key="m.id">

                    <!-- Información del Alumno -->
                    <div class="flex items-center gap-4">
                        <div class="size-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-xl border-2 border-orange-200 shrink-0">
                            {{ m.nombre.charAt(0) }}{{ m.apellido_paterno.charAt(0) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-black text-gray-800 text-lg leading-tight truncate">
                                {{ m.nombre }} {{ m.apellido_paterno }} {{ m.apellido_materno }}
                            </p>
                            <p v-if="user.rol.clave === 'PROFESOR'" class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">
                                Matrícula: {{ m.matricula }}
                            </p>
                        </div>
                    </div>

                    <!-- Avance (Solo Profesor) -->
                    <div v-if="user.rol.clave === 'PROFESOR'" class="w-full">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-black text-gray-400 uppercase">Avance</span>
                            <span class="text-sm font-black text-orange-600">{{ m.porcentaje_avance }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-orange-500 h-full rounded-full transition-all duration-500" :style="{ width: m.porcentaje_avance + '%' }"></div>
                        </div>
                    </div>

                    <!-- Puntaje (Solo Profesor) -->
                    <div class="grid grid-cols-2">
                    <div v-if="user.rol.clave === 'PROFESOR'" class="flex flex-col md:items-end">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-tighter">Puntos Obtenidos</span>
                        <span class="text-xl font-black text-gray-800">
                            {{ m.puntos_obtenidos }}
                            <span class="text-gray-300 text-sm font-normal">/ {{ m.total_puntos_grupo }}</span>
                        </span>

                    </div>
                        <MedalGroup :porcentaje="m?.porcentaje_avance || 0" class="flex items-center justify-end"/>
                    </div>
                </div>
            </section>
            <section class="mt-4" v-else>
                <div @click.prevent="abrirReto(r)"
                    class="px-4 py-2  bg-white my-2 rounded-lg flex justify-between
                    hover:shadow-lg cursor-pointer transition-all transform hover:-translate-y-0.5"
                     v-for="r of retos">
                    <h2 class="font-semibold px-4 py-2">{{ r.titulo }}</h2>
                    <time>{{ r.fecha_limite }}</time>
                    <div>

                    </div>
                </div>
                <!-- Enlace para crear un reto !-->
                <Link class="fabBtn" v-if="user.rol.clave === 'PROFESOR'"
                      :href="`/retos/${grupo.id}/crear`"
                      aria-label="Unirse a un grupo">
                    <span class="plusV"></span>
                    <span class="plusH"></span>
                </Link>
            </section>
        </Transition>



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
