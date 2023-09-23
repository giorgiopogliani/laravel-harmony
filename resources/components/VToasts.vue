<script lang="ts" setup>
import * as toast from "@zag-js/toast";
import { normalizeProps, useActor } from "@zag-js/vue";
import { computed, defineComponent, onMounted, onUnmounted, ref } from "vue";

let props = defineProps<{
  actor: any;
  link: any;
}>();

const [state, send] = useActor(props.actor);

const api = computed(() => toast.connect(state.value, send, normalizeProps));

const timeout = ref<number | undefined>();

onMounted(() => {
  timeout.value = setTimeout(() => {
    api.value.dismiss();
  }, 3000);
});

onUnmounted(() => {
  clearTimeout(timeout.value);
});
</script>

<template>
  <div
    class="transition-all pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5"
  >
    <div class="p-4 w-full">
      <div class="flex items-start">
        <div class="flex-shrink-0" v-if="api.type == 'success'">
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
        <div class="flex-shrink-0" v-else-if="api.type == 'error'">
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
          <p class="text-sm font-medium text-gray-900">
            {{ api.title }}
          </p>
          <p class="mt-1 text-sm text-gray-500">
            {{ api.description }}
          </p>
          <div class="mt-3 flex space-x-7" v-if="api.render()">
            <NuxtLink
              :to="api.render().url"
              class="rounded-md bg-white text-sm font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
              {{ api.render().label }}
            </NuxtLink>
          </div>
        </div>
        <div class="ml-4 flex flex-shrink-0">
          <button
            type="button"
            @click="api.dismiss()"
            class="inline-flex h-6 w-6 items-center justify-center rounded-full text-xl bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          >
            <span class="sr-only">Close</span>
            &times;
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
