import { useStorage } from "@vueuse/core";
import { defineStore } from "pinia";

export const useDarkmodeStore = defineStore("darkmode", {
    state: () => ({ active: useStorage("darkmode", false) }),
    actions: {
        toggle() {
            this.active = !this.active;
            document.querySelector("html")?.classList.toggle("dark");
        },
    },
});
