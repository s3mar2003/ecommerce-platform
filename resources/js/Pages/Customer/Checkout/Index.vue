<template>
  <CustomerLayout title="الدفع">
    <Head title="الدفع" />
    
    <div class="py-6">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- رسائل التنبيه -->
        <!-- <div v-if="$page.props.flash.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
          {{ $page.props.flash.success }}
        </div>

        <div v-if="$page.props.flash.error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          {{ $page.props.flash.error }}
        </div> -->

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
          <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">إتمام الطلب</h1>
          </div>

          <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <!-- تفاصيل الطلب -->
              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">تفاصيل الطلب</h2>
                
                <div class="space-y-4 mb-6">
                  <div v-for="item in cart.cart_items" :key="item.id" class="flex items-center gap-3 p-3 border border-gray-200 dark:border-gray-700 rounded">
                    <img
                      :src="item.product.image_url || '/images/placeholder-product.png'"
                      :alt="item.product.name"
                      class="w-12 h-12 object-cover rounded"
                    />
                    <div class="flex-1">
                      <p class="font-medium text-gray-900 dark:text-white">{{ item.product.name }}</p>
                      <p class="text-sm text-gray-600 dark:text-gray-400">{{ item.quantity }} × {{ item.product.price }} ر.س</p>
                    </div>
                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">
                      {{ (item.product.price * item.quantity) }} ر.س
                    </span>
                  </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                  <div class="space-y-2">
                    <div class="flex justify-between items-center">
                      <span class="text-gray-600 dark:text-gray-400">المجموع الفرعي:</span>
                      <span class="font-semibold text-gray-900 dark:text-white">{{ total }} ر.س</span>
                    </div>
                    <div class="flex justify-between items-center">
                      <span class="text-gray-600 dark:text-gray-400">رسوم الشحن:</span>
                      <span class="font-semibold text-green-600 dark:text-green-400">15 ر.س</span>
                    </div>
                    <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                      <span class="text-lg font-bold text-gray-900 dark:text-white">الإجمالي:</span>
                      <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ total + 15 }} ر.س</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- معلومات الدفع -->
              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">معلومات الدفع</h2>
                
                <form @submit.prevent="placeOrder">
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        طريقة الدفع *
                      </label>
                      <select
                        v-model="form.payment_method"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                        required
                      >
                        <option value="">اختر طريقة الدفع</option>
                        <option value="cash_on_delivery">الدفع عند الاستلام</option>
                        <option value="credit_card">بطاقة ائتمان</option>
                        <option value="mada">مدى</option>
                      </select>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        عنوان التوصيل *
                      </label>
                      <textarea
                        v-model="form.shipping_address"
                        rows="3"
                        placeholder="أدخل العنوان الكامل للتوصيل"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                        required
                      ></textarea>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        ملاحظات (اختياري)
                      </label>
                      <textarea
                        v-model="form.notes"
                        rows="2"
                        placeholder="أي ملاحظات إضافية حول الطلب"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                      ></textarea>
                    </div>

                    <button
                      type="submit"
                      :disabled="form.processing"
                      class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition disabled:opacity-50 flex items-center justify-center gap-2"
                    >
                      <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                      </svg>
                      <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                      </svg>
                      {{ form.processing ? 'جاري إنشاء الطلب...' : 'تأكيد الطلب' }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </CustomerLayout>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { reactive } from 'vue';

const props = defineProps({
  cart: Object,
  total: Number,
  user: Object
});

const form = reactive({
  payment_method: 'cash_on_delivery',
  shipping_address: props.user?.address || '',
  notes: '',
  processing: false,
});

const placeOrder = () => {
  form.processing = true;
  
  router.post(route('customer.orders.place'), {
    payment_method: form.payment_method,
    shipping_address: form.shipping_address,
    notes: form.notes,
  }, {
    preserveScroll: true,
    onSuccess: () => {
      form.processing = false;
    },
    onError: (errors) => {
      form.processing = false;
      showToast('حدث خطأ أثناء إنشاء الطلب', 'error');
    },
  });
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