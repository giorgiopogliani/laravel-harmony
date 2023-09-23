<script lang="ts" setup>
import * as combobox from "@zag-js/combobox";
import { normalizeProps, useMachine } from "@zag-js/vue";
import { watch, watchEffect, computed, ref } from "vue";

const props = defineProps<{
  data: { label: string; value: string }[];
  label?: string;
  modelValue: string;
}>();

const emit = defineEmits(["update:modelValue"]);

const options = ref(props.data);

const [state, send] = useMachine(
  combobox.machine({
    id: `${Math.random()}`,
    onOpen() {
      options.value = props.data;
    },
    onInputChange({ value }) {
      const filtered = props.data.filter((item) => item.label.toLowerCase().includes(value.toLowerCase()));
      options.value = filtered.length > 0 ? filtered : props.data;
    },
  })
);

const api = computed(() => combobox.connect(state.value, send, normalizeProps));

watch(() => props.data, () => {
  if (props.data.length > 0) {
    let selected = props.data.find((item) => item.value == props.modelValue);
    console.log(props.data, props.modelValue);
    if (selected && state.value.context.selectionData?.value != selected.value) {
      api.value.setValue(selected);
    }
  }
})

watchEffect(() => {
  if (state.value.context.selectionData?.value) {
    emit("update:modelValue", state.value.context.selectionData?.value);
  }
});
</script>

<template>
  <div v-bind="api.rootProps">
    <label class="block text-sm font-medium leading-6 text-gray-900" v-bind="api.labelProps" v-if="label">
      {{ label }}
    </label>

    <div v-bind="api.controlProps" class="relative">
      <input class="form-input pr-10" v-bind="api.inputProps" />
      <button
        class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none"
        v-bind="api.triggerProps"
      >
        <v-icon name="chevron-down" class="h-5 w-5"></v-icon>
      </button>
      <div
        v-bind="api.positionerProps"
        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
      >
        <ul v-if="options.length > 0" v-bind="api.contentProps">
          <li
            v-for="(item, index) in options"
            :key="item.value"
            :class="[
              'relative cursor-default select-none py-2 pl-3 pr-9',
              state.context?.focusedOptionData?.value == item.value ? 'bg-indigo-600 text-white' : 'text-gray-900',
            ]"
            v-bind="
              api.getOptionProps({
                label: item.label,
                value: item.value,
                index,
              })
            "
          >
            <span :class="['block truncate', state.context.selectionData?.value == item.value && 'font-semibold']">
              {{ item.label }}
            </span>

            <span
              v-if="state.context.selectionData?.value == item.value"
              :class="[
                'absolute inset-y-0 right-0 flex items-center pr-4',
                state.context?.focusedOptionData?.value == item.value ? 'text-white' : 'text-indigo-600',
              ]"
            >
              <v-icon name="check" class="h-5 w-5" aria-hidden="true" />
            </span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
