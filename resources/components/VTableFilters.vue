<script setup lang="ts">
import { Filter } from "~/types";
import { useFilters } from "~/composables/useFilters";

import VIcon from "./VIcon.vue";

const props = defineProps<{
  filters: Filter[];
  query: any;
}>();

const { activeFilters, addFilter, isStandalone } = useFilters({
  filters: props.filters,
  query: props.query,
});
</script>

<template>
  <div>
    <div class="flex items-end flex-wrap gap-2 w-full">
      <VPopover label="Filters">
        <div>
          <div v-for="filter in filters" :key="filter.key">
            <button @click="addFilter(filter)" type="button" class="px-3 py-1 hover:bg-gray-100 flex w-full">
              {{ filter?.label }}
            </button>
          </div>
        </div>
      </VPopover>
      <div class="w-full" v-for="filter in activeFilters" :key="filter.name">
        <span class="block mb-1 text-sm">
          {{ filter.label }}
        </span>
        <div class="flex items-center gap-1">
          <select class="form-input w-full max-w-[12rem]" v-model="query[filter.name].operator">
            <option value="0">Seleziona...</option>
            <option :value="value.key" v-for="value in filter.operators">
              {{ value.label }}
            </option>
          </select>
          <input
            v-if="!filter.operators.find((o) => o.key == query[filter.name].operator)?.standalone"
            class="form-input w-full max-w-[12rem]"
            v-model="query[filter.name].value"
            v-bind="filter.props"
          />
          <button class="btn btn-white" @click="delete query[filter.name]">&times;</button>
        </div>
      </div>
    </div>
  </div>
</template>
