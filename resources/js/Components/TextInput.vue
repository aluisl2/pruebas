<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="border outline-none transition border-[#3B3F48] focus:border-[#E17101] focus:ring-2 px-4 py-3 lg:py-2 focus:ring-orange-200 rounded-md shadow-sm "
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
