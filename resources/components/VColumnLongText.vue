<script lang="ts" setup>
import { AnyRow, Column } from "~/types";
import { get } from "lodash";

defineProps<{
  value?: number | string;
  row: AnyRow;
  column: Column;
}>();
</script>

<template>
  <div class="w-full max-w-md px-3 py-4 text-sm text-gray-500">
    {{ get(row, column.key) }}
  </div>
</template>
