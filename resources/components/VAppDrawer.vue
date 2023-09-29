<script lang="ts" setup>
import { useSidebarStore } from '~/stores/sidebar';
import { ref } from 'vue';

const hover = ref(false);
</script>

<template>
  <div @mouseover="hover = true" @mouseleave="hover = false" class="transition flex-shrink-0 flex h-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
    <slot :hover="hover"></slot>
  </div>
</template>
