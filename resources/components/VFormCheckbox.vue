<script setup lang="ts">
import * as checkbox from "@zag-js/checkbox";
import { normalizeProps, useMachine } from "@zag-js/vue";
import { onMounted } from "vue";
import { watchEffect } from "vue";
import { computed } from "vue";
const [state, send] = useMachine(checkbox.machine({ id: Math.random() }));

const props = defineProps(["modelValue", "label"]);

const api = computed(() => checkbox.connect(state.value, send, normalizeProps));

const emit = defineEmits(["update:modelValue"]);

onMounted(() => {
  api.value.setChecked(true)
})

watchEffect(() => {
  emit("update:modelValue", api.value.isChecked);
});
</script>

<template>
  <label ref="ref" v-bind="api.rootProps">
    <span v-bind="api.labelProps">
      <span v-if="api.isChecked" class="form-checkbox bg-gray-900"></span>
      <span v-else class="form-checkbox"></span>
      <span v-if="label" class="ml-2">{{ label }}</span>
    </span>
    <input v-bind="api.inputProps" />
    <div v-bind="api.controlProps" />
  </label>
</template>
