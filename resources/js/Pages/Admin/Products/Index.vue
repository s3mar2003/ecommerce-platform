<template>
  <AdminLayout>
    <Head title="إدارة المنتجات" />
    
    <div class="space-y-4 sm:space-y-6">
      <!-- العنوان وأزرار العمل -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">إدارة المنتجات</h1>
        <Link :href="route('admin.products.create')" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm sm:text-base text-center">
          إضافة منتج جديد
        </Link>
      </div>

      <!-- البحث والتصفية -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <form @submit.prevent="filterProducts" class="grid grid-cols-1 md:grid-cols-3 gap-3">
          <input
            v-model="filters.search"
            type="text"
            placeholder="ابحث باسم المنتج..."
            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            @input="handleSearch"
          >
          <select 
            v-model="filters.status" 
            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            @change="filterProducts"
          >
            <option value="">جميع الحالات</option>
            <option value="active">نشط</option>
            <option value="inactive">غير نشط</option>
          </select>
          <select 
            v-model="filters.vendor" 
            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            @change="filterProducts"
          >
            <option value="">جميع البائعين</option>
            <option v-for="vendor in vendors" :key="vendor.id" :value="vendor.id">{{ vendor.name }}</option>
          </select>
        </form>
      </div>

      <!-- جدول المنتجات -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full min-w-full">
            <thead>
              <tr class="bg-gray-50 dark:bg-gray-700">
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300">المنتج</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 hidden md:table-cell">البائع</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300">السعر</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 hidden sm:table-cell">المخزون</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300">الحالة</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in products.data" :key="product.id" class="border-t border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-4 py-4">
                  <div class="flex items-center">
                    <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="w-10 h-10 rounded object-cover ml-3">
                    <div>
                      <div class="font-medium text-gray-900 dark:text-white">{{ product.name }}</div>
                      <div class="text-sm text-gray-500 dark:text-gray-400 sm:hidden">{{ product.vendor.name }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-4 py-4 hidden md:table-cell">{{ product.vendor.name }}</td>
                <td class="px-4 py-4">{{ product.price }} ر.س</td>
                <td class="px-4 py-4 hidden sm:table-cell">{{ product.stock }}</td>
                <td class="px-4 py-4">
                  <span :class="product.status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'" class="px-2 py-1 rounded-full text-xs">
                    {{ product.status === 'active' ? 'نشط' : 'غير نشط' }}
                  </span>
                </td>
                <td class="px-4 py-4">
                  <div class="flex space-x-2">
                    <Link :href="route('admin.products.edit', product.id)" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                      تعديل
                    </Link>
                    <button @click="deleteProduct(product)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                      حذف
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="products.data.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
          لا يوجد منتجات
        </div>
      </div>

      <!-- التصفح -->
      <Pagination :links="products.links" />
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  products: Object,
  vendors: Array,
  filters: Object
});

const filters = ref({
  search: props.filters.search || '',
  status: props.filters.status || '',
  vendor: props.filters.vendor || ''
});

const debouncedSearch = debounce(() => {
  filterProducts();
}, 500);

const handleSearch = () => {
  debouncedSearch();
};

const filterProducts = () => {
  router.get(route('admin.products'), filters.value, {
    preserveState: true,
    replace: true
  });
};

const deleteProduct = (product) => {
  if (confirm(`هل أنت متأكد من حذف المنتج "${product.name}"؟`)) {
    router.delete(route('admin.products.delete', product.id), {
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

watch(filters, () => {
  filterProducts();
}, { deep: true });
</script>