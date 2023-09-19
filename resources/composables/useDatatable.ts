import {
    AnyRow,
    Column,
    ColumnOptions,
    ColumnVisibility,
    Table,
} from "../types";
import { router } from "@inertiajs/vue3";
import { watch, computed, reactive, Ref, watchEffect, ref } from "vue";

export function useDatatable<Type extends AnyRow>(table: Ref<Table<Type>>) {
    let config = {
        endpoint: table.value.endpoint,
        reload: [table.value.key],
        filtersKey: table.value.key,
    };

    if (!config.filtersKey) {
        config.filtersKey = "filters";
    }

    const pagination = computed(() => {
        return table.value.rows;
    });

    const filters = computed(() => {
        return table.value.filters;
    });

    const columnVisibility = ref<ColumnVisibility>(
        table.value.columns.reduce((acc: ColumnVisibility, col) => {
            acc[col.key] = true;
            return acc;
        }, {})
    );

    const columnOrder = ref(table.value.columns.map((column) => column.key));

    const columnOptions = computed<ColumnOptions>(() =>
        table.value.columns.reduce((acc: ColumnOptions, col) => {
            acc[col.key] = col.title;
            return acc;
        }, {})
    );

    const query = ref<{ [key: string]: string }>({
        ...table.value.query,
    });

    const columns = computed(() => {
        return table.value.columns.filter((col) => {
            return columnVisibility.value[col.key];
        });
    });

    const datatable = reactive<{
        rows: AnyRow[];
        columns: Column[];
    }>({
        rows: table.value.rows.data,
        columns: columns.value,
    });

    watchEffect(() => {
        datatable.rows = table.value.rows.data;
        datatable.columns = columns.value;
    });

    watch(
        () => query.value,
        () => update(),
        { deep: true }
    );

    function update() {
        let o = Object.fromEntries(
            Object.entries(query.value).filter(([_, v]) => v != null)
        );

        router.visit(config.endpoint, {
            only: config.reload,
            data: {
                [config.filtersKey]: { ...o },
            },
            replace: true,
            preserveState: true,
            preserveScroll: true,
        });
    }

    return {
        datatable,
        pagination,
        filters,
        columnVisibility,
        columnOptions,
        columnOrder,
        query,
        update,
    };
}
