<script setup lang="ts">
import { computed, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import SidebarOnlyLayout from "@/Layouts/SidebarOnlyLayout.vue";
import JoinGroupModal from "@/Components/JoinGroupModal.vue";
import {useUser} from "@/composable/useUser";
import {router} from '@inertiajs/vue3'
import {Grupo} from "@/interfaces";

const user = useUser()

interface Props {
    grupos: Grupo[]
}

const props = defineProps<Props>()

const showForm = ref(false);

const hasGroups = computed(() => (props.grupos?.length ?? 0) > 0);

function openForm() {
    // en caso de que sea profesor, lo mandamos a la vista de crear
    if (user.rol.clave === 'PROFESOR')
        return router.visit('/grupos/crear')

    showForm.value = true;
}


function fmtDate(d) {
    if (!d) return "";
    return d;
}
</script>

<template>
    <SidebarOnlyLayout
        title="Grupos"
        :groups="grupos"
        :activeGroupId="null"
        hrefHome="/dashboard">

        <div class="mx-auto px-4 md:px-0">
            <section class="w-full">
                <div class="groupsGrid">
                    <Link
                        v-for="g in grupos"
                        :key="g.id"
                        class="groupCard"
                        :href="`/grupo/${g.id}`"
                    >
                        <div class="cardInner">
                            <div class="cardText">
                                <div class="cardTitle">{{ g.nombre ?? "Grupo" }}</div>
                                <div class="cardDate">{{ g.created_at }}</div>
                            </div>

                            <button
                                type="button"
                                class="kebabBtn"
                                aria-label="Opciones del grupo"
                                @click.prevent
                            >
                                <span class="kebabDot"></span>
                                <span class="kebabDot"></span>
                                <span class="kebabDot"></span>
                            </button>
                        </div>
                    </Link>

                    <div v-if="!hasGroups" class="emptyBox">
                        <h2 class="emptyTitle">Aún no tienes grupos</h2>

                        <p v-if="user.rol.clave === 'ALUMNO'" class="emptyText">
                            Únete con el botón <span :style="{ color: '#E17101', fontWeight: 800 }">+</span>
                            usando el código que te compartió tu profesor.
                        </p>

                        <p v-else-if="user.rol.clave === 'PROFESOR'">
                            Para crear un grupo da click en botón de +
                        </p>

                        <p v-else>
                            a
                        </p>
                    </div>
                </div>
            </section>

            <!-- FAB "+" más pequeño -->
            <!-- dependiendo -->
            <button class="fabBtn" type="button"
                    aria-label="Unirse a un grupo"
                    @click="openForm">
                <span class="plusV"></span>
                <span class="plusH"></span>
            </button>

            <JoinGroupModal v-if="user.rol.clave === 'ALUMNO'"
                            :show="showForm"
                            @close="showForm = false" />
        </div>
    </SidebarOnlyLayout>
</template>

<style scoped>
/* Grid */
.groupsGrid{
    display:grid;
    grid-template-columns: repeat(2, 1fr);
    gap:22px;
}

/* Card grupo */
.groupCard{
    height:130px;
    background:#2B2E36;
    border-radius:14px;
    text-decoration:none;
    display:block;
    overflow:hidden;
    transition: transform 120ms ease, box-shadow 120ms ease;
}
.groupCard:hover{
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
}
.cardInner{
    height:100%;
    padding:18px;
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.cardTitle{ font-weight:700; font-size:18px; color:#FFFFFF; }
.cardDate{ margin-top:6px; font-size:14px; color:#DADDE0; }

/* 3 puntos */
.kebabBtn{
    width:40px; height:60px; border:none; background:transparent;
    display:flex; flex-direction:column; justify-content:center; align-items:center;
    gap:6px; cursor:pointer;
}
.kebabDot{ width:5px; height:5px; border-radius:999px; background:#E17101; }

/* Empty */
.emptyBox{
    grid-column: 1 / -1;
    background:#FFFFFF;
    border:1px solid #BBC2CF;
    border-radius:14px;
    padding:24px;
}
.emptyTitle{ font-size:22px; font-weight:800; color:#2B2E36; }
.emptyText{ margin-top:8px; color:#4B556B; }


/* Responsive */
@media (max-width: 1100px){
    .groupsGrid{ grid-template-columns: 1fr; }
}
</style>
