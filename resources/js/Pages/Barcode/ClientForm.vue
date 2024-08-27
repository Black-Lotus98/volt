<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const client = ref({
    name: '',
    reference_number: '',
});

const form = useForm({
    name: client.value.name,
    reference_number: client.value.reference_number,
});

const emits = defineEmits(['client-added']);

const submitForm = () => {
    form.post('/clients', {
        onSuccess: () => {
            emits('client-added');
        },
        onError: (errors) => {
            console.error(errors); // For debugging if needed
        }
    });
};
</script>

<template>
    <div class="max-w-lg mx-auto py-6 px-4 sm:px-6 lg:px-8 border-2 shadow-xl rounded-md">
        <h2 class="text-2xl font-bold mb-4">Create Client</h2>
        <form @submit.prevent="submitForm" class="flex flex-col gap-4">
            <div class="w-full">
                <label for="name" class="block text-right text-gray-700 text-sm font-bold mb-2">Client Name</label>
                <input type="text" v-model="form.name"
                    class="text-right shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="name" required />
                <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
            </div>
            <div class="w-full">
                <label for="reference_number" class="block text-right text-gray-700 text-sm font-bold mb-2">Reference
                    Number</label>
                <input type="text" v-model="form.reference_number"
                    class="text-right shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="reference_number" required />
                <span v-if="form.errors.reference_number" class="text-red-500 text-sm">{{ form.errors.reference_number
                    }}</span>
            </div>
            <button type="submit" class="text-white bg-[#000547] hover:bg-slate-300 hover:border hover:border-[#000547] hover:text-[#000547]
                 active:outline font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                Create Client
            </button>
        </form>
    </div>
</template>
