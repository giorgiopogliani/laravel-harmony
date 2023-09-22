import { defineStore } from "pinia";

export const useTableStore = defineStore("useTableStore", {
    state: (): { columns: any } => ({
        columns: {},
    }),
    actions: {
        setColumns(columns: any) {
            this.columns = columns;
        },
        resolve(column: string): any {
            return this.columns[column];
        },
    },
});
