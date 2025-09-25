<template>
  <CustomerLayout title="إتمام الطلب">
    <div class="py-6">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- رسائل التنبيه -->
        <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
          {{ $page.props.flash.success }}
        </div>

        <div v-if="$page.props.flash && $page.props.flash.error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          {{ $page.props.flash.error }}
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
          <!-- عنوان الصفحة -->
          <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">إتمام الطلب</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">أكمل معلومات الشحن والدفع</p>
          </div>

          <div class="p-6">
            <form @submit.prevent="submitOrder">
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- معلومات الشحن -->
                <div>
                  <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">معلومات الشحن</h2>
                  
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الاسم الكامل</label>
                      <input 
                        v-model="form.name"
                        type="text" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required
                      >
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">رقم الهاتف</label>
                      <input 
                        v-model="form.phone"
                        type="tel" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required
                      >
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">عنوان الشحن</label>
                      <textarea 
                        v-model="form.shipping_address"
                        rows="3" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="الشارع، المدينة، الرمز البريدي"
                        required
                      ></textarea>
                    </div>
                  </div>
                </div>

                <!-- ملخص الطلب وطرق الدفع -->
                <div>
                  <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">ملخص الطلب</h2>
                  
                  <!-- عناصر الطلب -->
                  <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                    <div v-for="item in cart.cart_items" :key="item.id" class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-600 last:border-b-0">
                      <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <img :src="item.product.image_url" :alt="item.product.name" class="w-10 h-10 object-cover rounded">
                        <div>
                          <p class="text-sm font-medium text-gray-900 dark:text-white">{{ item.product.name }}</p>
                          <p class="text-xs text-gray-500 dark:text-gray-400">الكمية: {{ item.quantity }}</p>
                        </div>
                      </div>
                      <p class="text-sm font-medium text-gray-900 dark:text-white">{{ formatCurrency(item.product.price * item.quantity) }}</p>
                    </div>
                  </div>

                  <!-- المجموع -->
                  <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                    <div class="space-y-2">
                      <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">المجموع الفرعي:</span>
                        <span class="text-gray-900 dark:text-white">{{ formatCurrency(subtotal) }}</span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">رسوم الشحن:</span>
                        <span class="text-gray-900 dark:text-white">{{ formatCurrency(shippingFee) }}</span>
                      </div>
                      <div class="flex justify-between border-t border-gray-200 dark:border-gray-600 pt-2">
                        <span class="font-semibold text-gray-900 dark:text-white">الإجمالي:</span>
                        <span class="font-bold text-lg text-indigo-600 dark:text-indigo-400">{{ formatCurrency(total) }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- طرق الدفع -->
                  <div class="mb-6">
                    <h3 class="text-md font-semibold text-gray-900 dark:text-white mb-3">طريقة الدفع</h3>
                    <div class="space-y-3">
                      <label v-for="method in paymentMethods" :key="method.value" class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700">
                        <input 
                          v-model="form.payment_method" 
                          :value="method.value" 
                          type="radio" 
                          class="text-indigo-600 focus:ring-indigo-500"
                          required
                        >
                        <div class="mr-3">
                          <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ method.label }}</span>
                          <span class="block text-xs text-gray-500 dark:text-gray-400">{{ method.description }}</span>
                        </div>
                      </label>
                    </div>
                  </div>

                  <!-- زر التأكيد -->
                  <button 
                    type="submit" 
                    :disabled="form.processing"
                    class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition disabled:opacity-50 disabled:cursor-not-allowed font-semibold"
                  >
                    <span v-if="form.processing">جاري إنشاء الطلب...</span>
                    <span v-else>تأكيد الطلب والدفع</span>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </CustomerLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
  cart: Object,
  subtotal: Number,
  shippingFee: Number,
  total: Number,
  user: Object
});

const form = useForm({
  name: props.user.name,
  phone: props.user.phone || '',
  shipping_address: props.user.address || '',
  payment_method: 'cash_on_delivery'
});

const paymentMethods = [
  {
    value: 'cash_on_delivery',
    label: 'الدفع عند الاستلام',
    description: 'ادفع عند استلام الطلب'
  },
  {
    value: 'credit_card',
    label: 'بطاقة ائتمان',
    description: 'دفع آمن عبر البطاقات'
  },
  {
    value: 'paypal',
    label: 'PayPal',
    description: 'الدفع عبر PayPal'
  },
  {
    value: 'bank_transfer',
    label: 'تحويل بنكي',
    description: 'التحويل المباشر للبنك'
  }
];

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('ar-SA', {
    style: 'currency',
    currency: 'SAR',
    minimumFractionDigits: 0
  }).format(amount);
};

const submitOrder = () => {
  form.post(route('customer.orders.place'), {
    preserveScroll: true,
    onSuccess: () => {
      console.log('تم إنشاء الطلب بنجاح');
    },
    onError: (errors) => {
      console.error('خطأ في إنشاء الطلب:', errors);
    }
  });
};
</script>

<style scoped>
input[type="radio"] {
  margin-left: 8px;
}
</style>