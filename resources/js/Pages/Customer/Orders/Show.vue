<template>
  <CustomerLayout :title="`طلب #${order.id}`">
    <Head :title="`طلب #${order.id}`" />
    
    <div class="py-6">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- مسار التنقل -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
          <ol class="flex items-center space-x-2 rtl:space-x-reverse">
            <li>
              <Link :href="route('customer.orders')" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                الطلبات
              </Link>
            </li>
            <li>
              <span class="text-gray-400 mx-2">/</span>
            </li>
            <li>
              <span class="text-gray-900 dark:text-white">طلب #{{ order.id }}</span>
            </li>
          </ol>
        </nav>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
          <div class="p-6">
            <!-- معلومات الطلب -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
              <!-- معلومات الطلب الأساسية -->
              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">معلومات الطلب</h2>
                <div class="space-y-3">
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">رقم الطلب:</span>
                    <span class="font-medium">#{{ order.id }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">التاريخ:</span>
                    <span class="font-medium">{{ formatDate(order.created_at) }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">الحالة:</span>
                    <span :class="getStatusClass(order.order_status)" class="px-2 py-1 rounded text-sm">
                      {{ getStatusText(order.order_status) }}
                    </span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">طريقة الدفع:</span>
                    <span class="font-medium">{{ getPaymentMethodText(order.payment_method) }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">حالة الدفع:</span>
                    <span :class="getPaymentStatusClass(order.payment_status)" class="px-2 py-1 rounded text-sm">
                      {{ getPaymentStatusText(order.payment_status) }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- معلومات العميل -->
              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">معلومات العميل</h2>
                <div class="space-y-3">
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">الاسم:</span>
                    <!-- استخدام optional chaining -->
                    <span class="font-medium">{{ order.customer?.name || 'غير متوفر' }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">البريد الإلكتروني:</span>
                    <span class="font-medium">{{ order.customer?.email || 'غير متوفر' }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">الهاتف:</span>
                    <span class="font-medium">{{ order.phone || 'غير متوفر' }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">عنوان الشحن:</span>
                    <span class="font-medium text-right">{{ order.shipping_address || 'غير متوفر' }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- عناصر الطلب -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">عناصر الطلب</h2>
              <div class="space-y-4">
                <div 
                  v-for="item in order.order_items" 
                  :key="item.id"
                  class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg"
                >
                  <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <img 
                      :src="item.product?.image_url || '/images/placeholder-product.png'" 
                      :alt="item.product?.name"
                      class="w-16 h-16 object-cover rounded"
                    >
                    <div>
                      <h3 class="font-medium text-gray-900 dark:text-white">{{ item.product?.name || 'منتج غير متوفر' }}</h3>
                      <p class="text-sm text-gray-600 dark:text-gray-400">
                        البائع: {{ item.product?.vendor?.name || 'غير معروف' }}
                      </p>
                      <p class="text-sm text-gray-600 dark:text-gray-400">الكمية: {{ item.quantity }}</p>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                      {{ formatCurrency(item.price * item.quantity) }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                      {{ formatCurrency(item.price) }} × {{ item.quantity }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- المجموع -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
              <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-900 dark:text-white">المبلغ الإجمالي:</span>
                <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                  {{ formatCurrency(order.total_amount) }}
                </span>
              </div>
            </div>

            <!-- الإجراءات -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6 flex justify-end space-x-4 rtl:space-x-reverse">
              <Link 
                :href="route('customer.orders')" 
                class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300 transition dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
              >
                العودة للطلبات
              </Link>
              <button 
                v-if="order.order_status === 'pending' || order.order_status === 'processing'"
                @click="cancelOrder(order)"
                class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition"
              >
                إلغاء الطلب
              </button>
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

// التأكد من أن البيانات تصل بشكل صحيح
console.log('Order Data:', props.order);
console.log('Order Customer:', props.order?.customer);

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

const getPaymentMethodText = (method) => {
  const texts = {
    'cash_on_delivery': 'الدفع عند الاستلام',
    'credit_card': 'بطاقة ائتمان',
    'paypal': 'PayPal',
    'bank_transfer': 'تحويل بنكي'
  };
  return texts[method] || method;
};

const getPaymentStatusClass = (status) => {
  const classes = {
    'pending': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
    'paid': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    'failed': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
  };
  return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
};

const getPaymentStatusText = (status) => {
  const texts = {
    'pending': 'قيد الانتظار',
    'paid': 'مدفوع',
    'failed': 'فشل الدفع'
  };
  return texts[status] || status;
};

const cancelOrder = (order) => {
  if (!confirm('هل أنت متأكد من إلغاء هذا الطلب؟')) {
    return;
  }

  router.post(route('customer.orders.cancel', order.id), {}, {
    preserveScroll: true,
    onSuccess: () => {
      console.log('تم إلغاء الطلب بنجاح');
    },
    onError: (errors) => {
      console.error('خطأ في إلغاء الطلب:', errors);
    }
  });
};
</script>