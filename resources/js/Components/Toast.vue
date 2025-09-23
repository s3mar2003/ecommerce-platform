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
      class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none"
    >
      <div
        class="max-w-sm w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
        :class="toastClasses"
      >
        <div class="p-4">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <component :is="toastIcon" class="h-6 w-6" :class="iconClasses" />
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
              <p class="text-sm font-medium text-gray-900 dark:text-white">
                {{ message }}
              </p>
            </div>
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
    default: 3000
  }
})

const show = ref(false)

const toastConfig = {
  success: {
    icon: CheckCircleIcon,
    bgColor: 'bg-green-50 dark:bg-green-900',
    iconColor: 'text-green-400'
  },
  error: {
    icon: XCircleIcon,
    bgColor: 'bg-red-50 dark:bg-red-900',
    iconColor: 'text-red-400'
  },
  warning: {
    icon: ExclamationIcon,
    bgColor: 'bg-yellow-50 dark:bg-yellow-900',
    iconColor: 'text-yellow-400'
  },
  info: {
    icon: InformationCircleIcon,
    bgColor: 'bg-blue-50 dark:bg-blue-900',
    iconColor: 'text-blue-400'
  }
}

const toastClasses = computed(() => toastConfig[props.type].bgColor)
const toastIcon = computed(() => toastConfig[props.type].icon)
const iconClasses = computed(() => toastConfig[props.type].iconColor)

onMounted(() => {
  show.value = true
  setTimeout(() => {
    show.value = false
    setTimeout(() => {
      // إزالة العنصر من DOM بعد الانتهاء من animation
      if (typeof remove === 'function') {
        remove()
      }
    }, 300)
  }, props.duration)
})

defineExpose({ remove: () => {} })
</script>