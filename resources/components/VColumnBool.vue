<script lang="ts" setup>
defineProps<{
  value?: number | string;
  row?: Object;
}>();
</script>

<template>
  <div class="whitespace-nowrap">
    <span
      v-if="value"
      class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20"
    >
      {{ 'Yes' }}
    </span>
    <span
      v-else
      class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10"
    >
      {{ 'No' }}
    </span>
  </div>
</template>
