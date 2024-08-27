<script setup>
import { ref } from "vue";
import Button from "@/Components/ui/button/Button.vue";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faSortDown } from '@fortawesome/free-solid-svg-icons';

const props = defineProps({
    clients: { type: Array, required: true }, // Corrected to Array
    selectedClient: Object, // Expecting selectedClient from parent
});

const emit = defineEmits(['update:selectedClient']); // Emit event for v-model

const handleClick = (client) => {
    emit('update:selectedClient', client); // Emit the selected client
};

</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger class="w-full flex justify-between hover:underline">
            Selected Client: {{ selectedClient.name || "All Clients" }}
            <FontAwesomeIcon :icon="faSortDown" />
        </DropdownMenuTrigger>
        <DropdownMenuContent>
            <DropdownMenuLabel>{{ $t("Clients") }}</DropdownMenuLabel>
            <DropdownMenuSeparator />
            <DropdownMenuItem @click="() => handleClick({ id: 0, name: 'All Clients' })">
                All Clients
            </DropdownMenuItem>
            <div v-for="client in clients" :key="client.id">
                <DropdownMenuItem @click="() => handleClick(client)">
                    {{ client.name }}
                </DropdownMenuItem>
            </div>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
