// resources/js/utils/toast.js
import { createApp } from 'vue'
import ToastComponent from '@/Components/Toast.vue'

// إنشاء حاوية رئيسية للـ toasts
let toastContainer = null

const createToastContainer = () => {
  if (!toastContainer) {
    toastContainer = document.createElement('div')
    toastContainer.id = 'toast-container'
    toastContainer.className = 'fixed top-4 right-4 z-50 space-y-3'
    document.body.appendChild(toastContainer)
  }
  return toastContainer
}

export const toast = {
  success(message, duration = 5000) {
    this.show(message, 'success', duration)
  },
  
  error(message, duration = 5000) {
    this.show(message, 'error', duration)
  },
  
  warning(message, duration = 5000) {
    this.show(message, 'warning', duration)
  },
  
  info(message, duration = 5000) {
    this.show(message, 'info', duration)
  },
  
  show(message, type = 'info', duration = 5000) {
    const container = createToastContainer()
    const toastElement = document.createElement('div')
    
    const app = createApp(ToastComponent, {
      message,
      type,
      duration,
      remove: () => {
        setTimeout(() => {
          app.unmount()
          if (toastElement.parentNode) {
            toastElement.parentNode.removeChild(toastElement)
          }
        }, 300)
      }
    })
    
    app.mount(toastElement)
    container.appendChild(toastElement)
    
    // تحديد الحد الأقصى لعدد الـ toasts
    const toasts = container.children
    if (toasts.length > 5) {
      toasts[0].remove()
    }
  }
}

// تهيئة الـ toast عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', () => {
  createToastContainer()
})

// جعل الدالة متاحة globally
window.toast = toast
window.showToast = toast.show