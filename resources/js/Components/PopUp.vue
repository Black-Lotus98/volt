<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-auto bg-smoke-light flex">
        <div class="relative p-8 bg-white w-full max-w-md m-auto flex-col flex rounded-lg">
            <button @click="closeModal" class="absolute top-0 right-0 p-2">
                <svg class="h-6 w-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    }
});

const emits = defineEmits(['close']);

const closeModal = () => {
    emits('close');
};
</script>

<style scoped>
.bg-smoke-light {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
