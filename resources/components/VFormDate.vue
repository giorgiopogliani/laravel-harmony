<script lang="ts">
export default {
  inheritAttrs: false,
}
</script>

<script lang="ts" setup>
  defineProps(['label', 'modelValue', 'errors']);
</script>

<template>
  <label>
    <span v-if="label" class="form-label">{{ label }}</span>
    <input
      type="date"
      class="form-input"
      v-bind="$attrs"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
    />
    <div v-if="errors && errors.length > 0">
      <div v-for="error in errors" class="text-red-500 text-xs italic">{{ error }}</div>
    </div>
  </label>
</template>
