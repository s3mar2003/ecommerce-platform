<!-- resources/js/Pages/Vendor/Products/Create.vue -->
<template>
  <VendorLayout>
    <Head title="إضافة منتج جديد" />
    
    <div class="space-y-6">
      <!-- العنوان -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">إضافة منتج جديد</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1">أضف منتجاً جديداً إلى متجرك</p>
        </div>
        <Link 
          :href="route('vendor.products')" 
          class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white flex items-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          رجوع إلى المنتجات
        </Link>
      </div>

      <!-- نموذج الإضافة -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form @submit.prevent="submit" class="p-6 space-y-6" enctype="multipart/form-data">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- اسم المنتج -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                اسم المنتج *
              </label>
              <input
                v-model="form.name"
                type="text"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                :class="{ 'border-red-500': form.errors.name }"
                required
                placeholder="أدخل اسم المنتج"
              >
              <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</p>
            </div>

            <!-- السعر -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                السعر (ر.س) *
              </label>
              <input
                v-model="form.price"
                type="number"
                step="0.01"
                min="0"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                :class="{ 'border-red-500': form.errors.price }"
                required
                placeholder="0.00"
              >
              <p v-if="form.errors.price" class="text-red-600 text-sm mt-1">{{ form.errors.price }}</p>
            </div>

            <!-- المخزون -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                الكمية في المخزون *
              </label>
              <input
                v-model="form.stock"
                type="number"
                min="0"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                :class="{ 'border-red-500': form.errors.stock }"
                required
                placeholder="0"
              >
              <p v-if="form.errors.stock" class="text-red-600 text-sm mt-1">{{ form.errors.stock }}</p>
            </div>

            <!-- الحالة -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                الحالة *
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
            </div>

            <!-- صورة المنتج -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                صورة المنتج
              </label>
              <div class="flex items-center gap-4">
                <input
                  type="file"
                  @input="form.image = $event.target.files[0]"
                  accept="image/*"
                  class="hidden"
                  ref="fileInput"
                  id="image"
                >
                <label for="image" class="cursor-pointer bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                  اختر صورة
                </label>
                <span v-if="form.image" class="text-sm text-gray-600 dark:text-gray-400">
                  {{ form.image.name }}
                </span>
              </div>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">الصيغ المدعومة: JPEG, PNG, JPG, GIF - الحد الأقصى: 2MB</p>
              <p v-if="form.errors.image" class="text-red-600 text-sm mt-1">{{ form.errors.image }}</p>
              
              <!-- معاينة الصورة -->
              <div v-if="imagePreview" class="mt-4">
                <img :src="imagePreview" class="w-32 h-32 object-cover rounded-lg border" alt="معاينة الصورة">
              </div>
            </div>
          </div>

          <!-- الوصف -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              الوصف
            </label>
            <textarea
              v-model="form.description"
              rows="4"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
              :class="{ 'border-red-500': form.errors.description }"
              placeholder="وصف المنتج..."
            ></textarea>
            <p v-if="form.errors.description" class="text-red-600 text-sm mt-1">{{ form.errors.description }}</p>
          </div>

          <!-- أزرار الحفظ -->
          <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
            <Link 
              :href="route('vendor.products')" 
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
              {{ form.processing ? 'جاري الحفظ...' : 'حفظ المنتج' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </VendorLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import VendorLayout from '@/Layouts/VendorLayout.vue';

const form = useForm({
  name: '',
  description: '',
  price: '',
  stock: '',
  image: null,
  status: 'active'
});

const imagePreview = ref(null);
const fileInput = ref(null);

watch(() => form.image, (file) => {
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  } else {
    imagePreview.value = null;
  }
});

const submit = () => {
  form.post(route('vendor.products.store'), {
    onSuccess: () => {
      if (window.toast) {
        window.toast.success('تم إضافة المنتج بنجاح');
      }
    },
    onError: (errors) => {
      if (window.toast) {
        window.toast.error('حدث خطأ أثناء إضافة المنتج');
      }
    }
  });
};
</script>