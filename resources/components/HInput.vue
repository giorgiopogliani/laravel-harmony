<script lang="ts">
export default {
  inheritAttrs: false,
};
</script>

<script lang="ts" setup>
defineProps<{ modelValue: string; label: string; name: string, error: string }>();
</script>

<template>
  <label class="block">
    <span class="text-gray-700">
      {{ label }}
    </span>
    <input
      v-bind="$attrs"
      :name="name"
      :value="modelValue"
      class="form-input"
      @input="$emit('update:modelValue', $event.target.value)"
    />
    <span v-if="error" class="text-red-500 text-sm">{{ error }}</span>
  </label>
</template>
