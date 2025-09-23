<template>
  <CustomerLayout title="المنتجات">
    <Head title="المنتجات" />
    
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- البحث والسلة -->
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-6">
          <div class="p-6">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
              <div class="flex-1 w-full md:w-auto">
                <input
                  v-model="search"
                  type="text"
                  placeholder="ابحث عن منتج..."
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                />
              </div>
              
              <Link
                :href="route('customer.cart')"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2 relative"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6h9M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"/>
                </svg>
                السلة
                <span v-if="$page.props.auth.user.cart_items_count > 0" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                  {{ $page.props.auth.user.cart_items_count }}
                </span>
              </Link>
            </div>
          </div>
        </div>

        <!-- رسائل التنبيه -->
        <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
          {{ $page.props.flash.success }}
        </div>

        <div v-if="$page.props.flash && $page.props.flash.error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
          {{ $page.props.flash.error }}
        </div>

        <!-- قائمة المنتجات -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <div
            v-for="product in products.data"
            :key="product.id"
            class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
          >
            <img
              :src="product.image_url || '/images/placeholder-product.jpg'"
              :alt="product.name"
              class="w-full h-48 object-cover"
            />
            <div class="p-4">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ product.name }}</h3>
              <p class="text-gray-600 dark:text-gray-300 text-sm mb-3 line-clamp-2">{{ product.description }}</p>
              <div class="flex justify-between items-center mb-3">
                <span class="text-xl font-bold text-green-600 dark:text-green-400">{{ product.price }} ر.س</span>
                <span class="text-sm" :class="product.stock > 0 ? 'text-green-600' : 'text-red-600'">
                  {{ product.stock > 0 ? `المخزون: ${product.stock}` : 'نفذ من المخزون' }}
                </span>
              </div>
              <div class="flex gap-2">
                <Link
                  :href="route('customer.products.show', product.id)"
                  class="flex-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white px-3 py-2 rounded text-center hover:bg-gray-300 dark:hover:bg-gray-600 transition"
                >
                  التفاصيل
                </Link>
                
                <!-- استخدام Form مباشرة -->
                <form @submit.prevent="addToCart(product)" class="flex-1">
                  <button
                    type="submit"
                    :disabled="product.stock === 0 || product.status !== 'active'"
                    :class="[
                      'w-full px-3 py-2 rounded text-white transition',
                      (product.stock === 0 || product.status !== 'active')
                        ? 'bg-gray-400 cursor-not-allowed'
                        : 'bg-blue-600 hover:bg-blue-700'
                    ]"
                  >
                    {{ (product.stock === 0 || product.status !== 'active') ? 'غير متوفر' : 'إضافة للسلة' }}
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- لا توجد منتجات -->
        <div v-if="products.data.length === 0" class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow">
          <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">لا توجد منتجات</h3>
          <p class="text-gray-600 dark:text-gray-400">لم يتم العثور على منتجات تطابق بحثك</p>
        </div>

        <!-- الترقيم -->
        <div class="mt-6" v-if="products.data.length > 0">
          <Pagination :links="products.links" />
        </div>
      </div>
    </div>
  </CustomerLayout>
</template>

<script setup>
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link, router, useForm, Head } from '@inertiajs/vue3'; // تأكد من استيراد Head هنا
import { ref, watch } from 'vue';

const props = defineProps({
  products: Object,
  filters: Object,
  cartCount: Number
});

const search = ref(props.filters.search || '');
const cartCount = ref(props.cartCount || 0);

// البحث في الوقت الحقيقي
watch(search, (value) => {
  router.get(route('customer.products'), { search: value }, {
    preserveState: true,
    replace: true,
  });
});

// إضافة المنتج إلى السلة
const addToCart = (product) => {
  if (product.stock === 0 || product.status !== 'active') return;

  const form = useForm({
    product_id: product.id,
    quantity: 1
  });

  form.post(route('customer.cart.add'), {
    preserveScroll: true,
    onSuccess: () => {
      // تحديث عداد السلة
      router.reload({ only: ['cartCount'] });
    },
    onError: (errors) => {
      console.error('Error adding to cart:', errors);
    }
  });
};
</script>

<style>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>