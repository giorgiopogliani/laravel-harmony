<script lang="ts" setup>
defineProps<{
  title?: string
  subtitle?: string
}>()
</script>

<template>
  <li class="w-full text-sm">
    <slot>
      <p class="text-gray-900 dark:text-gray-100 dark:font-medium">{{ title }}</p>
      <p class="text-gray-700 dark:text-gray-100">{{ subtitle }}</p>
    </slot>
  </li>
</template>
