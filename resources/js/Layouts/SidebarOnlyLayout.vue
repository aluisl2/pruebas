<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { getGrupoUri } from "@/composable/getGrupoUri.ts";
import { useUser } from "@/composable/useUser.ts";

const user = useUser();
const page = usePage();
const isMobileMenuOpen = ref(false);

const props = defineProps({
    title: { type: String, default: "Dashboard" },

    // ✅ esto es lo que tú pasas desde GruposDashboard.vue
    groups: { type: Array, default: null },

    // grupo seleccionado (para pintar naranja)
    activeGroupId: { type: [Number, String, null], default: null },

    // a dónde manda Home
    hrefHome: { type: String, default: "/dashboard" },
});

// ✅ ahora sí respeta el prop; si no viene, cae a page.props.grupos
const groups = computed(() => props.groups ?? page.props.grupos ?? []);
</script>


<template>
    <app-layout :title="props.title">
        <div class="min-h-screen flex flex-col md:flex-row" :style="{ background: '#E5EDF9' }">
            <!-- MOBILE HEADER -->
            <div class="md:hidden flex items-center justify-between p-4 border-b border-[#BBC2CF] bg-[#E5EDF9]">
                <span class="font-bold text-lg text-[#2B2E36]">{{ props.title }}</span>
                <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="p-2 rounded-md bg-[#2B2E36] text-white">
                    <svg v-if="!isMobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
                    <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <!-- MOBILE BACKDROP OVERLAY -->
            <div v-if="isMobileMenuOpen" class="fixed inset-0 bg-black/50 z-40 md:hidden backdrop-blur-sm" @click="isMobileMenuOpen = false"></div>
            <!-- SIDEBAR -->
            <aside
                class="fixed inset-y-0 left-0 z-50 w-[280px] sm:w-[351px] transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:z-auto md:border-r" :class="[isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full']"
                :style="{ background: '#E5EDF9', borderColor: '#BBC2CF' }"
            >
                <!-- Mobile Close Button -->
                <div class="flex justify-end p-4 md:hidden">
                    <button @click="isMobileMenuOpen = false" class="p-2 text-[#2B2E36]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <nav class="pt-6 flex flex-col items-center" :style="{ gap: '17px' }">
                    <!-- HOME -->
                    <Link
                        :href="props.hrefHome"
                        class="sidebarBtn sidebarBtn--dark"
                        :style="{ width: '280px', height: '61px' }"
                    >
                        <svg class="homeIcon" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M3 10.5 12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-6h-6v6H4a1 1 0 0 1-1-1z"
                            />
                        </svg>
                        <span class="sidebarText">Home</span>
                    </Link>

                    <!-- GRUPOS -->
                    <Link
                        v-for="g in groups"
                        :key="g.id"
                        :href="`/grupo/${g.id}`"
                        class="sidebarBtn"
                        :class="{ 'sidebarBtn--selected': String(props.activeGroupId) === String(g.id) }"
                        @click="isMobileMenuOpen = false"
                        :style="{ width: '280px', height: '61px' }"
                    >
                        <span class="sidebarText">{{ g.nombre ?? "Grupo" }}</span>
                    </Link>

                    <!-- fallback si no hay grupos -->
                    <div
                        v-if="!groups?.length"
                        class="sidebarBtn"
                        :style="{ width: '280px', height: '61px', opacity: 0.75 }"
                    >
                        <span class="sidebarText">Sin grupos</span>
                    </div>
                </nav>
            </aside>

            <!-- contenido principal -->
            <main class="flex-1 p-4 md:p-8">
                <slot />
            </main>
        </div>
    </app-layout>
</template>

<style scoped>
.sidebarBtn{
    display:flex;
    align-items:center;
    justify-content:flex-start;
    gap:12px;
    padding-left:18px;

    border-radius:12px;
    cursor:pointer;
    user-select:none;
    font-weight:600;

    color:#000000;
    background:#E5EDF9;
    border:1px solid #BBC2CF;
    text-decoration:none;

    transition: background 120ms ease, color 120ms ease, transform 120ms ease;
}

.sidebarBtn--dark{
    background:#2B2E36;
    color:#FFFFFF;
    border:none;
}

.sidebarBtn--selected{
    background:#D8934E;
    color:#000000;
    border:none;
}

.homeIcon{
    width:35px;
    height:35px;
    fill:#E17101;
    flex-shrink:0;
}

.sidebarText{
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

/* Hover paleta sidebar */
aside nav > a.sidebarBtn:hover,
aside nav > div.sidebarBtn:hover{
    background:#D9D9D9 !important;
    color:#000000 !important;
}
</style>
