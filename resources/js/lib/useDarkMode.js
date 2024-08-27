import { ref, onMounted } from 'vue';

export function useDarkMode() {
    const isDarkMode = ref(false);

    onMounted(() => {
        const savedMode = localStorage.getItem('darkMode');
        if (savedMode) {
            isDarkMode.value = savedMode === 'true';
            document.documentElement.classList.toggle('dark', isDarkMode.value);
        }
    });

    function toggleDarkMode() {
        isDarkMode.value = !isDarkMode.value;
        document.documentElement.classList.toggle('dark', isDarkMode.value);
        localStorage.setItem('darkMode', isDarkMode.value);
    }

    return {
        isDarkMode,
        toggleDarkMode
    };
}
