<script setup>
import { computed, ref } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    activeTab: { type: String, required: true }, // 'retos' | 'miembros'
    groupCode: { type: String, default: "18473938" },
    groupName: { type: String, default: "NOMBRE DEL GRUPO" },
    groupDate: { type: String, default: "12/11/2025" },

    //  RUTAS (ya no hardcodeadas)
    hrefRetos: { type: String, required: true },
    hrefMiembros: { type: String, required: true },
});

const hoverTab = ref(null);

const isRetosActive = computed(() => {
    if (hoverTab.value === "miembros") return false;
    if (hoverTab.value === "retos") return true;
    return props.activeTab === "retos";
});

const isMiembrosActive = computed(() => {
    if (hoverTab.value === "retos") return false;
    if (hoverTab.value === "miembros") return true;
    return props.activeTab === "miembros";
});
</script>

<template>
    <div class="mx-auto" :style="{ width: '850px' }">
        <!-- Tabs + Código -->
        <section class="rounded-xl" :style="{ width: '850px', background: '#FFFFFF', border: '1px solid #BBC2CF' }">
            <div class="px-8 py-4 flex items-center justify-between">
                <div class="flex items-end gap-10">
                    <Link
                        :href="props.hrefRetos"
                        class="tabBase"
                        :class="{ tabActive: isRetosActive }"
                        @mouseenter="hoverTab = 'retos'"
                        @mouseleave="hoverTab = null"
                    >
                        Retos
                    </Link>

                    <Link
                        :href="props.hrefMiembros"
                        class="tabBase"
                        :class="{ tabActive: isMiembrosActive }"
                        @mouseenter="hoverTab = 'miembros'"
                        @mouseleave="hoverTab = null"
                    >
                        Miembros
                    </Link>
                </div>

                <div class="codePill">
                    Codigo del grupo: <span class="font-extrabold">{{ props.groupCode }}</span>
                </div>
            </div>
        </section>

        <!-- Header Grupo -->
        <section class="mt-4" :style="{ width: '850px' }">
            <div class="rounded-xl overflow-hidden flex items-center" :style="{ background: '#2B2E36', width: '850px', height: '110px' }">
                <div class="px-8">
                    <div class="text-4xl font-bold tracking-wide" :style="{ color: '#FFFFFF' }">
                        {{ props.groupName }}
                    </div>
                    <div class="mt-2 text-lg" :style="{ color: '#FFFFFF' }">
                        {{ props.groupDate }}
                    </div>
                </div>
            </div>
        </section>

        <slot />
    </div>
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
.tabActive{ color:#E17101; }
.tabActive::after{ transform:scaleX(1); }

.codePill{
    background:#DADDE0;
    color:#000000;
    border-radius:12px;
    padding:6px 18px;
    font-weight:600;
}
</style>
