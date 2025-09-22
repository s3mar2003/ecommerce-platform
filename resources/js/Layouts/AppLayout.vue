<template>
  <div class="min-h-screen transition-colors duration-300" :class="{'bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white': true}" dir="rtl">
    <!-- شريط التنقل -->
    <nav class="bg-white dark:bg-gray-800 shadow-lg">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- الشعار -->
          <div class="flex items-center">
            <Link :href="route('welcome')" class="text-xl font-bold text-blue-600 dark:text-indigo-400">
              متجرنا الإلكتروني
            </Link>
          </div>

          <!-- أزرار التنقل -->
          <div class="flex items-center space-x-4">
            <button @click="toggleDarkMode" class="px-3 py-1 border rounded text-sm">
              {{ isDark ? 'وضع فاتح' : 'وضع داكن' }}
            </button>

            <template v-if="$page.props.auth.user">
              <span class="text-sm">{{ $page.props.auth.user.name }}</span>
              <Link :href="route('logout')" method="post" as="button" class="text-sm hover:text-gray-300 dark:hover:text-white">
                تسجيل الخروج
              </Link>
            </template>
            <template v-else>
              <Link :href="route('login')" class="text-sm hover:text-gray-700 dark:hover:text-gray-300">
                تسجيل الدخول
              </Link>
              <Link :href="route('register')" class="text-sm hover:text-gray-700 dark:hover:text-gray-300">
                إنشاء حساب
              </Link>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- المحتوى الرئيسي -->
    <main>
      <slot />
    </main>

    <!-- التذييل -->
    <footer class="bg-gray-800 text-white mt-16">
      <div class="max-w-7xl mx-auto py-8 px-4 text-center">
        © 2024 متجرنا الإلكتروني. جميع الحقوق محفوظة.
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';


import isDark, { toggleDarkMode } from '@/state/darkMode';


</script>
