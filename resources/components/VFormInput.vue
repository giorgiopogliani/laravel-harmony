<script lang="ts">
export default {
  inheritAttrs: false,
}
</script>

<script lang="ts" setup>
  defineProps(['label', 'modelValue', 'icon', 'errors']);
  defineEmits(['update:modelValue']);
</script>

<template>
  <label class="block w-full">
    <span v-if="label" class="form-label">{{ label }}</span>
    <div class="relative">
      <div class="absolute flex items-center h-full pl-2">
        <v-icon v-if="icon" :name="icon"
          class="text-gray-500 h-4 w-4"
        />
      </div>
      <FormKit
        type="text"
        class="form-input"
        :class="{ 'pl-8': icon }"
        v-bind="$attrs"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
      />
    </div>
    <div v-if="errors && errors.length > 0">
      <div v-for="error in errors" class="text-red-500 text-xs italic">{{ error }}</div>
    </div>
  </label>
</template>
