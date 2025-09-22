<template>
  <CustomerLayout>
    <Head title="المنتجات" />
    
    <div class="space-y-6">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">المنتجات</h1>
          <p class="text-gray-600 mt-1">استكشف مجموعتنا من المنتجات</p>
        </div>
        <div class="relative">
          <input
            v-model="search"
            type="text"
            placeholder="ابحث عن منتج..."
            class="w-full sm:w-64 px-4 py-2 border border-gray-300 rounded-lg"
          >
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div v-for="product in products.data" :key="product.id" class="bg-white rounded-lg shadow">
          <img 
            :src="product.image_url || '/images/placeholder-product.png'" 
            :alt="product.name"
            class="w-full h-48 object-cover rounded-t-lg"
          >
          <div class="p-4">
            <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ product.name }}</h3>
            <p class="text-gray-600 text-sm mb-3">{{ product.description }}</p>
            <div class="flex items-center justify-between mb-3">
              <span class="text-2xl font-bold text-indigo-600">{{ formatCurrency(product.price) }}</span>
              <span class="text-sm text-gray-500">المخزون: {{ product.stock }}</span>
            </div>
            <div class="flex items-center justify-between">
              <Link 
                :href="route('customer.products.show', product.id)" 
                class="flex-1 mr-2 bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 text-center"
              >
                عرض التفاصيل
              </Link>
              <button 
                @click="addToCart(product)"
                :disabled="product.stock === 0 || product.status !== 'active'"
                class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 disabled:opacity-50"
              >
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="products.data.length === 0" class="text-center py-12">
        <p class="text-gray-500 text-lg">لا توجد منتجات</p>
      </div>

      <div class="flex justify-center">
        <div v-for="link in products.links" :key="link.label">
          <Link :href="link.url || '#'" class="px-3 py-2 border border-gray-300 mx-1 rounded" :class="{
            'bg-indigo-600 text-white': link.active,
            'text-gray-700': !link.active
          }">
            {{ link.label }}
          </Link>
        </div>
      </div>
    </div>
  </CustomerLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
  products: Object,
  filters: Object
});

const search = ref(props.filters.search || '');

watch(search, (value) => {
  router.get(route('customer.products'), {
    search: value
  }, {
    preserveState: true
  });
});

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('ar-SA', {
    style: 'currency',
    currency: 'SAR'
  }).format(amount);
};

const addToCart = (product) => {
  if (product.stock === 0 || product.status !== 'active') {
    return;
  }

  router.post(route('customer.cart.add'), {
    product_id: product.id,
    quantity: 1
  }, {
    preserveScroll: true,
    onSuccess: () => {
      showToast('تمت إضافة المنتج إلى السلة', 'success');
    }
  });
};
</script>