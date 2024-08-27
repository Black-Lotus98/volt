<script setup>
import { computed } from 'vue';
import { useLanguageStore } from '@/store/language';

const languageStore = useLanguageStore();
const currentLanguage = computed(() => languageStore.language);

const toggleLanguage = () => {
    languageStore.toggleLanguage();
};
</script>

<style scoped>
button {
    cursor: pointer;
}
</style>


<template>
    <div>
        <button @click="toggleLanguage" class="p-2 rounded">
            {{ $t("language") }}
        </button>
    </div>
</template>
