<!-- resources/js/Layouts/CustomerLayout.vue -->
<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- منطقة الإشعارات -->
    <div class="fixed top-4 right-4 z-50 space-y-2">
      <Toast
        v-for="toast in toasts"
        :key="toast.id"
        :message="toast.message"
        :type="toast.type"
        :duration="toast.duration"
        @close="removeToast(toast.id)"
      />
    </div>

    <!-- شريط التنقل -->
    <nav class="bg-white dark:bg-gray-800 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <!-- الشعار -->
            <Link :href="route('customer.dashboard')" class="flex-shrink-0 flex items-center">
              <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">متجرك</span>
            </Link>
            
            <!-- روابط التنقل -->
            <div class="hidden sm:ml-6 sm:flex sm:space-x-4 rtl:space-x-reverse">
              <Link 
                :href="route('customer.dashboard')" 
                class="px-3 py-2 rounded-md text-sm font-medium transition-colors"
                :class="route().current('customer.dashboard') 
                  ? 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-900/20' 
                  : 'text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400'"
              >
                لوحة التحكم
              </Link>
              <Link 
                :href="route('customer.products')" 
                class="px-3 py-2 rounded-md text-sm font-medium transition-colors"
                :class="route().current('customer.products') 
                  ? 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-900/20' 
                  : 'text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400'"
              >
                المنتجات
              </Link>
              <Link 
                :href="route('customer.orders')" 
                class="px-3 py-2 rounded-md text-sm font-medium transition-colors"
                :class="route().current('customer.orders*') 
                  ? 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-900/20' 
                  : 'text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400'"
              >
                طلباتي
              </Link>
            </div>
          </div>

          <div class="flex items-center space-x-4 rtl:space-x-reverse">
            <!-- السلة -->
            <Link 
              :href="route('customer.cart')" 
              class="relative p-2 text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400 transition-colors"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6h9M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"/>
              </svg>
              <span v-if="$page.props.auth.user.cart_items_count > 0" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                {{ $page.props.auth.user.cart_items_count }}
              </span>
            </Link>

            <!-- الإشعارات -->
            <Link 
              :href="route('customer.notifications')" 
              class="relative p-2 text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400 transition-colors"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
              </svg>
              <span v-if="$page.props.auth.user.unread_notifications_count > 0" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                {{ $page.props.auth.user.unread_notifications_count }}
              </span>
            </Link>

            <!-- الملف الشخصي -->
            <div class="relative">
              <button 
                @click="toggleProfileMenu" 
                class="flex items-center space-x-2 rtl:space-x-reverse text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500"
              >
                <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                  <span class="text-indigo-600 dark:text-indigo-400 font-medium text-sm">
                    {{ $page.props.auth.user.name.charAt(0) }}
                  </span>
                </div>
                <span class="text-gray-700 dark:text-gray-300 hidden md:block">
                  {{ $page.props.auth.user.name }}
                </span>
              </button>

              <!-- قائمة الملف الشخصي -->
              <div 
                v-show="profileMenuOpen" 
                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50"
              >
                <Link 
                  :href="route('customer.profile')" 
                  class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                >
                  الملف الشخصي
                </Link>
                <Link 
                  :href="route('customer.orders')" 
                  class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                >
                  طلباتي
                </Link>
                <Link 
                  :href="route('customer.notifications')" 
                  class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                >
                  الإشعارات
                </Link>
                <div class="border-t border-gray-200 dark:border-gray-600 my-1"></div>
                <form @submit.prevent="logout">
                  <button 
                    type="submit" 
                    class="block w-full text-right px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                  >
                    تسجيل الخروج
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- المحتوى الرئيسي -->
    <main class="pb-16">
      <slot />
    </main>

    <!-- تذييل الصفحة -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 fixed bottom-0 w-full">
      <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        <div class="text-center text-gray-600 dark:text-gray-400 text-sm">
          <p>© 2024 متجرك. جميع الحقوق محفوظة.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, provide } from 'vue';
import Toast from '@/Components/Toast.vue';

const profileMenuOpen = ref(false);
const toasts = ref([]);
let toastId = 0;

// توفير دالة إضافة الإشعارات للمكونات الفرعية
const addToast = (message, type = 'info', duration = 5000) => {
  const id = toastId++;
  toasts.value.push({ id, message, type, duration });
};

// جعل الدالة متاحة للمكونات الفرعية
provide('addToast', addToast);

// إغلاق قائمة الملف الشخصي عند النقر خارجها
const closeProfileMenu = (event) => {
  if (!event.target.closest('.relative')) {
    profileMenuOpen.value = false;
  }
};

const toggleProfileMenu = () => {
  profileMenuOpen.value = !profileMenuOpen.value;
};

// إزالة الإشعار
const removeToast = (id) => {
  toasts.value = toasts.value.filter(toast => toast.id !== id);
};

// تسجيل الخروج
const logout = () => {
  router.post(route('logout'));
};

// إعداد event listener
onMounted(() => {
  document.addEventListener('click', closeProfileMenu);
});

onUnmounted(() => {
  document.removeEventListener('click', closeProfileMenu);
});
</script>