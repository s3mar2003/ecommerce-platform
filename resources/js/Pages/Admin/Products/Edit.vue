<template>
  <AdminLayout>
    <Head title="تعديل المنتج" />
    
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">تعديل المنتج: {{ product.name }}</h1>
          <Link :href="route('admin.products')" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            رجوع
          </Link>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <form @submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- البائع -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">البائع</label>
                <select
                  v-model="form.vendor_id"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                  required
                >
                  <option v-for="vendor in vendors" :key="vendor.id" :value="vendor.id">
                    {{ vendor.name }}
                  </option>
                </select>
                <div v-if="form.errors.vendor_id" class="text-red-600 text-sm mt-1">{{ form.errors.vendor_id }}</div>
              </div>

              <!-- اسم المنتج -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">اسم المنتج</label>
                <input
                  v-model="form.name"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                  required
                >
                <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</div>
              </div>

              <!-- السعر -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">السعر (ر.س)</label>
                <input
                  v-model="form.price"
                  type="number"
                  step="0.01"
                  min="0"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                  required
                >
                <div v-if="form.errors.price" class="text-red-600 text-sm mt-1">{{ form.errors.price }}</div>
              </div>

              <!-- المخزون -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الكمية في المخزون</label>
                <input
                  v-model="form.stock"
                  type="number"
                  min="0"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                  required
                >
                <div v-if="form.errors.stock" class="text-red-600 text-sm mt-1">{{ form.errors.stock }}</div>
              </div>

              <!-- الحالة -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الحالة</label>
                <select
                  v-model="form.status"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                  required
                >
                  <option value="active">نشط</option>
                  <option value="inactive">غير نشط</option>
                </select>
                <div v-if="form.errors.status" class="text-red-600 text-sm mt-1">{{ form.errors.status }}</div>
              </div>

              <!-- صورة المنتج -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">رابط الصورة</label>
                <input
                  v-model="form.image_url"
                  type="url"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                  placeholder="https://example.com/image.jpg"
                >
                <div v-if="form.errors.image_url" class="text-red-600 text-sm mt-1">{{ form.errors.image_url }}</div>
              </div>
            </div>

            <!-- الوصف -->
            <div class="mt-6">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الوصف</label>
              <textarea
                v-model="form.description"
                rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="وصف المنتج..."
              ></textarea>
              <div v-if="form.errors.description" class="text-red-600 text-sm mt-1">{{ form.errors.description }}</div>
            </div>

            <!-- زر الحفظ -->
            <div class="mt-6">
              <button
                type="submit"
                :disabled="form.processing"
                class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition disabled:opacity-50"
              >
                {{ form.processing ? 'جاري التحديث...' : 'تحديث المنتج' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  product: Object,
  vendors: Array
});

const form = useForm({
  vendor_id: props.product.vendor_id,
  name: props.product.name,
  description: props.product.description,
  price: props.product.price,
  stock: props.product.stock,
  image_url: props.product.image_url,
  status: props.product.status
});

const submit = () => {
  form.put(route('admin.products.update', props.product.id), {
    onSuccess: () => {
      if (window.toast) {
        window.toast.success('تم تحديث المنتج بنجاح');
      }
    },
    onError: () => {
      if (window.toast) {
        window.toast.error('حدث خطأ أثناء التحديث');
      }
    }
  });
};
</script>