// resources/js/utils/toast.js
import { createApp } from 'vue'
import ToastComponent from '@/Components/Toast.vue'

const toastQueue = []
let isProcessing = false

export const toast = {
  success(message, duration = 3000) {
    this.show(message, 'success', duration)
  },
  
  error(message, duration = 3000) {
    this.show(message, 'error', duration)
  },
  
  warning(message, duration = 3000) {
    this.show(message, 'warning', duration)
  },
  
  info(message, duration = 3000) {
    this.show(message, 'info', duration)
  },
  
  show(message, type = 'info', duration = 3000) {
    toastQueue.push({ message, type, duration })
    if (!isProcessing) {
      this.processQueue()
    }
  },
  
  processQueue() {
    if (toastQueue.length === 0) {
      isProcessing = false
      return
    }
    
    isProcessing = true
    const { message, type, duration } = toastQueue.shift()
    
    this.createToast(message, type, duration)
  },
  
  createToast(message, type, duration) {
    const toastElement = document.createElement('div')
    toastElement.className = 'toast-container'
    document.body.appendChild(toastElement)
    
    const app = createApp(ToastComponent, {
      message,
      type,
      duration,
      remove: () => {
        app.unmount()
        document.body.removeChild(toastElement)
        setTimeout(() => this.processQueue(), 300)
      }
    })
    
    app.mount(toastElement)
  }
}

// جعل الدالة متاحة globally
window.toast = toast
window.showToast = toast.show