<template>
  <Head title="متجر الهواتف الذكية - الرئيسية" />
  
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 transition-colors duration-300">
    <!-- شريط التنقل -->
    <nav class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- الشعار -->
          <div class="flex items-center">
            <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
              متجر الهواتف الذكية
            </div>
          </div>

          <!-- أزرار التنقل -->
          <div class="flex items-center space-x-4">
            <button @click="toggleDarkMode" class="p-2 text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400">
              <svg v-if="!isDark" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
              </svg>
              <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
              </svg>
            </button>
            
            <template v-if="$page.props.auth.user">
              <Link :href="route('dashboard')" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                لوحة التحكم
              </Link>
            </template>
            <template v-else>
              <Link :href="route('login')" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2">
                تسجيل الدخول
              </Link>
              <Link :href="route('register')" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                إنشاء حساب
              </Link>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- القسم الرئيسي -->
    <main class="relative">
      <!-- Hero Section -->
      <section class="pt-20 pb-16 px-4 sm:px-6 lg:px-8">
  <div class="max-w-7xl mx-auto text-center">
    <h1 class="text-4xl md:text-6xl font-bold text-gray-900 dark:text-white mb-6">
      مرحباً بك في 
      <span class="text-indigo-600 dark:text-indigo-400">عالم الهواتف الذكية</span>
    </h1>
    
    <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto">
      اكتشف أحدث الموبايلات الأصلية بأسعار تنافسية وتوصيل سريع إلى باب منزلك
    </p>

    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
      <Link 
        :href="route('customer.products')" 
        class="bg-indigo-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-indigo-700 transition shadow-lg"
      >
        تسوق الموبايلات الآن
      </Link>
    </div>
  </div>
</section>


      <!-- المميزات -->
      <section class="py-16 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
              لماذا تختار متجرنا؟
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300">
              نقدم لك تجربة تسوق فريدة لا تُنسى
            </p>
          </div>

          <div class="grid md:grid-cols-3 gap-8">
            <!-- ميزة 1 -->
            <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-xl">
              <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">جودة عالية</h3>
              <p class="text-gray-600 dark:text-gray-300">منتجاتنا مختارة بعناية لتلبي أعلى معايير الجودة</p>
            </div>

            <!-- ميزة 2 -->
            <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-xl">
              <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">توصيل سريع</h3>
              <p class="text-gray-600 dark:text-gray-300">توصيل خلال 24-48 ساعة لمعظم المناطق</p>
            </div>

            <!-- ميزة 3 -->
            <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-xl">
              <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">دفع آمن</h3>
              <p class="text-gray-600 dark:text-gray-300">أنظمة دفع متعددة وآمنة لحماية معلوماتك</p>
            </div>
          </div>
        </div>
      </section>

      <!-- إحصائيات -->
      <section class="py-16 bg-indigo-600 dark:bg-indigo-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="grid md:grid-cols-4 gap-8 text-center">
            <div>
              <div class="text-4xl font-bold mb-2">10,000+</div>
              <div class="text-lg">منتج متوفر</div>
            </div>
            <div>
              <div class="text-4xl font-bold mb-2">50,000+</div>
              <div class="text-lg">عميل راضي</div>
            </div>
            <div>
              <div class="text-4xl font-bold mb-2">100+</div>
              <div class="text-lg">بائع موثوق</div>
            </div>
            <div>
              <div class="text-4xl font-bold mb-2">24/7</div>
              <div class="text-lg">دعم فني</div>
            </div>
          </div>
        </div>
      </section>

      <!-- دعوة للعمل -->
      <section class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6">
          انضم إلينا   
          </h2>
          <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
             رحلة التسوق الخاصة بك مع  متجر الهواتف الذكية

          </p>
          
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <template v-if="!$page.props.auth.user">
              <Link 
                :href="route('register')" 
                class="bg-indigo-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-indigo-700 transition"
              >
                إنشاء حساب جديد
              </Link>
              
              <Link 
                :href="route('login')" 
                class="border-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-indigo-600 hover:text-white transition"
              >
                تسجيل الدخول
              </Link>
            </template>
            <template v-else>
              <Link 
                :href="route('customer.products')" 
                class="bg-indigo-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-indigo-700 transition"
              >
                تصفح المنتجات
              </Link>
              
              <Link 
                :href="route('dashboard')" 
                class="border-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 dark:border-indigo-400 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-indigo-600 hover:text-white transition"
              >
                لوحة التحكم
              </Link>
            </template>
          </div>
        </div>
      </section>
    </main>

    <!-- التذييل -->
    <footer class="bg-gray-900 text-white py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-8">
          <div>
            <div class="text-2xl font-bold mb-4">متجر الهواتف الذكية
</div>
            <p class="text-gray-400">نقدم لكم أفضل المنتجات بأفضل الأسعار</p>
          </div>
          
          <div>
            <h3 class="text-lg font-semibold mb-4">الحساب</h3>
            <ul class="space-y-2">
              <li><Link :href="route('login')" class="text-gray-400 hover:text-white">تسجيل الدخول</Link></li>
              <li><Link :href="route('register')" class="text-gray-400 hover:text-white">إنشاء حساب</Link></li>
            </ul>
          </div>
        </div>
        
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
          © 202 متجر الهواتف الذكية. جميع الحقوق محفوظة.
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
});

const isDark = ref(false);

const toggleDarkMode = () => {
  isDark.value = !isDark.value;
  document.documentElement.classList.toggle('dark', isDark.value);
  localStorage.setItem('darkMode', isDark.value);
};

onMounted(() => {
  const savedDarkMode = localStorage.getItem('darkMode') === 'true';
  isDark.value = savedDarkMode;
  document.documentElement.classList.toggle('dark', savedDarkMode);
});
</script>