<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">
    <h1 class="text-6xl font-bold text-red-600 mb-4">403</h1>
    <p class="text-xl mb-6">غير مصرح بالوصول</p>
    <button
      @click.prevent="logout"
      class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
    >
      تسجيل الخروج
    </button>
  </div>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'

// دالة تسجيل الخروج
function logout() {
  Inertia.post('/logout', {}, {
    onSuccess: () => {
      Inertia.visit('/login') // إعادة التوجيه بعد تسجيل الخروج
    },
    onError: (errors) => {
      console.error('Logout failed:', errors)
    }
  })
}
</script>
