<script lang="ts" setup>
import { useSidebarStore } from '~/stores/sidebar';

const sidebarStore = useSidebarStore();

defineProps<{
  title: string
}>()
</script>

<template>
  <div class="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="flex-1 px-4 flex justify-between">
      <div class="flex items-center justify-between w-full text-gray-900 dark:text-gray-100">
        <slot></slot>
      </div>
    </div>
  </div>
</template>
