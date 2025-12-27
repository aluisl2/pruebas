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

    // retos (puede venir como retos o challenges)
    retos: { type: Array, default: () => [] },
    challenges: { type: Array, default: () => [] },

    // hrefs (si ya los manda el controller)
    hrefRetos: { type: String, default: "" },
    hrefMiembros: { type: String, default: "" },

    // ✅ IMPORTANTE: estos props te faltaban (por eso no se actualizaba)
    studentName: { type: String, default: "" },
    totalPoints: { type: [Number, String], default: 0 },
    avgPercent: { type: [Number, String], default: 0 },

    // opcional si decides mandar un objeto alumno desde el backend
    alumno: { type: Object, default: null },
});

/* Normalizaciones */
const sidebarGroups = computed(() =>
    props.groups?.length ? props.groups : props.gruposSidebar
);

const groupInfo = computed(() => props.group ?? props.grupoActual ?? {});

const retosList = computed(() =>
    props.challenges?.length ? props.challenges : props.retos
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
const groupDate = computed(
    () => groupInfo.value?.fecha ?? groupInfo.value?.created_at ?? ""
);

// ✅ Nombre alumno: prioriza studentName del controller, si no, usa alumno.nombre completo
const studentName = computed(() => {
    if (props.studentName && props.studentName.trim() !== "") return props.studentName;

    // si backend manda alumno con nombre/apellidos
    const a = props.alumno;
    if (a) {
        const full = [a.nombre, a.apellido_paterno, a.apellido_materno].filter(Boolean).join(" ");
        if (full.trim() !== "") return full;
    }

    // fallback (no debería pasar si controller manda studentName)
    return "Nombre del alumno";
});

const totalPoints = computed(() => Number(props.totalPoints ?? 0));
const avgPercent = computed(() => Number(props.avgPercent ?? 0));
</script>

<template>
    <SidebarOnlyLayout

        :groups="sidebarGroups"
        :activeGroupId="groupId"
        hrefHome="/dashboard"


    >
        <GruposRightLayout
            activeTab="retos"
            :hrefRetos="hrefRetosFinal"
            :hrefMiembros="hrefMiembrosFinal"
            :groupCode="groupCode"
            :groupName="groupName"
            :groupDate="groupDate"
            :studentName="studentName"
            :totalPoints="totalPoints"
            :avgPercent="avgPercent"
        >
            <!-- Bloque Retos -->
            <section class="mt-4 rounded-xl" :style="{ width: '850px', background: '#E5EDF9' }">
                <div class="py-6 space-y-5">
                    <div
                        v-for="(c, idx) in retosList"
                        :key="c.id ?? idx"
                        class="challengeCard"
                        :style="{ width: '850px', height: '69px' }"
                    >
                        <div class="grid grid-cols-12 items-center h-full px-8">
                            <div class="col-span-4 font-semibold" :style="{ color: '#000000' }">
                                {{ c.title ?? c.titulo }}
                            </div>

                            <div class="col-span-3 text-center" :style="{ color: '#000000' }">
                                {{ c.progress ?? c.progreso }}
                            </div>

                            <div class="col-span-2 text-center font-semibold" :style="{ color: '#000000' }">
                                {{ c.points ?? c.puntos }}
                            </div>

                            <div class="col-span-2 text-center" :style="{ color: '#000000' }">
                                {{ c.status ?? c.estado }}
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
.challengeCard{
    background:#FFFFFF;
    border: 3px solid #BBC2CF;
    border-radius: 12px;
    cursor: pointer;
    user-select: none;
    transition: border-color 120ms ease;
    box-sizing: border-box;
}
.challengeCard:hover{
    border-color: #3FD99E;
}
</style>
