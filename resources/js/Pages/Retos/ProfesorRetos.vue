<script setup>
import SidebarOnlyLayout from "@/Layouts/SidebarOnlyLayout.vue";
import GruposProfesorLayout from "@/Layouts/GruposProfesorLayout.vue";

const retos = [
    { title: "Reto de ejemplo", progress: "05/07/35" },
    { title: "Reto de ejemplo", progress: "05/07/35" },
];

function onAddReto() {
    console.log("Agregar reto");
}
function onRemoveReto(idx) {
    console.log("Quitar reto", idx);
}
</script>

<template>
    <SidebarOnlyLayout title="Profesor - Retos">
        <GruposProfesorLayout
            activeTab="retos"
            groupCode="18473938"
            groupName="NOMBRE DEL GRUPO"
            groupDate="12/11/2025"
            hrefRetos="/profesor/retos"
            hrefMiembros="/profesor/miembros"
        >
            <!-- Lista de retos -->
            <section
                class="mt-6"
                :style="{
          width: '850px',
          background: '#E5EDF9',
          paddingBottom: '140px' /* 👈 evita que el FAB tape contenido */
        }"
            >
                <div class="space-y-5">
                    <div
                        v-for="(r, idx) in retos"
                        :key="idx"
                        class="retoRow"
                        :style="{ width: '850px', height: '69px' }"
                    >
                        <div class="grid grid-cols-12 items-center h-full px-10">
                            <div class="col-span-6 font-semibold text-left" :style="{ color: '#000000' }">
                                {{ r.title }}
                            </div>

                            <div class="col-span-4 text-center font-semibold" :style="{ color: '#000000' }">
                                {{ r.progress }}
                            </div>

                            <div class="col-span-2 flex justify-end">
                                <button
                                    class="minusBtn"
                                    type="button"
                                    aria-label="Quitar reto"
                                    @click="onRemoveReto(idx)"
                                >
                                    <span class="minusBar"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Botón flotante "+" -->
            <button class="fabBtn" type="button" aria-label="Agregar reto" @click="onAddReto">
                <span class="plusV"></span>
                <span class="plusH"></span>
            </button>
        </GruposProfesorLayout>
    </SidebarOnlyLayout>
</template>

<style scoped>
.retoRow{
    background:#FFFFFF;
    border: 1px solid #BBC2CF;
    border-radius: 12px;
    box-sizing: border-box;
}

/* Botón circular "-" */
.minusBtn{
    width: 44px;
    height: 44px;
    border-radius: 999px;
    background: #3B3F48;
    display:flex;
    align-items:center;
    justify-content:center;
    border:none;
    cursor:pointer;
}
.minusBar{
    width: 18px;
    height: 4px;
    background: #E17101;
    border-radius: 4px;
}

/* FAB "+" */
.fabBtn{
    position: fixed;
    width: 55px;
    height: 55px;
    border-radius: 999px;
    background: #3B3F48;
    border: none;
    cursor: pointer;

    display:flex;
    align-items:center;
    justify-content:center;

    right: 20px;
    bottom: 20px;
    z-index: 50;
}

/* Cruz 60x60 */
.plusV{
    position:absolute;
    width: 4px;
    height: 30px;
    background:#E17101;
    border-radius: 8px;
}
.plusH{
    position:absolute;
    width: 30px;
    height: 4px;
    background:#E17101;
    border-radius: 8px;
}
</style>
