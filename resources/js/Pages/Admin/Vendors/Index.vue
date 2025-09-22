<template>
  <AdminLayout>
    <Head title="إدارة البائعين" />
    
    <div class="space-y-6">
      <!-- العنوان وأزرار العمل -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">إدارة البائعين</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1">إدارة حسابات البائعين في المنصة</p>
        </div>
        <Link 
          :href="route('admin.vendors.create')" 
          class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          إضافة بائع جديد
        </Link>
      </div>

      <!-- البحث والتصفية -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">بحث</label>
            <input
              v-model="search"
              type="text"
              placeholder="ابحث بالاسم أو البريد..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
              @input="debouncedSearch"
            >
          </div>
          <div>
            <!-- <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الحالة</label> -->
            <!-- <select
              v-model="statusFilter"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
              @change="filterVendors"
            >
              <option value="">جميع الحالات</option>
              <option value="active">نشط</option>
              <option value="inactive">غير نشط</option>
            </select> -->
          </div>
        </div>
      </div>

      <!-- جدول البائعين -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full min-w-full">
            <thead>
              <tr class="bg-gray-50 dark:bg-gray-700">
                <th class="px-6 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-300">البائع</th>
                <th class="px-6 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-300 hidden md:table-cell">البريد</th>
                <th class="px-6 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-300">المنتجات</th>
                <!-- <th class="px-6 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-300">الحالة</th> -->
                <th class="px-6 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-300">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              <tr 
                v-for="vendor in vendors.data" 
                :key="vendor.id" 
                class="border-t border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
              >
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                      <span class="text-indigo-600 dark:text-indigo-400 font-medium">
                        {{ vendor.name.charAt(0) }}
                      </span>
                    </div>
                    <div class="mr-4">
                      <div class="font-medium text-gray-900 dark:text-white">{{ vendor.name }}</div>
                      <div class="text-sm text-gray-500 dark:text-gray-400 md:hidden">{{ vendor.email }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 hidden md:table-cell">
                  <div class="text-gray-900 dark:text-white">{{ vendor.email }}</div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">{{ vendor.phone || 'لا يوجد' }}</div>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                    {{ vendor.products_count }} منتج
                  </span>
                </td>
                <!-- <td class="px-6 py-4">
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input 
                      type="checkbox" 
                      :checked="vendor.status === 'active'"
                      @change="toggleVendorStatus(vendor)"
                      class="sr-only peer"
                    >
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                      {{ vendor.status === 'active' ? 'نشط' : 'غير نشط' }}
                    </span>
                  </label>
                </td> -->
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <Link 
                      :href="route('admin.vendors.edit', vendor.id)" 
                      class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
                      title="تعديل"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                    </Link>
                    
                    <button 
  @click="deleteVendor(vendor)" 
  class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors"
  title="حذف"
  :disabled="vendor.products_count > 0"
>
  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
  </svg>
</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- حالة عدم وجود بائعين -->
        <div v-if="vendors.data.length === 0" class="text-center py-12">
          <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
          <p class="text-gray-500 dark:text-gray-400 text-lg">لا يوجد بائعين</p>
          <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">ابدأ بإضافة أول بائع إلى المنصة</p>
        </div>
      </div>

      <!-- التصفح -->
      <Pagination :links="vendors.links" />
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
  vendors: Object,
  filters: Object
});

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');

// البحث بانتظار الكتابة - إصلاح الدالة
const debouncedSearch = debounce(() => {
  router.get(route('admin.vendors'), {
    search: search.value,
    status: statusFilter.value
  }, {
    preserveState: true,
    replace: true,
    preserveScroll: true
  });
}, 500);

// التصفية بالحالة
const filterVendors = () => {
  router.get(route('admin.vendors'), {
    search: search.value,
    status: statusFilter.value
  }, {
    preserveState: true,
    replace: true,
    preserveScroll: true
  });
};

// مراقبة البحث
watch(search, () => {
  debouncedSearch();
});

// إعادة تعيين البحث
const resetSearch = () => {
  search.value = '';
  statusFilter.value = '';
  router.get(route('admin.vendors'), {}, {
    preserveState: true,
    replace: true,
    preserveScroll: true
  });
};
const deleteVendor = (vendor) => {
  if (confirm(`هل أنت متأكد من حذف البائع "${vendor.name}"؟`)) {
    router.delete(route('admin.vendors.delete', vendor.id), {
      preserveScroll: true,
      onSuccess: () => {
        showToast('تم حذف البائع بنجاح', 'success');
      },
      onError: () => {
        showToast('حدث خطأ أثناء الحذف', 'error');
      }
    });
  }
};
</script>

<style scoped>

</style>