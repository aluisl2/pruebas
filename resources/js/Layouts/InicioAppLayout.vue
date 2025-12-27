<script setup lang="ts">

import {Link} from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
</script>

<template>
    <nav class="border border-transparent border-b-[#BBC2CF] bg-[#E5EDF9] fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <Link :href="'/'">
                    <ApplicationMark class="block h-9 w-auto" />
                </Link>

                <!-- Desktop Navigation -->
                <div class="flex items-center gap-4 sm:gap-8">
                    <slot name="inic"/>
                </div>
            </div>
        </div>
    </nav>
    <div>
        <slot/>
    </div>
</template>

<style scoped>

</style>
