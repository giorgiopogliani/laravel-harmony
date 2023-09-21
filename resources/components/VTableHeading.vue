<script lang="ts" setup>
import { useSorter } from "~/composables/useSorter";

defineProps<{
  sort: string | null;
}>();

const { sortBy, isDesc, isCurrent } = useSorter();
</script>

<template>
  <th class="">
    <div
      :class="{
        'cursor-pointer hover:bg-gray-100': sort,
        // 'text-left': align == 'left',
        // 'text-right': align == 'right',
      }"
      class="border-none px-3 py-2 text-left uppercase font-medium tracking-wide text-xs text-gray-700"
    >
      <template v-if="!sort">
        <slot></slot>
      </template>
      <span
        v-else
        @click="sortBy(sort)"
        class="flex items-center justify-between"
      >
        <slot></slot>
        <svg
          v-if="isCurrent(sort) && !isDesc(sort)"
          xmlns="http://www.w3.org/2000/svg"
          class="h-4 w-4 ml-2"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="2"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
          />
        </svg>
        <svg
          v-else-if="isCurrent(sort) && isDesc(sort)"
          xmlns="http://www.w3.org/2000/svg"
          class="h-4 w-4 ml-2"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="2"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"
          />
        </svg>
        <svg
          v-else
          xmlns="http://www.w3.org/2000/svg"
          class="h-4 w-4 ml-2"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="2"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M8 9l4-4 4 4m0 6l-4 4-4-4"
          />
        </svg>
      </span>
    </div>
  </th>
</template>
