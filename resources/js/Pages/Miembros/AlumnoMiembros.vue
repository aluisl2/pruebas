<script setup>
import { computed } from "vue";
import SidebarOnlyLayout from "@/Layouts/SidebarOnlyLayout.vue";
import GruposRightLayout from "@/Layouts/GruposRightLayout.vue";

const props = defineProps({
    // sidebar grupos (puede venir como groups o gruposSidebar)
    groups: { type: Array, default: () => [] },
    gruposSidebar: { type: Array, default: () => [] },
    activeGroupId: { type: [Number, String, null], default: null },

    // grupo actual (puede venir como group o grupoActual)
    group: { type: Object, default: null },
    grupoActual: { type: Object, default: null },

    // miembros (puede venir como miembros o members)
    miembros: { type: Array, default: () => [] },
    members: { type: Array, default: () => [] },

    // hrefs (si ya los manda el controller)
    hrefRetos: { type: String, default: "" },
    hrefMiembros: { type: String, default: "" },


    studentName: { type: String, default: "" },
    totalPoints: { type: [Number, String], default: 0 },
    avgPercent: { type: [Number, String], default: 0 },
});

/* Normalizaciones */
const sidebarGroups = computed(() =>
    props.groups?.length ? props.groups : props.gruposSidebar
);

const groupInfo = computed(() => props.group ?? props.grupoActual ?? {});

const membersList = computed(() =>
    props.members?.length ? props.members : props.miembros
);

const groupId = computed(() => props.activeGroupId ?? groupInfo.value?.id ?? null);

/* hrefs: si no vienen, los armamos con el groupId */
const hrefRetosFinal = computed(() => {
    if (props.hrefRetos) return props.hrefRetos;
    if (!groupId.value) return "#";
    return `/alumnos/grupos/${groupId.value}/retos`;
});

const hrefMiembrosFinal = computed(() => {
    if (props.hrefMiembros) return props.hrefMiembros;
    if (!groupId.value) return "#";
    return `/alumnos/grupos/${groupId.value}/miembros`;
});

/* Props para layout derecho */
const groupCode = computed(() => groupInfo.value?.clave ?? "");
const groupName = computed(() => groupInfo.value?.nombre ?? "NOMBRE DEL GRUPO");
const groupDate = computed(() => groupInfo.value?.fecha ?? groupInfo.value?.created_at ?? "");

// ✅ ahora sí vienen del controller
const studentName = computed(() => props.studentName || "Nombre del alumno");
const totalPoints = computed(() => props.totalPoints ?? 0);
const avgPercent = computed(() => props.avgPercent ?? 0);
</script>

<template>
    <SidebarOnlyLayout
        :groups="sidebarGroups"
        :activeGroupId="groupId"
        hrefHome="/dashboard"
    >
        <GruposRightLayout
            activeTab="miembros"
            :hrefRetos="hrefRetosFinal"
            :hrefMiembros="hrefMiembrosFinal"
            :groupCode="groupCode"
            :groupName="groupName"
            :groupDate="groupDate"
            :studentName="studentName"
            :totalPoints="totalPoints"
            :avgPercent="avgPercent"
        >
            <section class="mt-4 rounded-xl" :style="{ width: '850px', background: '#E5EDF9' }">
                <div class="space-y-5">
                    <div
                        v-for="(m, idx) in membersList"
                        :key="m.id ?? idx"
                        class="memberCard"
                        :style="{ width: '850px', height: '69px' }"
                    >
                        <div class="grid grid-cols-12 items-center h-full px-8">
                            <div class="col-span-7 text-center font-semibold" :style="{ color: '#000000' }">
                                {{ m.name ?? m.nombre }}
                            </div>

                            <div class="col-span-2 text-center font-semibold" :style="{ color: '#000000' }">
                                {{ m.percent ?? (m.points !== undefined ? (m.points + ' pts') : '0%') }}
                            </div>

                            <div class="col-span-3 flex justify-end">
                                <svg viewBox="0 0 24 24" class="w-10 h-10" :style="{ fill: '#D9D9D9' }">
                                    <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </GruposRightLayout>
    </SidebarOnlyLayout>
</template>

<style scoped>
/* SOLO hover verde (no se queda) */
.memberCard{
    background:#FFFFFF;
    border: 3px solid #BBC2CF;
    border-radius: 12px;
    cursor: pointer;
    user-select: none;
    transition: border-color 120ms ease;
    box-sizing: border-box;
}
.memberCard:hover{
    border-color: #3FD99E;
}
</style>
