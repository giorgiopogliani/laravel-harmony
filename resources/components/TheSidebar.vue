<script lang="ts" setup>
import { ref, onMounted } from "vue";
import { useSidebarStore } from "~/stores/useSidebarStore";
const sidebarStore = useSidebarStore();

const showingNavigationDropdown = ref(false);
</script>

<template>
  <nav class="h-screen overflow-auto py-2 px-4 sm:px-6 transition" :class="{
    '-translate-x-full': !sidebarStore.isOpen,
    'translate-x-0': sidebarStore.isOpen,
  }">
    <!-- Primary Navigation Menu -->
    <div class="">
      <div class="flex flex-col">
        <div class="flex flex-col gap-8">
          <!-- Navigation Links -->
          <div class="flex flex-col gap-2" v-for="menu in $page.props.menu.children">
            <h3>{{ menu.title }}</h3>
            <NavLink v-for="item in menu.children" v-bind="item">
              {{ item.title }}
            </NavLink>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>
