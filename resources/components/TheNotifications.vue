<script lang="ts" setup>
import { watch } from "vue";
import { useNotificationStore } from "~/stores/useNotificationStore";
const notifications = useNotificationStore();

let props = defineProps({
  flash: {
    type: Object,
    required: false,
    default: () => ({}),
  },
});

watch(
  () => props.flash,
  (flash) => {
    if (flash) {
      notifications.send({
        title: flash.message,
        type: "success",
      });
    }
  }
);
</script>

<template>
  <div
    aria-live="assertive"
    class="pointer-events-none fixed inset-0 flex items-end gap-4 flex-col px-4 py-6 z-10"
  >
    <VToast
      class="flex w-full flex-col items-center space-y-4 sm:items-end right-0"
      v-for="toast in notifications.items"
      :key="toast.title"
      :notification="toast"
    />
  </div>
</template>
