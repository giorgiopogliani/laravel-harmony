import { useStorage } from "@vueuse/core";
import { defineStore } from "pinia";

export const useSidebarStore = defineStore("sidebar", {
  state: () => {
    return {
        open: useStorage('sidebar-open', false)
    }
  },
  actions: {
    toggle() {
      this.open = !this.open;
    },
    close() {
      this.open = false;
    }
  },
});
