<!-- resources/js/Pages/Customer/Orders/Index.vue -->
<template>
  <CustomerLayout>
    <Head title="طلباتي" />
    
    <div class="space-y-6">
      <!-- العنوان والتصفية -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">طلباتي</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1">تتبع حالة طلباتك</p>
        </div>
        
        <select
          v-model="statusFilter"
          @change="filterOrders"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
          <option value="">جميع الطلبات</option>
          <option value="pending">قيد الانتظار</option>
          <option value="processing">قيد المعالجة</option>
          <option value="shipped">تم الشحن</option>
          <option value="delivered">تم التوصيل</option>
          <option value="cancelled">ملغية</option>
        </select>
      </div>

      <!-- قائمة الطلبات -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full min-w-full">
            <thead>
              <tr class="bg-gray-50 dark:bg-gray-700">
                <th class="px-6 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-300">رقم الطلب</th>
                <th class="px-6 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-300">التاريخ</th>
                <th class="px-6 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-300">المبلغ</th>
                <th class="px-6 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-300">حالة الطلب</th>
                <th class="px-6 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-300">حالة الدفع</th>
                <th class="px-6 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-300">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <tr 
                v-for="order in orders.data" 
                :key="order.id" 
                class="border-t border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
              >
                <td class="px-6 py-4 font-medium">#{{ order.id }}</td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 dark:text-white">{{ formatDate(order.created_at) }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-gray-900 dark:text-white">{{ formatCurrency(order.total_amount) }}</div>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" :class="getStatusClass(order.order_status)">
                    {{ getStatusText(order.order_status) }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" :class="order.payment_status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'">
                    {{ order.payment_status === 'paid' ? 'مدفوع' : 'قيد الانتظار' }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <Link 
                    :href="route('customer.orders.show', order.id)" 
                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors flex items-center gap-1"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    التفاصيل
                  </Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- حالة عدم وجود طلبات -->
        <div v-if="orders.data.length === 0" class="text-center py-12">
          <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
          </svg>
          <p class="text-gray-500 dark:text-gray-400 text-lg">لا توجد طلبات</p>
          <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">لم تقم بأي طلبات بعد</p>
          <Link 
            :href="route('customer.products')" 
            class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
          >
            ابدأ التسوق
          </Link>
        </div>
      </div>

      <!-- التصفح -->
      <Pagination :links="orders.links" />
    </div>
  </CustomerLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  orders: Object,
  filters: Object
});

const statusFilter = ref(props.filters.status || '');

const filterOrders = () => {
  router.get(route('customer.orders'), {
    status: statusFilter.value
  }, {
    preserveState: true
  });
};

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
    day: 'numeric'
  });
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