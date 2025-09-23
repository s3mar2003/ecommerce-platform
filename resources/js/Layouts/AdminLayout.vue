<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <Link
              :href="route('admin.dashboard')"
              class="text-xl font-bold text-indigo-600 dark:text-indigo-400"
            >
              لوحة التحكم
            </Link>

            <div class="hidden md:flex items-center space-x-8 rtl:space-x-reverse mr-8">
              <NavLink 
                :href="route('admin.dashboard')" 
                :active="route().current('admin.dashboard')"
              >
                الإحصائيات
              </NavLink>
              <NavLink 
                :href="route('admin.vendors')" 
                :active="route().current('admin.vendors')"
              >
                البائعون
              </NavLink>
              <NavLink 
                :href="route('admin.products')" 
                :active="route().current('admin.products')"
              >
                المنتجات
              </NavLink>
              <NavLink 
                :href="route('admin.orders')" 
                :active="route().current('admin.orders')"
              >
                الطلبات
              </NavLink>
            </div>
          </div>

          <div class="flex items-center space-x-4 rtl:space-x-reverse">
            <button
              @click="toggleDarkMode"
              class="p-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 
                     dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 
                     transition-colors duration-300"
              aria-label="Toggle Dark Mode"
            >
              <svg
                v-if="!isDark"
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 3v1m0 16v1m8.66-12.66l-.71.71M4.05 19.95l.71-.71M21 12h1M2 12H3m15.95 7.95l-.71-.71M4.76 4.76l.71.71M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                />
              </svg>

              <svg
                v-else
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"
                />
              </svg>
            </button>

            <div class="relative">
              <button
                @click="userMenuOpen = !userMenuOpen"
                class="flex items-center text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
              >
                <div>{{ $page.props.auth.user.name }}</div>
                <svg
                  class="w-4 h-4 mr-1 rtl:ml-1 rtl:mr-0"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </button>

              <div 
                v-show="userMenuOpen" 
                class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700"
              >
                <DropdownLink :href="route('profile.edit')">الملف الشخصي</DropdownLink>
                <DropdownLink :href="route('logout')" method="post" as="button">
                  تسجيل الخروج
                </DropdownLink>
              </div>
            </div>

            <button
              @click="showMobileMenu = !showMobileMenu"
              class="md:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  :d="showMobileMenu ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'"
                />
              </svg>
            </button>
          </div>
        </div>

        <div v-if="showMobileMenu" class="md:hidden pt-2 pb-3 space-y-1">
          <NavLink 
            :href="route('admin.dashboard')" 
            :active="route().current('admin.dashboard')"
            class="block px-3 py-2"
            @click="showMobileMenu = false"
          >
            الإحصائيات
          </NavLink>
          <NavLink 
            :href="route('admin.vendors')" 
            :active="route().current('admin.vendors')"
            class="block px-3 py-2"
            @click="showMobileMenu = false"
          >
            البائعون
          </NavLink>
          <NavLink 
            :href="route('admin.products')" 
            :active="route().current('admin.products')"
            class="block px-3 py-2"
            @click="showMobileMenu = false"
          >
            المنتجات
          </NavLink>
          <NavLink 
            :href="route('admin.orders')" 
            :active="route().current('admin.orders')"
            class="block px-3 py-2"
            @click="showMobileMenu = false"
          >
            الطلبات
          </NavLink>
        </div>
      </div>
    </nav>

    <main>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { ref, onMounted, onUnmounted } from 'vue';

const showMobileMenu = ref(false);
const userMenuOpen = ref(false);
const isDark = ref(false);

// إغلاق القوائم المنسدلة عند النقر خارجها
const closeDropdowns = (event) => {
  if (!event.target.closest('.relative')) {
    userMenuOpen.value = false;
  }
  if (!event.target.closest('nav')) {
    showMobileMenu.value = false;
  }
};

onMounted(() => {
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'dark') {
    isDark.value = true;
    document.documentElement.classList.add('dark');
  }
  
  document.addEventListener('click', closeDropdowns);
});

onUnmounted(() => {
  document.removeEventListener('click', closeDropdowns);
});

const toggleDarkMode = () => {
  isDark.value = !isDark.value;
  if (isDark.value) {
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  }
};
</script>