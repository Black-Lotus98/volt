<script setup>
import Card from "@/Components/Card/Card.vue";
import { ref, computed } from "vue";
import ChartThree from "../Charts/ChartThree.vue";
import ClientDropDown from "../Clients/ClientDropDown.vue";

const props = defineProps({
    clients: { type: Array, required: true }
});

const selectedClient = ref({});

const chartData = computed(() => {
    return {
        series: props.clients.map(client => client.total_barcodes),
        labels: props.clients.map(client => client.name),
    };
});
</script>

<template>
    <ChartThree :chartData="chartData" title="Client Barcode Distribution" />
</template>
