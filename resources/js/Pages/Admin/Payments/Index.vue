<template>
  <AdminLayout>
    <Head title="إدارة المدفوعات" />
    
    <div class="space-y-4 sm:space-y-6">
      <!-- العنوان -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">إدارة المدفوعات</h1>
      </div>

      <!-- البحث والتصفية -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
          <input
            type="text"
            placeholder="ابحث برقم العملية..."
            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
          >
          <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">جميع الحالات</option>
            <option value="pending">قيد الانتظار</option>
            <option value="completed">مكتمل</option>
            <option value="failed">فاشل</option>
          </select>
          <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">جميع الطرق</option>
            <option value="credit_card">بطاقة ائتمان</option>
            <option value="paypal">PayPal</option>
            <option value="bank_transfer">تحويل بنكي</option>
          </select>
        </div>
      </div>

      <!-- جدول المدفوعات -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full min-w-full">
            <thead>
              <tr class="bg-gray-50 dark:bg-gray-700">
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300">رقم العملية</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300">العميل</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300">المبلغ</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 hidden sm:table-cell">الطريقة</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300">الحالة</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 hidden md:table-cell">التاريخ</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="payment in payments.data" :key="payment.id" class="border-t border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-4 py-4 font-medium">#{{ payment.id }}</td>
                <td class="px-4 py-4">{{ payment.order.customer.name }}</td>
                <td class="px-4 py-4">{{ payment.amount }} ر.س</td>
                <td class="px-4 py-4 hidden sm:table-cell">{{ getPaymentMethodText(payment.method) }}</td>
                <td class="px-4 py-4">
                  <select :value="payment.status" @change="updatePaymentStatus(payment, $event.target.value)" 
                    class="px-2 py-1 rounded text-xs border" :class="getStatusClass(payment.status)">
                    <option value="pending">قيد الانتظار</option>
                    <option value="completed">مكتمل</option>
                    <option value="failed">فاشل</option>
                    <option value="refunded">تم الاسترداد</option>
                  </select>
                </td>
                <td class="px-4 py-4 hidden md:table-cell">{{ formatDate(payment.created_at) }}</td>
                <td class="px-4 py-4">
                  <Link :href="route('admin.payments.show', payment.id)" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                    التفاصيل
                  </Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="payments.data.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
          لا يوجد مدفوعات
        </div>
      </div>

      <!-- التصفح -->
      <Pagination :links="payments.links" />
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  payments: Object
});

const getStatusClass = (status) => {
  const classes = {
    'pending': 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'completed': 'bg-green-100 text-green-800 border-green-200',
    'failed': 'bg-red-100 text-red-800 border-red-200',
    'refunded': 'bg-gray-100 text-gray-800 border-gray-200'
  };
  return classes[status] || 'bg-gray-100 text-gray-800 border-gray-200';
};

const getPaymentMethodText = (method) => {
  const methods = {
    'credit_card': 'بطاقة ائتمان',
    'paypal': 'PayPal',
    'bank_transfer': 'تحويل بنكي',
    'cash_on_delivery': 'الدفع عند الاستلام'
  };
  return methods[method] || method;
};

const updatePaymentStatus = (payment, newStatus) => {
  router.put(route('admin.payments.updateStatus', payment.id), {
    status: newStatus
  }, {
    preserveScroll: true
  });
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('ar-SA');
};
</script>