<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import ClientsCard from '@/Components/DashboardContent/ClientsCard.vue';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const clients = usePage().props.clients;

</script>

<template>
    <AppLayout title="Dashboard">
        <div class="mt-4 grid grid-cols-12 gap-4 md:mt-6 md:gap-6 2xl:mt-7.5 2xl:gap-7.5">
            <ClientsCard :clients="clients" />
        </div>
    </AppLayout>
</template>
