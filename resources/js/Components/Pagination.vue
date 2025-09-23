<template>
  <div v-if="links.length > 3" class="flex flex-wrap justify-center -mb-1 mt-6">
    <template v-for="(link, index) in links" :key="index">
      <div
        v-if="link.url === null"
        class="mr-1 mb-1 px-4 py-3 text-sm text-gray-400 border rounded dark:border-gray-600"
        v-html="link.label"
      />
      <Link
        v-else
        class="mr-1 mb-1 px-4 py-3 text-sm border rounded focus:outline-none focus:border-indigo-500 focus:text-indigo-500 dark:border-gray-600"
        :class="{ 
          'bg-indigo-500 text-white border-indigo-500': link.active,
          'text-gray-700 border-gray-300 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700': !link.active
        }"
        :href="link.url"
        v-html="link.label"
      />
    </template>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  links: {
    type: Array,
    required: true
  }
});
</script>