<!-- resources/js/Components/Toast.vue -->
<template>
  <transition
    enter-active-class="transform ease-out duration-300 transition"
    enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
    leave-active-class="transition ease-in duration-100"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div
      v-if="show"
      class="max-w-md w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
      :class="toastClasses"
    >
      <div class="p-4">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <component 
              :is="toastIcon" 
              class="h-6 w-6" 
              :class="iconClasses" 
              aria-hidden="true" 
            />
          </div>
          <div class="ml-3 w-0 flex-1 pt-0.5">
            <p class="text-sm font-medium text-gray-900" :class="textClasses">
              {{ message }}
            </p>
          </div>
          <div class="ml-4 flex-shrink-0 flex">
            <button
              @click="closeToast"
              class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              :class="closeButtonClasses"
            >
              <span class="sr-only">إغلاق</span>
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { 
  CheckCircleIcon, 
  XCircleIcon, 
  ExclamationTriangleIcon, 
  InformationCircleIcon 
} from '@heroicons/vue/24/outline'

const props = defineProps({
  message: String,
  type: {
    type: String,
    default: 'info',
    validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
  },
  duration: {
    type: Number,
    default: 5000
  }
})

const emit = defineEmits(['close'])

const show = ref(false)

const toastConfig = {
  success: {
    icon: CheckCircleIcon,
    bgColor: 'bg-green-50',
    iconColor: 'text-green-400',
    textColor: 'text-green-800',
    closeButtonColor: 'focus:ring-green-500'
  },
  error: {
    icon: XCircleIcon,
    bgColor: 'bg-red-50',
    iconColor: 'text-red-400',
    textColor: 'text-red-800',
    closeButtonColor: 'focus:ring-red-500'
  },
  warning: {
    icon: ExclamationTriangleIcon,
    bgColor: 'bg-yellow-50',
    iconColor: 'text-yellow-400',
    textColor: 'text-yellow-800',
    closeButtonColor: 'focus:ring-yellow-500'
  },
  info: {
    icon: InformationCircleIcon,
    bgColor: 'bg-blue-50',
    iconColor: 'text-blue-400',
    textColor: 'text-blue-800',
    closeButtonColor: 'focus:ring-blue-500'
  }
}

const toastClasses = computed(() => toastConfig[props.type].bgColor)
const toastIcon = computed(() => toastConfig[props.type].icon)
const iconClasses = computed(() => toastConfig[props.type].iconColor)
const textClasses = computed(() => toastConfig[props.type].textColor)
const closeButtonClasses = computed(() => toastConfig[props.type].closeButtonColor)

const closeToast = () => {
  show.value = false
  setTimeout(() => {
    emit('close')
  }, 300)
}

onMounted(() => {
  show.value = true
  const timer = setTimeout(() => {
    closeToast()
  }, props.duration)

  // تنظيف المؤقت عند إلغاء التثبيت
  return () => clearTimeout(timer)
})
</script>

<style scoped>
/* تخصيصات إضافية للعربية */
[dir='rtl'] .ml-3 {
  margin-left: 0;
  margin-right: 0.75rem;
}

[dir='rtl'] .ml-4 {
  margin-left: 0;
  margin-right: 1rem;
}
</style>