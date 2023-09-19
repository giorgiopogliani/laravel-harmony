<template>
  <div class="relative">
    <div @click="state.isOpen = !state.isOpen" ref="triggerRef">
      <slot name="trigger"></slot>
    </div>

    <!-- Full Screen Dropdown Overlay -->
    <div v-show="state.isOpen" class="fixed inset-0 z-40" @click="state.isOpen = false"></div>

    <teleport to="body">
      <transition
        enter-active-class="transition ease-out duration-200"
        enter-class="transform opacity-0 scale-95"
        enter-to-class="transform opacity-100 scale-100"
        leave-active-class="transition ease-in duration-75"
        leave-class="transform opacity-100 scale-100"
        leave-to-class="transform opacity-0 scale-95">
        <div
          v-show="state.isOpen"
          class="w-56 rounded-sm border py-2 bg-white mt-2 ring-1 ring-black ring-opacity-5 z-40"
          ref="dropdownRef">
          <div>
            <slot name="content"></slot>
          </div>
        </div>
      </transition>
    </teleport>
  </div>
</template>

<script>
import { createPopper } from "@popperjs/core";
import { computed, reactive, ref, watchEffect } from "vue";

export default {
  props: {
    align: {
      default: "bottom-start",
    },
    width: {
      default: "48",
    },
    contentClasses: {
      default: () => ["py-1", "bg-white"],
    },
  },

  setup(props) {
    const widthClass = computed(() => {
      return {
        48: "w-48",
      }[props.width.toString()];
    });

    const alignmentClasses = computed(() => {
      if (props.align === "left") {
        return "origin-top-left left-0";
      } else if (props.align === "right") {
        return "origin-top-right right-0";
      } else {
        return "origin-top";
      }
    });

    const state = reactive({
      isOpen: false,
      popper: null,
    });

    const triggerRef = ref(null);
    const dropdownRef = ref(null);

    watchEffect(
      () => {
        if (!state.popper) {
          state.popper = createPopper(triggerRef.value, dropdownRef.value, {
            placement: props.align,
          });
        }
      },
      {
        flush: "post",
      }
    );

    return { state, widthClass, alignmentClasses, triggerRef, dropdownRef };
  },
};
</script>
