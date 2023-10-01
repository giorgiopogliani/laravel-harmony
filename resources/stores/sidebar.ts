import { useStorage } from "@vueuse/core";
import hotkeys from "hotkeys-js";
import { defineStore } from "pinia";

export const useSidebarStore = defineStore("sidebar", () => {
    const open = useStorage("sidebar-open", false);

    hotkeys("cmd+b", function (event, handler) {
      toggle();
    });

    function toggle() {
      open.value = !open.value;
    }

    function close() {
      open.value = false;
    }

    return {
      open,
      toggle,
      close,
    };
  });
