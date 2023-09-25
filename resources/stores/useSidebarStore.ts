import { defineStore } from "pinia";

export const useSidebarStore = defineStore("sidebar", {
    state: (): { isOpen: any } => ({
        isOpen: {},
    }),
    actions: {
        close() {
            this.isOpen = false;
        },
        open() {
            this.isOpen = true;
        },
        toggle() {
            this.isOpen = !this.isOpen;
        },
    },
});
