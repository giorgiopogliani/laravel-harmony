<script lang="ts" setup>
defineProps<{
  value?: number|string,
  row?: Object,
}>()
</script>

<template>
  <div class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">
    <slot></slot>
  </div>
</template>
