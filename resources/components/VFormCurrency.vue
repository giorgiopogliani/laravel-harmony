<script lang="ts">
export default {
  inheritAttrs: false,
};
</script>

<script lang="ts" setup>
import { Money3Component  } from "v-money3";
import { ref, watchEffect, watch } from 'vue';

const config = {
  prefix: "",
  suffix: " €",
  thousands: ".",
  decimal: ",",
  precision: 2,
  masked: false,
  disableNegative: false,
  disabled: false,
  min: null,
  max: null,
  allowBlank: false,
  minimumNumberOfCharacters: 0,
  shouldRound: true,
  focusOnRight: false,
};

const props = defineProps(["label", "modelValue", "errors"]);
const emit = defineEmits(["update:modelValue"]);

const value = ref(props.modelValue);

watch(() => props.modelValue, (val) => {
  if (val !== value.value) {
    value.value = val;
  }
})

watchEffect(() => {
  emit("update:modelValue", value.value);
})
</script>

<template>
  <label class="block">
    <span v-if="label" class="form-label w-64">{{ label }}</span>
    <Money3Component
      class="form-input text-right"
      v-bind="{ ...config, ...$attrs}"
      v-model.number="value"
    />
    <div v-if="errors && errors.length > 0">
      <div v-for="error in errors" class="text-red-500 text-xs italic">{{ error }}</div>
    </div>
  </label>
</template>
