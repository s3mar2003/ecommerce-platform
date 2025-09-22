<template>
  <CustomerLayout>
    <Head :title="`طلب #${order.id}`" />
    
    <div class="py-6">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- رأس الصفحة -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">طلب #{{ order.id }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">بتاريخ {{ formatDate(order.created_at) }}</p>
          </div>
          <Link 
            :href="route('customer.orders')" 
            class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 flex items-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            رجوع إلى الطلبات
          </Link>
        </div>

        <!-- رسائل التنبيه -->
        <!-- <div v-if="$page.props.flash.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
          {{ $page.props.flash.success }}
        </div> -->

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- معلومات الطلب -->
          <div class="lg:col-span-2 space-y-6">
            <!-- حالة الطلب -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">حالة الطلب</h2>
              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <div class="p-3 rounded-full" :class="getStatusColor(order.order_status)">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                  </div>
                  <div class="mr-4">
                    <h3 class="font-medium text-gray-900 dark:text-white">{{ getStatusText(order.order_status) }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">آخر تحديث: {{ formatDateTime(order.updated_at) }}</p>
                  </div>
                </div>
                <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ order.total_amount }} ر.س</span>
              </div>
            </div>

            <!-- عناصر الطلب -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
              <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">عناصر الطلب</h2>
              </div>
              <div class="p-6">
                <div v-for="item in order.order_items" :key="item.id" class="flex items-center justify-between py-4 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                  <div class="flex items-center">
                    <img 
                      :src="item.product.image_url || '/images/placeholder-product.png'" 
                      :alt="item.product.name"
                      class="w-16 h-16 object-cover rounded-lg ml-4"
                    >
                    <div>
                      <h3 class="font-medium text-gray-900 dark:text-white">{{ item.product.name }}</h3>
                      <p class="text-sm text-gray-500 dark:text-gray-400">الكمية: {{ item.quantity }}</p>
                      <p class="text-sm text-gray-500 dark:text-gray-400">البائع: {{ item.vendor.name }}</p>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="font-medium text-gray-900 dark:text-white">{{ item.price * item.quantity }} ر.س</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ item.price }} ر.س للقطعة</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- المعلومات الجانبية -->
          <div class="space-y-6">
            <!-- معلومات التوصيل -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">معلومات التوصيل</h2>
              <div class="space-y-2">
                <p class="text-sm text-gray-600 dark:text-gray-400">الاسم: {{ order.customer.name }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">البريد: {{ order.customer.email }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">العنوان: {{ order.shipping_address }}</p>
              </div>
            </div>

            <!-- معلومات الدفع -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">معلومات الدفع</h2>
              <div class="space-y-3">
                <div class="flex justify-between">
                  <span class="text-sm text-gray-600 dark:text-gray-400">المجموع الفرعي</span>
                  <span class="text-sm text-gray-900 dark:text-white">{{ order.total_amount }} ر.س</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-600 dark:text-gray-400">الشحن</span>
                  <span class="text-sm text-gray-900 dark:text-white">15 ر.س</span>
                </div>
                <div class="flex justify-between border-t border-gray-200 dark:border-gray-700 pt-2">
                  <span class="text-base font-medium text-gray-900 dark:text-white">المجموع</span>
                  <span class="text-base font-medium text-gray-900 dark:text-white">{{ order.total_amount + 15 }} ر.س</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-600 dark:text-gray-400">طريقة الدفع</span>
                  <span class="text-sm font-medium text-indigo-600">{{ getPaymentMethodText(order.payment_method) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-600 dark:text-gray-400">حالة الدفع</span>
                  <span class="text-sm font-medium" :class="order.payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600'">
                    {{ order.payment_status === 'paid' ? 'مدفوع' : 'قيد الانتظار' }}
                  </span>
                </div>
              </div>
            </div>

            <!-- إجراءات سريعة -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">الإجراءات</h2>
              <div class="space-y-2">
                <button 
                  v-if="order.order_status === 'pending'"
                  @click="cancelOrder"
                  class="w-full text-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition"
                >
                  إلغاء الطلب
                </button>
                <Link 
                  :href="route('customer.products')" 
                  class="block w-full text-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                >
                  متجر جديد
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
import { Head, Link, router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
  order: Object
});

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('ar-SA', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const formatDateTime = (dateString) => {
  return new Date(dateString).toLocaleString('ar-SA', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const getStatusColor = (status) => {
  const colors = {
    'pending': 'bg-yellow-500',
    'processing': 'bg-blue-500',
    'shipped': 'bg-purple-500',
    'delivered': 'bg-green-500',
    'cancelled': 'bg-red-500'
  };
  return colors[status] || 'bg-gray-500';
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

const getPaymentMethodText = (method) => {
  const texts = {
    'cash_on_delivery': 'الدفع عند الاستلام',
    'credit_card': 'بطاقة ائتمان',
    'mada': 'مدى'
  };
  return texts[method] || method;
};

const cancelOrder = () => {
  if (confirm('هل أنت متأكد من إلغاء هذا الطلب؟')) {
    router.put(route('customer.orders.cancel', props.order.id), {}, {
      preserveScroll: true,
      onSuccess: () => {
        // سيتم إعادة التوجيه مع رسالة نجاح
      },
      onError: () => {
        showToast('حدث خطأ أثناء إلغاء الطلب', 'error');
      }
    });
  }
};

const showToast = (message, type = 'info') => {
  const toast = document.createElement('div');
  toast.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${
    type === 'success' ? 'bg-green-500' : 'bg-red-500'
  }`;
  toast.textContent = message;
  document.body.appendChild(toast);
  
  setTimeout(() => toast.remove(), 3000);
};
</script>