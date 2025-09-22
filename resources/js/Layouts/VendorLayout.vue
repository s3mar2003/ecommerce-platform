<!-- resources/js/Layouts/VendorLayout.vue -->
<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- شريط التنقل -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <!-- الشعار -->
            <div class="flex-shrink-0 flex items-center">
              <Link :href="route('vendor.dashboard')" class="text-xl font-bold text-green-600 dark:text-green-400">
                لوحة البائع
              </Link>
            </div>

            <!-- أزرار التنقل لشاشات كبيرة -->
            <div class="hidden md:ml-6 md:flex md:items-center md:space-x-4 rtl:space-x-reverse">
              <NavLink :href="route('vendor.dashboard')" :active="route().current('vendor.dashboard')">
                الإحصائيات
              </NavLink>
              <NavLink :href="route('vendor.products')" :active="route().current('vendor.products')">
                المنتجات
              </NavLink>
              <NavLink :href="route('vendor.orders')" :active="route().current('vendor.orders')">
                الطلبات
              </NavLink>
              <NavLink 
                :href="route('vendor.manage.stock-orders')" 
                :active="route().current('vendor.manage.stock-orders')"
                class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 px-3 py-2 rounded-md text-sm font-medium"
              >
                إدارة المخزون
              </NavLink>
            </div>
          </div>

          <!-- قسم المستخدم -->
          <div class="flex items-center">
            <!-- زر الوضع الداكن -->
            <button @click="toggleDarkMode" class="p-2 rounded-md text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
              <svg v-if="!isDark" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-12.66l-.71.71M4.05 19.95l.71-.71M21 12h1M2 12H3m15.95 7.95l-.71-.71M4.76 4.76l.71.71M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
              </svg>
              <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
              </svg>
            </button>

            <!-- زر القائمة للجوال -->
            <div class="md:hidden">
              <button @click="showMobileMenu = !showMobileMenu" class="p-2 rounded-md text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
              </button>
            </div>

            <!-- Dropdown المستخدم -->
            <div class="hidden md:ml-4 md:flex md:items-center">
              <Dropdown align="right" width="48">
                <template #trigger>
                  <button class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                    <div>{{ $page.props.auth.user.name }}</div>
                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                  </button>
                </template>

                <template #content>
                  <DropdownLink :href="route('profile.edit')">الملف الشخصي</DropdownLink>
                  <DropdownLink :href="route('logout')" method="post" as="button">
                    تسجيل الخروج
                  </DropdownLink>
                </template>
              </Dropdown>
            </div>
          </div>
        </div>
      </div>

      <!-- القائمة المتنقلة للجوال -->
      <div v-if="showMobileMenu" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
          <NavLink :href="route('vendor.dashboard')" class="block px-3 py-2" :active="route().current('vendor.dashboard')">
            الإحصائيات
          </NavLink>
          <NavLink :href="route('vendor.products')" class="block px-3 py-2" :active="route().current('vendor.products')">
            المنتجات
          </NavLink>
          <NavLink :href="route('vendor.orders')" class="block px-3 py-2" :active="route().current('vendor.orders')">
            الطلبات
          </NavLink>
          <NavLink 
            :href="route('vendor.manage.stock-orders')" 
            class="block px-3 py-2 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 rounded-md"
            :active="route().current('vendor.manage.stock-orders')"
          >
            إدارة المخزون
          </NavLink>
        </div>
      </div>
    </nav>

    <!-- المحتوى الرئيسي -->
    <main>
      <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { ref } from 'vue';

const showMobileMenu = ref(false);
const isDark = ref(document.documentElement.classList.contains('dark'));

const toggleDarkMode = () => {
  isDark.value = !isDark.value;
  if (isDark.value) {
    document.documentElement.classList.add('dark');
    localStorage.setItem('dark-mode', 'true');
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('dark-mode', 'false');
  }
};
</script>