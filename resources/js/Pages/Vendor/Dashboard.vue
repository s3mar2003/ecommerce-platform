<!-- resources/js/Pages/Vendor/Dashboard.vue -->
<template>
  <VendorLayout>
    <Head title="لوحة تحكم البائع" />
    
    <div class="space-y-6 px-4 sm:px-6 lg:px-8 py-4">
      <!-- العنوان -->
      <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">لوحة تحكم البائع</h1>
        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">نظرة عامة على أداء متجرك</p>
      </div>

      <!-- الإحصائيات -->
      <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- إجمالي المنتجات -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
              </svg>
            </div>
            <div class="mr-3 sm:mr-4">
              <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ stats.products_count }}</h3>
              <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">المنتجات</p>
            </div>
          </div>
        </div>

        <!-- إجمالي الطلبات -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 bg-green-100 dark:bg-green-900 rounded-full">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
              </svg>
            </div>
            <div class="mr-3 sm:mr-4">
              <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ stats.orders_count }}</h3>
              <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">الطلبات</p>
            </div>
          </div>
        </div>

        <!-- إجمالي الإيرادات -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div class="mr-3 sm:mr-4">
              <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(stats.revenue) }}</h3>
              <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">الإيرادات</p>
            </div>
          </div>
        </div>

        <!-- طلبات قيد الانتظار -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div class="mr-3 sm:mr-4">
              <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ stats.pending_orders }}</h3>
              <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">قيد الانتظار</p>
            </div>
          </div>
        </div>
      </div>

      <!-- الطلبات الحديثة -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
          <div class="flex flex-col xs:flex-row xs:justify-between xs:items-center gap-2">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">أحدث الطلبات</h2>
            <Link :href="route('vendor.orders')" class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 whitespace-nowrap">
              عرض الكل
            </Link>
          </div>
        </div>
        <div class="p-4 sm:p-6">
          <div v-if="recentOrders.length > 0" class="overflow-x-auto">
            <table class="w-full min-w-full">
              <thead>
                <tr class="bg-gray-50 dark:bg-gray-700">
                  <th class="px-3 py-2 sm:px-4 sm:py-3 text-right text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-300">رقم الطلب</th>
                  <th class="px-3 py-2 sm:px-4 sm:py-3 text-right text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-300">العميل</th>
                  <th class="px-3 py-2 sm:px-4 sm:py-3 text-right text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-300">المبلغ</th>
                  <th class="px-3 py-2 sm:px-4 sm:py-3 text-right text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-300">الحالة</th>
                  <th class="px-3 py-2 sm:px-4 sm:py-3 text-right text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-300">التاريخ</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="order in recentOrders" :key="order.id" class="border-t border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td class="px-3 py-3 sm:px-4 sm:py-4 font-medium text-xs sm:text-sm">#{{ order.id }}</td>
                  <td class="px-3 py-3 sm:px-4 sm:py-4 text-xs sm:text-sm">{{ order.customer.name }}</td>
                  <td class="px-3 py-3 sm:px-4 sm:py-4 text-xs sm:text-sm">{{ formatCurrency(order.total_amount) }}</td>
                  <td class="px-3 py-3 sm:px-4 sm:py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-medium" :class="getStatusClass(order.order_status)">
                      {{ getStatusText(order.order_status) }}
                    </span>
                  </td>
                  <td class="px-3 py-3 sm:px-4 sm:py-4 text-xs sm:text-sm">{{ formatDate(order.created_at) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="text-center py-8 text-gray-500 dark:text-gray-400">
            لا توجد طلبات حديثة
          </div>
        </div>
      </div>
    </div>
  </VendorLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import VendorLayout from '@/Layouts/VendorLayout.vue';

defineProps({
  stats: Object,
  recentOrders: Array
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
    'pending': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
    'processing': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
    'shipped': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
    'delivered': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    'cancelled': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
  };
  return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
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
<style>
@media (max-width: 640px) {
  .line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
}
</style>