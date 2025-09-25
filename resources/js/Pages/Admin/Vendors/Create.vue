<template>
  <AdminLayout>
    <Head title="إضافة بائع جديد" />
    
    <div class="space-y-6">
      <!-- العنوان -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">إضافة بائع جديد</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1">أضف بائعاً جديداً إلى المنصة</p>
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

      <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form @submit.prevent="submit" class="p-6 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- الاسم الرباعي -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                الاسم الرباعي *
              </label>
              <input
                v-model="form.name"
                type="text"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                :class="{ 'border-red-500': form.errors.name || nameError }"
                required
                placeholder="أدخل الاسم الرباعي الكامل"
                @blur="validateName"
              >
              <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</p>
              <p v-if="nameError" class="text-red-600 text-sm mt-1">{{ nameError }}</p>
              <p v-else class="text-gray-500 text-sm mt-1">يجب إدخال الاسم الرباعي الكامل (4 كلمات على الأقل)</p>
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
                :class="{ 'border-red-500': form.errors.email || emailError }"
                required
                placeholder="example@email.com"
                @blur="validateEmail"
              >
              <p v-if="form.errors.email" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</p>
              <p v-if="emailError" class="text-red-600 text-sm mt-1">{{ emailError }}</p>
            </div>

            <!-- كلمة المرور -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                كلمة المرور *
              </label>
              <input
                v-model="form.password"
                type="password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                :class="{ 'border-red-500': form.errors.password || passwordError }"
                required
                placeholder="أدخل كلمة مرور قوية"
                @blur="validatePassword"
              >
              <p v-if="form.errors.password" class="text-red-600 text-sm mt-1">{{ form.errors.password }}</p>
              <p v-if="passwordError" class="text-red-600 text-sm mt-1">{{ passwordError }}</p>
              <p v-else class="text-gray-500 text-sm mt-1">كلمة المرور يجب أن تحتوي على 6 أحرف على الأقل</p>
            </div>

            <!-- تأكيد كلمة المرور -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                تأكيد كلمة المرور *
              </label>
              <input
                v-model="form.password_confirmation"
                type="password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                :class="{ 'border-red-500': form.errors.password_confirmation || passwordConfirmationError }"
                required
                placeholder="أعد إدخال كلمة المرور"
                @blur="validatePasswordConfirmation"
              >
              <p v-if="form.errors.password_confirmation" class="text-red-600 text-sm mt-1">{{ form.errors.password_confirmation }}</p>
              <p v-if="passwordConfirmationError" class="text-red-600 text-sm mt-1">{{ passwordConfirmationError }}</p>
            </div>

            <!-- الهاتف -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                رقم الهاتف *
              </label>
              <input
                v-model="form.phone"
                type="tel"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                :class="{ 'border-red-500': form.errors.phone || phoneError }"
                required
                placeholder="771234567"
                @blur="validatePhone"
              >
              <p v-if="form.errors.phone" class="text-red-600 text-sm mt-1">{{ form.errors.phone }}</p>
              <p v-if="phoneError" class="text-red-600 text-sm mt-1">{{ phoneError }}</p>
              <p v-else class="text-gray-500 text-sm mt-1">رقم الهاتف يجب أن يكون 8 أرقام على الأقل</p>
            </div>

            <!-- العنوان -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                العنوان *
              </label>
              <textarea
                v-model="form.address"
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                :class="{ 'border-red-500': form.errors.address || addressError }"
                required
                placeholder="أدخل العنوان الكامل للبائع"
                @blur="validateAddress"
              ></textarea>
              <p v-if="form.errors.address" class="text-red-600 text-sm mt-1">{{ form.errors.address }}</p>
              <p v-if="addressError" class="text-red-600 text-sm mt-1">{{ addressError }}</p>
              <p v-else class="text-gray-500 text-sm mt-1">العنوان مطلوب ويجب أن يكون تفصيلياً</p>
            </div>
          </div>

          <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
            <Link 
              :href="route('admin.vendors')" 
              class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
            >
              إلغاء
            </Link>
            <button 
              type="submit" 
              :disabled="form.processing || !isFormValid"
              class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center gap-2"
            >
              <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              {{ form.processing ? 'جاري الحفظ...' : 'حفظ البائع' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue'; // تم تصحيح الاستيراد هنا
import AdminLayout from '@/Layouts/AdminLayout.vue';

// تعريف الـ refs للأخطاء
const nameError = ref('');
const emailError = ref('');
const passwordError = ref('');
const passwordConfirmationError = ref('');
const phoneError = ref('');
const addressError = ref('');

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  phone: '',
  address: '',
});

// التحقق من صحة النموذج
const isFormValid = computed(() => {
  return form.name && 
         form.email && 
         form.password && 
         form.password_confirmation && 
         form.phone && 
         form.address &&
         !nameError.value &&
         !emailError.value &&
         !passwordError.value &&
         !passwordConfirmationError.value &&
         !phoneError.value &&
         !addressError.value;
});

// دوال التحقق من الصحة
const validateName = () => {
  const nameWords = form.name.trim().split(/\s+/).filter(word => word.length > 0);
  if (nameWords.length < 4) {
    nameError.value = 'الاسم يجب أن يكون رباعي';
  } else {
    nameError.value = '';
  }
};

const validateEmail = () => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(form.email)) {
    emailError.value = 'البريد الإلكتروني غير صحيح';
  } else {
    emailError.value = '';
  }
};

const validatePassword = () => {
  if (form.password.length < 8) {
    passwordError.value = 'كلمة المرور يجب أن تكون 8 أحرف على الأقل';
  } else {
    passwordError.value = '';
  }
};

const validatePasswordConfirmation = () => {
  if (form.password !== form.password_confirmation) {
    passwordConfirmationError.value = 'كلمة المرور غير متطابقة';
  } else {
    passwordConfirmationError.value = '';
  }
};

const validatePhone = () => {
  const phoneRegex = /^[0-9]{8,}$/;
  if (!phoneRegex.test(form.phone.replace(/\s/g, ''))) {
    phoneError.value = 'رقم الهاتف يجب أن يكون 8 أرقام على الأقل';
  } else {
    phoneError.value = '';
  }
};

const validateAddress = () => {
  if (form.address.trim().length < 10) {
    addressError.value = 'العنوان يجب أن يكون تفصيلياً)';
  } else {
    addressError.value = '';
  }
};

const submit = () => {
  // التحقق من الصحة أولاً
  if (!isFormValid.value) {
    window.toast.error('يرجى تصحيح الأخطاء في النموذج قبل الحفظ');
    return;
  }

  form.post(route('admin.vendors.store'), {
    onSuccess: () => {
      window.toast.success('تم إضافة البائع بنجاح');
    },
    onError: () => {
      window.toast.error('حدث خطأ أثناء إضافة البائع');
    }
  });
};
</script>

<style scoped>
input:focus, textarea:focus {
  outline: none;
  ring: 2px;
}

.border-red-500 {
  border-color: #ef4444;
}

button:disabled {
  cursor: not-allowed;
  opacity: 0.6;
}
</style>