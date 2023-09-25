<script lang="ts" setup>
import { AnyRow, Column } from "~/types";
import { get } from "lodash";

defineProps<{
  value?: number | string | any[];
  row: AnyRow;
  column: Column;
}>();
</script>

<template>
  <div class="w-full max-w-md px-3 py-4 text-sm text-gray-500">
    <div class="flex items-center gap-2">
      <VButton v-for="action in get(row, column.key)" v-bind="action">
        {{ action.title }}
      </VButton>
    </div>
  </div>
</template>
