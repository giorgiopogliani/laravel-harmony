<script lang="ts" setup>
defineProps<{ title?: string }>();
</script>

<template>
  <div class="bg-white shadow rounded">
    <div class="px-4 py-5">
      <h3 v-if="title" class="text-base font-semibold leading-6 text-gray-900">{{ title }}</h3>
      <div :class="{ 'mt-5': !!title }">
        <slot></slot>
      </div>
    </div>
  </div>
</template>
