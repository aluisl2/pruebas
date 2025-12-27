<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-5 py-3 lg:px-4 lg:py-2 bg-[#E17101] border border-transparent rounded-md font-semibold text-xs sm:text-md lg:text-xs text-white uppercase tracking-widest hover:bg-[#e78728] focus:bg-[#e78728] active:bg-[#c46100] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150 hover:shadow-lg hover:-translate-y-0.5 transition-all">
        <slot />
    </button>
</template>
