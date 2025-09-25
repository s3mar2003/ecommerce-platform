<template>
  <CustomerLayout>
    <Head title="لوحة تحكم العميل" />
    
    <div class="space-y-6">
      <!-- العنوان -->
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">لوحة تحكم العميل</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">مرحباً بك في متجرنا الإلكتروني</p>
      </div>

      <!-- الإحصائيات -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- إجمالي الطلبات -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
              <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
              </svg>
            </div>
            <div class="mr-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.orders_count }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">الطلبات</p>
            </div>
          </div>
        </div>

        <!-- طلبات قيد الانتظار -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
              <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div class="mr-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.pending_orders }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">قيد الانتظار</p>
            </div>
          </div>
        </div>

        <!-- طلبات مكتملة -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
              <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
            </div>
            <div class="mr-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.completed_orders }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">مكتملة</p>
            </div>
          </div>
        </div>

        <!-- إجمالي الإنفاق -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
              <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
              </svg>
            </div>
            <div class="mr-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(stats.total_spent) }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">إجمالي الإنفاق</p>
            </div>
          </div>
        </div>
      </div>

      <!-- الطلبات الحديثة -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
          <div class="flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">أحدث الطلبات</h2>
            <Link :href="route('customer.orders')" class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
              عرض الكل
            </Link>
          </div>
        </div>
        <div class="p-6">
          <div v-if="recentOrders.length > 0" class="space-y-4">
            <div v-for="order in recentOrders" :key="order.id" class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
              <div>
                <h3 class="font-medium text-gray-900 dark:text-white">طلب #{{ order.id }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ formatDate(order.created_at) }}</p>
                <p class="text-sm" :class="getStatusClass(order.order_status)">
                  {{ getStatusText(order.order_status) }}
                </p>
              </div>
              <div class="text-right">
                <p class="font-medium text-gray-900 dark:text-white">{{ formatCurrency(order.total_amount) }}</p>
                <Link :href="route('customer.orders.show', order.id)" class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                  التفاصيل
                </Link>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-8 text-gray-500 dark:text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <p>لا توجد طلبات حديثة</p>
          </div>
        </div>
      </div>

      <!-- المنتجات المميزة -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white">منتجات قد تعجبك</h2>
        </div>
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div v-for="product in featuredProducts" :key="product.id" class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
              <img :src="product.image_url || '/images/placeholder-product.png'" :alt="product.name" class="w-full h-32 object-cover rounded mb-3">
              <h3 class="font-medium text-gray-900 dark:text-white mb-1">{{ product.name }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-2">{{ product.description }}</p>
              <div class="flex items-center justify-between mt-3">
                <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ formatCurrency(product.price) }}</span>
                <Link :href="route('customer.products.show', product.id)" class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                  عرض
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </CustomerLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

defineProps({
  stats: Object,
  recentOrders: Array,
  featuredProducts: Array
});

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('ar-SA', {
    style: 'currency',
    currency: 'SAR',
    minimumFractionDigits: 0
  }).format(amount);
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('ar-SA');
};

const getStatusClass = (status) => {
  const classes = {
    'pending': 'text-yellow-600 dark:text-yellow-400',
    'processing': 'text-blue-600 dark:text-blue-400',
    'shipped': 'text-purple-600 dark:text-purple-400',
    'delivered': 'text-green-600 dark:text-green-400',
    'cancelled': 'text-red-600 dark:text-red-400'
  };
  return classes[status] || 'text-gray-600 dark:text-gray-400';
};

const getStatusText = (status) => {
  const texts = {
    'pending': 'قيد الانتظار',
    'processing': 'قيد المعالجة',
    'shipped': 'تم الشحن',
    'delivered': 'تم التوصيل',
    'cancelled': 'ملغي'
  };
  return texts[status] || status;
};
</script>
