import { ref, shallowRef, watch } from "vue";

export function useSelection(rows: number[]) {
    const selectedRows = shallowRef<any[]>([]);
    const lastSelected = ref<any>(null);
    const isSelectedAll = ref<any>(false);
    const isIndeterminate = ref<any>(false);

    watch(selectedRows, (newSelectedRows) => {
        lastSelected.value = newSelectedRows[newSelectedRows.length - 1];
        isSelectedAll.value = selectedRows.value.length == rows.length;
        isIndeterminate.value =
            selectedRows.value.length > 0 &&
            selectedRows.value.length < rows.length;
    });

    function handleSelectAll(event: any) {
        selectedRows.value = event.target.checked ? [...rows] : [];
    }

    function handleSelectRow(event: any, row: any) {
        if (event.shiftKey && lastSelected.value) {
            // Select a range of rows
            const startIndex = rows.indexOf(lastSelected.value);
            const endIndex = rows.indexOf(row);
            const rangeStart = Math.min(startIndex, endIndex);
            const rangeEnd = Math.max(startIndex, endIndex);

            selectedRows.value = [
                ...new Set([
                    ...selectedRows.value,
                    ...rows.slice(rangeStart, rangeEnd + 1),
                ]),
            ];
        } else {
            // Select or deselect the current row
            const selectedIndex = selectedRows.value.indexOf(row);

            if (selectedIndex === -1) {
                selectedRows.value = [row, ...selectedRows.value];
            } else if (selectedIndex !== -1) {
                selectedRows.value = selectedRows.value.filter(
                    (id) => id != row
                );
            }
        }

        // Update lastSelected
        lastSelected.value = row;
    }

    function isSelected(item: any) {
        return selectedRows.value.includes(item);
    }

    function handleClearAll() {
        selectedRows.value = [];
    }

    return {
        selectedRows,
        handleSelectRow,
        handleSelectAll,
        handleClearAll,
        isSelected,
        isSelectedAll,
        isIndeterminate,
    };
}
