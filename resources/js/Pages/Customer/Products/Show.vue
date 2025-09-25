<!-- resources/js/Pages/Customer/Products/Show.vue -->
<template>
  <CustomerLayout>
    <Head :title="product.name" />
    
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- مسار التنقل -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
          <ol class="flex items-center space-x-2 rtl:space-x-reverse">
            <li>
              <Link :href="route('customer.products')" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                المنتجات
              </Link>
            </li>
            <li>
              <span class="text-gray-400 mx-2">/</span>
            </li>
            <li>
              <span class="text-gray-900 dark:text-white">{{ product.name }}</span>
            </li>
          </ol>
        </nav>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
          <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <!-- صورة المنتج -->
              <div>
                <img
                  :src="product.image_url || '/images/placeholder-product.png'"
                  :alt="product.name"
                  class="w-full h-96 object-cover rounded-lg"
                />
              </div>

              <!-- معلومات المنتج -->
              <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ product.name }}</h1>
                
                <div class="flex items-center mb-4">
                  <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ formatCurrency(product.price) }}</span>
                  <span v-if="product.stock > 0" class="mr-4 text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900 px-2 py-1 rounded">
                    متوفر
                  </span>
                  <span v-else class="mr-4 text-sm text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900 px-2 py-1 rounded">
                    غير متوفر
                  </span>
                </div>

                <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">{{ product.description || 'لا يوجد وصف للمنتج' }}</p>

                <!-- معلومات إضافية -->
                <div class="space-y-3 mb-6">
                  <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-gray-600 dark:text-gray-400">البائع:</span>
                    <span class="mr-2 font-medium text-gray-900 dark:text-white">{{ product.vendor.name }}</span>
                  </div>

                  <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                    <span class="text-gray-600 dark:text-gray-400">المخزون:</span>
                    <span class="mr-2 font-medium" :class="product.stock > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                      {{ product.stock }} قطعة
                    </span>
                  </div>
                </div>

                <!-- إضافة إلى السلة -->
                <div v-if="product.status === 'active' && product.stock > 0" class="space-y-4">
                  <div class="flex items-center">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 ml-4">الكمية:</label>
                    <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded">
                      <button
                        @click="decrementQuantity"
                        :disabled="quantity <= 1"
                        class="px-3 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-50"
                      >
                        -
                      </button>
                      <span class="px-4 py-2 text-gray-900 dark:text-white">{{ quantity }}</span>
                      <button
                        @click="incrementQuantity"
                        :disabled="quantity >= product.stock"
                        class="px-3 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-50"
                      >
                        +
                      </button>
                    </div>
                  </div>

                  <button
                    @click="addToCart"
                    class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition flex items-center justify-center gap-2"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6h9M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"/>
                    </svg>
                    إضافة إلى السلة
                  </button>
                </div>

                <div v-else class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 text-center">
                  <p class="text-gray-600 dark:text-gray-300">هذا المنتج غير متاح حاليًا</p>
                </div>
              </div>
            </div>

            <!-- المنتجات المشابهة -->
            <div v-if="relatedProducts.length > 0" class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">منتجات مشابهة</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                  v-for="relatedProduct in relatedProducts"
                  :key="relatedProduct.id"
                  class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow"
                >
                  <Link :href="route('customer.products.show', relatedProduct.id)">
                    <img
                      :src="relatedProduct.image_url || '/images/placeholder-product.png'"
                      :alt="relatedProduct.name"
                      class="w-full h-32 object-cover rounded mb-3"
                    />
                    <h3 class="font-medium text-gray-900 dark:text-white mb-1">{{ relatedProduct.name }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-2">{{ relatedProduct.description }}</p>
                    <div class="flex items-center justify-between">
                      <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ formatCurrency(relatedProduct.price) }}</span>
                      <span class="text-xs" :class="relatedProduct.stock > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                        {{ relatedProduct.stock > 0 ? 'متوفر' : 'غير متوفر' }}
                      </span>
                    </div>
                  </Link>
                </div>
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
import { ref, inject } from 'vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
  product: Object,
  relatedProducts: Array,
});

const quantity = ref(1);
const addToast = inject('addToast');

const incrementQuantity = () => {
  if (quantity.value < props.product.stock) {
    quantity.value++;
  }
};

const decrementQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--;
  }
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('ar-SA', {
    style: 'currency',
    currency: 'SAR'
  }).format(amount);
};

const addToCart = () => {
  router.post(route('customer.cart.add'), {
    product_id: props.product.id,
    quantity: quantity.value
  }, {
    preserveScroll: true,
    onSuccess: () => {
      quantity.value = 1;
      addToast(`تم إضافة "${props.product.name}" إلى السلة`, 'success');
    },
    onError: (errors) => {
      addToast(errors.message || 'حدث خطأ أثناء الإضافة', 'error');
    }
  });
};
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>