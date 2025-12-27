<script setup>
import { computed, ref } from "vue";
import { usePage, router } from '@inertiajs/vue3'; // Importar hooks de Inertia
import SidebarOnlyLayout from "@/Layouts/SidebarOnlyLayout.vue";
import GruposRightLayout from "@/Layouts/GruposRightLayout.vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue"; // Importar Modal
import DangerButton from "@/Components/DangerButton.vue";       // Importar Botones
import SecondaryButton from "@/Components/SecondaryButton.vue";

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

/* --- Lógica Existente --- */
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
const studentName = computed(() => props.studentName || "Nombre del alumno");
const totalPoints = computed(() => props.totalPoints ?? 0);
const avgPercent = computed(() => props.avgPercent ?? 0);

/* --- NUEVA LÓGICA PARA ELIMINAR --- */
const page = usePage();

// Verificar si soy el profesor (dueño del grupo)
const isOwner = computed(() => {
    return groupInfo.value?.usuario_id === page.props.auth.user.id;
});

// Estado para el modal
const confirmingMemberRemoval = ref(false);
const memberToRemove = ref(null);

// Abrir modal
const confirmMemberRemoval = (member) => {
    memberToRemove.value = member;
    confirmingMemberRemoval.value = true;
};

// Cerrar modal
const closeModal = () => {
    confirmingMemberRemoval.value = false;
    memberToRemove.value = null;
};

// Ejecutar eliminación
const deleteMember = () => {
    if (!memberToRemove.value) return;

    router.delete(route('grupos.miembros.destroy', {
        grupo: groupId.value,
        user: memberToRemove.value.id
    }), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => closeModal(), // Opcional: manejar errores
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
                                {{ m.name ?? m.nombre }} {{ m.apellido_paterno }}
                            </div>

                            <div class="col-span-2 text-center font-semibold" :style="{ color: '#000000' }">
                                {{ m.percent ?? (m.points !== undefined ? (m.points + ' pts') : '0%') }}
                            </div>

                            <div class="col-span-3 flex justify-end items-center">
                                <button
                                    v-if="isOwner"
                                    @click.stop="confirmMemberRemoval(m)"
                                    class="text-red-500 hover:text-red-700 transition p-2 rounded-full hover:bg-red-100"
                                    title="Eliminar alumno"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>

                                <svg v-else viewBox="0 0 24 24" class="w-10 h-10" :style="{ fill: '#D9D9D9' }">
                                    <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </GruposRightLayout>

        <ConfirmationModal :show="confirmingMemberRemoval" @close="closeModal">
            <template #title>
                Eliminar Alumno
            </template>

            <template #content>
                ¿Estás seguro de que deseas eliminar a <b>{{ memberToRemove?.nombre }}</b> del grupo? Esta acción eliminará su progreso en este grupo.
            </template>

            <template #footer>
                <SecondaryButton @click="closeModal">
                    Cancelar
                </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    @click="deleteMember"
                    :class="{ 'opacity-25': false }"
                >
                    Eliminar
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
    cursor: pointer;
    user-select: none;
    transition: border-color 120ms ease;
    box-sizing: border-box;
}
.memberCard:hover{
    border-color: #3FD99E;
}
</style>
