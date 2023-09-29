<script setup lang="ts">
import { ref } from "vue";
import { useDarkmodeStore } from "~/stores/darkmode";
import { useSidebarStore } from "~/stores/sidebar";
import { onClickOutside } from "@vueuse/core";

const drawer = ref(null);
const darkMode = useDarkmodeStore();
const sidebarStore = useSidebarStore();

onClickOutside(drawer, (event) => sidebarStore.close());
</script>

<template>
  <v-app class="h-screen w-full bg-gray-100">
    <Head :title="$page.props.title" />

    <v-app-bar class="flex items-center justify-between">
      <v-list>
        <v-list-item>
          <v-button @click="sidebarStore.toggle()">
            <v-icon name="menu" class="h-5 w-5 flex-shrink-0 text-gray-900"></v-icon>
          </v-button>
        </v-list-item>
      </v-list>
      <v-list class="flex items-center gap-2">
        <v-list-item>
          <v-button @click="() => darkMode.toggle()">
            <v-icon v-if="darkMode.active" class="h-5 w-5" name="sun"></v-icon>
            <v-icon v-else class="h-5 w-5" name="moon"></v-icon>
          </v-button>
        </v-list-item>
        <div class="ml-3 relative">
          <Dropdown align="right" width="48">
            <template #trigger>
              <span class="inline-flex rounded-md">
                <button
                  type="button"
                  class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                >
                  {{ $page.props.auth.user.name }}
                  <svg
                    class="ml-2 -mr-0.5 h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </button>
              </span>
            </template>
            <template #content>
              <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
              <DropdownLink :href="route('logout')" method="post" as="button"> Log Out </DropdownLink>
            </template>
          </Dropdown>
        </div>
      </v-list>
    </v-app-bar>

    <div class="flex h-screen w-full overflow-hidden transition-all">
      <v-app-drawer
        class="hidden md:flex transition-all z-20 h-screen flex-col overflow-x-hidden gap-4 pt-8"
        :class="{ 'translate-x-0 w-64 p-2': sidebarStore.open, '-translate-x-full w-0': !sidebarStore.open }"
      >
        <v-list class="flex flex-col w-full justify-start items-start gap-2" v-for="menu in $page.props.menu.children">
          <v-list-item>
            {{ menu.title }}
          </v-list-item>
          <v-list-item v-for="item in menu.children">
            <v-list-link v-bind="item">
              <span>{{ item.title }}</span>
            </v-list-link>
          </v-list-item>
        </v-list>
      </v-app-drawer>

      <v-app-drawer
        class="flex md:hidden w-64 transition-transform p-2 z-20 fixed h-screen flex-col gap-4 pt-8"
        :class="{ 'translate-x-0': sidebarStore.open, '-translate-x-full': !sidebarStore.open }"
      >
        <v-list class="flex flex-col w-full justify-start items-start gap-2" v-for="menu in $page.props.menu.children">
          <v-list-item>
            {{ menu.title }}
          </v-list-item>
          <v-list-item v-for="item in menu.children">
            <v-list-link v-bind="item">
              <span>{{ item.title }}</span>
            </v-list-link>
          </v-list-item>
        </v-list>
      </v-app-drawer>

      <div class="h-full w-full overflow-y-auto overflow-x-hidden transition-all">
        <v-breadcrumbs v-if="$page.props.breadcrumbs && ($page.props.breadcrumbs as any[]).length > 0">
          <v-breadcrumbs-item v-for="item in $page.props.breadcrumbs" v-bind="item">
            {{ item.title }}
          </v-breadcrumbs-item>
        </v-breadcrumbs>

        <div class="p-4">
          <slot></slot>
        </div>
      </div>
    </div>
  </v-app>
</template>
