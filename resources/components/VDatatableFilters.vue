<script setup lang="ts">
import { PropType, computed } from "vue";
import { Filter } from "@/types";

import {
  PopoverArrow,
  PopoverClose,
  PopoverContent,
  PopoverPortal,
  PopoverRoot,
  PopoverTrigger,
} from "radix-vue";

const props = defineProps({
  filters: {
    type: Array as PropType<Filter[]>,
    required: true,
  },
  query: {
    type: Object as PropType<any>,
    required: true,
  },
});

const activeFilters = computed<Filter[]>(() => {
  return (
    props.filters.filter((f) => Object.keys(props.query).includes(f.name)) ?? []
  );
});

function addFilter(filter: any) {
  props.query[filter.name] = {
    value: filter.value ?? "",
    operator: Object.keys(filter.operators)[0] ?? "",
  };
}

function isStandalone(filter: Filter) {
  return false;
  // return filter.operators.find(op => op.key === props.query[filter.key]['operator'])?.standalone ?? false;
}
</script>

<template>
  <div>
    <div class="flex items-end flex-wrap gap-2 w-full">
      <PopoverRoot>
        <PopoverTrigger
          class="btn btn-blue"
          aria-label="Update dimensions"
        >
          <VIcon name="filters" /> Filters
        </PopoverTrigger>
        <PopoverPortal>
          <PopoverContent
            side="bottom"
            :side-offset="5"
            class="rounded p-5 w-64 bg-white shadow-sm transition"
          >
            <div>
              <div v-for="filter in filters" :key="filter.key">
                <button
                  @click="addFilter(filter)"
                  type="button"
                  class="px-3 py-1 hover:bg-gray-200 flex w-full"
                >
                  {{ filter?.name }}
                </button>
              </div>
            </div>
            <PopoverClose
              class="btn btn-white rounded-full absolute top-0 right-0 mt-2 mr-2"
              aria-label="Close"
            >
              <VIcon name="x" />
            </PopoverClose>
            <PopoverArrow class="fill-white" />
          </PopoverContent>
        </PopoverPortal>
      </PopoverRoot>
      <div class="w-full" v-for="filter in activeFilters" :key="filter.name">
        <span class="block mb-1 text-sm">
          {{ filter.label }}
        </span>
        <div class="flex items-center gap-1">
          <select
            class="form-input w-full max-w-[12rem]"
            v-model="query[filter.name].operator"
          >
            <option value="0">Seleziona...</option>
            <option :value="value.key" v-for="value in filter.operators">
              {{ value.label }}
            </option>
          </select>
          <input
            v-if="
              !filter.operators.find(
                (o) => o.key == query[filter.name].operator
              )?.standalone
            "
            class="form-input w-full max-w-[12rem]"
            v-model="query[filter.name].value"
            v-bind="filter.props"
          />
          <button class="btn btn-white" @click="delete query[filter.name]">
            &times;
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
