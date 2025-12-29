<script setup>
import { computed, ref } from "vue";
import { usePage, router } from '@inertiajs/vue3'; // Importar hooks necesarios
// --- IMPORTS DE COMPONENTES ---
import SidebarOnlyLayout from "@/Layouts/SidebarOnlyLayout.vue";
import GruposRightLayout from "@/Layouts/GruposRightLayout.vue";
// Importar Modal y Botones de Jetstream/Tus componentes
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

// --- TUS PROPS EXISTENTES (No las modifiques) ---
const props = defineProps({
    groups: { type: Array, default: () => [] },
    gruposSidebar: { type: Array, default: () => [] },
    activeGroupId: { type: [Number, String, null], default: null },
    group: { type: Object, default: null },
    grupoActual: { type: Object, default: null },
    miembros: { type: Array, default: () => [] },
    members: { type: Array, default: () => [] },
    hrefRetos: { type: String, default: "" },
    hrefMiembros: { type: String, default: "" },
    studentName: { type: String, default: "" },
    totalPoints: { type: [Number, String], default: 0 },
    avgPercent: { type: [Number, String], default: 0 },
});

// --- TU LÓGICA COMPUTADA EXISTENTE ---
const sidebarGroups = computed(() => props.groups?.length ? props.groups : props.gruposSidebar);
const groupInfo = computed(() => props.group ?? props.grupoActual ?? {});
const membersList = computed(() => props.members?.length ? props.members : props.miembros);
const groupId = computed(() => props.activeGroupId ?? groupInfo.value?.id ?? null);

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

const groupCode = computed(() => groupInfo.value?.clave ?? "");
const groupName = computed(() => groupInfo.value?.nombre ?? "NOMBRE DEL GRUPO");
const groupDate = computed(() => groupInfo.value?.fecha ?? groupInfo.value?.created_at ?? "");
const studentNameComputed = computed(() => props.studentName || "Nombre del alumno");
const totalPointsComputed = computed(() => props.totalPoints ?? 0);
const avgPercentComputed = computed(() => props.avgPercent ?? 0);


// --- NUEVA LÓGICA PARA ELIMINAR ALUMNOS ---

const page = usePage();

// 1. Determinar si soy el dueño del grupo (Profesor)
const isGroupOwner = computed(() => {
    // Compara el ID del dueño del grupo con el ID del usuario logueado
    return groupInfo.value?.usuario_id === page.props.auth.user.id;
});

// 2. Estado del Modal
const confirmingRemoval = ref(false);
const studentToRemove = ref(null);

// 3. Funciones del Modal
const confirmRemoval = (student) => {
    studentToRemove.value = student;
    confirmingRemoval.value = true;
};

const closeModal = () => {
    confirmingRemoval.value = false;
    setTimeout(() => { studentToRemove.value = null; }, 300); // Limpiar después de la animación
};

// 4. Ejecutar la eliminación
const executeRemoval = () => {
    if (!studentToRemove.value || !groupId.value) return;

    router.delete(route('grupos.miembros.destroy', {
        grupo: groupId.value,
        user: studentToRemove.value.id
    }), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => closeModal(),
    });
};
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
            :studentName="studentNameComputed"
            :totalPoints="totalPointsComputed"
            :avgPercent="avgPercentComputed"
        >
            <section class="mt-4 rounded-xl" :style="{ width: '850px', background: '#E5EDF9' }">
                <div class="space-y-5 p-4"> <div
                    v-for="(m, idx) in membersList"
                    :key="m.id ?? idx"
                    class="memberCard w-full h-[69px]"
                >
                    <div class="grid grid-cols-12 items-center h-full px-8">
                        <div class="col-span-6 text-left font-semibold text-black truncate pr-4">
                            {{ m.name ?? m.nombre }} {{ m.apellido_paterno }}
                        </div>

                        <div class="col-span-3 text-center font-semibold text-black">
                            {{ m.percent ?? (m.points !== undefined ? (m.points + ' pts') : '0%') }}
                        </div>

                        <div class="col-span-3 flex justify-end items-center">
                            <button
                                v-if="isGroupOwner"
                                @click="confirmRemoval(m)"
                                class="text-red-500 hover:text-red-700 hover:bg-red-100 p-2 rounded-full transition duration-150 ease-in-out focus:outline-none"
                                title="Eliminar alumno"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                </div>
            </section>
        </GruposRightLayout>

        <ConfirmationModal :show="confirmingRemoval" @close="closeModal">
            <template #title>
                Eliminar Alumno del Grupo
            </template>

            <template #content>
                <p>
                    ¿Estás seguro de que deseas eliminar a
                    <span class="font-bold text-gray-900">{{ studentToRemove?.name ?? studentToRemove?.nombre }} {{ studentToRemove?.apellido_paterno }}</span>
                    de este grupo?
                </p>
                <p class="mt-2 text-sm text-red-600">
                    Esta acción eliminará su progreso en el grupo y es irreversible.
                </p>
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
                    Eliminar Alumno
                </DangerButton>
            </template>
        </ConfirmationModal>

    </SidebarOnlyLayout>
</template>

<style scoped>
/* Tus estilos existentes */
.memberCard{
    background:#FFFFFF;
    border: 3px solid #BBC2CF;
    border-radius: 12px;
    /* cursor: pointer; Quitamos esto porque la tarjeta no es clickeable, solo el botón */
    user-select: none;
    transition: border-color 120ms ease, box-shadow 120ms ease;
    box-sizing: border-box;
}
.memberCard:hover{
    border-color: #3FD99E;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}
</style>
