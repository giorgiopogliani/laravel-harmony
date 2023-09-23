<script lang="ts" setup>
import { useNotificationStore } from "~/stores/useNotificationStore";

const notifications = useNotificationStore();

</script>

<template>
  <div
    aria-live="assertive"
    class="pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6 z-10"
  >
    <!-- Notification panel, dynamically insert this into the live region when it needs to be displayed -->
    <transition-group
      class="flex w-full flex-col items-center space-y-4 sm:items-end"
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
      enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
      leave-active-class="transition ease-in duration-100"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <VToast v-for="toast in notifications.api.toasts" :key="toast.id" :actor="toast" />
    </transition-group>
  </div>
</template>
