<!-- resources/js/Pages/Vendor/ManageStockOrders.vue -->
<template>
  <VendorLayout>
    <Head title="إدارة المخزون والطلبات" />
    
    <div class="space-y-6">
      <!-- العنوان -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">إدارة المخزون والطلبات</h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1">مراقبة المخزون وإدارة الطلبات في مكان واحد</p>
        </div>
        <div class="flex gap-2">
          <button 
            @click="exportData"
            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            تصدير التقرير
          </button>
        </div>
      </div>

      <!-- بطاقات الإحصائيات -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- إجمالي المنتجات -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">إجمالي المنتجات</p>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total_products }}</p>
            </div>
            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
              <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
              </svg>
            </div>
          </div>
        </div>

        <!-- المنتجات منخفضة المخزون -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">منخفضة المخزون</p>
              <p class="text-2xl font-bold text-red-600">{{ stats.low_stock_products }}</p>
            </div>
            <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
              <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
              </svg>
            </div>
          </div>
        </div>

        <!-- الطلبات الجديدة -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">طلبات جديدة</p>
              <p class="text-2xl font-bold text-yellow-600">{{ stats.pending_orders }}</p>
            </div>
            <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
              <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
        </div>

        <!-- إجمالي المبيعات -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">المبيعات اليوم</p>
              <p class="text-2xl font-bold text-green-600">{{ formatCurrency(stats.today_sales) }}</p>
            </div>
            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
              <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- البحث والتصفية -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">بحث بالمنتج</label>
            <input
              v-model="filters.product_search"
              type="text"
              placeholder="ابحث باسم المنتج..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">حالة المخزون</label>
            <select
              v-model="filters.stock_status"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
              <option value="all">جميع المنتجات</option>
              <option value="low">منخفضة المخزون</option>
              <option value="out">منتهية</option>
              <option value="sufficient">كافية</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">حالة الطلب</label>
            <select
              v-model="filters.order_status"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
              <option value="all">جميع الطلبات</option>
              <option value="pending">قيد الانتظار</option>
              <option value="processing">قيد المعالجة</option>
              <option value="shipped">تم الشحن</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الفترة</label>
            <select
              v-model="filters.period"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
              <option value="today">اليوم</option>
              <option value="week">هذا الأسبوع</option>
              <option value="month">هذا الشهر</option>
              <option value="all">جميع الفترات</option>
            </select>
          </div>
        </div>
      </div>

      <!-- المحتوى الرئيسي -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- قسم المنتجات -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
          <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">إدارة المخزون</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">المنتجات التي تحتاج إلى اهتمام</p>
          </div>
          <div class="p-6">
            <div v-if="filteredProducts.length > 0" class="space-y-4">
              <div 
                v-for="product in filteredProducts" 
                :key="product.id"
                class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg"
              >
                <div class="flex items-center gap-3">
                  <img 
                    v-if="product.image_url" 
                    :src="product.image_url" 
                    :alt="product.name"
                    class="w-10 h-10 rounded object-cover"
                  >
                  <div v-else class="w-10 h-10 bg-gray-100 dark:bg-gray-600 rounded flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                  </div>
                  <div>
                    <h3 class="font-medium text-gray-900 dark:text-white">{{ product.name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">المخزون: {{ product.stock }}</p>
                  </div>
                </div>
                <div class="flex items-center gap-2">
                  <span class="px-2 py-1 rounded-full text-xs font-medium" 
                    :class="getStockStatusClass(product.stock)">
                    {{ getStockStatusText(product.stock) }}
                  </span>
                  <Link 
                    :href="route('vendor.products.edit', product.id)"
                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400"
                  >
                    تحديث
                  </Link>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-8 text-gray-500 dark:text-gray-400">
              لا توجد منتجات تطابق معايير البحث
            </div>
          </div>
        </div>

        <!-- قسم الطلبات -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
          <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">الطلبات الحديثة</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">آخر الطلبات التي تحتاج إلى معالجة</p>
          </div>
          <div class="p-6">
            <div v-if="filteredOrders.length > 0" class="space-y-4">
              <div 
                v-for="order in filteredOrders" 
                :key="order.id"
                class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg"
              >
                <div class="flex justify-between items-start mb-2">
                  <div>
                    <h3 class="font-medium text-gray-900 dark:text-white">الطلب #{{ order.id }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ order.customer.name }}</p>
                  </div>
                  <span class="px-2 py-1 rounded-full text-xs font-medium" 
                    :class="getStatusClass(order.order_status)">
                    {{ getStatusText(order.order_status) }}
                  </span>
                </div>
                <div class="flex justify-between items-center text-sm">
                  <span class="text-gray-600 dark:text-gray-400">{{ formatCurrency(order.total_amount) }}</span>
                  <span class="text-gray-500">{{ formatDate(order.created_at) }}</span>
                </div>
                <div class="mt-3 flex gap-2">
                  <Link 
                    :href="route('vendor.orders.show', order.id)"
                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 text-sm"
                  >
                    عرض التفاصيل
                  </Link>
                  <button 
                    v-if="order.order_status === 'pending'"
                    @click="updateOrderStatus(order, 'processing')"
                    class="text-green-600 hover:text-green-900 dark:text-green-400 text-sm"
                  >
                    بدء المعالجة
                  </button>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-8 text-gray-500 dark:text-gray-400">
              لا توجد طلبات تطابق معايير البحث
            </div>
          </div>
        </div>
      </div>

      <!-- تنبيهات المخزون -->
      <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-6">
        <div class="flex items-center gap-3">
          <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
          </svg>
          <div>
            <h3 class="font-medium text-yellow-800 dark:text-yellow-300">تنبيهات المخزون</h3>
            <p class="text-yellow-700 dark:text-yellow-400 text-sm">
              لديك {{ lowStockCount }} منتج{{ lowStockCount !== 1 ? 'ات' : '' }} منخفضة المخزون تحتاج إلى إعادة تخزين
            </p>
          </div>
        </div>
      </div>
    </div>
  </VendorLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { debounce } from 'lodash';
import VendorLayout from '@/Layouts/VendorLayout.vue';

const props = defineProps({
  products: Array,
  orders: Array,
  stats: Object
});

const filters = ref({
  product_search: '',
  stock_status: 'all',
  order_status: 'all',
  period: 'today'
});

// تصفية المنتجات
const filteredProducts = computed(() => {
  let filtered = props.products;

  if (filters.value.product_search) {
    filtered = filtered.filter(product => 
      product.name.toLowerCase().includes(filters.value.product_search.toLowerCase())
    );
  }

  if (filters.value.stock_status !== 'all') {
    filtered = filtered.filter(product => {
      switch (filters.value.stock_status) {
        case 'low': return product.stock > 0 && product.stock <= 10;
        case 'out': return product.stock === 0;
        case 'sufficient': return product.stock > 10;
        default: return true;
      }
    });
  }

  return filtered;
});

// تصفية الطلبات
const filteredOrders = computed(() => {
  let filtered = props.orders;

  if (filters.value.order_status !== 'all') {
    filtered = filtered.filter(order => order.order_status === filters.value.order_status);
  }

  return filtered.slice(0, 5); // عرض آخر 5 طلبات فقط
});

// عد المنتجات منخفضة المخزون
const lowStockCount = computed(() => {
  return props.products.filter(product => product.stock > 0 && product.stock <= 10).length;
});

// دوال المساعدة
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('ar-SA', {
    style: 'currency',
    currency: 'SAR',
    minimumFractionDigits: 0
  }).format(amount);
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('ar-SA');
};

const getStockStatusClass = (stock) => {
  if (stock === 0) return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
  if (stock <= 10) return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
  return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
};

const getStockStatusText = (stock) => {
  if (stock === 0) return 'منتهي';
  if (stock <= 10) return 'منخفض';
  return 'كافي';
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

const updateOrderStatus = (order, newStatus) => {
  router.put(route('vendor.orders.updateStatus', order.id), {
    order_status: newStatus
  }, {
    preserveScroll: true,
    onSuccess: () => {
      if (window.toast) {
        window.toast.success('تم تحديث حالة الطلب بنجاح');
      }
    }
  });
};

const exportData = () => {
  router.get(route('vendor.manage.export'), {
    ...filters.value
  });
};

// البحث بانتظار
watch(filters, debounce(() => {
  // يمكن إضافة تحديث البيانات من السيرفر هنا إذا لزم الأمر
}, 500), { deep: true });
</script>