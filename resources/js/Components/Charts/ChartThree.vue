<script setup>
import { ref, computed, watch } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

const props = defineProps({
    chartData: {
        type: Object,
        required: true
    },
    title: {
        type: String,
        default: 'Chart'
    }
});

// Function to generate a random color in hexadecimal format
const generateRandomColor = () => {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
};

// Create colors dynamically based on the number of labels
const colors = ref([]);

watch(
    () => props.chartData.labels,
    (newLabels) => {
        colors.value = newLabels.map(() => generateRandomColor());
    },
    { immediate: true }
);

const apexOptions = computed(() => ({
    chart: {
        type: 'donut',
        width: 380
    },
    colors: colors.value,
    labels: props.chartData.labels,
    legend: {
        show: false,
        position: 'bottom'
    },
    plotOptions: {
        pie: {
            donut: {
                size: '65%',
                background: 'transparent'
            }
        }
    },
    dataLabels: {
        enabled: false
    },
    responsive: [
        {
            breakpoint: 640,
            options: {
                chart: {
                    width: 200
                }
            }
        }
    ]
}));

</script>

<template>
    <div
        class="col-span-12 rounded-sm border border-stroke bg-white px-5 pt-7.5 pb-5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:col-span-5">
        <div class="mb-3 justify-between gap-4 sm:flex">
            <div>
                <h4 class="text-xl font-bold text-black dark:text-white">{{ title }}</h4>
            </div>
        </div>
        <div class="mb-2">
            <div id="chartThree" class="mx-auto flex justify-center">
                <VueApexCharts type="donut" width="340" :options="apexOptions" :series="chartData.series" />
            </div>
        </div>

        <!-- Legend Section -->
        <div class="-mx-8 flex flex-wrap items-center justify-start gap-y-3">
            <div v-for="(label, index) in chartData.labels" :key="index" class="w-full px-8 sm:w-1/2">
                <div class="flex w-full items-center">
                    <span class="mr-2 block h-3 w-full max-w-3 rounded-full"
                        :style="{ backgroundColor: colors[index] || '#000000' }"></span>
                    <p class="flex w-full justify-between text-sm font-medium text-black dark:text-white">
                        <span>{{ label }}&nbsp;</span>
                        <span>{{ ((chartData.series[index] / chartData.series.reduce((a, b) => a + b, 0)) * 100
                        ).toFixed(2) }}
                            %</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
