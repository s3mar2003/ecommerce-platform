<template>
  <AdminLayout>
    <Head :title="`تعديل البائع: ${vendor.name}`" />
    
    <div class="space-y-6">
      <!-- العنوان -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">تعديل البائع: {{ vendor.name }}</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1">قم بتعديل معلومات البائع</p>
        </div>
        <Link 
          :href="route('admin.vendors')" 
          class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white flex items-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          رجوع إلى البائعين
        </Link>
      </div>

      <!-- نموذج التعديل -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form @submit.prevent="submit" class="p-6 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- الاسم -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                الاسم الكامل *
              </label>
              <input
                v-model="form.name"
                type="text"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                :class="{ 'border-red-500': form.errors.name }"
                required
                placeholder="أدخل الاسم الكامل للبائع"
              >
              <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</p>
            </div>

            <!-- البريد الإلكتروني -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                البريد الإلكتروني *
              </label>
              <input
                v-model="form.email"
                type="email"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                :class="{ 'border-red-500': form.errors.email }"
                required
                placeholder="example@email.com"
              >
              <p v-if="form.errors.email" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</p>
            </div>

            <!-- الهاتف -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                الهاتف
              </label>
              <input
                v-model="form.phone"
                type="tel"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                :class="{ 'border-red-500': form.errors.phone }"
                placeholder="+967"
              >
              <p v-if="form.errors.phone" class="text-red-600 text-sm mt-1">{{ form.errors.phone }}</p>
            </div>

            <!-- العنوان -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                العنوان
              </label>
              <textarea
                v-model="form.address"
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                :class="{ 'border-red-500': form.errors.address }"
                placeholder="أدخل العنوان الكامل للبائع"
              ></textarea>
              <p v-if="form.errors.address" class="text-red-600 text-sm mt-1">{{ form.errors.address }}</p>
            </div>

            <!-- حالة البائع -->
            <!-- <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                حالة البائع *
              </label>
              <select
                v-model="form.status"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                :class="{ 'border-red-500': form.errors.status }"
                required
              >
                <option value="active">نشط</option>
                <option value="inactive">غير نشط</option>
              </select>
              <p v-if="form.errors.status" class="text-red-600 text-sm mt-1">{{ form.errors.status }}</p>
            </div> -->
          </div>

          <!-- أزرار الحفظ -->
          <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
            <Link 
              :href="route('admin.vendors')" 
              class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
            >
              إلغاء
            </Link>
            <button 
              type="submit" 
              :disabled="form.processing"
              class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center gap-2"
            >
              <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              {{ form.processing ? 'جاري التحديث...' : 'تحديث البائع' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  vendor: Object
});

const form = useForm({
  name: props.vendor.name,
  email: props.vendor.email,
  phone: props.vendor.phone || '',
  address: props.vendor.address || '',
  status: props.vendor.status || 'active'
});

const submit = () => {
  form.put(route('admin.vendors.update', props.vendor.id), {
    onSuccess: () => {
      if (window.showToast) {
        window.showToast('تم تحديث البائع بنجاح', 'success');
      }
    },
    onError: () => {
      if (window.showToast) {
        window.showToast('حدث خطأ أثناء تحديث البائع', 'error');
      }
    }
  });
};
</script>

<style scoped>
input:focus, textarea:focus, select:focus {
  outline: none;
  ring: 2px;
}

.border-red-500 {
  border-color: #ef4444;
}
</style>