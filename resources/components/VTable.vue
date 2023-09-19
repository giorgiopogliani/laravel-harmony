<script lang="ts" setup>
import { ref, onMounted } from "vue";

const message = ref("Hello World!");
</script>

<template>
    <div class="mt-8 flow-root">
        <div class="overflow-x-auto bg-white rounded shadow-sm">
            <div class="inline-block min-w-full py-2 align-middle px-4">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                        <tr>
                            <th v-if="table.selectable">
                                <input
                                    type="checkbox"
                                    class="rounded-sm"
                                    :checked="isSelectedAll"
                                    :indeterminate="isIndeterminate"
                                    @change="handleSelectAll"
                                />
                            </th>
                            <th
                                v-for="col in datatable.columns"
                                scope="col"
                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                            >
                                {{ col.title }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="row in datatable.rows">
                            <td v-if="table.selectable">
                                <input
                                    type="checkbox"
                                    class="rounded-sm"
                                    :checked="isSelected(row.id)"
                                    @click="handleSelectRow($event, row.id)"
                                />
                            </td>
                            <td
                                v-for="col in datatable.columns"
                                class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                            >
                                {{ get(row, col.key) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
