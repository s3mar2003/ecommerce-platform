<!-- resources/js/Pages/Customer/Profile/Index.vue -->
<template>
  <CustomerLayout>
    <Head title="الملف الشخصي" />
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">الملف الشخصي</h1>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- الإحصائيات -->
        <div class="lg:col-span-1">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-center">
              <div class="w-20 h-20 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                  {{ user.name.charAt(0) }}
                </span>
              </div>
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ user.name }}</h2>
              <p class="text-gray-500 dark:text-gray-400">{{ user.email }}</p>
            </div>

            <div class="mt-6 space-y-4">
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">الطلبات</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ stats.orders_count }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">في السلة</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ stats.cart_items }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- تعديل الملف الشخصي -->
        <div class="lg:col-span-2">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
              <h2 class="text-lg font-medium text-gray-900 dark:text-white">تعديل المعلومات الشخصية</h2>
            </div>
            <div class="p-6">
              <form @submit.prevent="updateProfile">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الاسم الكامل</label>
                    <input 
                      type="text" 
                      v-model="form.name"
                      required
                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                    >
                    <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">البريد الإلكتروني</label>
                    <input 
                      type="email" 
                      v-model="form.email"
                      required
                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                    >
                    <p v-if="form.errors.email" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">رقم الهاتف</label>
                    <input 
                      type="tel" 
                      v-model="form.phone"
                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                    >
                    <p v-if="form.errors.phone" class="text-red-600 text-sm mt-1">{{ form.errors.phone }}</p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">المدينة</label>
                    <input 
                      type="text" 
                      v-model="form.city"
                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                    >
                    <p v-if="form.errors.city" class="text-red-600 text-sm mt-1">{{ form.errors.city }}</p>
                  </div>

                  <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">العنوان التفصيلي</label>
                    <textarea 
                      v-model="form.address"
                      rows="3"
                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                    ></textarea>
                    <p v-if="form.errors.address" class="text-red-600 text-sm mt-1">{{ form.errors.address }}</p>
                  </div>
                </div>

                <div class="mt-6 flex justify-end">
                  <button 
                    type="submit"
                    :disabled="form.processing"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 disabled:opacity-50"
                  >
                    <span v-if="form.processing">جاري الحفظ...</span>
                    <span v-else>حفظ التغييرات</span>
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- تغيير كلمة المرور -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
              <h2 class="text-lg font-medium text-gray-900 dark:text-white">تغيير كلمة المرور</h2>
            </div>
            <div class="p-6">
              <form @submit.prevent="changePassword">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">كلمة المرور الحالية</label>
                    <input 
                      type="password" 
                      v-model="passwordForm.current_password"
                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                    >
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">كلمة المرور الجديدة</label>
                    <input 
                      type="password" 
                      v-model="passwordForm.password"
                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                    >
                  </div>
                </div>
                <div class="mt-6 flex justify-end">
                  <button 
                    type="submit"
                    :disabled="passwordForm.processing"
                    class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 disabled:opacity-50"
                  >
                    تغيير كلمة المرور
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </CustomerLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
  user: Object,
  stats: Object
});

const form = useForm({
  name: props.user.name,
  email: props.user.email,
  phone: props.user.phone || '',
  city: props.user.city || '',
  address: props.user.address || ''
});

const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: ''
});

const updateProfile = () => {
  form.put(route('customer.profile.update'), {
    preserveScroll: true,
    onSuccess: () => {
      showToast('تم تحديث الملف الشخصي بنجاح', 'success');
    }
  });
};

const changePassword = () => {
  passwordForm.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => {
      passwordForm.reset();
      showToast('تم تغيير كلمة المرور بنجاح', 'success');
    },
    onError: () => {
      showToast('حدث خطأ أثناء تغيير كلمة المرور', 'error');
    }
  });
};
</script>