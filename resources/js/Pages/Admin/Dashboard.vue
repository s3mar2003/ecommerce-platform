<template>
  <AdminLayout>
    <Head title="لوحة تحكم المدير" />
    
    <div class="space-y-6">
      <!-- الإحصائيات -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
              </svg>
            </div>
            <div class="mr-3 sm:mr-4">
              <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ stats.users_count }}</h3>
              <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">المستخدمين</p>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 bg-green-100 dark:bg-green-900 rounded-full">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
            </div>
            <div class="mr-3 sm:mr-4">
              <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ stats.vendors_count }}</h3>
              <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">البائعين</p>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
              </svg>
            </div>
            <div class="mr-3 sm:mr-4">
              <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ stats.products_count }}</h3>
              <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">المنتجات</p>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
              </svg>
            </div>
            <div class="mr-3 sm:mr-4">
              <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ stats.orders_count }}</h3>
              <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">الطلبات</p>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
          <div class="flex items-center">
            <div class="p-2 sm:p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div class="mr-3 sm:mr-4">
              <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(stats.revenue) }}</h3>
              <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">الإيرادات</p>
            </div>
          </div>
        </div>
      </div>

      <!-- قسم الرسوم البيانية -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- رسم بياني للمبيعات الشهرية -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">المبيعات الشهرية</h3>
          <LineChart :data="salesChartData" :options="chartOptions" />
        </div>

        <!-- رسم بياني لحالات الطلبات -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">توزيع حالات الطلبات</h3>
          <BarChart :data="ordersChartData" :options="chartOptions" />
        </div>
      </div>

     <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
          <div class="flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">أحدث الطلبات</h2>
            <Link :href="route('admin.orders')" class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
              عرض الكل
            </Link>
          </div>
        </div>

        <div class="p-6">
          <div class="overflow-x-auto">
            <table class="w-full min-w-full">
              <thead>
                <tr class="bg-gray-50 dark:bg-gray-700">
                  <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-300">رقم الطلب</th>
                  <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-300">العميل</th>
                  <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-300 hidden sm:table-cell">المبلغ</th>
                  <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-300">الحالة</th>
                  <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-300 hidden md:table-cell">التاريخ</th>
                  <th class="px-4 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-300">الإجراءات</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="order in recentOrders" :key="order.id" class="border-t border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td class="px-4 py-4 font-medium">#{{ order.id }}</td>
                  <td class="px-4 py-4">{{ order.customer.name }}</td>
                  <td class="px-4 py-4 hidden sm:table-cell">{{ formatCurrency(order.total_amount) }}</td>
                  <td class="px-4 py-4">
                    <span class="px-3 py-1 rounded-full text-sm font-medium" :class="getStatusClass(order.order_status)">
                      {{ getStatusText(order.order_status) }}
                    </span>
                  </td>
                  <td class="px-4 py-4 hidden md:table-cell">{{ formatDate(order.created_at) }}</td>
                  <td class="px-4 py-4">
                    <!-- الحل: استبدال الرابط المفقود -->
                    <button 
                      @click="showOrderDetails(order)" 
                      class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400"
                    >
                      عرض
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-if="recentOrders.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
            لا توجد طلبات حديثة
          </div>
        </div>
      </div>


      <!-- إحصائيات إضافية -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- إحصائيات المنتجات -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">إحصائيات المنتجات</h3>
          <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <span class="text-gray-700 dark:text-gray-300">المنتجات النشطة</span>
              <span class="font-semibold text-green-600 dark:text-green-400">{{ stats.active_products || 0 }}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <span class="text-gray-700 dark:text-gray-300">المنتجات غير النشطة</span>
              <span class="font-semibold text-red-600 dark:text-red-400">{{ stats.inactive_products || 0 }}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <span class="text-gray-700 dark:text-gray-300">متوسط السعر</span>
              <span class="font-semibold text-blue-600 dark:text-blue-400">{{ formatCurrency(stats.average_price || 0) }}</span>
            </div>
          </div>
        </div>

        <!-- إحصائيات المبيعات -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">إحصائيات المبيعات</h3>
          <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <span class="text-gray-700 dark:text-gray-300">إجمالي الإيرادات</span>
              <span class="font-semibold text-green-600 dark:text-green-400">{{ formatCurrency(stats.revenue || 0) }}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <span class="text-gray-700 dark:text-gray-300">متوسط قيمة الطلب</span>
              <span class="font-semibold text-purple-600 dark:text-purple-400">{{ formatCurrency(stats.average_order_value || 0) }}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <span class="text-gray-700 dark:text-gray-300">أعلى طلب</span>
              <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ formatCurrency(stats.highest_order || 0) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'; // أضف router
import AdminLayout from '@/Layouts/AdminLayout.vue';
import LineChart from '@/Components/Charts/LineChart.vue';
import BarChart from '@/Components/Charts/BarChart.vue';

const props = defineProps({
  stats: Object,
  recentOrders: Array,
  chartData: Object
});

const showOrderDetails = (order) => {
  alert(`تفاصيل الطلب #${order.id}\nالعميل: ${order.customer.name}\nالمبلغ: ${formatCurrency(order.total_amount)}\nالحالة: ${getStatusText(order.order_status)}`);
  
};
// بيانات الرسوم البيانية
const salesChartData = {
  labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
  datasets: [
    {
      label: 'المبيعات الشهرية',
      data: props.chartData?.monthlySales || [12000, 19000, 15000, 21000, 18000, 25000, 22000, 28000, 26000, 30000, 32000, 35000],
      borderColor: 'rgb(79, 70, 229)',
      backgroundColor: 'rgba(79, 70, 229, 0.1)',
      tension: 0.4,
      fill: true
    }
  ]
};

const ordersChartData = {
  labels: ['قيد الانتظار', 'قيد المعالجة', 'تم الشحن', 'تم التوصيل', 'ملغي'],
  datasets: [
    {
      label: 'عدد الطلبات',
      data: props.chartData?.orderStatuses || [15, 22, 8, 45, 5],
      backgroundColor: [
        'rgba(255, 205, 86, 0.8)',
        'rgba(54, 162, 235, 0.8)',
        'rgba(153, 102, 255, 0.8)',
        'rgba(75, 192, 192, 0.8)',
        'rgba(255, 99, 132, 0.8)'
      ],
      borderWidth: 1
    }
  ]
};

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'top',
      labels: {
        usePointStyle: true,
        padding: 20
      }
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      grid: {
        color: 'rgba(0, 0, 0, 0.1)'
      }
    },
    x: {
      grid: {
        display: false
      }
    }
  }
};

const getStatusClass = (status) => {
  const classes = {
    'pending': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
    'processing': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
    'shipped': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
    'delivered': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    'cancelled': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
  };
  return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
};

const getStatusText = (status) => {
  const texts = {
    'pending': 'قيد الانتظار',
    'processing': 'قيد المعالجة',
    'shipped': 'تم الشحن',
    'delivered': 'تم التوصيل',
    'cancelled': 'ملغي'
  };
  return texts[status] || status;
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('ar-SA', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('ar-SA', {
    style: 'currency',
    currency: 'SAR',
    minimumFractionDigits: 0
  }).format(amount);
};
</script>

<style scoped>
canvas {
  max-height: 300px;
}
</style>