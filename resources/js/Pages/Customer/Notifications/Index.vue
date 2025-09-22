<!-- resources/js/Pages/Customer/Notifications/Index.vue -->
<template>
  <CustomerLayout>
    <Head title="الإشعارات" />
    
    <div class="space-y-6">
      <!-- العنوان والإجراءات -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">الإشعارات</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1">إدارة إشعاراتك</p>
        </div>
        
        <div class="flex gap-2">
          <button 
            @click="markAllAsRead"
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm"
          >
            تحديد الكل كمقروء
          </button>
          <button 
            @click="clearRead"
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
          >
            حذف المقروء
          </button>
        </div>
      </div>

      <!-- قائمة الإشعارات -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-y-auto max-h-96">
          <div v-if="notifications.data.length > 0" class="divide-y divide-gray-200 dark:divide-gray-700">
            <div 
              v-for="notification in notifications.data" 
              :key="notification.id" 
              class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
              :class="{ 'bg-blue-50 dark:bg-blue-900/20': notification.status === 'unread' }"
            >
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <div class="flex items-center mb-2">
                    <div class="p-2 rounded-full bg-indigo-100 dark:bg-indigo-900 mr-3">
                      <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                      </svg>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ formatDate(notification.created_at) }}</p>
                  </div>
                  <p class="text-gray-900 dark:text-white" :class="{ 'font-semibold': notification.status === 'unread' }">
                    {{ notification.message }}
                  </p>
                </div>
                <div class="flex items-center gap-2 ml-4">
                  <button 
                    v-if="notification.status === 'unread'"
                    @click="markAsRead(notification)"
                    class="p-1 text-gray-400 hover:text-green-500 transition"
                    title="تحديد كمقروء"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                  </button>
                  <button 
                    @click="deleteNotification(notification)"
                    class="p-1 text-gray-400 hover:text-red-500 transition"
                    title="حذف"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- حالة عدم وجود إشعارات -->
          <div v-else class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <p class="text-gray-500 dark:text-gray-400 text-lg">لا توجد إشعارات</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">سيظهر هنا أي إشعارات جديدة تتلقاها</p>
          </div>
        </div>
      </div>

      <!-- التصفح -->
      <Pagination :links="notifications.links" />
    </div>
  </CustomerLayout>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  notifications: Object
});

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('ar-SA', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const markAsRead = (notification) => {
  router.put(route('customer.notifications.read', notification.id), {}, {
    preserveScroll: true,
    onSuccess: () => {
      showToast('تم تحديد الإشعار كمقروء', 'success');
    }
  });
};

const markAllAsRead = () => {
  router.put(route('customer.notifications.read-all'), {}, {
    preserveScroll: true,
    onSuccess: () => {
      showToast('تم تحديد جميع الإشعارات كمقروءة', 'success');
    }
  });
};

const deleteNotification = (notification) => {
  if (confirm('هل أنت متأكد من حذف هذا الإشعار؟')) {
    router.delete(route('customer.notifications.delete', notification.id), {
      preserveScroll: true,
      onSuccess: () => {
        showToast('تم حذف الإشعار', 'success');
      }
    });
  }
};

const clearRead = () => {
  if (confirm('هل أنت متأكد من حذف جميع الإشعارات المقروءة؟')) {
    // يمكنك إضافة دالة لحذف المقروء هنا
    showToast('سيتم تطوير هذه الميزة قريباً', 'info');
  }
};
</script>