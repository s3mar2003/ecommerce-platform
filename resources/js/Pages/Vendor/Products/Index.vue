<template>
  <VendorLayout>
    <Head title="منتجاتي" />
    
    <div class="space-y-6">
      <!-- العنوان وأزرار العمل -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">منتجاتي</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1">إدارة منتجات متجرك</p>
        </div>
        <Link 
          :href="route('vendor.products.create')" 
          class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2 text-sm sm:text-base"
        >
          <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          إضافة منتج جديد
        </Link>
      </div>

      <!-- البحث والتصفية -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">بحث</label>
            <input
              v-model="search"
              type="text"
              placeholder="ابحث باسم المنتج..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
              @input="handleSearch"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الحالة</label>
            <select
              v-model="statusFilter"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
              @change="filterProducts"
            >
              <option value="all">جميع الحالات</option>
              <option value="active">نشط</option>
              <option value="inactive">غير نشط</option>
            </select>
          </div>
        </div>
      </div>

      <!-- جدول المنتجات -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full min-w-full">
            <thead>
              <tr class="bg-gray-50 dark:bg-gray-700">
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300">المنتج</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 hidden sm:table-cell">السعر</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300">المخزون</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 hidden md:table-cell">الحالة</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <tr 
                v-for="product in products.data" 
                :key="product.id" 
                class="border-t border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
              >
 <td class="px-4 py-4">
  <div class="flex items-center">
    <div v-if="hasImage(product)" class="flex-shrink-0">
      <img 
        :src="product.image_url" 
        :alt="product.name"
        class="w-8 h-8 sm:w-10 sm:h-10 rounded object-cover ml-2 sm:ml-3"
        @error="handleImageError"
      >
    </div>
    
    <div 
      v-else
      class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center ml-2 sm:ml-3"
    >
      <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
      </svg>
    </div>
    
    <div class="mr-2 sm:mr-3">
      <div class="text-sm font-medium text-gray-900 dark:text-white">{{ product.name }}</div>
      <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 hidden sm:block">{{ product.description }}</div>
    </div>
  </div>
</td>
                <td class="px-4 py-4 hidden sm:table-cell">
                  <div class="text-gray-900 dark:text-white">{{ formatCurrency(product.price) }}</div>
                </td>
                <td class="px-4 py-4">
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" 
                    :class="product.stock > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'">
                    {{ product.stock }}
                  </span>
                </td>
                <td class="px-4 py-4 hidden md:table-cell">
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" 
                    :class="product.status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'">
                    {{ product.status === 'active' ? 'نشط' : 'غير نشط' }}
                  </span>
                </td>
                <td class="px-4 py-4">
                  <div class="flex items-center gap-2 sm:gap-3">
                    <Link 
                      :href="route('vendor.products.edit', product.id)" 
                      class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors p-1"
                      title="تعديل"
                    >
                      <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                    </Link>
                    
                    <button 
                      @click="deleteProduct(product)" 
                      class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors p-1"
                      title="حذف"
                    >
                      <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- حالة عدم وجود منتجات -->
        <div v-if="products.data.length === 0" class="text-center py-12">
          <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
          <p class="text-gray-500 dark:text-gray-400 text-lg">لا يوجد منتجات</p>
          <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">ابدأ بإضافة أول منتج إلى متجرك</p>
          <Link 
            :href="route('vendor.products.create')" 
            class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm"
          >
            إضافة منتج جديد
          </Link>
        </div>
      </div>

      <!-- التصفح -->
      <Pagination :links="products.links" />
    </div>
  </VendorLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import VendorLayout from '@/Layouts/VendorLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  products: Object,
  filters: Object
});

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');

const debouncedSearch = debounce(() => {
  router.get(route('vendor.products'), {
    search: search.value,
    status: statusFilter.value
  }, {
    preserveState: true,
    replace: true
  });
}, 500);

const handleSearch = () => {
  debouncedSearch();
};

const filterProducts = () => {
  router.get(route('vendor.products'), {
    search: search.value,
    status: statusFilter.value
  }, {
    preserveState: true,
    replace: true,
    preserveScroll: true
  });
};

const hasImage = (product) => {
  return product.image_url && 
         product.image_url !== 'null' && 
         product.image_url !== 'undefined' &&
         product.image_url !== '' &&
         product.image_url !== null;
};

const handleImageError = (event) => {
  event.target.style.display = 'none';
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('ar-SA', {
    style: 'currency',
    currency: 'SAR',
    minimumFractionDigits: 0
  }).format(amount);
};

const deleteProduct = (product) => {
  if (confirm(`هل أنت متأكد من حذف المنتج "${product.name}"؟`)) {
    router.delete(route('vendor.products.delete', product.id), {
      preserveScroll: true,
      onSuccess: () => {
        if (window.toast) {
          window.toast.success('تم حذف المنتج بنجاح');
        }
      },
      onError: () => {
        if (window.toast) {
          window.toast.error('حدث خطأ أثناء الحذف');
        }
      }
    });
  }
};
// مراقبة التغييرات
watch([search, statusFilter], debounce(() => {
  filterProducts();
}, 500));
</script>

<style scoped>
/* تحسين التصميم للشاشات الصغيرة */
@media (max-width: 640px) {
  .line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
}
</style>