<script lang="ts" setup>
import { toRefs } from "vue";
import { Table, AnyRow } from "~/types";
import { get } from "lodash";
import { useDatatable } from "~/composables/useDatatable";
import { useSelection } from "~/composables/useSelection";
import { useTableStore } from "~/stores/useTableStore";

const props = defineProps<{
  title: string;
  actions: any[];
  table: Table<AnyRow>;
}>();

const { table } = toRefs(props);

const tableStore = useTableStore();

const { datatable, filters, pagination, query } = useDatatable(table);

const { selectedRows, handleSelectAll, handleSelectRow, isSelected, isSelectedAll, isIndeterminate } = useSelection([
  ...datatable.rows.map((row) => row.id),
]);
</script>

<template>
  <div class="w-full pb-8">

    <VPageHeader class="mb-4"/>

    <div class="sm:flex sm:items-center mt-4">
      <VTableFilters v-if="filters.length > 0" :filters="filters" :query="query" />
    </div>
    <div class="mt-8 flow-root">
      <VTableBulkActions :actions="table.actions" :selectedRows="selectedRows" @deselectAll="selectedRows = []" />
    </div>

    <div>
      <div class="overflow-x-auto bg-white rounded shadow-sm">
        <div class="inline-block min-w-full align-middle px-2">
          <table class="min-w-full divide-y divide-gray-300">
            <thead>
              <tr>
                <th v-if="table.selectable" class="text-center w-8">
                  <input
                    type="checkbox"
                    class="form-checkbox"
                    :checked="isSelectedAll"
                    :indeterminate="isIndeterminate"
                    @change="handleSelectAll"
                  />
                </th>
                <VTableHeading
                  v-for="col in datatable.columns"
                  scope="col"
                  class="px-3 whitespace-nowrap py-1 text-left text-sm font-semibold text-gray-900"
                  :sort="col.sortable ? col.key : null"
                >
                  {{ col.title }}
                </VTableHeading>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="row in datatable.rows" :key="row.id">
                <td v-if="table.selectable" class="text-center w-8">
                  <input
                    type="checkbox"
                    class="form-checkbox"
                    :checked="isSelected(row.id)"
                    @click="handleSelectRow($event, row.id)"
                  />
                </td>
                <td v-for="col in datatable.columns" :key="`${row.id + col.key}`">
                  <component :is="tableStore.resolve(col.type)" :value="get(row, col.key)" :row="row" :column="col">
                    {{ get(row, col.key) }}
                  </component>
                </td>
              </tr>
              <tr v-if="datatable.rows.length == 0">
                <td :colspan="datatable.columns.length + (table.selectable ? 1 : 0)" class="text-center py-4">
                  No records found.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="mt-4">
      <VPagination :query="query" :pagination="pagination" />
    </div>
  </div>
</template>
