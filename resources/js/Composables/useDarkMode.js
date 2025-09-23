import { ref, onMounted, watch } from 'vue';

export function useDarkMode() {
    const isDark = ref(false);

    // تهيئة الوضع الليلي من localStorage
    const initializeDarkMode = () => {
        const savedDarkMode = localStorage.getItem('darkMode') === 'true';
        isDark.value = savedDarkMode;
        updateDarkMode();
    };

    // تحديث الوضع الليلي في الـ DOM
    const updateDarkMode = () => {
        if (isDark.value) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('darkMode', 'true');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('darkMode', 'false');
        }
    };

    // تبديل الوضع الليلي
    const toggleDarkMode = () => {
        isDark.value = !isDark.value;
        updateDarkMode();
    };

    // التهيئة عند تحميل composable
    onMounted(() => {
        initializeDarkMode();
    });

    // مراقبة التغييرات
    watch(isDark, updateDarkMode);

    return {
        isDark,
        toggleDarkMode,
        initializeDarkMode
    };
}