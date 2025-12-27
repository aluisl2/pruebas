<template>
    <div class="bg-[#E5EDF9] flex items-start justify-center min-h-screen">

        <div class="not-md:w-full flex flex-col lg:flex-row sm:justify-center items-center pt-6 bg-white mt-7 md:mt-20 lg:mt-32 md:rounded-lg shadow-2xl px-2 sm:px-15 md:px-35 lg:px-0">
        <div>
            <slot name="logo" />
        </div>

        <div class="w-full md:max-w-fit px-1 lg:px-10 py-12 bg-white overflow-hidden rounded-lg">
            <slot />
        </div>
        </div>
    </div>
</template>
<script setup lang="ts">
</script>
