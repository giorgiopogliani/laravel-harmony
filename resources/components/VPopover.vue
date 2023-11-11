<script setup lang="ts">
import * as popover from "@zag-js/popover";
import { normalizeProps, useMachine } from "@zag-js/vue";
import { computed } from "vue";

const props = defineProps<{
  label?: string;
  icon?: string;
}>();
const [state, send] = useMachine(popover.machine({ id: `${Math.random()}`, positioning: { placement: 'bottom-start' } }));
const api = computed(() => popover.connect(state.value, send, normalizeProps));
</script>

<template>
  <div ref="ref">
    <button class="btn btn-secondary" v-bind="api.triggerProps">
      <v-icon v-if="icon" :name="icon" class="h-3 w-3 flex-shrink-0 mr-2"></v-icon>
      <span v-if="label" class="flex flex-shrink-0">
        {{ label }}
      </span>
      <v-icon name="chevron-down" class="h-4 w-4 flex-shrink-0" :class="{ 'ml-2': !!label }"></v-icon>
    </button>
    <Teleport to="body">
      <div v-bind="api.positionerProps" class="w-56 left-0">
        <div v-bind="api.contentProps" class="p-2 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-y-auto max-h-96">
          <slot></slot>
        </div>
      </div>
    </Teleport>
  </div>
</template>
