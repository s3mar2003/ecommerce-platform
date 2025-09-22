<!-- resources/js/Pages/Vendor/Orders/Show.vue -->
<template>
  <VendorLayout>
    <Head :title="`تفاصيل الطلب #${order.id}`" />
    
    <div class="space-y-6">
      <!-- العنوان -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">تفاصيل الطلب #{{ order.id }}</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1">بتاريخ {{ formatDate(order.created_at) }}</p>
        </div>
        <Link 
          :href="route('vendor.orders')" 
          class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white flex items-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          رجوع إلى الطلبات
        </Link>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- معلومات العميل -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">معلومات العميل</h2>
          <div class="space-y-2">
            <p><span class="font-medium">الاسم:</span> {{ order.customer.name }}</p>
            <p><span class="font-medium">البريد:</span> {{ order.customer.email }}</p>
            <p><span class="font-medium">الهاتف:</span> {{ order.customer.phone || '---' }}</p>
            <p><span class="font-medium">العنوان:</span> {{ order.customer.address || '---' }}</p>
          </div>
        </div>

        <!-- معلومات الطلب -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">معلومات الطلب</h2>
          <div class="space-y-2">
            <p><span class="font-medium">المبلغ الإجمالي:</span> {{ formatCurrency(order.total_amount) }}</p>
            <p>
              <span class="font-medium">حالة الطلب:</span>
              <select 
                :value="order.order_status" 
                @change="updateOrderStatus($event.target.value)"
                class="mr-2 px-2 py-1 rounded text-sm font-medium" 
                :class="getStatusClass(order.order_status)"
              >
                <option value="pending">قيد الانتظار</option>
                <option value="processing">قيد المعالجة</option>
                <option value="shipped">تم الشحن</option>
                <option value="delivered">تم التوصيل</option>
                <option value="cancelled">ملغي</option>
              </select>
            </p>
            <p><span class="font-medium">حالة الدفع:</span> 
              <span :class="order.payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600'">
                {{ order.payment_status === 'paid' ? 'مدفوع' : 'غير مدفوع' }}
              </span>
            </p>
          </div>
        </div>

        <!-- معلومات الشحن -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">معلومات الشحن</h2>
          <div class="space-y-2">
            <p><span class="font-medium">تاريخ الطلب:</span> {{ formatDate(order.created_at) }}</p>
            <p><span class="font-medium">آخر تحديث:</span> {{ formatDate(order.updated_at) }}</p>
          </div>
        </div>
      </div>

      <!-- عناصر الطلب -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white">عناصر الطلب</h2>
        </div>
        <div class="p-6">
          <div v-for="item in order.order_items" :key="item.id" class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
            <div class="flex items-center">
              <img 
                v-if="item.product.image_url" 
                :src="item.product.image_url" 
                :alt="item.product.name"
                class="w-12 h-12 rounded object-cover ml-4"
              >
              <div class="flex-shrink-0 h-12 w-12 bg-gray-100 dark:bg-gray-600 rounded flex items-center justify-center ml-4" v-else>
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <div>
                <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ item.product.name }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">الكمية: {{ item.quantity }}</p>
              </div>
            </div>
            <div class="text-right">
              <p class="text-sm font-medium text-gray-900 dark:text-white">{{ formatCurrency(item.price * item.quantity) }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ formatCurrency(item.price) }} للقطعة</p>
            </div>
          </div>
          
          <div class="flex justify-between items-center pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
            <span class="text-lg font-bold text-gray-900 dark:text-white">الإجمالي</span>
            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ formatCurrency(order.total_amount) }}</span>
          </div>
        </div>
      </div>
    </div>
  </VendorLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, reactive, computed } from 'vue';
import VendorLayout from '@/Layouts/VendorLayout.vue';

const props = defineProps({
  order: Object
});

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('ar-SA', {
    style: 'currency',
    currency: 'SAR',
    minimumFractionDigits: 0
  }).format(amount);
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('ar-SA', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const getStatusClass = (status) => {
  const classes = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'processing': 'bg-blue-100 text-blue-800',
    'shipped': 'bg-purple-100 text-purple-800',
    'delivered': 'bg-green-100 text-green-800',
    'cancelled': 'bg-red-100 text-red-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const updateOrderStatus = (newStatus) => {
  router.put(route('vendor.orders.updateStatus', props.order.id), {
    order_status: newStatus
  }, {
    preserveScroll: true,
    onSuccess: () => {
      if (window.showToast) {
        window.showToast('تم تحديث حالة الطلب بنجاح', 'success');
      }
    },
    onError: () => {
      if (window.showToast) {
        window.showToast('حدث خطأ أثناء تحديث حالة الطلب', 'error');
      }
    }
  });
};
</script>