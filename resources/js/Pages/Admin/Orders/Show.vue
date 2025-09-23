<template>
  <AdminLayout>
    <Head :title="`تفاصيل الطلب #${order.id}`" />
    
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">تفاصيل الطلب #{{ order.id }}</h1>
            <p class="text-gray-600 dark:text-gray-400">بتاريخ {{ formatDate(order.created_at) }}</p>
          </div>
          <Link :href="route('admin.orders')" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            رجوع للطلبات
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
            </div>
          </div>

          <!-- معلومات الطلب -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">معلومات الطلب</h2>
            <div class="space-y-2">
              <p><span class="font-medium">المبلغ الإجمالي:</span> {{ order.total_amount }} ر.س</p>
              <p>
                <span class="font-medium">حالة الطلب:</span>
                <select :value="order.order_status" @change="updateOrderStatus($event.target.value)"
                  class="ml-2 px-2 py-1 rounded text-xs" :class="getStatusClass(order.order_status)">
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
              <p><span class="font-medium">العنوان:</span> {{ order.customer.address || '---' }}</p>
              <p><span class="font-medium">تاريخ الطلب:</span> {{ formatDate(order.created_at) }}</p>
            </div>
          </div>
        </div>

        <!-- عناصر الطلب -->
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow">
          <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">عناصر الطلب</h2>
          </div>
          <div class="p-6">
            <div v-for="item in order.order_items" :key="item.id" class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
              <div class="flex items-center">
                <div class="ml-4">
                  <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ item.product.name }}</h3>
                  <p class="text-sm text-gray-500 dark:text-gray-400">الكمية: {{ item.quantity }}</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ item.price * item.quantity }} ر.س</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ item.price }} ر.س للقطعة</p>
              </div>
            </div>
            <div class="flex justify-between items-center pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
              <span class="text-lg font-bold text-gray-900 dark:text-white">الإجمالي</span>
              <span class="text-lg font-bold text-gray-900 dark:text-white">{{ order.total_amount }} ر.س</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  order: Object
});

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
  router.put(route('admin.orders.updateStatus', props.order.id), {
    order_status: newStatus
  }, {
    preserveScroll: true
  });
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('ar-SA', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};
</script>