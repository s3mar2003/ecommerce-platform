<template>
  <div 
    class="min-h-screen flex items-center justify-center p-4 transition-colors duration-300" 
    :class="isDark ? 'bg-gray-900' : 'bg-gradient-to-br from-blue-50 to-indigo-100'" 
    dir="rtl"
  >
    <div class="w-full max-w-md">
      <!-- الشعار -->
      <div class="text-center mb-8">
        <Link href="/">
          <div :class="isDark ? 'text-indigo-400' : 'text-indigo-600'" class="text-2xl font-bold">
            متجرنا الإلكتروني
          </div>
        </Link>
        <p :class="isDark ? 'text-gray-300' : 'text-gray-600'" class="mt-2">أهلاً بك في متجرنا</p>
      </div>

      <!-- محتوى الصفحة -->
      <div :class="isDark ? 'bg-gray-800 border-gray-700 text-white' : 'bg-white border-gray-200 text-gray-900'" 
           class="rounded-lg shadow-xl p-6 border">
        <slot />
      </div>

      <!-- روابط إضافية -->
      <div class="text-center mt-6">
        <p :class="isDark ? 'text-gray-300' : 'text-gray-600'">
          <Link v-if="!isLoginPage" :href="route('login')" class="text-indigo-600 hover:text-indigo-500">
            تسجيل الدخول
          </Link>
          <Link v-if="!isRegisterPage" :href="route('register')" class="text-indigo-600 hover:text-indigo-500 ms-4">
            إنشاء حساب
          </Link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';

const isLoginPage = computed(() => usePage().url === '/login');
const isRegisterPage = computed(() => usePage().url === '/register');

const isDark = ref(false);

onMounted(() => {
  const savedDarkMode = localStorage.getItem('darkMode') === 'true';
  isDark.value = savedDarkMode;
  document.documentElement.classList.toggle('dark', savedDarkMode);
});
</script>
