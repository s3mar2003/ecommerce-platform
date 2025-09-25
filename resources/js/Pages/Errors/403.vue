<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 px-4">
    <div class="max-w-md w-full text-center">
      <!-- أيقونة الخطأ -->
      <div class="mb-8">
        <div class="mx-auto w-24 h-24 bg-red-100 rounded-full flex items-center justify-center">
          <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>
      </div>

      <!-- رسالة الخطأ -->
      <h1 class="text-6xl font-bold text-gray-900 mb-4">403</h1>
      <h2 class="text-2xl font-semibold text-gray-800 mb-2">وصول مرفوض</h2>
      <p class="text-gray-600 mb-8 leading-relaxed">
        عذراً، ليس لديك الصلاحية اللازمة للوصول إلى هذه الصفحة.
        يرجى التواصل مع المسؤول إذا كنت تعتقد أن هذا خطأ.
      </p>

      <!-- أزرار الإجراءات -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <button
          @click="goBack"
          class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium"
        >
          العودة للخلف
        </button>
        <button
          @click="goHome"
          class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-200 font-medium"
        >
          الصفحة الرئيسية
        </button>
        <button
          @click="logout"
          class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200 font-medium"
        >
          تسجيل الخروج
        </button>
      </div>

    
    </div>
  </div>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { onMounted } from 'vue'

// دالة العودة للخلف
function goBack() {
  window.history.length > 1 ? window.history.back() : goHome()
}

// دالة الذهاب للصفحة الرئيسية
function goHome() {
  const userRole = getCurrentUserRole() // تحتاج لتطبيق هذه الدالة
  const homeRoute = getHomeRouteByRole(userRole)
  Inertia.visit(homeRoute)
}

// دالة تسجيل الخروج المحسنة
function logout() {
  Inertia.post('/logout', {}, {
    onSuccess: () => {
      Inertia.visit('/login')
    },
    onError: (errors) => {
      console.error('فشل تسجيل الخروج:', errors)
      // عرض رسالة خطأ للمستخدم
      showErrorMessage('فشل تسجيل الخروج. يرجى المحاولة مرة أخرى.')
    }
  })
}

// دالة مساعدة للحصول على دور المستخدم الحالي
function getCurrentUserRole() {
  // يمكنك الحصول على هذا من الـ props أو الـ store
  return 'guest' // قيمة افتراضية
}

// دالة مساعدة لتحديد مسار الصفحة الرئيسية حسب الدور
function getHomeRouteByRole(role) {
  const routes = {
    admin: '/admin/dashboard',
    vendor: '/vendor/dashboard',
    customer: '/customer/dashboard',
    guest: '/'
  }
  return routes[role] || '/'
}

// دالة لعرض رسائل الخطأ (يمكن استبدالها بمكون رسائل حقيقي)
function showErrorMessage(message) {
  alert(message) // يمكن استبدال هذا بمكون Toast أو Notification
}

// تأثيرات عند تحميل الصفحة
onMounted(() => {
  console.warn('تم رفض الوصول إلى الصفحة (403)')
})
</script>

<style scoped>
/* تأثيرات حركية إضافية */
button {
  transition: all 0.3s ease;
}

button:active {
  transform: scale(0.98);
}
</style>