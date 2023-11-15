<script lang="ts" setup>
import { router } from '@inertiajs/vue3';

defineProps<{ notification: {
  level: string;
  message: string;
} }>();
</script>

<template>
  <div
    class="transition-all pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5"
    :class="{
      'bg-green-50 text-green-800': notification.level == 'success',
      'bg-red-50 text-red-800': notification.level == 'danger',
    }"
  >
    <div class="p-4 w-full">
      <div class="flex items-start">
        <div class="flex-shrink-0" v-if="notification.level == 'success'">
          <svg
            class="h-6 w-6 text-green-400"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            aria-hidden="true"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
        </div>
        <div class="flex-shrink-0" v-else-if="notification.level == 'danger'">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="h-6 w-6 text-red-400"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
        </div>
        <div class="ml-3 w-0 flex-1 pt-0.5">
          <p class="text-sm font-medium ">
            {{ notification.message }}
          </p>
        </div>
        <div class="ml-4 flex flex-shrink-0">
          <button
            type="button"
            @click="router.reload()"
            class="inline-flex h-6 w-6 items-center justify-center rounded-full text-xl text-gray-500 hover:text-gray-700 focus:outline-none"
          >
            <span class="sr-only">Close</span>
            &times;
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
