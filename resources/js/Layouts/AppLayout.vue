<script setup>
import { ref } from 'vue';
import Sidebar from '@/Components/Sidebar/Sidebar.vue';
import HeaderArea from '@/Components/Header/HeaderArea.vue';
// import Banner from '@/Components/Banner.vue';
import { Head } from '@inertiajs/vue3';
import { menuGroups } from '@/utilities/MenuItems/MeunGrops';


const searchQuery = ref('');

const updateSearchQuery = (newQuery) => {
    searchQuery.value = newQuery;
};

defineProps({
    title: String,
});
</script>

<template>

    <Head :title="title" />
    <!-- <Banner /> -->
    <div class="flex h-screen overflow-hidden dark:bg-black dark:text-gray">
        <Sidebar :menuGroups="menuGroups" />
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <HeaderArea @update-search-query="updateSearchQuery" />
            <main>
                <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
                    <slot :searchQuery="searchQuery"></slot>
                </div>
            </main>
        </div>
    </div>
</template>
