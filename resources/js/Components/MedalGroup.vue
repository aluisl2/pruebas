<script setup lang="ts">
import medalBR1 from '../../img/bronze01.webp'
import medalBR2 from '../../img/bronze02.webp'
import medalBR3 from '../../img/bronze03.webp'
import medalSL1 from '../../img/silver01.webp'
import medalSL2 from '../../img/silver02.webp'
import medalSL3 from '../../img/silver03.webp'
import medalGL1 from '../../img/gold01.webp'
import medalGL2 from '../../img/gold02.webp'
import medalGL3 from '../../img/gold03.webp'
import medalInf from '../../img/infinite.webp'

interface Props {
    porcentaje: number;
}

defineProps<Props>()
</script>

<template>
    <div v-if="porcentaje < 15">
        <div class="flex gap-2 items-center">
            <img :src="medalBR1" alt="Medalla Bronce 1" class="flex w-15 h-15 lg:w-20 lg:h-20 2xl:w-25 2xl:h-25 items-center justify-center min-w-fit">
        </div>
    </div>
    <div v-else-if="porcentaje >= 15 && porcentaje < 35">
        <div class="flex gap-2 items-center">
            <img :src="medalBR2" alt="Medalla Bronce 2" class="flex w-15 h-15 lg:w-20 lg:h-20 2xl:w-25 2xl:h-25 items-center justify-center min-w-fit">
        </div>
    </div>
    <div v-else-if="porcentaje >= 35 && porcentaje < 60">
        <div class="flex gap-2 items-center">
            <img :src="medalBR3" alt="Medalla Bronce 3" class="flex w-15 h-15 lg:w-20 lg:h-20 2xl:w-25 2xl:h-25 items-center justify-center min-w-fit">
        </div>
    </div>
    <div v-else-if="porcentaje >= 60 && porcentaje < 70">
        <div class="flex gap-2 items-center">
            <img :src="medalSL1" alt="Medalla Plata 1" class="flex w-15 h-15 lg:w-20 lg:h-20 2xl:w-25 2xl:h-25 items-center justify-center min-w-fit">
        </div>
    </div>
    <div v-else-if="porcentaje >= 70 && porcentaje < 75">
        <div class="flex gap-2 items-center">
            <img :src="medalSL2" alt="Medalla Plata 2" class="flex w-15 h-15 lg:w-20 lg:h-20 2xl:w-25 2xl:h-25 items-center justify-center min-w-fit">
        </div>
    </div>
    <div v-else-if="porcentaje >= 75 && porcentaje < 80">
        <div class="flex gap-2 items-center">
            <img :src="medalSL3" alt="Medalla Plata 3" class="flex w-15 h-15 lg:w-20 lg:h-20 2xl:w-25 2xl:h-25 items-center justify-center min-w-fit">
        </div>
    </div>
    <div v-else-if="porcentaje >= 80 && porcentaje < 85">
        <div class="flex gap-2 items-center">
            <img :src="medalGL1" alt="Medalla Oro 1" class="flex w-15 h-15 lg:w-20 lg:h-20 2xl:w-25 2xl:h-25 items-center justify-center min-w-fit">
        </div>
    </div>
    <div v-else-if="porcentaje >= 85 && porcentaje < 90">
        <div class="flex gap-2 items-center">
            <img :src="medalGL2" alt="Medalla Oro 2" class="flex w-15 h-15 lg:w-20 lg:h-20 2xl:w-25 2xl:h-25 items-center justify-center min-w-fit">
        </div>
    </div>
    <div v-else-if="porcentaje >= 90 && porcentaje < 98">
        <div class="flex gap-2 items-center">
            <img :src="medalGL3" alt="Medalla Oro 3" class="flex w-15 h-15 lg:w-20 lg:h-20 2xl:w-25 2xl:h-25 items-center justify-center min-w-fit">
        </div>
    </div>
    <div v-else-if="porcentaje >= 98 && porcentaje <= 100">
        <div class="flex gap-2 items-center">
            <img :src="medalInf" alt="Medalla Infinita" class="flex w-15 h-15 lg:w-20 lg:h-20 2xl:w-25 2xl:h-25 items-center justify-center min-w-fit">
        </div>
    </div>
    <div v-else>
    </div>
</template>
