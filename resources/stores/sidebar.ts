import { defineStore } from "pinia";

export const useSidebarStore = defineStore("sidebar", {
  state: () => ({ autoClose: true, open: false }),
  actions: {
    toggle() {
      this.open = !this.open;
    },
    close() {
      this.open = false;
    }
  },
});
