<template>
  <CustomerLayout title="سلة التسوق">
    
    <div class="py-6">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- رسائل التنبيه -->
        <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
          {{ $page.props.flash.success }}
        </div>

        <div v-if="$page.props.flash && $page.props.flash.error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          {{ $page.props.flash.error }}
        </div>

        <!-- التصحيح النهائي: استخدام cart.cart_items -->
        <div v-if="cart && cart.cart_items && cart.cart_items.length > 0" class="bg-white dark:bg-gray-800 rounded-lg shadow">
          <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">سلة التسوق</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">عدد العناصر: {{ cart.cart_items.length }}</p>
          </div>

          <div class="p-6">
            <!-- عناصر السلة -->
            <div class="space-y-4">
              <div
                v-for="item in cart.cart_items"
                :key="item.id"
                class="flex items-center gap-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg"
              >
                <img
                  :src="item.product?.image_url || '/images/placeholder-product.png'"
                  :alt="item.product?.name"
                  class="w-20 h-20 object-cover rounded"
                />
                
                <div class="flex-1">
                  <h3 class="font-medium text-gray-900 dark:text-white">{{ item.product?.name || 'منتج غير متوفر' }}</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400">البائع: {{ item.product?.vendor?.name || 'غير معروف' }}</p>
                  <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ item.product?.price }} ر.س</p>
                  <p class="text-sm text-gray-500">المخزون: {{ item.product?.stock }}</p>
                </div>

                <div class="flex items-center gap-2">
                  <button
                    @click="updateQuantity(item, item.quantity - 1)"
                    :disabled="item.quantity <= 1"
                    class="w-8 h-8 rounded-full border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-50"
                  >
                    -
                  </button>
                  <span class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded min-w-[40px] text-center">
                    {{ item.quantity }}
                  </span>
                  <button
                    @click="updateQuantity(item, item.quantity + 1)"
                    :disabled="item.quantity >= item.product?.stock"
                    class="w-8 h-8 rounded-full border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-50"
                  >
                    +
                  </button>
                </div>

                <div class="text-right">
                  <p class="font-semibold text-gray-900 dark:text-white">
                    {{ (item.product?.price * item.quantity) || 0 }} ر.س
                  </p>
                  <button
                    @click="removeItem(item)"
                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm mt-1"
                  >
                    إزالة
                  </button>
                </div>
              </div>
            </div>

            <!-- المجموع والدفع -->
            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="space-y-2 mb-4">
                <div class="flex justify-between items-center">
                  <span class="text-gray-600 dark:text-gray-400">المجموع الفرعي:</span>
                  <span class="text-gray-900 dark:text-white">{{ total }} ر.س</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-gray-600 dark:text-gray-400">رسوم الشحن:</span>
                  <span class="text-gray-900 dark:text-white">15 ر.س</span>
                </div>
                <div class="flex justify-between items-center border-t border-gray-200 dark:border-gray-600 pt-2">
                  <span class="text-lg font-semibold text-gray-900 dark:text-white">الإجمالي:</span>
                  <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">{{ total + 15 }} ر.س</span>
                </div>
              </div>
              
              <div class="flex gap-4">
                <button
                  @click="clearCart"
                  class="flex-1 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white px-6 py-3 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition"
                >
                  تفريغ السلة
                </button>
                
                <Link
                  :href="route('customer.checkout')"
                  class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition text-center flex items-center justify-center gap-2"
                >
                  اتمام الشراء
                </Link>
              </div>
            </div>
          </div>
        </div>

        <!-- السلة فارغة -->
        <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow">
          <div class="p-12 text-center">
            <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6h9M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"/>
            </svg>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">سلة التسوق فارغة</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">ابدأ بإضافة بعض المنتجات إلى سلة التسوق الخاصة بك</p>
            <Link
              :href="route('customer.products')"
              class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition inline-flex items-center gap-2"
            >
              تصفح المنتجات
            </Link>
          </div>
        </div>
      </div>
    </div>
  </CustomerLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { onMounted } from 'vue';

const props = defineProps({
  cart: Object,
  total: Number,
});

onMounted(() => {
  console.log('=== DEBUG CART DATA ===');
  console.log('Full Cart Object:', props.cart);
  console.log('Cart Items Array:', props.cart?.cart_items);
  console.log('Cart Items Count:', props.cart?.cart_items?.length);
  console.log('Total Amount:', props.total);
  console.log('=== END DEBUG ===');
});

const updateQuantity = (item, newQuantity) => {
  if (newQuantity < 1 || newQuantity > item.product?.stock) return;

  router.patch(route('customer.cart.update', item.id), {
    quantity: newQuantity
  }, {
    preserveScroll: true,
  });
};

const removeItem = (item) => {
  if (!confirm('هل أنت متأكد من إزالة هذا المنتج من السلة؟')) return;

  router.delete(route('customer.cart.remove', item.id), {
    preserveScroll: true,
  });
};

const clearCart = () => {
  if (!confirm('هل أنت متأكد من تفريغ السلة بالكامل؟')) return;

  router.delete(route('customer.cart.clear'), {
    preserveScroll: true,
  });
};
</script>