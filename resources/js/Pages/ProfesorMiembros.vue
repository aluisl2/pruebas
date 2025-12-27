<script setup>
import SidebarOnlyLayout from "@/Layouts/SidebarOnlyLayout.vue";
import GruposProfesorLayout from "@/Layouts/GruposProfesorLayout.vue";

const members = [
    { name: "Miembro 1", percent: "30%" },
    { name: "Miembro 2", percent: "70%" },
];
</script>

<template>
    <SidebarOnlyLayout title="Miembros">
        <GruposProfesorLayout
            activeTab="miembros"
            groupCode="18473938"
            groupName="NOMBRE DEL GRUPO"
            groupDate="12/11/2025"
            hrefRetos="/profesor/retos"
            hrefMiembros="/profesor/miembros"
        >

        <section class="mt-6" :style="{ width: '850px', background: '#E5EDF9' }">
                <div class="space-y-5">
                    <div v-for="(m, idx) in members" :key="idx" class="memberRow" :style="{ width: '850px', height: '69px' }">
                        <div class="grid grid-cols-12 items-center h-full px-10">
                            <div class="col-span-5 font-semibold text-center" :style="{ color: '#000000' }">{{ m.name }}</div>
                            <div class="col-span-2 font-semibold text-center" :style="{ color: '#000000' }">{{ m.percent }}</div>

                            <div class="col-span-3 flex justify-center">
                                <svg viewBox="0 0 24 24" class="w-10 h-10" :style="{ fill: '#D9D9D9' }">
                                    <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                            </div>

                            <div class="col-span-2 flex justify-end">
                                <button class="minusBtn" type="button" aria-label="Quitar miembro">
                                    <span class="minusBar"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </GruposProfesorLayout>
    </SidebarOnlyLayout>
</template>

<style scoped>
.memberRow{
    background:#FFFFFF;
    border: 1px solid #BBC2CF;
    border-radius: 12px;
    box-sizing: border-box;
}

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
</style>
