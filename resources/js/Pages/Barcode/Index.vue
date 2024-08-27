<script setup>
import { ref, computed, watch } from 'vue';
import jsPDF from 'jspdf';
import JsBarcode from 'jsbarcode';
import ClientForm from './ClientForm.vue';
import PopUp from '@/Components/PopUp.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Card from '@/Components/Card/Card.vue';
import Button from '@/Components/ui/button/Button.vue';

const props = usePage().props;

const clients = ref(props.clients);
const selectedClientId = ref(null);
const referenceNumber = ref('');
const count = ref('');
const totalGenerated = ref(0);
const startBarcode = ref('');
const isModalOpen = ref(false);
const pdfLink = ref('');

const form = useForm({
    client_id: null,
    count: null,
});

const openModal = () => {
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const handleClientAdded = () => {
    closeModal();
};

watch([selectedClientId, totalGenerated], () => {
    updateStartBarcode();
});

const updateStartBarcode = () => {
    if (referenceNumber.value && totalGenerated.value) {
        startBarcode.value = `${referenceNumber.value}${(totalGenerated.value + 1).toString().padStart(5, '0')}`;
    } else {
        startBarcode.value = '';
    }
};

const updateReferenceNumber = () => {
    const selectedClient = clients.value.find(client => client.id === selectedClientId.value);
    referenceNumber.value = selectedClient ? selectedClient.reference_number : '';
    totalGenerated.value = selectedClient ? selectedClient.total_barcodes : 0;
    updateStartBarcode();
};

// Ensure form data is kept in sync with the actual form inputs
watch(selectedClientId, (newValue) => {
    form.client_id = newValue;
});

watch(count, (newValue) => {
    form.count = newValue;
});

// Calculate start and end numbers based on the selected client and count
const calculateStartAndEndNumbers = () => {
    const startNumber = totalGenerated.value + 1;
    const endNumber = startNumber + parseInt(count.value) - 1;
    return { startNumber, endNumber };
};

// Function to generate barcodes
const generateBarcodes = async () => {
    const { startNumber, endNumber } = calculateStartAndEndNumbers();

    try {
        const pdf = new jsPDF({
            orientation: 'landscape',
            unit: 'cm',
            format: [4, 2.5],
        });

        for (let i = startNumber; i <= endNumber; i++) {
            const barcodeValue = `${referenceNumber.value}${i.toString().padStart(5, '0')}`;

            const canvas = document.createElement('canvas');
            JsBarcode(canvas, barcodeValue, {
                format: 'CODE128',
                width: 2,
                height: 100,
            });

            const imgData = canvas.toDataURL('image/png');
            pdf.addImage(imgData, 'PNG', 0.5, 0.5, 3, 2);

            if (i < endNumber) {
                pdf.addPage();
            }
        }

        pdf.save('barcodes.pdf');
        // Optionally update pdfLink here if needed
    } catch (error) {
        console.error('Error generating barcodes:', error);
    }
};

// Submit form
const submitForm = async () => {
    try {
        await form.post('/generateBarcode', {
            onSuccess: () => {
                generateBarcodes();
            },
            onError: (errors) => {
                console.error('Error submitting form:', errors);
            }
        });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

const showBarcodeDetails = computed(() => {
    return !!referenceNumber.value;
});

</script>

<template>
    <AppLayout title="Barcode Generator">
        <div class="mt-4 grid grid-cols-12 gap-4 md:mt-6 md:gap-6 2xl:mt-7.5 2xl:gap-7.5">

            <PopUp :show="isModalOpen" @close="closeModal">
                <ClientForm @client-added="handleClientAdded" />
            </PopUp>
            <Card class="col-span-8 col-start-3 max-sm:col-span-12 max-sm:col-start-1">
                <template #title>
                    {{ $t("Barcode Generator") }}
                </template>
                <template #content>
                    <div class="barcodeCard__picture barcodeCard__picture--volt">
                        &nbsp;
                    </div>
                    <div class="min-h-52 mb-0 p-0">
                        <form @submit.prevent="submitForm"
                            class="flex flex-col gap-4 justify-center items-center px-8 pb-4">
                            <div class="w-full py-4">
                                <label class="block text-right text-sm font-bold mb-2" for="client_id">
                                    <div class="flex flex-row justify-between items-center">
                                        <Button @click="openModal" type="button"
                                            class=" bg-[#000547] hover:text-[#000547] hover:bg-slate-300">
                                            {{ $t("Add Client") }}
                                        </Button>
                                        {{ $t("Source Company") }}
                                    </div>
                                </label>
                                <select v-model="selectedClientId" @change="updateReferenceNumber"
                                    class="dark:bg-black text-right pr-8 shadow appearance-none rounded w-full p-3 leading-tight focus:outline-none focus:shadow-outline"
                                    id="client_id" required>
                                    <option v-for="client in clients" :key="client.id" :value="client.id">
                                        {{ client.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="w-full py-4">
                                <label class="block text-right text-sm font-bold mb-2" for="reference_number">
                                    {{ $t("Reference Number") }}
                                </label>
                                <input type="text" v-model="referenceNumber"
                                    class="dark:bg-black text-right shadow appearance-none rounded w-full p-3 leading-tight focus:outline-none focus:shadow-outline"
                                    id="reference_number" readonly />
                            </div>
                            <div class="w-full py-4">
                                <label class="block text-right text-sm font-bold mb-2" for="count">
                                    {{ $t("Number to generate") }}
                                </label>
                                <input type="number" v-model="count" @input="updateStartBarcode"
                                    class="dark:bg-black shadow appearance-none rounded w-full p-3 leading-tight focus:outline-none focus:shadow-outline"
                                    id="count" required />
                            </div>
                            <div v-if="showBarcodeDetails" id="barcodeCard__details"
                                class="m-4 mb-0 max-w-md text-center">
                                <p class="text-lg mb-3">سيتم اصدار الباركود بهذا الشكل</p>
                                <img class="barcodeImage m-0" src="/assets/images/barcode.png" alt="Barcode Logo" />
                                <small id="StartBarcode" class="text-lg">
                                    {{ startBarcode }}
                                </small>
                            </div>
                            <Button type="submit"
                                class="bg-[#000547] hover:border hover:border-[#000547] hover:text-[#000547] hover:bg-slate-300">
                                {{ $t("Generate Barcodes") }}
                            </Button>
                        </form>
                        <div v-if="pdfLink">
                            <a :href="pdfLink" target="_blank" class="text-blue-500 hover:underline">Download Generated
                                Barcodes PDF</a>
                        </div>
                    </div>
                </template>
            </Card>
        </div>
    </AppLayout>
</template>
