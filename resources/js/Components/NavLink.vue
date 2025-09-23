<template>
  <Link
    :href="href"
    :class="[
      'px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200',
      active
        ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900'
        : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700'
    ]"
  >
    <slot />
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  href: String,
  active: Boolean
});
</script>